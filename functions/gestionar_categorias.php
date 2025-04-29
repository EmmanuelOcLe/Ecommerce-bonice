<?php
if (!isset($_SESSION["user"]) || $_SESSION["user_rol"] != "admin")
{
    header ("Location: ../index.php");
}
require_once __DIR__ . '/../config/db.php';

function obtenerCategorias() {
    global $conexion;

    $resultado = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id");
    $categorias = [];

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $categorias[] = $fila;
        }
    }

    if ($resultado) {
        mysqli_free_result($resultado);
    }

    return $categorias;
}

function obtenerCategoriaPorId($id) {
    global $conexion;
    $id = intval($id);

    $query = "SELECT * FROM categorias WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        $categoria = mysqli_fetch_assoc($resultado);

        mysqli_stmt_close($stmt);
        if ($resultado) {
            mysqli_free_result($resultado);
        }

        return $categoria;
    }

    return null;
}

function eliminarCategoria($id) {
    global $conexion;
    $id = intval($id);

    if ($id > 0) {
        $query = "DELETE FROM lineas_pedidos WHERE producto_id IN (SELECT id FROM productos WHERE categoria_id = ?)";
        $stmt = mysqli_prepare($conexion, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }


        $query = "DELETE FROM productos WHERE categoria_id = ?";
        $stmt = mysqli_prepare($conexion, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }


        $query = "DELETE FROM categorias WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

// Lógica de actualización
if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);

    if ($id > 0 && !empty($nombre)) {
        $query = "UPDATE categorias SET nombre = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $nombre, $id);
            $resultado = mysqli_stmt_execute($stmt);

            if ($resultado) {
                header("Location: gestionar_categorias.php");
                exit();
            } else {
                echo "Error al actualizar la categoría.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>