<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BonIce Login</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<div class="wave-container">
    <div class="top-wave"></div>

    <!-- Imagen flotante -->
    <div class="logo-floating">
        <img src="../assets/img/logo-bonice2.png" alt="BonIce Logo">
    </div>

    <div class="form-container">
        <form action="registro.php" method="POST">

            <input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" required>
            <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>

            <button type="submit" class="btn-register">Iniciar Sesion</button>
        </form>
    </div>

    <div class="bottom-wave"></div>
</div>
</body>
</html>