<?php
if (!isset($_SESSION["user"]))
{
    header("Location: ../../index.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BonIce - Formulario de Pedido</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/carrito.css">
    <link rel="stylesheet" href="../../assets/css/compra.css">

</head>
<body>


    <?php require "../../includes/header-usuario.php" ?>
    
    <div class="form-container">
        <div class="form-logo">
            <img src="../../assets/img/logo-bonice.png" alt="BonIce Logo">
        </div>
        
        <form>
            <div class="form-row">
                <div class="form-group">
                    <input type="text" placeholder="Dirección">
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Ciudad">
                </div>
            </div>
            
            <input type="text" placeholder="Departamento">
            <input type="text" placeholder="Número de Contacto">
            <input type="text" placeholder="Método de Pago">
            
            <button type="submit" class="btn-confirm">Confirmar Pedido</button>
        </form>
    </div>

    <?php require "../../includes/footer.php" ?>
</body>
</html>