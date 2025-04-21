<?php
$pedido_id = $_GET['pedido_id'] ?? '';
$total = $_GET['total'] ?? '';
$cantidad = $_GET['cantidad'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Pedido</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .popup {
            max-width: 500px;
            margin: 100px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .popup h2 {
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #e91e63;
            display: inline-block;
            padding-bottom: 5px;
        }
        .popup p {
            margin: 10px 0;
            font-size: 16px;
        }
        .popup .btn {
            margin-top: 20px;
            background-color: #ffc107;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        .popup .btn:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>

<div class="popup">
    <h2>Su Pedido Fue Guardado Con Éxito</h2>
    <p>Tu pedido ha sido registrado. Una vez que realices la transferencia bancaria a la cuenta <strong>7382947289239ADD</strong> con el precio total del pedido, será procesado y enviado.</p>
    <hr style="margin: 20px 0;">
    <p><strong>Datos del pedido</strong></p>
    <p>Número de pedido: <?= htmlspecialchars($pedido_id) ?></p>
    <p>Cantidad de productos: <?= htmlspecialchars($cantidad) ?></p>
    <p>Total a pagar: $<?= number_format($total, 0, ',', '.') ?></p>
    <form action="../index.php">
        <button class="btn">Confirmar</button>
    </form>
</div>

</body>
</html>
