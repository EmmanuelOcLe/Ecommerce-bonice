<?php
include_once(__DIR__ . '/../functions/carrito.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$productosEnCarrito = obtenerCarrito();
?>

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
                        <?php else: ?>
                            <a href="pages/user/mis_pedidos.php">Mis pedidos</a>
                        <?php endif; ?>
                        <a href="functions/cerrar_sesion.php">Cerrar Sesión</a>
                    </div>

                <?php endif; ?>

                <div class="navbar-nav">
                    <?php
                    $categorias_menu = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id");
                    $urls_por_id = [
                        1 => "index.php?page=home",
                        2 => "index.php?page=user/productos",
                        3 => "index.php?page=user/quienes",
                        4 => "index.php?page=user/equipo"
                    ];

                    while ($cat = mysqli_fetch_assoc($categorias_menu)):
                        $id_categoria = $cat['id'];
                        $nombre_categoria = $cat['nombre'];
                        $url = $urls_por_id[$id_categoria] ?? "index.php?page=user/category&id=$id_categoria";
                    ?>
                        <a class="nav-link" href="<?= $url ?>"><?= $nombre_categoria ?></a>
                    <?php endwhile; ?>
                </div>

                <!-- Ícono de carrito con dropdown -->
                <div class="carrito-icono">
                    <div class="dropdown-carrito">
                        <a href="pages/user/carrito.php" title="Ver carrito">
                            <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
                        </a>
                        <?php if (!empty($productosEnCarrito)): ?>
                            <div class="carrito-dropdown">
                                <ul>
                                    <?php foreach ($productosEnCarrito as $producto): ?>
                                        <li>
                                            <?php echo htmlspecialchars($producto['nombre']); ?> -
                                            $<?php echo number_format($producto['precio'], 1); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="pages/user/carrito.php" class="ver-carrito">Ver carrito completo</a>
                            </div>
                        <?php else: ?>
                            <div class="carrito-dropdown">
                                <p>Carrito vacío</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </nav>

    </div>
</header>
