<?php
session_start(); // ¡IMPORTANTE! Siempre iniciar sesión antes de todo
if (!isset($_SESSION["user"]) || isset($_SESSION["user"]) && $_SESSION["user_rol"] != "admin")
{
  header ("Location: ../../index.php");
}
require_once '../../config/db.php';

// Variables necesarias
$productosEnCarrito = $_SESSION['carrito'] ?? [];

$id_pedido = $_GET['id'] ?? null;

// Obtener datos de pedido si el ID es válido
$pedido_info = null;
$productos_pedido = [];

if ($id_pedido && is_numeric($id_pedido)) {
    // Obtener localidad y dirección
    $sql_pedido = "SELECT * FROM pedidos WHERE id = ?";
    if ($stmt_pedido = mysqli_prepare($conexion, $sql_pedido)) {
        mysqli_stmt_bind_param($stmt_pedido, "i", $id_pedido);
        mysqli_stmt_execute($stmt_pedido);
        $result_pedido = mysqli_stmt_get_result($stmt_pedido);
        $pedido_info = mysqli_fetch_assoc($result_pedido);
        mysqli_stmt_close($stmt_pedido);
    }

    // Obtener productos del pedido
    $sql = "SELECT 
                p.nombre AS producto,
                p.precio AS precio_unitario,
                lp.cantidad AS cantidad,
                (p.precio * lp.cantidad) AS total_por_producto
            FROM lineas_pedidos lp
            INNER JOIN productos p ON p.id = lp.producto_id
            WHERE lp.pedido_id = ?";

    if ($stmt = mysqli_prepare($conexion, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id_pedido);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $productos_pedido[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!-- Aquí empieza el HTML -->

<header>
<div class="header-container">
    <div class="img-container">
      <a href="../../index.php?page=home">
        <img src="../../assets/img/bonice.png" alt="Logo Bonice" class="logo-bonice" style="width:25%;">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../assets/css/style.css">
              <link rel="stylesheet" href="../../assets/css/global.css">
              <link rel="stylesheet" href="../../assets/css/pedidos.css">
              <link rel="stylesheet" href="../../assets/css/boton-detalles.css">
              <link rel="stylesheet" href="../../assets/css/producto-detalle.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      </a>
    </div>

    <nav class="navbar">
      <div class="container-fluid">

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

        <div class="navbar-nav">
          <a class="nav-link" href="../../index.php?page=home">Inicio</a>

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

<main style="padding: 20px;">
  <div class="table-pedidos-container">
    <?php if ($pedido_info && !empty($productos_pedido)): ?>
      <div>
        <h2 style="font-size: 15px">Detalle del Pedido #<?= htmlspecialchars($id_pedido) ?></h2>
        <p><strong>Localidad:</strong> <?= htmlspecialchars($pedido_info['localidad']) ?></p>
        <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido_info['direccion']) ?></p>
        <p><strong>Número de contacto:</strong><?= htmlspecialchars($pedido_info['numero_contacto']) ?></p>
      </div>
    
        <table border="1" cellpadding="5" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="padding: 10px;">Producto</th>
                    <th style="padding: 10px;">Cantidad</th>
                    <th style="padding: 10px;">Precio Unitario</th>
                    <th style="padding: 10px;">Total</th>
                </tr>
            </thead>
            <tbody>
              <?php
                $suma_total = 0;
              ?>
                <?php foreach ($productos_pedido as $producto): ?>
                  <?php
                    $suma_total += $producto["total_por_producto"];
                  ?>
                    <tr>
                        <td style="padding: 8px;"><?= htmlspecialchars($producto['producto']) ?></td>
                        <td style="padding: 8px;"><?= htmlspecialchars($producto['cantidad']) ?></td>
                        <td style="padding: 8px;">$<?= number_format($producto['precio_unitario'], 2, ',', '.') ?></td>
                        <td style="padding: 8px;">$<?= number_format($producto['total_por_producto'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
    
                <?php
                  echo "<tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td style='padding: 8px;'>$" . number_format($suma_total, 0, ',', '.') . "</td>
                </tr>";
                ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color: red;">❌ No se encontró información para este pedido.</p>
    <?php endif; ?>
  </div>
</main>

<?php include "../../includes/footer.php"; ?>