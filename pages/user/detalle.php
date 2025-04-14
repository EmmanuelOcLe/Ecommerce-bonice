<?php
$producto_id = $_GET['producto'] ?? '0';

$productos = [
    '1' => [
        'nombre' => 'Popetas Surtidas x 3U',
        'descripcion' => 'Sabores surtidos irresistibles para compartir o disfrutar solo. Ideal para compartir en familia, en reuniones o simplemente disfrutar en un día caluroso. ¡Disfruta el doble de sabor en cada mordida!',
        'precio' => '$3.500',
        'imagen' => 'uploads/productos/producto1.png',
        'stock' => '10 artículos'
    ],
    '2' => [
        'nombre' => 'Popetas Dulce Saladas x 1U',
        'descripcion' => 'Una mezcla de dulce y salado para los paladares exigentes.',
        'precio' => '$3.950',
        'imagen' => 'uploads/productos/producto2.png',
        'stock' => '10 artículos'
    ],
    '3' => [
        'nombre' => 'Triangulito Chocolate x 1U',
        'descripcion' => 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor 
        (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos
         y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, 
         sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum..',
        'precio' => '$3.500',
        'imagen' => 'uploads/productos/producto3.png',
        'stock' => '5 artículos'
    ],
    '4' => [
        'nombre' => 'Bonice Doble Surtido x 10U',
        'descripcion' => 'Es la combinación perfecta de sabor y diversión. Cada empaque incluye 10 bonices con doble mezcla de sabores frutales que refrescan y encantan a grandes y chicos, ideal para compartir en familia, en reuniones o simplemente disfrutar en un día caluroso. ¡Disfruta el doble de sabor en cada mordida!',
        'precio' => '$7.900',
        'imagen' => 'uploads/productos/producto4.png',
        'stock' => '10 artículos'
    ],
];

$producto = $productos[$producto_id] ?? null;

// Productos relacionados (excluyendo el producto actual)
$productos_relacionados = [];
foreach ($productos as $id => $prod) {
    if ($id != $producto_id) {
        $productos_relacionados[$id] = $prod;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Producto - <?= $producto ? $producto['nombre'] : 'No encontrado' ?></title>
    <link rel="stylesheet" href="estilos/detalle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    
    
    <div class="container">
        <?php if ($producto): ?>
            <!-- Detalle del producto principal -->
            <div class="producto-principal">
                <div class="producto-imagen">
                    <img src="<?= $producto['imagen'] ?>" alt="<?= $producto['nombre'] ?>">
                </div>
                <div class="producto-info">
                    <h1 class="producto-titulo"><?= strtoupper($producto['nombre']) ?></h1>
                    <p class="producto-descripcion"><?= $producto['descripcion'] ?></p>
                    <p class="producto-disponibilidad">Disponibilidad: En stock (<?= $producto['stock'] ?>)</p>
                    <p class="producto-precio"><?= $producto['precio'] ?></p>
                    <button class="btn-agregar">Agregar Al Carrito</button>
                </div>
            </div>

            <!-- Línea rosa superior -->
            <div class="mid-line"></div>

            <!-- Productos relacionados -->
            <div class="productos-relacionados">
                <button class="nav-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                
                <div class="productos-carousel">
                    <?php 
                    // Limitar a 3 productos relacionados
                    $count = 0;
                    foreach ($productos_relacionados as $id => $prod): 
                        if ($count >= 3) break;
                        $count++;
                    ?>
                        <div class="producto-relacionado">
                            <img src="<?= $prod['imagen'] ?>" alt="<?= $prod['nombre'] ?>">
                            <h3><?= strtoupper($prod['nombre']) ?></h3>
                            <p class="disponibilidad">Disponibilidad: En stock (<?= $prod['stock'] ?>)</p>
                            <p class="precio"><?= $prod['precio'] ?></p>
                            <a href="index.php?page=user/detalle&producto=<?= $id ?>">
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