<?php
  session_start();

  if (!isset($_SESSION["user"]) || isset($_SESSION["user"]) && $_SESSION["user_rol"] != "admin")
  {
    header("Location: ../../index.php");
    exit();
  }
  
  require_once "../../functions/gestionar_categorias.php"; // Incluye la lógica de funciones
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestionar Categorías</title>
  <link rel="icon" href="../../assets/img/icon.png">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="../../assets/css/global.css">
  <link rel="stylesheet" href="../../assets/css/categorias.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <div class="todo">

  <header>
    <div class="header-container">

            <div class="img-container">
                <img src="../../assets/img/bonice.png" alt="logo bonice" class="logo-bonice" style="width: 25%;">
            </div>

            <nav class="navbar">
                <div class="container-fluid">

                    <?php if (isset($_SESSION["user_rol"]) && $_SESSION["user_rol"] == "admin"): ?>
                        <div class="admin-user-icon">
                            <i class="bi bi-person-circle"></i>
                            <i class="bi bi-chevron-down"></i>
                        </div>

                        <div class="admin-user-options">
                            <a href="../../index.php?page=admin/gestionar_productos">Gestionar productos</a>
                            <a href="gestionar_categorias.php">Gestionar categorías</a>
                            <a href="../../index.php?page=admin/gestionar_pedidos">Gestionar pedidos</a>
                            <a href="../../functions/cerrar_sesion.php">Cerrar Sesión</a>
                        </div>
                    <?php endif; ?>

                    <div class="navbar-nav">
                        <?php
                        // Cargar las categorías desde la BD
                        $categorias_menu = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id");

                        // Asignar rutas fijas por ID
                        $urls_por_id = [
                            1 => "../../index.php?page=home",              // Inicio
                            2 => "../../index.php?page=user/productos",    // Productos
                            3 => "../../index.php?page=user/quienes",      // Quienes Somos
                            4 => "../../index.php?page=../../user/equipo"        // Nuestro Equipo
                          
                        ];

                        while ($cat = mysqli_fetch_assoc($categorias_menu)):
                            $id_categoria = $cat['id'];
                            $nombre_categoria = $cat['nombre'];

                            // Determinar la URL según el ID
                            if (array_key_exists($id_categoria, $urls_por_id)) {
                                $url = $urls_por_id[$id_categoria];
                            } else {
                                // Si no está en el array, usa la genérica
                                $url = "index.php?page=user/category&id=$id_categoria";
                            }
                        ?>
                            <a class="nav-link" href="<?= $url ?>"><?= $nombre_categoria ?></a>
                        <?php endwhile; ?>
                    </div>

                </div>
            </nav>

        </div>
    </header>

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
            <a href="?editar=<?= $cat['id'] ?>">
              <i class="bi bi-pencil-fill"></i>
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <footer>
      <div class="footer">
          <p>Desarrollado po Grupo #1 | SENA CDITI 2025</p>
      </div>
    </footer>

  </div>
</body>
</html>