<?php
require_once 'functions/productos.php'; 
$productos = listarProductos(); 
?>

<div class="main">

    <div class="product-titule">
        <h2 id="nuestros-productos">Nuestros Productos</h2> 
    </div>

    <div class="cards-container">

        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>

                <div class="product-card">
                    <!-- Enlace a la página de detalle del producto con el ID dinámico -->
                    <a href="index.php?page=user/detalle&producto=<?php echo $producto['id']; ?>">
                        <img src="uploads/productos/<?php echo $producto['imagen']; ?>" class="product-image"  alt="<?php echo $producto['nombre']; ?>">
                        <h3 class="product-name"><?php echo $producto['nombre']; ?></h3>
                    </a>

                    <p class="product-description"><?php echo $producto['descripcion']; ?></p>
                    <p class="product-price"><strong>$<?php echo number_format($producto['precio'], 2); ?></strong></p>

                    <!-- Enlace "Ver Más" con el ID dinámico -->
                    <a href="index.php?page=user/detalle&producto=<?php echo $producto['id']; ?>" class="view-more-button">Ver Más</a>
                    
                    <!-- Enlace para agregar al carrito con el ID del producto -->
                    <a href="pages/user/carrito.php?agregar=<?= $producto['id'] ?>" class="btn-agregar">Agregar al carrito</a>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>

    </div>
</div>