<?php
    require  'config/db.php';
    session_start();
    if (isset($_SESSION["user_times"]))
    {
        $_SESSION["user_times"] ++;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/icon.png">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/detalle.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/carrito.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>BonIce</title>
</head>
<body>
    <div class="todo">
        <?php
            if (isset($_SESSION["user_times"]) && $_SESSION["user_times"] == 1):
        ?>
        <div class="user-welcome">
            <h1>Bienvenido/a, <?= $_SESSION["user"]; ?></h1>
        </div>
        <?php endif; ?>

        <?php if (isset($_COOKIE["user_session"]) && $_COOKIE["user_session"] == "finish"): ?>
        <div class="user-farewell">
            <h1>Sesion cerrada correctamente</h1>
        </div>
        <?php
            setcookie("user_session", "", time() - 3600, "/");
            unset($_COOKIE["user_session"]);
        ?>
        <?php endif ?>

        <?php include 'includes/header.php'; ?> 
        
        <div class="contenedor">

            <?php if (!isset($_SESSION["user"])): ?>
            <aside class="bloque-sesion">
                <?php include 'includes/aside.php'; ?>
                
            </aside>
            
            <hr>
            <?php endif; ?>
            
            <main>
                <?php include "includes/main.php"; ?> 
            </main>

            
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

<script src="assets/js/script.js"></script>
</body>
</html>

