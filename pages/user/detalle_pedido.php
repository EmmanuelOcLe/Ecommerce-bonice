<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION["user"]))
{
  header("Location: ../../index.php");
}

include_once('../../config/db.php');
include_once('../../functions/carrito.php');

$productosEnCarrito = obtenerCarrito();

// Obtener productos en carrito para mostrar en el ícono (si quieres esa funcionalidad)
$productosEnCarrito = $_SESSION['carrito'] ?? [];

$id_pedido = $_GET['id'] ?? null;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/global.css">
    <link rel="stylesheet" href="../../assets/css/navbar.css"> <!-- Agrega el CSS de navbar si tienes -->
    <link rel="stylesheet" href="../../assets/css/carrito.css"> <!-- Y del carrito si quieres -->
    <link rel="stylesheet" href="../../assets/css/producto-detalle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<?php

?>


<header>
  <div class="header-container">
    <!-- Logo -->
    <div class="img-container">
      <a href="../../index.php?page=home">
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
          <?php if (isset($_SESSION["user_rol"]) && $_SESSION["user_rol"] == "admin"): ?>
                          <a href="../../index.php?page=admin/gestionar_productos">Gestionar productos</a>
                          <a href="../admin/gestionar_categorias.php">Gestionar categorías</a>
                          <a href="../../index.php?page=admin/gestionar_pedidos">Gestionar pedidos</a>
                      <?php else: ?>
                  <a href="mis_pedidos.php">Mis pedidos</a>
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


<main class="detalle-pedido-container" style="padding: 20px;">
  <div class="table-pedidos-container">
<?php
// --- Lógica del pedido ---
if ($id_pedido && is_numeric($id_pedido)) {

    // Obtener localidad y dirección del pedido
    $sql_pedido = "SELECT * FROM pedidos WHERE id = ?";
    if ($stmt_pedido = mysqli_prepare($conexion, $sql_pedido)) {
        mysqli_stmt_bind_param($stmt_pedido, "i", $id_pedido);
        mysqli_stmt_execute($stmt_pedido);
        $result_pedido = mysqli_stmt_get_result($stmt_pedido);
        $pedido_info = mysqli_fetch_assoc($result_pedido);
        mysqli_stmt_close($stmt_pedido);
    }

    // Consulta productos del pedido
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

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<h2>Detalle del Pedido #$id_pedido</h2>";

            // Mostrar localidad y dirección
            echo "<p><strong>Localidad:</strong> " . htmlspecialchars($pedido_info['localidad']) . "</p> ";
            echo "<p><strong>Dirección:</strong> " . htmlspecialchars($pedido_info['direccion']) . "</p>";
            echo "<p><strong>Número de contacto:</strong> " . htmlspecialchars($pedido_info['numero_contacto']) . "</p>";

            echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 80%; margin-top: 20px;'>
                    <thead>
                        <tr>
                            <th style='padding: 10px;'>Producto</th>
                            <th style='padding: 10px;'>Cantidad</th>
                            <th style='padding: 10px;'>Precio Unitario</th>
                            <th style='padding: 10px;'>Total</th>
                        </tr>
                    </thead>
                    <tbody>";

        $suma_total = 0;

            while ($row = mysqli_fetch_assoc($result)) {
              $suma_total += $row["total_por_producto"];
                echo "<tr>
                        <td style='padding: 8px;'>{$row['producto']}</td>
                        <td style='padding: 8px;'>{$row['cantidad']}</td>
                        <td style='padding: 8px;'>$" . number_format($row['precio_unitario'], 0, ',', '.') . "</td>
                        <td style='padding: 8px;'>$" . number_format($row['total_por_producto'], 0, ',', '.') . "</td>
                      </tr>";
            }

            echo "<tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style='padding: 8px;'>$" . number_format($suma_total, 0, ',', '.') . "</td>
                      </tr>";

            echo "</tbody></table>";
        } else {
            echo "❌ No hay productos para este pedido.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "❌ Error al preparar la consulta.";
    }
} else {
    echo "❗ ID de pedido no proporcionado o no válido.";
}
?>

</div>


</main>

<?php include "../../includes/footer.php"; ?>

</body>
</html>
