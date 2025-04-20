<?php
session_start();
ob_start();

require_once '../config/db.php';
require_once '../functions/carrito.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_email'])) {
        echo "Usuario no autenticado";
        exit;
    }

    // Obtener ID del usuario desde su correo
    $email = $_SESSION['user_email'];
    $sqlUsuario = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conexion, $sqlUsuario);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($resultado);

    if (!$usuario) {
        echo "Usuario no encontrado";
        exit;
    }
    mysqli_stmt_close($stmt);

    $usuario_id = $usuario['id'];

    // Obtener datos del formulario
    $direccion = $_POST['direccion'] ?? '';
    $provincia = $_POST['departamento'] ?? '';
    $localidad = $_POST['ciudad'] ?? '';
    $contacto = $_POST['contacto'] ?? '';
    $metodo_pago = $_POST['pago'] ?? '';
    $estado = 'confirmado';
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');

    // Obtener carrito
    $carrito = obtenerCarrito();
    if (empty($carrito)) {
        echo "El carrito está vacío";
        exit;
    }

    // Calcular el coste total y cantidad total
    $coste_total = 0;
    $cantidad_total = 0;
    foreach ($carrito as $producto) {
        $coste_total += $producto['precio'] * $producto['cantidad'];
        $cantidad_total += $producto['cantidad'];
    }

    // Insertar pedido
    $sqlPedido = "INSERT INTO pedidos (usuario_id, provincia, localidad, direccion, coste, estado, fecha, hora)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtPedido = mysqli_prepare($conexion, $sqlPedido);
    mysqli_stmt_bind_param($stmtPedido, "isssdsss", $usuario_id, $provincia, $localidad, $direccion, $coste_total, $estado, $fecha, $hora);

    if (mysqli_stmt_execute($stmtPedido)) {
        // Obtener el ID del nuevo pedido
        $pedido_id = mysqli_insert_id($conexion);

        // Vaciar carrito
        $_SESSION['carrito'] = [];

        // Redirigir a la página de confirmación con los datos
        header("Location: ../functions/confirmacion.php?pedido_id=$pedido_id&total=$coste_total&cantidad=$cantidad_total");
        exit();
    } else {
        echo "Error al guardar el pedido";
        exit();
    }
}
?>
