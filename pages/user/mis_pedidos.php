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
  <link rel="stylesheet" href="../../assets/css/boton-detalles.css">
  <link rel="stylesheet" href="../../assets/css/producto-detalle.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>Mis pedidos</title>

</head>
<body>
<div class="todo">

<?php
  include_once('../../functions/carrito.php');
  $productosEnCarrito = obtenerCarrito();
?>

<header>
<div class="header-container">
    <!-- Logo -->
    <div class="img-container">
      <a href="index.php?page=home">
        <img src="../../assets/img/bonice.png" alt="Logo Bonice" class="logo-bonice" style="width:25%;">
      </a>
    </div>

    <!-- Navegación -->
    <nav class="navbar">
      <div class="container-fluid">

        <!-- Opciones de usuario/admin -->
        <?php if (isset($_SESSION["user"])): ?>
          <div class="admin-user-icon">
            <i class="bi bi-person-circle"></i>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="admin-user-options">
            <?php if ($_SESSION["user_rol"] === "admin"): ?>
              <a href="../../index.php?page=admin/gestionar_productos">Gestionar productos</a>
              <a href="../../index.php?page=admin/gestionar_categorias">Gestionar categorías</a>
              <a href="../../index.php?page=admin/gestionar_pedidos">Gestionar pedidos</a>
            <?php endif; ?>
            <a href="../../functions/cerrar_sesion.php">Cerrar Sesión</a>
          </div>
        <?php endif; ?>

        <!-- Enlaces principales -->
        <div class="navbar-nav">
          <a class="nav-link" href="../../index.php?page=home">Inicio</a>

          <!-- Dropdown de “Productos” -->
          <div class="dropdown-productos">
            <a class="nav-link" href="../../index.php?page=user/productos">Productos</a>
            <div class="dropdown-content">
              <?php
                $categorias_menu = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id");
                while ($cat = mysqli_fetch_assoc($categorias_menu)):
              ?>
                <a href="../../index.php?page=user/productos&categoria=<?= $cat['id'] ?>">
                  <?= htmlspecialchars($cat['nombre']) ?>
                </a>
              <?php endwhile; ?>
            </div>
          </div>

          <a class="nav-link" href="../../index.php?page=user/quienes">Quiénes Somos</a>
          <a class="nav-link" href="../../index.php?page=user/equipo">Nuestro Equipo</a>
        </div>

        <!-- Carrito -->
        <div class="carrito-icono">
          <a href="index.php?page=user/carrito" title="Ver carrito">
            <img class="carrito" src="../../assets/img/Shopping_car.png" alt="Icono carrito">
          </a>
          <?php if (!empty($productosEnCarrito)): ?>
            <div class="carrito-dropdown">
              <ul>
                <?php foreach ($productosEnCarrito as $p): ?>
                  <li><?= htmlspecialchars($p['nombre']) ?> – $<?= number_format($p['precio'], 1) ?></li>
                <?php endforeach; ?>
              </ul>
              <a href="../../index.php?page=user/carrito" class="ver-carrito">Ver carrito completo</a>
            </div>
          <?php else: ?>
            <div class="carrito-dropdown"><p>Carrito vacío</p></div>
          <?php endif; ?>
        </div>

      </div>
    </nav>
  </div>
</header>


  <div class="contenedor gestionar-pedidos-main-container">
    <h1>MIS PEDIDOS</h1>
    <div class="gestionar-pedidos-row header">
      <div>N° pedido</div>
      <div>Precio</div>
      <div>Fecha</div>
      <div>Estado</div>
    </div>

    <?php while ($pedido = mysqli_fetch_assoc($resultado)) : ?>
      <?php
        $estado_clase = match ($pedido['estado']) {
          'pendiente'     => 'estado-pendiente',
          'en proceso'    => 'estado-en-proceso',
          'enviado'       => 'estado-enviado',
          'entregado'     => 'estado-entregado',
          default         => 'estado-desconocido',
        };
      ?>
            <div class="gestionar-pedidos-row">
              <div><?= htmlspecialchars($pedido['id']) ?></div>
              <div>$<?= number_format($pedido['coste'], 0, ',', '.') ?></div>
              <div><?= htmlspecialchars($pedido['fecha']) ?></div>
              <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="estado-label <?= $estado_clase ?>">
                  <?= ucfirst($pedido['estado']) ?>
                </span>
                
                <form action="detalle_pedido.php" method="GET">
                  <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                  <button type="submit" class="boton-detalles">Detalles</button>
                </form>
              </div>
            </div>
    <?php endwhile; ?>

  </div>

  <?php include "../../includes/footer.php"; ?>
</div>
</body>
</html>
