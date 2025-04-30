<?php
session_start();
require_once __DIR__ . '/../../functions/carrito.php';
require_once __DIR__ . '/../../config/db.php';

$productosEnCarrito = obtenerCarrito();


if (!isset($_SESSION['user'])) {
    header("Location: ../login-page.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pedido</title>
  <link rel="stylesheet" href="../../assets/css/gestion.css">
  <link rel="stylesheet" href="../../assets/css/global.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="todo"> <!-- Contenedor principal -->

<header>
<div class="header-container">
    <!-- Logo -->
    <div class="img-container">
      <a href="index.php?page=home">
        <img src="../../assets/img/bonice.png" alt="Logo Bonice" class="logo-bonice" style="width:25%;">
      </a>
    </div>

    <!-- Navegación -->
    <nav class="navbar">
      <div class="container-fluid">

        <!-- Opciones de usuario/admin -->
        <?php if (isset($_SESSION["user"])): ?>
          <div class="admin-user-icon">
            <i class="bi bi-person-circle"></i>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="admin-user-options">
            <?php if ($_SESSION["user_rol"] === "admin"): ?>
              <a href="../../index.php?page=admin/gestionar_productos">Gestionar productos</a>
              <a href="../../index.php?page=admin/gestionar_categorias">Gestionar categorías</a>
              <a href="../../index.php?page=admin/gestionar_pedidos">Gestionar pedidos</a>
            <?php endif; ?>
            <a href="../../functions/cerrar_sesion.php">Cerrar Sesión</a>
          </div>
        <?php endif; ?>

        <!-- Enlaces principales -->
        <div class="navbar-nav">
          <a class="nav-link" href="../../index.php?page=home">Inicio</a>

          <!-- Dropdown de “Productos” -->
          <div class="dropdown-productos">
            <a class="nav-link" href="../../index.php?page=user/productos">Productos</a>
            <div class="dropdown-content">
              <?php
                $categorias_menu = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id");
                while ($cat = mysqli_fetch_assoc($categorias_menu)):
              ?>
                <a href="../../index.php?page=user/productos&categoria=<?= $cat['id'] ?>">
                  <?= htmlspecialchars($cat['nombre']) ?>
                </a>
              <?php endwhile; ?>
            </div>
          </div>

          <a class="nav-link" href="../../index.php?page=user/quienes">Quiénes Somos</a>
          <a class="nav-link" href="../../index.php?page=user/equipo">Nuestro Equipo</a>
        </div>

        <!-- Carrito -->
        <div class="carrito-icono">
          <a href="../../index.php?page=user/carrito" title="Ver carrito">
            <img class="carrito" src="../../assets/img/Shopping_car.png" alt="Icono carrito">
          </a>
          <?php if (!empty($productosEnCarrito)): ?>
            <div class="carrito-dropdown">
              <ul>
                <?php foreach ($productosEnCarrito as $p): ?>
                  <li><?= htmlspecialchars($p['nombre']) ?> – $<?= number_format($p['precio'], 1) ?></li>
                <?php endforeach; ?>
              </ul>
              <a href="../../index.php?page=user/carrito" class="ver-carrito">Ver carrito completo</a>
            </div>
          <?php else: ?>
            <div class="carrito-dropdown"><p>Carrito vacío</p></div>
          <?php endif; ?>
        </div>

      </div>
    </nav>
  </div>
</header>

<div class="wave-container">
  <div class="logo-floating">
    <img src="../../assets/img/icon-2.png" alt="BON ICE Logo">
  </div>

  <div class="form-container-gestion">
    <form id="form-pedido" class="checkout-form" method="POST">
      <input type="text" name="direccion" class="form-control-dir" placeholder="Dirección" required>
      <input type="text" name="ciudad" class="form-control-ciud" placeholder="Ciudad" required>
      <input type="text" name="departamento" class="form-control" placeholder="Departamento" required>
      <input type="text" id="numero_contacto" name="contacto" class="form-control" placeholder="Número de Contacto" required>
      <input type="text" name="pago" class="form-control" placeholder="Método de Pago" required>
      <button type="submit" class="btn-register">Confirmar Pedido</button>
    </form>
  </div>
</div>

<script>
  const button = document.getElementById('numero_contacto');
  let numero_contacto = "";
  button.addEventListener("input", ()=>{
    numero_contacto = button.value;
    if (button.value.length > 10)
    {
      numero_contacto = button.value.slice(0, -1);
      alert("El número debe ser máximo de 10 digitos");
      button.value = numero_contacto;
    };
  });
</script>

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
