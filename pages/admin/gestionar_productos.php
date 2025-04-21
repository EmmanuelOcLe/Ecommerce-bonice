<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"]) || $_SESSION["user_rol"] != "admin") {
    header("Location: ../../index.php?page=home");
    exit();
}

require_once(__DIR__ . '/../../functions/productos.php');

//elimina el producto
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"]) && isset($_GET["page"]) && $_GET["page"] === "admin/gestionar_productos") {
  eliminarProducto($_GET["id"]);
  header("Location: index.php?page=admin/gestionar_productos");
  exit();
}


// Crear producto
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["crear_producto"])) {
    crearProducto(
        $_POST["nombre"],
        $_POST["descripcion"],
        $_POST["precio"],
        $_POST["stock"],
        $_POST["categoria_id"],
        $_FILES["imagen"]
    );
    header("Location: index.php?page=admin/gestionar_productos");
    exit();
}

// Editar producto
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editar_producto"])) {
    actualizarProducto(
        $_POST["id_editar"],
        $_POST["nombre"],
        $_POST["descripcion"],
        $_POST["precio"],
        $_POST["stock"],
        $_POST["categoria_id"],
        $_FILES["imagen"]
    );
    header("Location: index.php?page=admin/gestionar_productos");
    exit();
}

$productos = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["buscar"])) {
    $nombreBuscado = $_POST["buscar_nombre"];
    $productos = buscarProductosPorNombre($nombreBuscado);
} else {
    $productos = listarProductos();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestionar productos</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/productos.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

  <div class="todo">
    <div class="contenedor">
      <h1>GESTIONAR PRODUCTOS</h1>
      <div class="separador"></div>

      <div class="gestion-productos-container">
        <form method="POST" class="input-field-container">
            <input type="text" name="buscar_nombre" placeholder="Ingrese el nombre del producto">
            <button type="submit" name="buscar">Buscar</button>
            <span>|</span>
            <button type="button" id="abrirModal">Crear Producto</button>
        </form>

        <div class="productos-container">
          <div class="productos-row encabezado">
            <div>ID</div>
            <div>Producto</div>
            <div>Nombre</div>
            <div>Precio</div>
            <div>Cantidad</div>
            <div>Acciones</div>
          </div>

          <?php foreach ($productos as $producto): ?>
            <div class="productos-row">
              <div><?= htmlspecialchars($producto['id']) ?></div>
              <div>
                <img src="assets/img/<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen producto" >
              </div>
              <div><?= htmlspecialchars($producto['nombre']) ?></div>
              <div>$<?= number_format($producto['precio'], 0, ',', '.') ?></div>
              <div><?= htmlspecialchars($producto['stock']) ?></div>
              <div>
                <button class="btn-editar"
                        data-id="<?= $producto['id'] ?>"
                        data-nombre="<?= htmlspecialchars($producto['nombre']) ?>"
                        data-precio="<?= $producto['precio'] ?>"
                        data-stock="<?= $producto['stock'] ?>"
                        data-descripcion="<?= htmlspecialchars($producto['descripcion']) ?>">
                  <i class="bi bi-pencil-fill"></i>
                </button>

                <button class="btn-eliminar"
                        data-id="<?= $producto['id'] ?>"
                        data-nombre="<?= htmlspecialchars($producto['nombre']) ?>">
                  <i class="bi bi-trash-fill"></i>
                </button>

              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL CREAR PRODUCTO -->
  <div id="modalCrear" class="modal">
    <div class="modal-content">
      <span class="cerrar" id="cerrarModal">&times;</span>
      <h2>Crear Producto</h2>
      <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nombre" placeholder="Nombre del producto" required>
        <input type="number" step="0.01" name="precio" placeholder="Precio" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <textarea name="descripcion" placeholder="Descripción" required></textarea>
        <input type="file" name="imagen" accept="image/*" required>
        <input type="hidden" name="categoria_id" value="2">
        <button type="submit" name="crear_producto">Guardar Producto</button>
      </form>
    </div>
  </div>

  <!-- MODAL EDITAR PRODUCTO -->
  <div id="modalEditar" class="modal">
    <div class="modal-content">
      <span class="cerrar" id="cerrarEditar">&times;</span>
      <h2>Editar Producto</h2>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_editar" id="id_editar">
        <input type="text" name="nombre" id="nombre_editar" placeholder="Nombre" required>
        <input type="number" name="precio" id="precio_editar" placeholder="Precio" required>
        <input type="number" name="stock" id="stock_editar" placeholder="Stock" required>
        <textarea name="descripcion" id="descripcion_editar" placeholder="Descripción" required></textarea>
        <input type="file" name="imagen">
        <input type="hidden" name="categoria_id" value="2">
        <button type="submit" name="editar_producto">Guardar Cambios</button>
      </form>
    </div>
  </div>


  <!-- MODAL ELIMINAR PRODUCTO -->
<div id="modalEliminar" class="modal" >
  <div class="modal-content">
    <span class="cerrar" id="cerrarEliminar">&times;</span>
    <h2>¿Eliminar producto?</h2>
    <p id="mensajeEliminar"></p>
    <div >

      <form method="GET" action="index.php">
        <input type="hidden" name="page" value="admin/gestionar_productos">
        <input type="hidden" name="id" id="id_eliminar">
        <button type="submit" >Eliminar</button>
      </form>


      <button id="cancelarEliminar">Cancelar</button>
    </div>
  </div>
</div>


  <script src="assets/js/modal.js"></script>
</body>
</html>
