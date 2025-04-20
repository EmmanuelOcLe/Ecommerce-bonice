<?php
require "../../config/db.php";
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
                        <?php if (isset($_SESSION["user_rol"]) && $_SESSION["user_rol"] == "admin"): ?>
                            <a href="../../index.php?page=admin/gestionar_productos">Gestionar productos</a>
                            <a href="../admin/gestionar_categorias.php">Gestionar categorías</a>
                            <a href="../../index.php?page=admin/gestionar_pedidos">Gestionar pedidos</a>
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

</div>


</body>
</html>
