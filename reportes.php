<?php
include 'conexion.php';

// --- Ventas por mes ---
$sql_mes = "
    SELECT 
        MONTH(f.fecha) AS mes,
        SUM(d.subtotal) AS total_ventas
    FROM Facturas f
    INNER JOIN Detalle_Factura d ON f.id_factura = d.id_factura
    GROUP BY MONTH(f.fecha)
    ORDER BY mes ASC
";
$res_mes = $conn->query($sql_mes);

$meses = [];
$totales = [];

while ($row = $res_mes->fetch_assoc()) {
    $meses[] = date("F", mktime(0, 0, 0, $row['mes'], 1));
    $totales[] = $row['total_ventas'];
}

// --- Ventas por cliente ---
$sql_cliente = "
    SELECT 
        c.nombre,
        SUM(d.subtotal) AS total_gastado
    FROM Facturas f
    INNER JOIN Detalle_Factura d ON f.id_factura = d.id_factura
    INNER JOIN Clientes c ON f.id_cliente = c.id_cliente
    GROUP BY c.nombre
    ORDER BY total_gastado DESC
";
$res_cliente = $conn->query($sql_cliente);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Ventas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { max-width: 900px; margin: auto; }
        #ventasMes {
            max-width: 600px;  /* ancho máximo del gráfico */
            max-height: 300px; /* alto máximo del gráfico */
            margin: 0 auto 30px;
            display: block;
        }
    </style>
</head>
<body class="container p-4">

<div class="container mb-4">
    <h1>📊 Reportes de Ventas</h1>
    <a href="index.php" class="btn btn-secondary mb-3">Regresar</a>

    <?php if (empty($meses)) { ?>
        <p style="color:red; text-align:center;">No hay datos para mostrar. Asegúrate de tener facturas con detalles y totales.</p>
    <?php } else { ?>
        <canvas id="ventasMes"></canvas>
    <?php } ?>

    <h2>Total de Ventas por Cliente</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Total Gastado ($)</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $res_cliente->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= number_format($row['total_gastado'], 2) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
const ctx = document.getElementById('ventasMes')?.getContext('2d');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($meses) ?>,
            datasets: [{
                label: 'Total de Ventas (C$)',
                data: <?= json_encode($totales) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false, // permite ajustar tamaño libremente
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}
</script>

<script>
    // aplicar tema guardado
    document.documentElement.setAttribute(
      "data-bs-theme",
      localStorage.getItem("theme") || "light"
    );
</script>
</body>
</html>
