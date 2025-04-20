<?php
include_once __DIR__ . '/../config/db.php';


function listarProductos() {
    global $conexion;

    $sql = "SELECT * FROM productos";
    $resultado = $conexion->query($sql);

    $productos = array();

    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }
    }

    return $productos;
}

?>
