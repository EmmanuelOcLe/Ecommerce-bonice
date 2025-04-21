<?php
session_start();
require_once '../config/db.php';
require_once '../functions/carrito.php';

header('Content-Type: application/json');



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
    $contacto = $_POST['contacto'] ?? '';
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

    $sqlPedido = "INSERT INTO pedidos (usuario_id, provincia, localidad, direccion, coste, estado, fecha, hora)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtPedido = mysqli_prepare($conexion, $sqlPedido);
    mysqli_stmt_bind_param($stmtPedido, "isssdsss", $usuario_id, $provincia, $localidad, $direccion, $coste_total, $estado, $fecha, $hora);

    if (mysqli_stmt_execute($stmtPedido)) {
        $_SESSION['carrito'] = [];
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar el pedido']);
    }

    // Procesas el formulario
// Guardas el pedido en la base de datos

// Y luego rediriges
header("Location: detalle_pedido.php?id=$id_pedido");
exit;

}
