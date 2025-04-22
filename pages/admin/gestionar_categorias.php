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
    header("Location: index.php?page=admin/gestionar_categorias");
    exit();
}

// Lógica para crear nueva categoría
if (isset($_POST['crear_categoria'])) {
    $nombre_categoria = $_POST['nombre_categoria'];
    if (!empty($nombre_categoria)) {
        // Función para crear la nueva categoría en la base de datos
        crearCategoria($nombre_categoria);
        header("Location: index.php?page=admin/gestionar_categorias");
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
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/categorias.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <div class="todo">
    <div class="contenedor gestionar-categorias-container">
      <h1>GESTIONAR CATEGORÍAS</h1>

      <!-- Botón para abrir el modal de crear categoría -->
      <button onclick="mostrarModalCrearCategoria()" class="btn-crear-categoria">Crear Nueva Categoría</button>

      <!-- Modal para crear nueva categoría -->
      <div id="modal-crear-categoria" class="modal-overlay" style="display:none;">
        <div class="modal-form">
          <span class="modal-close" onclick="cerrarModalCrearCategoria()">&times;</span>
          <img src="assets/img/logo-bonice.png" alt="Bonice Logo" class="logo-bonice">
          <form method="POST" action="index.php?page=admin/gestionar_categorias" class="formulario-actualizar">
            <input type="text" name="nombre_categoria" placeholder="Nombre de la nueva categoría" required>
            <button type="submit" name="crear_categoria" class="btn-actualizar">Crear Categoría</button>
          </form>
        </div>
      </div>

      <!-- Modal para editar categoría -->
      <?php if ($categoria_editar): ?>
        <div class="modal-overlay">
          <div class="modal-form">
            <span class="modal-close" onclick="window.location.href='index.php?page=admin/gestionar_categorias'">&times;</span>
            <img src="assets/img/logo-bonice.png" alt="Bonice Logo" class="logo-bonice">
            <form method="POST" action="index.php?page=admin/gestionar_categorias" class="formulario-actualizar">
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

      <?php if ($categorias && mysqli_num_rows($categorias) > 0): ?>
        <?php while ($cat = mysqli_fetch_assoc($categorias)): ?>
          <div class="categoria-row">
            <div><?= $cat['id'] ?></div>
            <div><?= $cat['nombre'] ?></div>
            <div>
              <a href="index.php?page=admin/gestionar_categorias&editar=<?= urlencode($cat['id']) ?>"><i class="bi bi-pencil-fill"></i></a>
              <a href="index.php?page=admin/gestionar_categorias&eliminar=<?= urlencode($cat['id']) ?>" onclick="return confirm('¿Seguro que desea eliminar esta categoría?')"><i class="bi bi-trash-fill"></i></a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No se encontraron categorías.</p>
      <?php endif; ?>
    </div>
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
