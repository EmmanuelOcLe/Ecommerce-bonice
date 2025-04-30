<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php'; // Conexión centralizada





// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}



// Obtener producto por ID desde la base de datos
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


// Obtener stock disponible de un producto
function obtenerStockDisponible($idProducto) {
    global $conexion;

    $stmt = $conexion->prepare("SELECT stock FROM productos WHERE id = ?");
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $producto = $resultado->fetch_assoc();
    $stmt->close();

    return $producto ? $producto['stock'] : 0;  // Retorna 0 si no se encuentra el producto
}


// Agregar producto al carrito
// Agregar producto al carrito
// Agregar producto al carrito
function agregarAlCarrito($idProducto, $cantidad = 1) {
    $producto = obtenerProductoPorId($idProducto);
    if ($producto) {
        // Verificar el stock disponible
        $stockDisponible = obtenerStockDisponible($idProducto);

        // Si la cantidad solicitada es mayor al stock disponible, no agregar al carrito
        if ($cantidad > $stockDisponible) {
            // Devolver mensaje de error si el stock no es suficiente
            echo json_encode(["error" => "No hay suficiente stock para agregar esta cantidad."]);
            exit;
        }

        // Verificar si el producto ya está en el carrito
        if (isset($_SESSION['carrito'][$idProducto])) {
            $_SESSION['carrito'][$idProducto]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$idProducto] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'descripcion' => $producto['descripcion'],
                'imagen' => '../../uploads/productos/' . $producto['imagen'], // Ruta corregida
                'precio' => $producto['precio'],
                'cantidad' => $cantidad
            ];
        }
        echo json_encode(["success" => "Producto agregado al carrito"]);
        exit;
    }
}



// Eliminar producto del carrito
function eliminarDelCarrito($id) {
    unset($_SESSION['carrito'][$id]);
}

// Aumentar cantidad de producto

function aumentarCantidad($id) {
    if (isset($_SESSION['carrito'][$id])) {
        $cantidadActual = $_SESSION['carrito'][$id]['cantidad'];
        $stockDisponible = obtenerStockDisponible($id);

        // Verificar si se puede aumentar la cantidad sin exceder el stock
        if ($cantidadActual < $stockDisponible) {
            $_SESSION['carrito'][$id]['cantidad']++;
        } else {
            $_SESSION['error_carrito'] = "No hay suficiente stock disponible para seguir aumentando este producto.";
        }
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
    header("Location: index.php?page=user/carrito");
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
    global $conexion;

    $carritoActualizado = [];

    foreach ($_SESSION['carrito'] as $id => $productoEnSesion) {
        // Consultar el producto actualizado desde la base de datos
        $stmt = $conexion->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $productoDB = $resultado->fetch_assoc();
        $stmt->close();

        if ($productoDB) {
            // Actualizar el precio y nombre por si cambiaron
            $productoEnSesion['nombre'] = $productoDB['nombre'];
            $productoEnSesion['precio'] = $productoDB['precio'];
        }

        $carritoActualizado[$id] = $productoEnSesion;
    }

    return $carritoActualizado;
}


// Manejo de acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        eliminarDelCarrito((int) $_POST['eliminar']);
        header("Location: ../index.php?page=user/carrito");
        exit();
    } elseif (isset($_POST['aumentar'])) {
        aumentarCantidad((int) $_POST['aumentar']);
        header("Location: ../index.php?page=user/carrito");
        exit();
    } elseif (isset($_POST['disminuir'])) {
        disminuirCantidad((int) $_POST['disminuir']);
        header("Location: ../index.php?page=user/carrito");
        exit();
    } elseif (isset($_POST['vaciar'])) {
        vaciarCarrito();
        header("Location: ../index.php?page=user/carrito");
        exit();
    }
}


// Manejo de agregar vía GET con AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['agregar'])) {
    $idProducto = intval($_GET['agregar']);
    $cantidad = 1;

    $stock = obtenerStockDisponible($idProducto);
    $cantidadActual = isset($_SESSION['carrito'][$idProducto]) ? $_SESSION['carrito'][$idProducto]['cantidad'] : 0;

    // Validación de stock
    if (($cantidadActual + $cantidad) > $stock) {
        http_response_code(409); // stock insuficiente
        exit;
    }

    // Si hay stock, agrega
    agregarAlCarrito($idProducto, $cantidad);
    http_response_code(200);
    exit;
}
