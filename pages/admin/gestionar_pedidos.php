<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

if (!isset($_SESSION["user"]) || $_SESSION["user_rol"] != "admin") {
    header("Location: index.php");
    exit();
}

// Cambiar estado si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_estado'])) {
    $pedido_id = intval($_POST['pedido_id']);
    $estado_actual = $_POST['estado_actual'];

    $nuevo_estado = ($estado_actual === 'confirmado') ? 'pendiente' : 'confirmado';

    $update_sql = "UPDATE pedidos SET estado = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $update_sql);
    mysqli_stmt_bind_param($stmt, "si", $nuevo_estado, $pedido_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Obtener pedidos actualizados
$sql = "SELECT id, coste, fecha, estado FROM pedidos ORDER BY fecha DESC";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/pedidos.css">
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
            <form method="POST" style="display:inline;">
              <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
              <input type="hidden" name="estado_actual" value="<?= $pedido['estado'] ?>">
              <button type="submit" name="cambiar_estado"
                class="<?= $pedido['estado'] == 'pendiente' ? 'button-warning' : 'button-success' ?>">
                <?= ucfirst($pedido['estado']) ?>
              </button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>

    </div>
  </div>
</body>
</html>


