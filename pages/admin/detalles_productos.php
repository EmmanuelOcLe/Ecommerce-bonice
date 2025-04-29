<?php
// Incluye la conexión a la base de datos
require_once 'config/db.php';

// Verifica si el ID del producto está en la URL
if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];

    // Consulta SQL para obtener los detalles del producto
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica si se encontró el producto
    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit;
    }
} else {
    echo "No se proporcionó el ID del producto.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
</head>
<body>
    <h1>Detalles del Producto</h1>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <td><?= htmlspecialchars($producto['nombre']) ?></td>
        </tr>
        <tr>
            <th>Descripción</th>
            <td><?= htmlspecialchars($producto['descripcion']) ?></td>
        </tr>
        <tr>
            <th>Precio</th>
            <td>$<?= number_format($producto['precio'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <th>Stock</th>
            <td><?= $producto['stock'] ?></td>
        </tr>
        <tr>
            <th>Imagen</th>
            <td><img src="images/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" width="100"></td>
        </tr>
    </table>

    <a href="index.php">Volver a la lista de productos</a>
</body>
</html>
