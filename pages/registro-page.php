<?php
    session_start();
    
    if (isset($_SESSION["user"]))
    {
        header("Location: ../index.php");
        exit();
    }
    require_once  '../config/db.php';
    $statuses = [
        "1" => "Debe completar todos los campos.",
        "2" => "Correo no válido.",
        "3" => "Este correo ya está registrado",
        "0" => "Registro exitoso. Se le redireccionará dentro de 5 segundos."
    ];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/registro.css">
    <title>BonIce</title>
</head>
<body>
    <div class="wave-container">
        <div class="top-wave"></div>
    
        <!-- Imagen flotante -->
        <div class="logo-floating">
            <img src="../assets/img/logo-bonice2.png" alt="BonIce Logo">
        </div>
    
        <div class="form-container">

        <?php 
            if (isset($_GET["status"])):
                if ($_GET["status"] > 3 || $_GET["status"] < 0)
                {
                    $mensaje = "";
                }
                else
                {
                    $mensaje = $statuses[strval($_GET["status"])];
                }
        ?>
        <p class=<?php if ($_GET["status"] == 0){echo "status-blue";} else {echo "status-red";} ?> ><?= $mensaje ?></p>
        <?php if ($_GET["status"] == 0){ echo "<meta http-equiv='refresh' content='5; URL=../index.php'>";} ?>
        <?php endif; ?>
            <form action="../functions/registro.php" method="POST">
                <!-- <div class="form-row">
                    <select name="rol" class="form-control" required>
                        <option value="" disabled selected>Seleccionar Rol</option>
                        <option value="usuario">Usuario</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div> -->
    
                <div class="form-row">
                    <input type="text" name="nombres" placeholder="Nombres" required>
                    <input type="text" name="apellidos" placeholder="Apellidos" required>
                </div>
    
                <input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" required>
                <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
    
                <button type="submit" class="btn-register">Registrarse</button>
            </form>
        </div>
    
        <div class="bottom-wave"></div>
    </div>
</body>
</html>