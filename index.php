<?php
include("conexion.php");
include("funciones.php");

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
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Sistema de Facturación: Toner & Más</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
  </head>

  <body>
     <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Toner & Más</a>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="index.php">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="ver_todas_facturas.php">
                  <span data-feather="file"></span>
                  Facturas
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="crud_facturas/crear_factura.php">
                  <span data-feather="plus-circle"></span>
                  Crear Factura
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="equipos.php">
                  <span data-feather="shopping-cart"></span>
                  Equipos
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="clientes.php">
                  <span data-feather="users"></span>
                  Clientes
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="reportes.php">
                  <span data-feather="bar-chart-2"></span>
                  Reportes
                </a>
              </li>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            </h6>
            <ul class="nav flex-column mb-2">
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
            </div>
          </div>

        <?php if (empty($meses)) { ?>
            <p style="color:red; text-align:center;">No hay datos para mostrar. Asegúrate de tener facturas con detalles y totales.</p>
        <?php } else { ?>
          <canvas class="my-4" id="ventasMes" width="800" height="300"></canvas>
        <?php } ?>

          <h2>Facturas Recientes</h2>
           <?php
            $sql = "SELECT * FROM Detalle_Factura ORDER BY id_detalle DESC"; // DESC = descendente
            $result = $conn->query($sql);
            mostrarTabla($result, ['id_factura','nombre_equipo','cantidad','total']);
          ?>
          
      </div>
    </div>
    
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

    <!-- Graphs -->
    <script>
      const ctx = document.getElementById('ventasMes')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($meses) ?>,
                    datasets: [{
                    label: 'Total de Ventas (C$)',
                    data: <?= json_encode($totales) ?>,
                    lineTension: 0,
                    backgroundColor: 'transparent',
                    borderColor: '#007bff',
                    borderWidth: 4,
                    pointBackgroundColor: '#007bff'
                }]
                },
                options: {
                scales: {
                    yAxes: [{
                    ticks: {
                        beginAtZero: false
                    }
                    }]
                },
                legend: {
                    display: false,
                }
            }
         })};
    </script>
  </body>
</html>
