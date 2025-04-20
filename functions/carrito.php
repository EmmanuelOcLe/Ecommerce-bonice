<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Obtener producto por ID desde la base de datos
function obtenerProductoPorId($id) {
    $conexion = new mysqli("localhost", "root", "123456789", "bonice");

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $stmt = $conexion->prepare("SELECT id, nombre, precio, descripcion, imagen FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();

    $stmt->close();
    $conexion->close();

    return $producto;
}

// Agregar producto al carrito
function agregarAlCarrito($idProducto, $cantidad = 1) {
    if (isset($_SESSION['carrito'][$idProducto])) {
        $_SESSION['carrito'][$idProducto]['cantidad'] += $cantidad;
    } else {
        $producto = obtenerProductoPorId($idProducto);
        if ($producto) {
            $_SESSION['carrito'][$idProducto] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'descripcion' => $producto['descripcion'],
                'imagen' => $producto['imagen'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad
            ];
        }
    }
}

// Eliminar producto del carrito
function eliminarDelCarrito($id) {
    unset($_SESSION['carrito'][$id]);
}

// Aumentar cantidad de producto
function aumentarCantidad($id) {
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad']++;
    }
}

// Disminuir cantidad de producto
function disminuirCantidad($id) {
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad']--;
        if ($_SESSION['carrito'][$id]['cantidad'] <= 0) {
            eliminarDelCarrito($id);
        }
    }
}

// Vaciar el carrito completo
function vaciarCarrito() {
    $_SESSION['carrito'] = [];
}

// Calcular el total del carrito
function calcularTotalCarrito() {
    $total = 0;
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
    return $total;
}

// Obtener el contenido del carrito
function obtenerCarrito() {
    return $_SESSION['carrito'];
}

// Manejo de acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        eliminarDelCarrito((int) $_POST['eliminar']);
    } elseif (isset($_POST['aumentar'])) {
        aumentarCantidad((int) $_POST['aumentar']);
    } elseif (isset($_POST['disminuir'])) {
        disminuirCantidad((int) $_POST['disminuir']);
    } elseif (isset($_POST['vaciar'])) {
        vaciarCarrito();
    }

    header("Location: ../pages/user/carrito.php");
    exit();
}
