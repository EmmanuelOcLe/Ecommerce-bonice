<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre     = trim($_POST['nombres']);
    $apellidos  = trim($_POST['apellidos']);
    $correo     = trim($_POST['correo']);
    $contrasena = $_POST['contrasena']; 
    // $rol        = $_POST['rol'];

    // Validación básica
    if (empty($nombre) || empty($apellidos) || empty($correo) || empty($contrasena)) {
        header("Location: ../pages/registro-page.php?status=1");
        exit();
        // die("Debe completar todos los campos.");
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../pages/registro-page.php?status=2");
        exit();
        // die("Correo no válido.");
    }

    // Verificar si el correo ya existe
    $sql_check = "SELECT id FROM usuarios WHERE email = '$correo'";
    $resultado = mysqli_query($conexion, $sql_check);

    if (mysqli_num_rows($resultado) > 0) {
        header("Location: ../pages/registro-page.php?status=3");
        exit();
        // die("Este correo ya está registrado.");
    }

    // Cifrar la contraseña
    $contrasena_segura = md5(strval($contrasena));

    // Insertar usuario
    $sql_insert = "INSERT INTO usuarios (nombre, apellidos, email, password, rol) 
                   VALUES ('$nombre', '$apellidos', '$correo', '$contrasena_segura', 'user')";

    if (mysqli_query($conexion, $sql_insert)) {
        header("Location: ../pages/registro-page.php?status=0");
        // header("Location: ../index.php");
        exit();
    } else {
        header("Refresh: 5, URL=../pages/registro-page.php");
        echo "Error al registrar: " . mysqli_error($conexion) . "<br/>";
        echo "Se le redireccionará dentro de 5 segundos";
    }

    mysqli_close($conexion);
}
?>
