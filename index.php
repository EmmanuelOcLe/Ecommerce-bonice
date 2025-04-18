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
        <?php include 'includes/header.php'; ?> 
        
        <div class="contenedor">

            <aside class="bloque-sesion">
                <?php include 'includes/aside.php'; ?>
                
            </aside>

            <hr>
            
            <main>
                <?php include "includes/main.php"; ?> 
            </main>

            
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

<script src="assets/js/script.js"></script>
</body>
</html>

