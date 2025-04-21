<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user_rol"] != "admin") {
    header("Location: ../index.php");
    exit();
}
require_once '../../config/db.php';

if (isset($_POST['pedido_id'])) {
    $pedido_id = intval($_POST['pedido_id']); 

    // Obtener pedido con datos del usuario
    $sql = "SELECT p.*, u.nombre FROM pedidos p 
            JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.id = $pedido_id";
    $resultado = mysqli_query($conexion, $sql);
    $pedido = mysqli_fetch_assoc($resultado);

    // Obtener productos del pedido
    $sql_productos = "SELECT lp.*, pr.nombre AS producto_nombre 
                      FROM lineas_pedidos lp
                      JOIN productos pr ON lp.producto_id = pr.id
                      WHERE lp.pedido_id = $pedido_id";

    $productos_result = mysqli_query($conexion, $sql_productos);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalles del Pedido</title>
  <link rel="stylesheet" href="../assets/css/global.css">
  <style>
    .detalle-container {
      width: 80%;
      margin: auto;
      background: #f4f4f4;
      padding: 20px;
      border-radius: 10px;
    }
    .detalle-container h2 {
      text-align: center;
    }
    table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }
    table, th, td {
      border: 1px solid #999;
    }
    th, td {
      padding: 10px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="detalle-container">
    <h2>Detalles del Pedido #<?php echo $pedido['id']; ?></h2>
    <p><strong>Nombre:</strong> <?php echo $pedido['nombre']; ?></p>
    <p><strong>Dirección:</strong> <?php echo $pedido['direccion']; ?></p>
    <p><strong>Fecha:</strong> <?php echo $pedido['fecha']; ?></p>
    <p><strong>Estado:</strong> <?php echo $pedido['estado']; ?></p>

    <table>
      <thead>
        <tr>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio Unitario</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($prod = mysqli_fetch_assoc($productos_result)) { ?>
          <tr>
            <td><?php echo $prod['producto_nombre']; ?></td>
            <td><?php echo $prod['cantidad']; ?></td>
            <td>$<?php echo number_format($prod['precio'], 0, '', '.'); ?></td>
            <td>$<?php echo number_format($prod['precio'] * $prod['cantidad'], 0, '', '.'); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>
