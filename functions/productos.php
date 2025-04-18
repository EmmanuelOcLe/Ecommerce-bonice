<?php
include("config/db.php");


function listarProductos() {
    global $conn;

    $sql = "SELECT * FROM productos";
    $resultado = $conn->query($sql);

    $productos = array();

    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }
    }

    return $productos;
}

?>
