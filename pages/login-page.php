<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BonIce Login</title>
  <link rel="stylesheet" href="../assets/css/login.css">
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="wave-container">

  <!-- Imagen flotante -->
  <div class="logo-floating">
      <img src="../assets/img/logo-bonice2.png" alt="BonIce Logo">
  </div>

  <div class="form-container">
      <form action="../functions/login.php" method="POST">
          <input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" required>
          <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>

          <button type="submit" class="btn-register">Iniciar Sesión</button>
          <br>
          <a href="registro-page.php" style="font-weight: 400;">Registrarse</a>
      </form>
  </div>

  <!-- Olas decorativas -->
  <div class="wave-top">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
          <path fill="#c40069" fill-opacity="1" d="M0,128L40,160C80,192,160,256,240,245.3C320,235,400,149,
          480,128C560,107,640,149,720,186.7C800,224,880,256,960,245.3C1040,
          235,1120,181,1200,160C1280,139,1360,149,1400,154.7L1440,160L1440,
          320L0,320Z" />
      </svg>
  </div>

  <div class="wave-bottom">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
          <path fill="#c40069" fill-opacity="1" d="M0,128L40,160C80,192,160,256,240,245.3C320,235,400,149,
          480,128C560,107,640,149,720,186.7C800,224,880,256,960,245.3C1040,
          235,1120,181,1200,160C1280,139,1360,149,1400,154.7L1440,160L1440,
          320L0,320Z" />
      </svg>
  </div>
</div>

<!-- SweetAlert2 para errores -->
<?php if (isset($_GET["status"])): ?>
  <script>
    <?php if ($_GET["status"] == 1): ?>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'No se encuentra registrado.',
        confirmButtonColor: '#c40069'
      });
    <?php elseif ($_GET["status"] == 2): ?>
      Swal.fire({
        icon: 'warning',
        title: 'Campos Vacíos',
        text: 'Por favor, complete todos los campos.',
        confirmButtonColor: '#c40069'
      });
    <?php elseif ($_GET["status"] == 3): ?>
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Contraseña incorrecta.',
        confirmButtonColor: '#c40069'
      });
    <?php endif; ?>
  </script>
<?php endif; ?>
</body>
</html>
