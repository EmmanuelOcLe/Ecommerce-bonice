<?php
require_once __DIR__ . '/../../functions/carrito.php';
require_once __DIR__ . '/../../functions/productos.php';

$producto_id = $_GET['producto'] ?? null;
$producto = $producto_id ? obtenerProductoPorId($producto_id) : null;

// Obtener productos relacionados (otros productos, excepto el actual)
$productos_relacionados = listarProductos();
$productos_relacionados = array_filter($productos_relacionados, function($p) use ($producto_id) {
    return $p['id'] != $producto_id;
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Producto - <?= $producto ? htmlspecialchars($producto['nombre']) : 'No encontrado' ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/detalle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="todo">

<?php
    include_once('../../functions/carrito.php');
    $productosEnCarrito = obtenerCarrito();
?>

<header>
    <div class="header-container">
        <div class="img-container">
            <img src="../../assets/img/bonice.png" alt="logo bonice" class="logo-bonice" style="width: 25%;">
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
                            <a href="../../index.php?page=admin/gestionar_productos">Gestionar productos</a>
                            <a href="../admin/gestionar_categorias.php">Gestionar categorías</a>
                            <a href="../../index.php?page=admin/gestionar_pedidos">Gestionar pedidos</a>
                        <?php else: ?>
                            <a href="mis_pedidos.php">Mis pedidos</a>
                        <?php endif; ?>
                        <a href="../../functions/cerrar_sesion.php">Cerrar Sesión</a>
                    </div>

                <?php endif; ?>

                <div class="navbar-nav">
                    <?php
                    $categorias_menu = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id");
                    $urls_por_id = [
                        1 => "../../index.php?page=home",
                        2 => "../../index.php?page=user/productos",
                        3 => "../../index.php?page=user/quienes",
                        4 => "../../index.php?page=user/equipo"
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
                        <a href="../user/carrito.php" title="Ver carrito">
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
                                <a href="../user/carrito.php" class="ver-carrito">Ver carrito completo</a>
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

<div class="container">
    <?php if ($producto): ?>
        <!-- Detalle del producto principal -->
        <div class="producto-principal">
            <div class="producto-imagen">
                <img src="../../assets/img/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
            </div>
            <div class="producto-info">
                <h1 class="producto-titulo"><?= strtoupper(htmlspecialchars($producto['nombre'])) ?></h1>
                <p class="producto-descripcion"><?= htmlspecialchars($producto['descripcion']) ?></p>
                <p class="producto-disponibilidad">
                    Disponibilidad: En stock (
                    <?= isset($producto['stock']) ? htmlspecialchars($producto['stock']) : 'N/A' ?> artículos)
                </p>
                <p class="producto-precio">$<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                <a href="../../index.php?page=user/carrito&agregar=<?= $producto_id ?>" class="btn-agregar">Agregar al carrito</a>
            </div>
        </div>

        <!-- Línea rosa superior -->
        <div class="mid-line"></div>

        <!-- Productos relacionados -->
        <div class="productos-relacionados">
            <button class="nav-btn prev-btn"><i class="fas fa-chevron-left"></i></button>

            <div class="productos-carousel">
                <?php 
                $count = 0;
                foreach ($productos_relacionados as $prod):
                    if ($count >= 3) break;
                    $count++;
                ?>
                    <div class="producto-relacionado">
                        <img src="../../assets/img/<?= htmlspecialchars($prod['imagen']) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                        <h3><?= strtoupper(htmlspecialchars($prod['nombre'])) ?></h3>
                        <p class="disponibilidad">
                            Disponibilidad: En stock (
                            <?= isset($prod['stock']) ? htmlspecialchars($prod['stock']) : 'N/A' ?> artículos)
                        </p>
                        <p class="precio">$<?= number_format($prod['precio'], 0, ',', '.') ?></p>
                        <a href="../../index.php?page=user/detalle&producto=<?= $prod['id'] ?>">
                            <button class="btn-ver-mas">Ver Más</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="nav-btn next-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
    <?php else: ?>
        <div class="producto-no-encontrado">
            <p>Producto no encontrado.</p>
            <a href="../../index.php?page=user/productos" class="volver">← Volver a Productos</a>
        </div>
    <?php endif; ?>
</div>

<?php include "../../includes/footer.php"; ?>

</div>
</body>
</html>
