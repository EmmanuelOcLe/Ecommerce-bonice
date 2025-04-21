<?php
session_start();
require_once __DIR__ . '/../../functions/carrito.php';
require_once __DIR__ . '/../../config/db.php';

$productosEnCarrito = obtenerCarrito();
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
    <div class="img-container">
      <img src="../../assets/img/bonice.png" alt="logo bonice" class="logo-bonice" style="width: 25%;">
    </div>

    <nav class="navbar">
      <div class="container-fluid">
        <?php if (isset($_SESSION["user"])): ?>
          <div class="admin-user-icon">
            <i class="bi bi-person-circle"></i>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="admin-user-options">
            <?php if ($_SESSION["user_rol"] == "admin"): ?>
              <a href="../../index.php?page=admin/gestionar_productos">Gestionar productos</a>
              <a href="../admin/gestionar_categorias.php">Gestionar categorías</a>
              <a href="../../index.php?page=admin/gestionar_pedidos">Gestionar pedidos</a>
            <?php else: ?>
              <a href="mis_pedidos.php">Mis pedidos</a>
            <?php endif; ?>
            <a href="../../functions/cerrar_sesion.php">Cerrar Sesión</a>
          </div>
        <?php endif; ?>

        <div class="navbar-nav">
          <?php
          $categorias_menu = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id");
          $urls_por_id = [
            1 => "../../index.php?page=home",
            2 => "../../index.php?page=user/productos",
            3 => "../../index.php?page=user/quienes",
            4 => "../../index.php?page=user/equipo"
          ];
          while ($cat = mysqli_fetch_assoc($categorias_menu)):
            $id_categoria = $cat['id'];
            $nombre_categoria = $cat['nombre'];
            $url = $urls_por_id[$id_categoria] ?? "index.php?page=user/category&id=$id_categoria";
          ?>
            <a class="nav-link" href="<?= $url ?>"><?= htmlspecialchars($nombre_categoria) ?></a>
          <?php endwhile; ?>
        </div>

        <div class="carrito-icono">
          <div class="dropdown-carrito">
            <a href="../user/carrito.php" title="Ver carrito">
              <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
            </a>
            <?php if (!empty($productosEnCarrito)): ?>
              <div class="carrito-dropdown">
                <ul>
                  <?php foreach ($productosEnCarrito as $producto): ?>
                    <li>
                      <?= htmlspecialchars($producto['nombre']) ?> - $<?= number_format($producto['precio'], 1) ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
                <a href="../user/carrito.php" class="ver-carrito">Ver carrito completo</a>
              </div>
            <?php else: ?>
              <div class="carrito-dropdown"><p>Carrito vacío</p></div>
            <?php endif; ?>
          </div>
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
