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
    <link rel="stylesheet" href="../../assets/css/detalle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container">
    <?php if ($producto): ?>
        <!-- Detalle del producto principal -->
        <div class="producto-principal">
            <div class="producto-imagen">
                <img src="uploads/productos/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
            </div>
            <div class="producto-info">
                <h1 class="producto-titulo"><?= strtoupper(htmlspecialchars($producto['nombre'])) ?></h1>
                <p class="producto-descripcion"><?= htmlspecialchars($producto['descripcion']) ?></p>
                <p class="producto-disponibilidad">
                    Disponibilidad: En stock (
                    <?= isset($producto['stock']) ? htmlspecialchars($producto['stock']) : 'N/A' ?> artículos)
                </p>
                <p class="producto-precio">$<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                <a href="index.php?page=user/carrito&agregar=<?= $producto_id ?>" class="btn-agregar">Agregar al carrito</a>
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
                        <img src="uploads/productos/<?= htmlspecialchars($prod['imagen']) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                        <h3><?= strtoupper(htmlspecialchars($prod['nombre'])) ?></h3>
                        <p class="disponibilidad">
                            Disponibilidad: En stock (
                            <?= isset($prod['stock']) ? htmlspecialchars($prod['stock']) : 'N/A' ?> artículos)
                        </p>
                        <p class="precio">$<?= number_format($prod['precio'], 0, ',', '.') ?></p>
                        <a href="index.php?page=user/detalle&producto=<?= $prod['id'] ?>">
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
            <a href="index.php?page=user/productos" class="volver">← Volver a Productos</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
