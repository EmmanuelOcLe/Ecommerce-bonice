<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

function agregarAlCarrito($id, $cantidad = 1) {
    $existe = false;

    foreach ($_SESSION['carrito'] as &$producto) {
        if ($producto['id'] == $id) {
            $producto['cantidad'] += $cantidad;
            $existe = true;
            break;
        }
    }

    if (!$existe) {
        // Conexión a la base de datos
        $conexion = new mysqli("localhost", "root", "", "bonice");
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $stmt = $conexion->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($nombre, $precio);
        $stmt->fetch();
        $stmt->close();
        $conexion->close();

        if ($nombre && $precio) {
            $_SESSION['carrito'][] = array(
                'id' => $id,
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad
            );
        }
    }
}

function EliminarDelCarrito($id) {
    foreach ($_SESSION['carrito'] as $i => $producto) {
        if ($producto['id'] == $id) {
            unset($_SESSION['carrito'][$i]);
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            break;
        }
    }
}

function vaciarCarrito() {
    $_SESSION['carrito'] = array();
}

function calcularTotalCarrito() {
    $total = 0;
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
    return $total;
}
