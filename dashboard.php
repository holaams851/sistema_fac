<?php
include("conexion.php");
include("funciones.php");

// --- Ventas por mes ---
$sql_mes = "
    SELECT 
        MONTH(f.fecha) AS mes,
        SUM(d.total) AS total_ventas
    FROM Facturas f
    INNER JOIN Detalle_Factura d ON f.id_factura = d.id_factura
    WHERE YEAR(f.fecha) = YEAR(CURDATE())
    GROUP BY MONTH(f.fecha)
    ORDER BY mes ASC
";
$res_mes = $conn->query($sql_mes);

$meses = [];
$totales = [];

while ($row = $res_mes->fetch_assoc()) {
     $meses[] = date("n", mktime(0, 0, 0, $row['mes'], 1));
     $totales[] = $row['total_ventas'];
}

$total_ventas = array_sum($totales);
$total_ventas = number_format($total_ventas);
$nombres_meses = ["1" => "Enero",
                  "2" => "Febrero",
                  "3" => "Marzo",
                  "4" => "Abril",
                  "5" => "Mayo",
                  "6" => "Junio",
                  "7" => "Julio",
                  "8" => "Agosto",
                  "9" => "Septiembre",
                  "10" => "Octubre",
                  "11" => "Noviembre",
                  "12" => "Diciembre"];

$mes_obj = (object) $nombres_meses;

$cant_meses = count($meses);
$arr_meses = [];


for($i = 1; $i <= 12; $i++){
    for($j = 0; $j < $cant_meses; $j++){
        if($i == $meses[$j]){
            $arr_meses[] = $mes_obj->$i;
        }
	}
}

// --- Ganancias ---

$sql_ganancias = "
     SELECT 
         df.subtotal AS subtotal,
         df.mano_de_obra AS mano_obra
     FROM Detalle_Factura df
";

$res_ganancias = $conn->query($sql_ganancias);
$subtotal = [];
$mano_obra = [];

while ($row = $res_ganancias->fetch_assoc()) {
     $subtotal[] = $row['subtotal'];
     $mano_obra[] = $row['mano_obra'];
}
$mano_obra_total = array_sum($mano_obra);
$ganancias = array_sum($subtotal);
$ganancias = $ganancias * 0.30;
$ganancias = $ganancias + $mano_obra_total;
$ganancias = number_format($ganancias);

// --- Clientes ---

$sql_clientes = "
     SELECT 
         c.id_cliente as id_cliente
     FROM Clientes c
";
$res_clientes = $conn->query($sql_clientes);

$id_clientes = [];
while ($row = $res_clientes->fetch_assoc()) {
     $id_clientes[] = $row['id_cliente'];
}

$cant_clientes = count($id_clientes);
$cant_clientes = number_format($cant_clientes);

// --- Equipos ---

$sql_equipos = "
     SELECT 
         e.id_equipo as id_equipo
     FROM Equipos e
";
$res_equipos = $conn->query($sql_equipos);

$id_equipos = [];
while ($row = $res_equipos->fetch_assoc()) {
     $id_equipos[] = $row['id_equipo'];
}

$cant_equipos = count($id_equipos);
$cant_equipos = number_format($cant_equipos);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ProService</title>
  <style>
      html {
          font-size: 125%; /* Increases the default base font size */
      }
  </style>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo me-5" href="dashboard.php"><img src="logoCut.png" class="me-2" alt="logo"></a>
        <a class="navbar-brand brand-logo-mini" href="dashboard.php"><img src="logo.png" alt="logo"></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="ti-view-list"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="user.png" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="index.php">
                <i class="ti-power-off text-primary"></i>
                Cerrar Sesión
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="ti-view-list"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
              <i class="ti-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
             <a class="nav-link" href="clientes.php">
              <i class="ti-user menu-icon"></i>
              <span class="menu-title">Clientes</span>
            </a>
          <li class="nav-item">
              <a class="nav-link" href="proveedores.php">
              <i class="ti-truck menu-icon"></i>
              <span class="menu-title">Proveedores</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="equipos.php">
              <i class="ti-desktop menu-icon"></i>
              <span class="menu-title">Equipos</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="crud_facturas/crear_factura.php">
              <i class="ti-pencil-alt menu-icon"></i>
              <span class="menu-title">Crear Factura</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="ver_todas_facturas.php">
              <i class="ti-file menu-icon"></i>
              <span class="menu-title">Facturas</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="reportes.php">
              <i class="ti-bar-chart menu-icon"></i>
              <span class="menu-title">Reportes</span>
            </a>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="font-weight-bold mb-0">Dashboard</h3>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title text-md-center text-xl-left">Ventas</p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                        <?php if (empty($meses)) { 
                         echo "<h3 class=mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0>0</h3>";
                      	} else { 
                         echo "<h3 class=mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0>{$total_ventas}C$</h3>";
						}?>
                    <i class="ti-shopping-cart icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title text-md-center text-xl-left">Ganancias</p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                      <?php if (empty($meses)) { 
                         echo "<h3 class=mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0>0</h3>";
                      	} else { 
                         echo "<h3 class=mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0>{$ganancias}C$</h3>";
						}?>
                    <i class="ti-stats-up icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title text-md-center text-xl-left">Clientes</p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                     <?php if (empty($meses)) { 
                         echo "<h3 class=mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0>0</h3>";
                      	} else { 
                         echo "<h3 class=mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0>{$cant_clientes}</h3>";
						}?>
                    <i class="ti-user icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title text-md-center text-xl-left">Equipos</p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <?php if (empty($meses)) { 
                         echo "<h3 class=mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0>0</h3>";
                      	} else { 
                         echo "<h3 class=mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0>{$cant_equipos}</h3>";
						}?>
                    <i class="ti-desktop icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Ventas</p>
                   <?php if (empty($meses)) { ?>
                      <p style="color:red; text-align:center;">No hay facturas para mostrar. Asegúrate de tener facturas con detalles y totales.</p>
                  <?php } else { ?>
                     <canvas id="barChart" width="800" height="400" style="display: block; height: 193px; width: 645px;" class="chartjs-render-monitor"></canvas>
                  <?php } ?>
                </div>
                <div class="card border-right-0 border-left-0 border-bottom-0">
                  <div class="d-flex justify-content-center justify-content-md-end">
                    </div>
                  </div>
                </div>
              </div>
            <div class="col-md-6 grid-margin stretch-card">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title">Facturas Recientes</h4>
									<div class="list-wrapper pt-2">
                      <?php
                      // Se asume que mostrarTabla está en funciones.php
                      $sql = "SELECT * FROM Detalle_Factura ORDER BY id_detalle DESC LIMIT 5"; 
                      $result = $conn->query($sql);
                      mostrarTabla($result, ['id_factura','total','nombre_equipo','descripcion']);
                      ?>
									</div>
								</div>
							</div>
                </div>
            </div>
            </div>
          </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <script src="js/jquery.cookie.js" type="text/javascript"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <script>
   
     if ($("#barChart").length) {
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var barChart = new Chart(barChartCanvas, {
          type: 'bar',
          data: {
              labels: <?= json_encode($arr_meses) ?>,
              datasets: [{
              label: 'Total de Ventas (C$)',
              data: <?= json_encode($totales) ?>,
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
              ],
              borderWidth: 1,
              fill: false
            }]
        },
          options:  {
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
        });
      }
    </script>
  <!-- End custom js for this page-->
</body>

</html>

