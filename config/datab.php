<?php
// Configuración de la base de datos

$host = 'localhost'; // Cambia esto si tu base de datos está en otro servidor
$usuario = 'root'; // Cambia esto por tu usuario de base de datos
$contrasena = '123456'; // Cambia esto por tu contraseña de base de datos
$nombre_base_datos = 'tienda_sena'; // Cambia esto por el nombre de tu base de datos
// Conexión a la base de datos
$conexion = mysqli_connect($host, $usuario, $contrasena, $nombre_base_datos);

// Verifica si la conexión fue exitosa
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

?>
