<?php
include 'conexion.php';

// --- Ventas por mes ---
$sql_mes = "
    SELECT 
        MONTH(f.fecha) AS mes,
        SUM(d.total) AS total_ventas
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

// --- Ventas por equipo ---
$sql_equipo = "
    SELECT 
        e.nombre,
        SUM(d.subtotal) AS total_gastado
    FROM Equipos e
    INNER JOIN Detalle_Factura d ON e.id_equipo = d.id_equipo
    GROUP BY e.nombre
    ORDER BY total_gastado DESC
";
$res_equipo = $conn->query($sql_equipo);

// --- Ventas por cliente ---
$sql_cliente = "
    SELECT 
        f.nombre,
        SUM(d.total) AS total_gastado
    FROM Facturas f
    INNER JOIN Detalle_Factura d ON f.id_factura = d.id_factura
    GROUP BY f.nombre
    ORDER BY total_gastado DESC
";
$res_cliente = $conn->query($sql_cliente);

// Variables para el layout
$meses_dummy = $meses;
$totales_dummy = $totales;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Ventas</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="dashboard.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <nav class="sidebar"> 
          <div class="sidebar-sticky">
            <a class="sidebar-title" href="index.php">Toner & Más</a> 
            
            <ul class="nav flex-column">
              <li class="nav-item"><a class="nav-link" href="index.php"><span data-feather="home"></span> Dashboard</a></li>
              <li class="nav-item"><a class="nav-link" href="clientes.php"><span data-feather="users"></span> Clientes</a></li>
              <li class="nav-item"><a class="nav-link" href="proveedores.php"><span data-feather="truck"></span> Proveedores</a></li>
              <li class="nav-item"><a class="nav-link" href="equipos.php"><span data-feather="shopping-cart"></span> Equipos</a></li>
              <li class="nav-item"><a class="nav-link" href="crud_facturas/crear_factura.php"><span data-feather="plus-circle"></span> Crear Factura</a></li>
              <li class="nav-item"><a class="nav-link" href="ver_todas_facturas.php"><span data-feather="file"></span> Facturas</a></li>
              <li class="nav-item"><a class="nav-link active" href="reportes.php"><span data-feather="bar-chart-2"></span> Reportes <span class="sr-only">(current)</span></a></li>
            </ul>
          </div>
        </nav>

        <main role="main" class="main-content px-4"> 
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Reportes de Ventas</h1>
                <div class="profile-area">
                    <span class="user-name">Admin</span>
                    <img src="logo.jpeg" alt="Foto de Perfil" class="profile-pic"> 
                </div>
            </div>

            <h2>Ventas por Mes</h2>
            <div class="chart-container" style="position: relative; height:40vh; width:100%; max-width: 900px; margin-bottom: 30px;">
                <?php if (empty($meses_dummy)) { ?>
                    <p style="color:red; text-align:center;">No hay datos para mostrar. Asegúrate de tener facturas con detalles y totales.</p>
                <?php } else { ?>
                    <canvas id="ventasMes"></canvas>
                <?php } ?>
            </div>

            <h2>Total de Ventas por Equipo</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Total Vendido (C$)</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $res_equipo->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= number_format($row['total_gastado'], 2) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            
            <h2>Total de Ventas por Cliente</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Total Gastado (C$)</th>
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
        </main>
    </div>
</div>

<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace();
</script>

<script>
const ctx = document.getElementById('ventasMes')?.getContext('2d');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($meses_dummy) ?>,
            datasets: [{
                label: 'Total de Ventas (C$)',
                data: <?= json_encode($totales_dummy) ?>,
                backgroundColor: 'rgba(0, 123, 255, 0.6)', 
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false, 
            responsive: true,
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { color: 'var(--text-light)' } 
                },
                x: {
                    ticks: { color: 'var(--text-light)' }
                }
            },
            barThickness: 50 // Sets a fixed bar width
        }
    });
}
</script>

</body>
</html>