<?php

include_once __DIR__ . '/../config/db.php';

function listarProductos() {
    global $conexion;

    $sql = "SELECT * FROM productos";
    $resultado = $conexion->query($sql);

    $productos = [];

    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }
    }

    return $productos;
}

function crearProducto($nombre, $descripcion, $precio, $stock, $categoria_id, $imagen) {
    global $conexion;

    $nombreImagen = basename($imagen["name"]);
    $rutaDestino = __DIR__ . "/../assets/img/" . $nombreImagen; // ✅ Misma ruta que en la vista

    if (!move_uploaded_file($imagen["tmp_name"], $rutaDestino)) {
        die("Error al subir la imagen.");
    }

    $stmt = $conexion->prepare("INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, imagen, fecha)
                                VALUES (?, ?, ?, ?, ?, ?, NOW())");

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("issdis", $categoria_id, $nombre, $descripcion, $precio, $stock, $nombreImagen);
    $stmt->execute();
    $stmt->close();
}

function eliminarProducto($id) {
    global $conexion;

    $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

if (!function_exists('obtenerProductoPorId')) {
    function obtenerProductoPorId($id) {
        global $conexion;

        $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();

        $stmt->close();
        return $producto;
    }
}

function actualizarProducto($id, $nombre, $descripcion, $precio, $stock, $categoria_id, $imagen = null) {
    global $conexion;

    if ($imagen && $imagen['size'] > 0) {
        $nombreImagen = basename($imagen["name"]);
        $rutaDestino = __DIR__ . "/../assets/img/" . $nombreImagen; // ✅ Ruta consistente

        if (!move_uploaded_file($imagen["tmp_name"], $rutaDestino)) {
            die("Error al subir la nueva imagen.");
        }

        $stmt = $conexion->prepare("UPDATE productos 
            SET nombre = ?, descripcion = ?, precio = ?, stock = ?, categoria_id = ?, imagen = ?
            WHERE id = ?");

        if (!$stmt) {
            die("Error al preparar la consulta: " . $conexion->error);
        }

        $stmt->bind_param("ssdissi", $nombre, $descripcion, $precio, $stock, $categoria_id, $nombreImagen, $id);
    } else {
        $stmt = $conexion->prepare("UPDATE productos 
            SET nombre = ?, descripcion = ?, precio = ?, stock = ?, categoria_id = ?
            WHERE id = ?");

        if (!$stmt) {
            die("Error al preparar la consulta: " . $conexion->error);
        }

        $stmt->bind_param("ssdisi", $nombre, $descripcion, $precio, $stock, $categoria_id, $id);
    }

    $stmt->execute();
    $stmt->close();
}
