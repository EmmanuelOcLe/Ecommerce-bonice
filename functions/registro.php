<?php
require '../config/datab.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre     = trim($_POST['nombres']);
    $apellidos  = trim($_POST['apellidos']);
    $correo     = trim($_POST['correo']);
    $contrasena = $_POST['contrasena']; 
    $rol        = $_POST['rol'];

    // Validación básica
    if (empty($nombre) || empty($apellidos) || empty($correo) || empty($contrasena) || empty($rol)) {
        die("Por favor completa todos los campos.");
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Correo no válido.");
    }

    // Verificar si el correo ya existe
    $sql_check = "SELECT id FROM usuarios WHERE email = '$correo'";
    $resultado = mysqli_query($conexion, $sql_check);

    if (mysqli_num_rows($resultado) > 0) {
        die("Este correo ya está registrado.");
    }

    // Cifrar la contraseña
    $contrasena_segura = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar usuario
    $sql_insert = "INSERT INTO usuarios (nombre, apellidos, email, password, rol) 
                   VALUES ('$nombre', '$apellidos', '$correo', '$contrasena_segura', '$rol')";

    if (mysqli_query($conexion, $sql_insert)) {
        header("Location: ../../index.php");
        exit();
    } else {
        echo "Error al registrar: " . mysqli_error($conexion);
    }

    mysqli_close($conexion);
}
?>
