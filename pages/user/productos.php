<?php
require_once 'functions/productos.php';

$categoriaId = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;

$productos = listarProductos($categoriaId); 
?>

<div class="main">
    <div class="product-titule">
        <h2 id="nuestros-productos">Nuestros Productos</h2> 
    </div>

    <div class="cards-container">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                <?php if ($producto["stock"] > 0): ?>
                    <div class="product-card">
                        <a href="pages/user/detalle.php?producto=<?php echo $producto['id']; ?>">
                            <img src="uploads/productos/<?php echo $producto['imagen']; ?>" class="product-image" alt="<?php echo $producto['nombre']; ?>">
                            <h3 class="product-name"><?php echo $producto['nombre']; ?></h3>
                        </a>
                        <p class="product-description"><?php echo $producto['descripcion']; ?></p>
                        <p class="product-price"><strong>$<?php echo number_format($producto['precio'], 2); ?></strong></p>
                        <a href="pages/user/detalle.php?producto=<?php echo $producto['id']; ?>" class="view-more-button">Ver Más</a>
                        <button class="btn-agregar" onclick="agregarAlCarrito(<?php echo $producto['id']; ?>)">Agregar al carrito</button>
                    </div>  
                    <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
    </div>
</div>


<!-- TOAST CSS -->
<style>
.notificacion-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4caf50;
    color: #fff;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 9999;
}

.notificacion-toast.mostrar {
    opacity: 1;
    transform: translateY(0);
}

.notificacion-toast.error {
    background-color: #e74c3c;
}
</style>

<!-- TOAST JS + AJAX -->
<script>
function agregarAlCarrito(idProducto) {
    fetch(`pages/user/carrito.php?agregar=${idProducto}`, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        if (res.ok) {
            mostrarNotificacion("Producto agregado al carrito");
        } else {
            throw new Error("No se pudo agregar");
        }
    })
    .catch(() => {
        mostrarNotificacion("Producto agotado", true);
    });
}

function mostrarNotificacion(mensaje, esError = false) {
    const noti = document.createElement("div");
    noti.className = `notificacion-toast ${esError ? 'error' : ''}`;
    noti.innerText = mensaje;
    document.body.appendChild(noti);

    setTimeout(() => {
        noti.classList.add("mostrar");
        setTimeout(() => {
            noti.classList.remove("mostrar");
            setTimeout(() => document.body.removeChild(noti), 300);
        }, 2500);
    }, 100);
}
</script>
