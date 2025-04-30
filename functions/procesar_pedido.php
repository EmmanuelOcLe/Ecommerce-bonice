<?php
session_start();
require_once '../config/db.php';
require_once '../functions/carrito.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    header("Location: ../../pages/login-page.php?error=no_session");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_email'])) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
        exit;
    }

    $email = $_SESSION['user_email'];
    $stmt = mysqli_prepare($conexion, "SELECT id FROM usuarios WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$usuario) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
        exit;
    }

    $usuario_id = $usuario['id'];
    $direccion = $_POST['direccion'] ?? '';
    $provincia = $_POST['departamento'] ?? '';
    $localidad = $_POST['ciudad'] ?? '';
    $contacto = intval($_POST['contacto']) ?? 0;
    $metodo_pago = $_POST['pago'] ?? '';
    $estado = 'confirmado';
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');

    $carrito = obtenerCarrito();
    if (empty($carrito)) {
        echo json_encode(['status' => 'error', 'message' => 'El carrito está vacío']);
        exit;
    }

    $coste_total = 0;
    $cantidad_total = 0;
    foreach ($carrito as $producto) {
        $coste_total += $producto['precio'] * $producto['cantidad'];
        $cantidad_total += $producto['cantidad'];
    }

    // Insertar el pedido en la tabla pedidos
    $sqlPedido = "INSERT INTO pedidos (usuario_id, provincia, localidad, direccion, numero_contacto, coste, estado, fecha, hora)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtPedido = mysqli_prepare($conexion, $sqlPedido);
    mysqli_stmt_bind_param($stmtPedido, "isssidsss", $usuario_id, $provincia, $localidad, $direccion, $contacto, $coste_total, $estado, $fecha, $hora);

    if (mysqli_stmt_execute($stmtPedido)) {
        $pedido_id = mysqli_insert_id($conexion);  // Obtener el ID del pedido recién insertado
        
        // Insertar los productos del carrito en la tabla lineas_pedidos
        $sqlLinea = "INSERT INTO lineas_pedidos (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)";
        $stmtLinea = mysqli_prepare($conexion, $sqlLinea);

        foreach ($carrito as $producto) {
            $producto_id = $producto['id'];
            $cantidad = $producto['cantidad'];
            mysqli_stmt_bind_param($stmtLinea, "iii", $pedido_id, $producto_id, $cantidad);
            mysqli_stmt_execute($stmtLinea);
        }

        // Limpiar el carrito
        foreach ($_SESSION["carrito"] as $key => $value)
        {
            $sql_producto = "SELECT stock FROM productos WHERE id = " . intval($key);
            $stock_producto = mysqli_query($conexion, $sql_producto);
            $result = mysqli_fetch_assoc($stock_producto);
            $stock = intval($result["stock"]);

            $resta = $stock - ($_SESSION["carrito"][$key]["cantidad"]);
            $sql = "UPDATE productos SET stock = $resta WHERE id = $key";
            mysqli_query($conexion, $sql);
        }

        $_SESSION['carrito'] = [];

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar el pedido']);
    }
}
?>
