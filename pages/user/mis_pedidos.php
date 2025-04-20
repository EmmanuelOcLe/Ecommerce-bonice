<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/db.php';

if (!isset($_SESSION["user"])) {
    header("Location: ../../index.php");
    exit();
}

// Obtener ID del usuario actual desde la sesión
$user_email = $_SESSION['user_email'];
$sqlUsuario = "SELECT id FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conexion, $sqlUsuario);
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
$resultadoUsuario = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultadoUsuario);
mysqli_stmt_close($stmt);

if (!$usuario) {
    echo "Usuario no encontrado";
    exit();
}

$usuario_id = $usuario['id'];

// Obtener pedidos del usuario actual
$sql = "SELECT id, coste, fecha, estado FROM pedidos WHERE usuario_id = ? ORDER BY fecha DESC";
$stmtPedidos = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmtPedidos, "i", $usuario_id);
mysqli_stmt_execute($stmtPedidos);
$resultado = mysqli_stmt_get_result($stmtPedidos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="../../assets/css/global.css">
  <link rel="stylesheet" href="../../assets/css/pedidos.css">
  <title>Mis pedidos</title>
</head>
<body>
<?php include '../../includes/header.php'; ?>
  <div class="todo">
    <div class="contenedor gestionar-pedidos-main-container">
      <h1>MIS PEDIDOS</h1>
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
            <span class="<?= $pedido['estado'] == 'pendiente' ? 'button-warning' : 'button-success' ?>">
              <?= ucfirst($pedido['estado']) ?>
            </span>
          </div>
        </div>
      <?php endwhile; ?>

    </div>
  </div>
</body>
</html>
