<?php
// Insertar los productos del carrito en la tabla lineas_pedido
foreach ($_SESSION['carrito'] as $producto) {
    $producto_id = $producto['producto_id'];
    $cantidad = $producto['cantidad'];

    // Insertar en la tabla lineas_pedido
    $sql_linea = "INSERT INTO lineas_pedido (pedido_id, producto_id, cantidad) 
                  VALUES (?, ?, ?)";
    $stmt_linea = $conexion->prepare($sql_linea);
    $stmt_linea->bind_param("iii", $pedido_id, $producto_id, $cantidad);
    $stmt_linea->execute();
}

?>
