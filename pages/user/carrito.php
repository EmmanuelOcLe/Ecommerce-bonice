<?php
// Incluir la configuración de la base de datos y las funciones necesarias
require_once $_SERVER['DOCUMENT_ROOT'] . '/Ecommerce-bonice/config/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Ecommerce-bonice/functions/carrito.php';

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Agregar un producto al carrito
if (isset($_GET['agregar'])) {
    $idProducto = intval($_GET['agregar']);
    agregarAlCarrito($idProducto);

    // Si es una solicitud AJAX, solo devolver la respuesta
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        http_response_code(200);
        exit;
    }

    // Redirigir a la página del carrito
    header("Location: carrito.php");
    exit();
}

// Obtener los productos del carrito
$productosEnCarrito = obtenerCarrito();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/carrito.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="todo">
    

    <main>
        <div class="contenido-principal">
            <div class="seccion-carrito">
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
                                    <?php
                                    // Obtiene el nombre de la imagen o asigna una por defecto
                                    $nombreImagen = !empty($producto['imagen']) ? $producto['imagen'] : 'imagen_default.png';

                                    // Ruta absoluta del sistema (para verificar existencia en disco)
                                    $rutaSistema = __DIR__ . "/../../assets/img/" . $nombreImagen;

                                    // Ruta relativa desde el navegador (para mostrarla en <img>)
                                    $rutaRelativa = "../../assets/img/" . $nombreImagen;

                                    // Si la imagen no existe en el sistema, usa la imagen por defecto
                                    if (!file_exists($rutaSistema)) {
                                        $rutaRelativa = "../../assets/img/imagen_default.png";
                                    }
                                    ?>
                                        <img src="<?= htmlspecialchars($rutaRelativa) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="imagen-producto">
                                    </td>
                                    <td class="columna-nombre">
                                        <?= strtoupper(htmlspecialchars($producto['nombre'])) ?><br>
                                        x <?= htmlspecialchars($producto['descripcion'] ?? '') ?>
                                    </td>
                                    <td class="columna-precio">$<?= number_format($producto['precio'], 1) ?></td>
                                    <td class="columna-cantidad">
                                        <div class="control-cantidad">
                                            <form method="POST" action="../../functions/carrito.php" style="display:inline;">
                                                <input type="hidden" name="aumentar" value="<?= $producto['id'] ?>">
                                                <button class="boton-cantidad aumentar" type="submit">+</button>
                                            </form>
                                            <span class="cantidad"><?= $producto['cantidad'] ?></span>
                                            <form method="POST" action="../../functions/carrito.php" style="display:inline;">
                                                <input type="hidden" name="disminuir" value="<?= $producto['id'] ?>">
                                                <button class="boton-cantidad disminuir" type="submit">-</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="columna-eliminar">
                                        <form method="POST" action="../../functions/carrito.php">
                                            <input type="hidden" name="eliminar" value="<?= $producto['id'] ?>">
                                            <button type="submit" class="boton-eliminar">×</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">No hay productos en el carrito.</td>
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
                                <td><?= htmlspecialchars($producto['nombre']) ?> x<?= $producto['cantidad'] ?></td>
                                <td>$<?= number_format($subtotal, 3) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2">
                                <div class="resumen-pago">
                                    <div class="fila-resumen">
                                        <span class="etiqueta">Cantidad de productos:</span>
                                        <span class="valor"><?= count($productosEnCarrito) ?></span>
                                    </div>
                                    <p>------------------------------------------------------------</p>
                                    <div class="fila-resumen">
                                        <span class="etiqueta">Total a Pagar:</span>
                                        <span class="valor">$<?= number_format($total) ?></span>
                                    </div>
                                    <form method="POST" action="../../pages/user/gestion.php">
                                    <button type="submit" class="boton-pagar">Pagar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>


</div>
</body>
</html>
