<?php
require_once __DIR__ . '/../config/db.php';

function obtenerCategorias() {
    global $conexion;
    return mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id");
}

function obtenerCategoriaPorId($id) {
    global $conexion;
    $id = intval($id);
    $query = "SELECT * FROM categorias WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($resultado);
}

function eliminarCategoria($id) {
    global $conexion;
    $id = intval($id);
    if ($id > 0) {
        $query = "DELETE FROM categorias WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Lógica de actualización
if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    if ($id > 0 && !empty($nombre)) {
        $query = "UPDATE categorias SET nombre = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "si", $nombre, $id);
        $resultado = mysqli_stmt_execute($stmt);

        if ($resultado) {
            header("Location: index.php?page=admin/gestionar_categorias");
            exit();
        } else {
            echo "Error al actualizar la categoría.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>