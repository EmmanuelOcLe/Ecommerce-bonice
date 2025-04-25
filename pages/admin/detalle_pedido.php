<?php
require_once __DIR__ . '/../../config/db.php';

if (isset($_GET['id'])) {
    $id_pedido = $_GET['id'];

    $sql_pedido = "SELECT p.id, p.coste, p.estado, p.fecha, p.hora, u.nombre
                   FROM pedidos p
                   INNER JOIN usuarios u ON p.usuario_id = u.id
                   WHERE p.id = ?";
    $stmt_pedido = $conexion->prepare($sql_pedido);
    $stmt_pedido->bind_param("i", $id_pedido);
    $stmt_pedido->execute();
    $result_pedido = $stmt_pedido->get_result();

    if ($result_pedido->num_rows > 0) {
        $pedido = $result_pedido->fetch_assoc();
    } else {
        echo "Pedido no encontrado.";
        exit;
    }

    $sql_productos = "SELECT pr.nombre AS producto_nombre, pr.precio, lp.cantidad
                      FROM lineas_pedidos lp
                      INNER JOIN productos pr ON lp.producto_id = pr.id
                      WHERE lp.pedido_id = ?";
    $stmt_productos = $conexion->prepare($sql_productos);
    $stmt_productos->bind_param("i", $id_pedido);
    $stmt_productos->execute();
    $result_productos = $stmt_productos->get_result();
    $productos = $result_productos->fetch_all(MYSQLI_ASSOC);
} else {
    echo "No se proporcionó el ID del pedido.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../assets/css/producto-detalle.css">
    <title>Detalles del Pedido</title>
</head>
<body>

    <div class="table-container">
        <h2>Detalles Del producto</h2>
        <table>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Total</th>
            </tr>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= $producto['producto_nombre'] ?></td>
                    <td><?= $producto['cantidad'] ?></td>
                    <td>$<?= number_format($producto['precio'], 0, ',', '.') ?></td>
                    <td>$<?= number_format($producto['precio'] * $producto['cantidad'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>

