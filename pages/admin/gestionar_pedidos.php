<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';


if (!isset($_SESSION["user"]) || $_SESSION["user_rol"] != "admin") {
    header("Location: index.php");
    exit();
}

// Cambiar estado si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_estado'])) {
    $pedido_id = intval($_POST['pedido_id']);
    $nuevo_estado = $_POST['nuevo_estado'];

    $estados_validos = ['pendiente', 'en proceso', 'enviado', 'entregado'];
    if (in_array($nuevo_estado, $estados_validos)) {
        $update_sql = "UPDATE pedidos SET estado = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $update_sql);
        mysqli_stmt_bind_param($stmt, "si", $nuevo_estado, $pedido_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Obtener pedidos actualizados
$sql = "SELECT id, coste, fecha, estado FROM pedidos ORDER BY id DESC";
$resultado = mysqli_query($conexion, $sql);

// Lista de estados
$estados = ['pendiente', 'en proceso', 'enviado', 'entregado'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/pedidos.css">
  <link rel="stylesheet" href="assets/css/boton-detalles.css">
  <link rel="stylesheet" href="../../assets/css/producto-detalle.css">

  <title>Gestionar pedidos</title>
</head>
<body>
  <div class="todo">
    <div class="contenedor gestionar-pedidos-main-container">
      <h1>GESTIONAR PEDIDOS</h1>
      <div class="gestionar-pedidos-row header">
        <div>N° pedido</div>
        <div>Precio</div>
        <div>Fecha</div>
        <div>Estado</div>
      </div>

      <?php while ($pedido = mysqli_fetch_assoc($resultado)) : ?>
  <div class="gestionar-pedidos-row">
    <div><?= htmlspecialchars($pedido['id']) ?></div>
    <div>$<?= number_format($pedido['coste'], 0, ',', '.') ?></div>
    <div><?= htmlspecialchars($pedido['fecha']) ?></div>
    <div>
      <form method="POST" style="display:flex; align-items:center; gap: 0.5rem;">
        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
        <select name="nuevo_estado">
          <?php foreach ($estados as $estado): ?>
            <option value="<?= $estado ?>" <?= $estado == $pedido['estado'] ? 'selected' : '' ?>>
              <?= ucfirst($estado) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" name="cambiar_estado" class="button-warning">Actualizar</button>
      </form>
    </div>
    <form action="pages/admin/detalle_pedido.php" method="GET">
      <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
      <button type="submit" class="boton-detalles">Detalles</button>
    </form>
  </div>
<?php endwhile; ?>

    </div>
  </div>
</body>
</html>
