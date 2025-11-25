<?php
include 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<div class="container p-4 rounded shadow-sm">
    <h2 class="mb-4">Facturas</h2>

    <!-- Botón regresar -->
    <a href="index.php" class="btn btn-secondary mb-3">← Regresar</a>

<?php
// Obtener facturas con su total
$sql_facturas = "
    SELECT 
        f.id_factura,
        f.nombre,
        f.fecha,
        SUM(df.subtotal) AS total
    FROM Facturas f
    LEFT JOIN Detalle_Factura df ON df.id_factura = f.id_factura
    GROUP BY f.id_factura, f.nombre, f.fecha
    ORDER BY f.fecha DESC
";

$res_facturas = $conn->query($sql_facturas);

while ($factura = $res_facturas->fetch_assoc()):
    $id_factura = $factura['id_factura'];

    // Obtener detalle de cada factura
    $sql_detalle = "
        SELECT 
            df.id_detalle,
            f.nombre AS nombre,
            df.nombre_equipo AS nombre,
            df.cantidad,
            df.precio_unitario,
            df.subtotal
        FROM Detalle_Factura df JOIN Facturas f ON f.id_factura = df.id_factura
        WHERE df.id_factura = $id_factura
    ";

    $res_detalle = $conn->query($sql_detalle);
?>

    <div class="mb-4">
        <h4>Factura #<?= $factura['id_factura'] ?> - <?= $factura['nombre'] ?: "<i>Cliente eliminado</i>" ?> (<?= $factura['fecha'] ?>)</h4>

        <a href="eliminar_factura.php?id=<?= $factura['id_factura'] ?>" 
           class="btn btn-danger btn-sm"
           onclick="return confirm('¿Estás seguro de eliminar esta factura?');">Eliminar</a>

        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Equipo</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($d = $res_detalle->fetch_assoc()): ?>

                <?php
                    $nombre = $d['nombre'];
                    $precio_unit = $d['precio_unitario'];
                ?>

                <tr>
                    <td><?= $nombre?></td>
                    <td><?= $d['cantidad'] ?></td>
                    <td>$<?= number_format($precio_unit, 2) ?></td>
                    <td>$<?= number_format($d['subtotal'], 2) ?></td>
                </tr>

            <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Total:</th>
                    <th>$<?= number_format($factura['total'],2) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

<?php endwhile; ?>

</div>

</body>
</html>
