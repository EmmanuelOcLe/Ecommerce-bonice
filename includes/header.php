<?php
include_once(__DIR__ . '/../config/db.php');
include_once(__DIR__ . '/../functions/carrito.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$productosEnCarrito = obtenerCarrito();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bonice</title>
  <link rel="stylesheet" href="assets/css/global.css">
</head>
<body>
<header>
  <div class="header-container">
    <!-- Logo -->
    <div class="img-container">
      <a href="index.php?page=home">
        <img src="assets/img/bonice.png" alt="Logo Bonice" class="logo-bonice" style="width:25%;">
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
          <?php if (isset($_SESSION["user_rol"]) && $_SESSION["user_rol"] == "admin"): ?>
                          <a href="index.php?page=admin/gestionar_productos">Gestionar productos</a>
                          <a href="pages/admin/gestionar_categorias.php">Gestionar categorías</a>
                          <a href="index.php?page=admin/gestionar_pedidos">Gestionar pedidos</a>
                      <?php else: ?>
                  <a href="pages/user/mis_pedidos.php">Mis pedidos</a>
                  <?php endif; ?>
              <a href="functions/cerrar_sesion.php">Cerrar Sesión</a>
          </div>
        <?php endif; ?>

        <!-- Enlaces principales -->
        <div class="navbar-nav">
          <a class="nav-link" href="index.php?page=home">Inicio</a>

          <!-- Dropdown de “Productos” -->
          <div class="dropdown-productos">
            <a class="nav-link" href="index.php?page=user/productos">Productos</a>
            <div class="dropdown-content">
              <?php
                $categorias_menu = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id");
                while ($cat = mysqli_fetch_assoc($categorias_menu)):
              ?>
                <a href="index.php?page=user/productos&categoria=<?= $cat['id'] ?>">
                  <?= htmlspecialchars($cat['nombre']) ?>
                </a>
              <?php endwhile; ?>
            </div>
          </div>

          <a class="nav-link" href="index.php?page=user/quienes">Quiénes Somos</a>
          <a class="nav-link" href="index.php?page=user/equipo">Nuestro Equipo</a>
        </div>

        <!-- Carrito -->
        <div class="carrito-icono">
          <a href="index.php?page=user/carrito" title="Ver carrito">
            <img class="carrito" src="assets/img/Shopping_car.png" alt="Icono carrito">
          </a>
          <?php if (!empty($productosEnCarrito)): ?>
            <div class="carrito-dropdown">
              <ul>
                <?php foreach ($productosEnCarrito as $p): ?>
                  <li><?= htmlspecialchars($p['nombre']) ?> – $<?= number_format($p['precio'], 1) ?></li>
                <?php endforeach; ?>
              </ul>
              <a href="index.php?page=user/carrito" class="ver-carrito">Ver carrito completo</a>
            </div>
          <?php else: ?>
            <div class="carrito-dropdown"><p>Carrito vacío</p></div>
          <?php endif; ?>
        </div>

      </div>
    </nav>
  </div>
</header>
</body>
</html>