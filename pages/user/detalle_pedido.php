<?php
require_once '../../config/db.php'; // ajusta según tu estructura

$id_pedido = $_GET['id'] ?? null;

if ($id_pedido && is_numeric($id_pedido)) {
    // Consulta preparada para evitar inyección SQL
    $sql = "SELECT 
                p.nombre AS producto,
                p.precio AS precio_unitario,
                lp.cantidad AS cantidad,
                (p.precio * lp.cantidad) AS total_por_producto
            FROM lineas_pedido lp
            INNER JOIN productos p ON p.id = lp.producto_id
            WHERE lp.pedido_id = ?";

    if ($stmt = mysqli_prepare($db, $sql)) {
        // Enlazamos el parámetro
        mysqli_stmt_bind_param($stmt, "i", $id_pedido);
        // Ejecutamos la consulta
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<h2>Detalle del Pedido #$id_pedido</h2>";
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>
                    <thead>
                        <tr>
                            <th style='padding: 10px; text-align: left;'>Producto</th>
                            <th style='padding: 10px; text-align: left;'>Cantidad</th>
                            <th style='padding: 10px; text-align: left;'>Precio Unitario</th>
                            <th style='padding: 10px; text-align: left;'>Total</th>
                        </tr>
                    </thead>
                    <tbody>";

            // Mostrar cada fila de resultados
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td style='padding: 8px;'>{$row['producto']}</td>
                        <td style='padding: 8px;'>{$row['cantidad']}</td>
                        <td style='padding: 8px;'>$" . number_format($row['precio_unitario'], 2, ',', '.') . "</td>
                        <td style='padding: 8px;'>$" . number_format($row['total_por_producto'], 2, ',', '.') . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "❌ No hay productos para este pedido.";
        }

        // Cerramos la consulta
        mysqli_stmt_close($stmt);
    } else {
        echo "❌ Error al preparar la consulta.";
    }
} else {
    echo "❗ ID de pedido no proporcionado o no válido.";
}
?>
