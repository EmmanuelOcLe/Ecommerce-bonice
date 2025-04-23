<?php
session_start();
require_once __DIR__ . '/../../functions/carrito.php';
require_once __DIR__ . '/../../functions/productos.php';

$producto_id = $_GET['producto'] ?? null;
$producto = $producto_id ? obtenerProductoPorId($producto_id) : null;

// Obtener productos relacionados (otros productos, excepto el actual)
$productos_relacionados = listarProductos();
$productos_relacionados = array_filter($productos_relacionados, function($p) use ($producto_id) {
    return $p['id'] != $producto_id;
});

// Para el dropdown del carrito
$productosEnCarrito = obtenerCarrito();

// Para el menú de categorías
require_once __DIR__ . '/../../config/db.php';
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
                        <?php if ($_SESSION["user_rol"] == "admin"): ?>
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
                        <a class="nav-link" href="<?= $url ?>"><?= htmlspecialchars($nombre_categoria) ?></a>
                    <?php endwhile; ?>
                </div>

                <div class="carrito-icono">
                    <div class="dropdown-carrito">
                        <a href="../../index.php?page=user/carrito" title="Ver carrito">
                            <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
                        </a>
                        <?php if (!empty($productosEnCarrito)): ?>
                            <div class="carrito-dropdown">
                                <ul>
                                    <?php foreach ($productosEnCarrito as $item): ?>
                                        <li>
                                            <?= htmlspecialchars($item['nombre']) ?> –
                                            $<?= number_format($item['precio'], 1) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="../../index.php?page=user/carrito" class="ver-carrito">Ver carrito completo</a>
                            </div>
                        <?php else: ?>
                            <div class="carrito-dropdown"><p>Carrito vacío</p></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>




<div class="container">
    <?php if ($producto): ?>
        <div class="producto-principal">
            <div class="producto-imagen">
                <?php
                $nombreImagen = !empty($producto['imagen']) ? $producto['imagen'] : 'imagen_default.png';
                $rutaRelativa = "../../assets/img/" . $nombreImagen;
                $rutaSistema  = __DIR__ . "/../../assets/img/" . $nombreImagen;
                if (!file_exists($rutaSistema)) {
                    $rutaRelativa = "../../assets/img/imagen_default.png";
                }
                ?>
                <img src="<?= htmlspecialchars($rutaRelativa) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
            </div>
            <div class="producto-info">
                <h1 class="producto-titulo"><?= strtoupper(htmlspecialchars($producto['nombre'])) ?></h1>
                <p class="producto-descripcion"><?= htmlspecialchars($producto['descripcion']) ?></p>
                <p class="producto-disponibilidad">
                    Disponibilidad: En stock (<?= htmlspecialchars($producto['stock'] ?? 'N/A') ?> artículos)
                </p>
                <p class="producto-precio">
                    $<?= number_format($producto['precio'], 0, ',', '.') ?>
                </p>
                <button id="btn-agregar-carrito" data-producto-id="<?= $producto_id ?>" class="btn-agregar">Agregar al carrito</button>
            </div>
        </div>

        <!-- Línea rosa -->
        <div class="mid-line"></div>

        <!-- Productos relacionados -->
        <div class="productos-relacionados">
            <div class="productos-carousel">
                <?php 
                $count = 0;
                foreach ($productos_relacionados as $prod):
                    if ($count++ >= 3) break;

                    $imgRel = !empty($prod['imagen']) ? $prod['imagen'] : 'imagen_default.png';
                    $relRel  = "../../assets/img/" . $imgRel;
                    $sysRel  = __DIR__ . "/../../assets/img/" . $imgRel;
                    if (!file_exists($sysRel)) {
                        $relRel = "../../assets/img/imagen_default.png";
                    }
                ?>
                    <div class="producto-relacionado">
                        <img src="<?= htmlspecialchars($relRel) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                        <h3><?= strtoupper(htmlspecialchars($prod['nombre'])) ?></h3>
                        <p class="disponibilidad">
                            En stock (<?= htmlspecialchars($prod['stock'] ?? 'N/A') ?>)
                        </p>
                        <p class="precio">
                            $<?= number_format($prod['precio'], 0, ',', '.') ?>
                        </p>
                        <a href="detalle.php?producto=<?= $prod['id'] ?>">
                            <button class="btn-ver-mas">Ver Más</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="producto-no-encontrado">
            <p>Producto no encontrado.</p>
            <a href="productos.php" class="volver">← Volver a Productos</a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . "/../../includes/footer.php"; ?>

<script>
document.getElementById('btn-agregar-carrito').addEventListener('click', function() {
    var productoId = this.getAttribute('data-producto-id');
    
    // Realizar la solicitud AJAX
    fetch('carrito.php?agregar=' + productoId)
        .then(response => {
            var toast = document.createElement('div');
            toast.classList.add('notificacion-toast');
            
            if (response.status === 200) {
                toast.textContent = 'Producto agregado al carrito';
                toast.classList.add('mostrar');
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.remove('mostrar');
                    document.body.removeChild(toast);
                }, 3000); // Desaparece después de 3 segundos
            } else if (response.status === 409) {
                toast.textContent = 'No hay suficiente stock disponible';
                toast.classList.add('error', 'mostrar');
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.remove('mostrar');
                    document.body.removeChild(toast);
                }, 3000); // Desaparece después de 3 segundos
            }
        })
        .catch(error => {
            console.error('Error al agregar el producto al carrito:', error);
        });
});
</script>


</div>
</body>
</html>