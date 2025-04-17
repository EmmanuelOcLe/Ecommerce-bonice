<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/pedidos.css">
  <title>Detalles del pedido</title>
</head>
<body>
  <div class="todo">
    <div class="contenedor detalles-container">
      <h1>DETALLES DEL PEDIDO</h1>
      <div class="separador"></div>

      <div class="input-field">
        <input type="text" name="" placeholder="Cambiar el estado del pedido">
        <a href="#">
          <button>Confirmar</button>
        </a>
      </div>
      
      <div class="detalles-pedido-container">
        <aside>
          <div>
            <h4>Dirección de envío</h4>
            <br>
            <span>Dirección: {Direccion ingresada}</span>
            <br>
            <span>Ciudad: {Ciudad ingresada}</span>
            <br>
            <span>Departamento {ciudad ingresada}</span>
            <br>
            <span>Teléfono: {telefono ingresado}</span>
          </div>

          <div>
            <h4>Datos del pedido</h4>
            <br>
            <span>Número de pedido: 2</span>
            <br>
            <span>Total a pagar: $24.000</span>
          </div>
        </aside>

        <div class="separador-2"></div>

        <div class="detalles-producto-container">
          <div class="detalles-producto-row">
            <div>Producto</div>
            <div>Nombre</div>
            <div>Precio</div>
            <div>Cantidad</div>
          </div>

          <div class="detalles-producto-row">
            <div>
              <img src="assets/img/producto1-no-bg.png" alt="Imagen producto">
            </div>
            <div>
              BONICE DOBLE SURTIDO X 10U
            </div>
            <div>
              $7.950
            </div>
            <div>
              1
            </div>
          </div>

          <div class="detalles-producto-row">
            <div>
              <img src="assets/img/producto1-no-bg.png" alt="">
            </div>
            <div>POPETAS SURTIDAS X 3U</div>
            <div>$15.950</div>
            <div>1</div>
          </div>

        </div>
        
      </div>
    </div>
  </div>
</body>
</html>