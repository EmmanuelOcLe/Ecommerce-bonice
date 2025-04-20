<?php
include_once(__DIR__ . '/../../functions/carrito.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si se agregó un producto, agrégalo al carrito
if (isset($_GET['agregar'])) {
    $idProducto = intval($_GET['agregar']);
    agregarAlCarrito($idProducto);

    // Si es una petición AJAX, no redirigir
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        http_response_code(200);
        exit;
    }

    // Redirige normalmente solo si NO es AJAX
    header("Location: carrito.php");
    exit();
}

$productosEnCarrito = obtenerCarrito();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/carrito.css">
</head>
<body>

<?php include_once '../../includes/header-usuario.php'; ?>

<main>
    <div class="contenido-principal">
        <div class="seccion-carrito">
            <div class="barra-busqueda">
                <input type="text" placeholder="Buscar productos...">
            </div>

            <table class="tabla-carrito">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($productosEnCarrito)): ?>
                        <?php foreach ($productosEnCarrito as $producto): ?>
                            <tr>
                                <td class="columna-imagen">
                                    <img src="../../uploads/productos/<?php echo htmlspecialchars($producto['imagen'] ?? 'imagen_default.png'); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                </td>
                                <td class="columna-nombre">
                                    <?php echo strtoupper(htmlspecialchars($producto['nombre'])); ?><br>
                                    x <?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?>
                                </td>
                                <td class="columna-precio">$<?php echo number_format($producto['precio'], 1); ?></td>
                                <td class="columna-cantidad">
                                    <div class="control-cantidad">
                                        <form method="POST" action="../../functions/carrito.php" style="display: inline;">
                                            <input type="hidden" name="aumentar" value="<?php echo $producto['id']; ?>">
                                            <button class="boton-cantidad aumentar" type="submit">+</button>
                                        </form>
                                        <span class="cantidad"><?php echo $producto['cantidad']; ?></span>
                                        <form method="POST" action="../../functions/carrito.php" style="display: inline;">
                                            <input type="hidden" name="disminuir" value="<?php echo $producto['id']; ?>">
                                            <button class="boton-cantidad disminuir" type="submit">-</button>
                                        </form>
                                    </div>
                                </td>
                                <td class="columna-eliminar">
                                    <form method="POST" action="../../functions/carrito.php">
                                        <input type="hidden" name="eliminar" value="<?php echo $producto['id']; ?>">
                                        <button type="submit" class="boton-eliminar">×</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No hay productos en el carrito.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="resumen-pedido">
            <h3>RESUMEN DE TU PEDIDO</h3>
            <table class="tabla-recibo">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($productosEnCarrito as $producto): 
                        $subtotal = $producto['precio'] * $producto['cantidad'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td class="columna-nombre-recibo"><?php echo htmlspecialchars($producto['nombre']); ?> x<?php echo $producto['cantidad']; ?></td>
                            <td class="columna-precio-recibo">$<?php echo number_format($subtotal, 3); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td class="cantidad">Cantidad de productos: <?php echo count($productosEnCarrito); ?></td>
                        <td class="total">Total a Pagar: $<?php echo number_format($total); ?></td>
                    </tr>
                    <tr>
                        <form method="POST" action="../../pages/user/gestion.php">
                            <button type="submit" class="boton-pagar">Pagar</button>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include_once '../../includes/footer.php'; ?>

</body>
</html>
