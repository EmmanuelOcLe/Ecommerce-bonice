<?php
require_once('../../functions/gestionar_categorias.php');  // Incluye la lógica de funciones
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestionar Categorías</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="../../assets/css/global.css">
  <link rel="stylesheet" href="../../assets/css/categorias.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <div class="todo">
    <div class="contenedor gestionar-categorias-container">
      <h1>GESTIONAR CATEGORÍAS</h1>
      <a href="../../index.php" class="btn-retorno">← Volver al inicio</a>

      <?php if ($categoria_editar): ?>
        <div class="modal-overlay">
          <div class="modal-form">
            <span class="modal-close" onclick="window.location.href='gestionar_categorias.php'">&times;</span>
            <img src="../../assets/img/logo-bonice.png" alt="Bonice Logo" class="logo-bonice">
            <form method="POST" class="formulario-actualizar">
              <input type="hidden" name="id" value="<?= $categoria_editar['id'] ?>">
              <input type="text" name="nombre" value="<?= $categoria_editar['nombre'] ?>" required>
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

      <?php while ($cat = mysqli_fetch_assoc($categorias)): ?>
        <div class="categoria-row">
          <div><?= $cat['id'] ?></div>
          <div><?= $cat['nombre'] ?></div>
          <div>
            <a href="gestionar_categorias.php?editar=<?= $cat['id'] ?>">
              <i class="bi bi-pencil-fill"></i>
            </a>
            <a href="gestionar_categorias.php?eliminar=<?= $cat['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar esta categoría?');">
              <i class="bi bi-trash-fill"></i>
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>