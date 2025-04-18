<?php
require_once '../../functions/carrito.php';

if (isset($_GET['agregar'])) {
    agregarAlCarrito($_GET['agregar']);
    header("Location: carrito.php"); // evitar recarga doble
    exit;
}
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
    <header>
        <div class="encabezado">
            <div class="logo-contenedor">
                <img src="../../assets/img/bonice.png" alt="logo bonice" class="logo">
            </div>

            <nav class="barra-navegacion">
                <div class="navegacion-contenedor">
                    <!-- Parte izquierda (usuario) -->
                    <div class="usuario">
                        <img src="../../assets/img/user.png" class="icono-usuario" alt="Usuario">
                    </div>

                    <!-- Parte central (enlaces) -->
                    <div class="menu">
                        <a class="enlace" href="index.php?page=home">Inicio</a>
                        <a class="enlace" href="index.php?page=user/productos">Productos</a>
                        <a class="enlace" href="index.php?page=user/quienes">Quienes Somos</a>
                        <a class="enlace" href="index.php?page=user/equipo">Nuestro Equipo</a>
                    </div>

                    <!-- Parte derecha (carrito) -->
                    <div class="carrito">
                        <a class="icono-carrito-enlace" href="index.php?page=user/carrito">
                            <img src="../../assets/img/Shopping_car.png" alt="Carrito de compras" class="icono-carrito">
                        </a>
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
                        <tr>
                            <td class="columna-imagen">
                                <img src="../../uploads/productos/producto1.png" alt="BonIce Doble Surtido">
                            </td>
                            <td class="columna-nombre">
                                BONICE DOBLE<br>SURTIDO x 100
                            </td>
                            <td class="columna-precio">$7.950</td>
                            <td class="columna-cantidad">
                            <div class="control-cantidad" data-id="<?= $producto['id'] ?>">
                                <button class="boton-cantidad disminuir">-</button>
                                <input type="text" class="cantidad" value="1" readonly>
                                <button class="boton-cantidad aumentar">+</button>
                            </div>
                            </td>
                            <td class="columna-eliminar">
                                <button class="boton-eliminar">×</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="columna-imagen">
                                <img src="../../uploads/productos/producto4.png" alt="Paletas Surtidas">
                            </td>
                            <td class="columna-nombre">
                                PALETAS SURTIDAS<br>x 30
                            </td>
                            <td class="columna-precio">$15.950</td>
                            <td class="columna-cantidad">
                                <div class="control-cantidad">
                                    <button class="boton-cantidad aumentar">+</button>
                                    <span class="cantidad">1</span>
                                    <button class="boton-cantidad disminuir">-</button>
                                </div>
                            </td>
                            <td class="columna-eliminar">
                                <button class="boton-eliminar">×</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="resumen-pedido">
            <h3>RESUMEN DE TU PEDIDO - 1</h3>
            <table class="tabla-recibo">
                <thead>
                    <tr>
                        <th><b>Producto</b></th>
                        <th><b>Precio</b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="columna-nombre-recibo">BonIce Doble Surtido x 100</td>
                        <td class="columna-precio-recibo">$7.950</td>
                    </tr>
                    <tr>
                        <td class="columna-nombre-recibo">Paletas Surtidas x 30</td>
                        <td class="columna-precio-recibo">$15.950</td>
                    </tr>

                    <tr>
                        <div class="contenerdor-footer">
                            <td class="cantidad">Cantidad de productos:2</td>
                            <td class="total">Total a Pagar : 32.960</td>
                        </div>
                    </tr>

                    <tr>
                        <td >
                            <button class="boton-pagar">Pagar</button>
                        </td>

                    </tr>
                </tbody>
            </table>
            
            </div>
        </div>
    </main>

    <?php 
        include_once '../../includes/footer.php';
    ?>
</body>
<script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const botonesAumentar = document.querySelectorAll('.boton-cantidad.aumentar');
    const botonesDisminuir = document.querySelectorAll('.boton-cantidad.disminuir');

    botonesAumentar.forEach(boton => {
        boton.addEventListener('click', () => {
            const contenedor = boton.closest('.control-cantidad');
            const input = contenedor.querySelector('.cantidad');
            let cantidad = parseInt(input.value);
            input.value = cantidad + 1;
        });
    });

    botonesDisminuir.forEach(boton => {
        boton.addEventListener('click', () => {
            const contenedor = boton.closest('.control-cantidad');
            const input = contenedor.querySelector('.cantidad');
            let cantidad = parseInt(input.value);
            if (cantidad > 1) {
                input.value = cantidad - 1;
            }
        });
    });
});
</script>

</script>
</html>
