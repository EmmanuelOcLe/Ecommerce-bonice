<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"]) || $_SESSION["user_rol"] !== "admin") {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . "/../../config/db.php");
require_once(__DIR__ . "/../../functions/gestionar_categorias.php");
require_once(__DIR__ . "/../../functions/carrito.php");

$productosEnCarrito = obtenerCarrito();

// Lógica para editar categoría
$categoria_editar = null;
if (isset($_GET['editar'])) {
    $id_categoria = intval($_GET['editar']);
    $categoria_editar = obtenerCategoriaPorId($id_categoria);
}

// Lógica para eliminar categoría
if (isset($_GET['eliminar'])) {
    $id_categoria = intval($_GET['eliminar']);
    eliminarCategoria($id_categoria);
    header("Location: gestionar_categorias.php");
    exit();
}

// Lógica para crear nueva categoría
if (isset($_POST['crear_categoria'])) {
    $nombre_categoria = $_POST['nombre_categoria'];
    if (!empty($nombre_categoria)) {
        crearCategoria($nombre_categoria);
        header("Location: gestionar_categorias.php");
        exit();
    }
}

$categorias = obtenerCategorias();

// Función para crear categoría
function crearCategoria($nombre) {
    global $conexion;
    $query = "INSERT INTO categorias (nombre) VALUES (?)";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $nombre);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestionar Categorías</title>
  <link rel="icon" href="assets/img/icon.png">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="../../assets/css/global.css">
  <link rel="stylesheet" href="../../assets/css/categorias.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <div class="todo">

  <header>
  <div class="header-container">
    <!-- Logo -->
    <div class="img-container">
      <a href="../../index.php?page=home">
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
          <?php if (isset($_SESSION["user_rol"]) && $_SESSION["user_rol"] == "admin"): ?>
                          <a href="../../index.php?page=admin/gestionar_productos">Gestionar productos</a>
                          <a href="gestionar_categorias.php">Gestionar categorías</a>
                          <a href="../../index.php?page=admin/gestionar_pedidos">Gestionar pedidos</a>
                      <?php else: ?>
                  <a href="../user/mis_pedidos.php">Mis pedidos</a>
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
          <a href="../../index.php?page=user/carrito" title="Ver carrito">
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

    <div class="contenedor gestionar-categorias-container">
      <h1>GESTIONAR CATEGORÍAS</h1>

      <!-- Botón para abrir el modal de crear categoría -->
      <button onclick="mostrarModalCrearCategoria()" class="btn-crear-categoria">Crear Nueva Categoría</button>

      <!-- Modal para crear nueva categoría -->
      <div id="modal-crear-categoria" class="modal-overlay" style="display:none;">
        <div class="modal-form">
          <span class="modal-close" onclick="cerrarModalCrearCategoria()">&times;</span>
          <img src="../../assets/img/logo-bonice.png" alt="Bonice Logo" class="logo-bonice">
          <form method="POST" action="gestionar_categorias.php" class="formulario-actualizar">
            <input type="text" name="nombre_categoria" placeholder="Nombre de la nueva categoría" required>
            <button type="submit" name="crear_categoria" class="btn-actualizar">Crear Categoría</button>
          </form>
        </div>
      </div>

      <!-- Modal para editar categoría -->
      <?php if ($categoria_editar): ?>
        <div class="modal-overlay">
          <div class="modal-form">
            <span class="modal-close" onclick="window.location.href='../../index.php?page=admin/gestionar_categorias'">&times;</span>
            <img src="../../assets/img/logo-bonice.png" alt="Bonice Logo" class="logo-bonice">
            <form method="POST" action="gestionar_categorias.php" class="formulario-actualizar">
              <input type="hidden" name="id" value="<?= $categoria_editar['id'] ?>">
              <input type="text" name="nombre" value="<?= htmlspecialchars($categoria_editar['nombre']) ?>" required>
              <button type="submit" name="actualizar" class="btn-actualizar">Actualizar</button>
            </form>
          </div>
        </div>
      <?php endif; ?>

      <div class="categoria-row first-categoria-row">
        <div>ID</div>
        <div>Nombre</div>
        <div>Acciones</div>
      </div>

      <?php if ($categorias && count($categorias) > 0): ?>
        <?php foreach ($categorias as $cat): ?>
          <div class="categoria-row">
            <div><?= $cat['id'] ?></div>
            <div><?= $cat['nombre'] ?></div>
            <div>
              <a href="gestionar_categorias?editar=<?= urlencode($cat['id']) ?>"><i class="bi bi-pencil-fill"></i></a>
              <a href="gestionar_categorias?eliminar=<?= urlencode($cat['id']) ?>" onclick="return confirm('¿Seguro que desea eliminar esta categoría? Hacer esto eliminará todos los productos asociados a esta categoría y a la vez los pedidos relacionados a estos productos.')"><i class="bi bi-trash-fill"></i></a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No se encontraron categorías.</p>
      <?php endif; ?>
    </div>

    <footer>
    <div class="footer">
        <p>Desarrollado por Grupo #1 | SENA CDITI 2025</p>
    </div>
</footer>
  </div>

  <script>
    // Funciones de apertura y cierre del modal para crear categoría
    function mostrarModalCrearCategoria() {
      document.getElementById("modal-crear-categoria").style.display = "flex";
    }

    function cerrarModalCrearCategoria() {
      document.getElementById("modal-crear-categoria").style.display = "none";
    }
  </script>
</body>
</html>
