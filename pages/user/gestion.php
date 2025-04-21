<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pedido</title>
  <link rel="stylesheet" href="../../assets/css/gestion.css">
  <link rel="stylesheet" href="../../assets/css/global.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="checkout-container">
    <div class="logo-container">
        <img src="logo.png" alt="BON ICE Logo" class="logo">
    </div>
    
    <form class="checkout-form" method="POST" action="../../functions/procesar_pedido.php">
        <div class="form-row">
            <div class="form-group half">
                <input type="text" name="direccion" placeholder="Dirección" required>
            </div>
            <div class="form-group half">
                <input type="text" name="ciudad" placeholder="Ciudad" required>
            </div>
        </div>
        
        <div class="form-group">
            <input type="text" name="departamento" placeholder="Departamento" required>
        </div>
        
        <div class="form-group">
            <input type="text" name="contacto" placeholder="Número de Contacto" required>
        </div>
        
        <div class="form-group">
            <select name="pago" required>
                <option value="">Seleccione un método de pago</option>
                <option value="Pago contra entrega" selected>Pago contra entrega</option>
            </select>
        </div>
        
        <div class="form-group button-container">
            <button type="submit" class="confirm-button">Confirmar Pedido</button>
        </div>
    </form>
</div>
</body>
</html>
