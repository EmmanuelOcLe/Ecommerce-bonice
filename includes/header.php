<header>
    <div class="header-container">

        <div class="img-container">
            <img src="assets/img/bonice.png" alt="logo bonice" class="logo-bonice">
        </div>

        <nav class="navbar">
            <div class="container-fluid">

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
                        <?php endif; ?>
                        
                        <a href="functions/cerrar_sesion.php">Cerrar Sesión</a>
                    </div>
                <?php endif; ?>

                <div class="navbar-nav">
                    <?php
                    // Cargar las categorías desde la BD
                    $categorias_menu = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id");

                    // Asignar rutas fijas por ID
                    $urls_por_id = [
                        1 => "index.php?page=home",              // Inicio
                        2 => "index.php?page=user/productos",    // Productos
                        3 => "index.php?page=user/quienes",      // Quienes Somos
                        4 => "index.php?page=user/equipo"        // Nuestro Equipo
                      
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