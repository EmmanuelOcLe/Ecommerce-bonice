<?php
require_once __DIR__ . '/../config/db.php';

// Lógica para actualizar una categoría
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $query = "UPDATE categorias SET nombre = '$nombre' WHERE id = $id";
    mysqli_query($conexion, $query);
    header("Location: gestionar_categorias.php");
    exit();
}

// Lógica para eliminar una categoría
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $query = "DELETE FROM categorias WHERE id = $id";
    mysqli_query($conexion, $query);
    header("Location: gestionar_categorias.php");
    exit();
}

// Obtener todas las categorías
$categorias = mysqli_query($conexion, "SELECT * FROM categorias");

// Si se quiere editar una categoría específica
$categoria_editar = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $resultado = mysqli_query($conexion, "SELECT * FROM categorias WHERE id = $id");
    $categoria_editar = mysqli_fetch_assoc($resultado);
}
?>