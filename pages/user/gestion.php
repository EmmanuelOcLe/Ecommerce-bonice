<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pedido</title>
  <link rel="stylesheet" href="../../assets/css/gestion.css">
  <link rel="stylesheet" href="../../assets/css/global.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="todo"> <!-- Contenedor principal con grid -->

  <?php require "../../includes/header.php" ?>

  <div class="wave-container">

    <div class="logo-floating">
      <img src="../../assets/img/icon-2.png" alt="BON ICE Logo">
    </div>

    <div class="form-container-gestion">
      <form id="form-pedido" class="checkout-form" method="POST">
        <input type="text" name="direccion" class="form-control-dir" placeholder="Dirección" required>
        <input type="text" name="ciudad" class="form-control-ciud" placeholder="Ciudad" required>
        <input type="text" name="departamento" class="form-control" placeholder="Departamento" required>
        <input type="text" name="contacto" class="form-control" placeholder="Número de Contacto" required>
        <input type="text" name="pago" class="form-control" placeholder="Método de Pago" required>

        <button type="submit" class="btn-register">Confirmar Pedido</button>
      </form>
    </div>

  </div>

  <?php require "../../includes/footer.php" ?>

</div> <!-- Fin de .todo -->


<!-- SCRIPT para manejar el formulario con SweetAlert2 -->
<script>
  document.getElementById('form-pedido').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch('../../functions/procesar_pedido.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        Swal.fire({
          icon: 'success',
          title: '¡Pedido confirmado!',
          text: 'Gracias por tu compra',
          confirmButtonText: 'Ir al inicio'
        }).then(() => {
          window.location.href = '../../index.php';
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.message || 'Algo salió mal al procesar tu pedido.'
        });
      }
    })
    .catch(error => {
      console.error('Error del servidor:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error del servidor',
        text: 'Intenta más tarde.'
      });
    });
  });
</script>

</body>
</html>
