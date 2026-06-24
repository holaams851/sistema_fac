<?php

include 'conexion.php';

$meses = [];
$totales = [];
?>


<!DOCTYPE html>
<html lang="es">
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
            <div class="col-md-10 grid-margin">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="font-weight-bold mb-0">Facturas</h4>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
               	 <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center"> 
                    <i class="ti-file icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  	<p class="card-title text-md-center text-xl-left">Aquí puede ver y exportar sus facturas.</p>
                     <a href="crud_facturas/crear_factura.php" class="btn btn-primary me-2">Nueva Factura</a>
                  </div>  
                </div>
              </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                     <?php
                        // Obtener facturas con su total
                        $sql_facturas = "
                            SELECT 
                                f.id_factura,
                                f.nombre,
                                f.fecha,
                                df.total AS total
                            FROM Facturas f
                            LEFT JOIN Detalle_Factura df ON df.id_factura = f.id_factura
                            GROUP BY f.id_factura, f.nombre, f.fecha
                            ORDER BY f.id_factura DESC
                        ";

                        $res_facturas = $conn->query($sql_facturas);
                        $mano_de_obra = 0;

                        while ($factura = $res_facturas->fetch_assoc()):
                            $id_factura = $factura['id_factura'];

                            // Obtener detalle de cada factura
                            $sql_detalle = "
                                SELECT 
                                    df.id_detalle,
                                    f.nombre AS nombre_cliente,
                                    df.nombre_equipo,
                                    df.cantidad,
                                    df.precio_unitario,
                                    df.subtotal,
                                    df.mano_de_obra,
                                    df.descripcion
                                FROM Detalle_Factura df 
                                JOIN Facturas f ON f.id_factura = df.id_factura
                                WHERE df.id_factura = $id_factura
                            ";

                            $res_detalle = $conn->query($sql_detalle);

                            $equipos = [];
                            $manoObra = [];

                            while ($d = $res_detalle->fetch_assoc()) {

                                if (($d['nombre_equipo']) != '-') {
                                    $equipos[] = $d;
                                }

                                if (($d['descripcion']) != '-') {
                                    $manoObra[] = $d;
                                }
                            }
                        ?>
					
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <h4>Factura #<?= $factura['id_factura'] ?> - Cliente: <?= $factura['nombre'] ?: "<i>Cliente eliminado</i>" ?> (<?= $factura['fecha'] ?>)</h4>

                            <a href="crud_facturas/eliminar_factura.php?id=<?= $factura['id_factura'] ?>" 
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Estás seguro de eliminar esta factura?');">Eliminar</a>
                        </div>

                        <?php if (!empty($equipos)): ?>
                          <table class="table table-bordered">
                              <thead>
                                  <tr>
                                      <th>Equipo</th>
                                      <th>Cantidad</th>
                                      <th>Precio unitario</th>
                                      <th>Subtotal</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($equipos as $d): ?>
                                  <tr>
                                      <td><?= $d['nombre_equipo'] ?></td>
                                      <td><?= $d['cantidad'] ?></td>
                                      <td><?= number_format($d['precio_unitario'],2) ?></td>
                                      <td><?= number_format($d['subtotal'],2) ?></td>
                                  </tr>
                                  <?php endforeach; ?>
                              </tbody>
                          <?php endif; ?>
                          <?php if (!empty($manoObra)): ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="3">Descripción</th>
                                        <th colspan="1">Mano de Obra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($manoObra as $d): ?>
                                    <tr>
                                        <td colspan="3" style="max-width: 300px; white-space: normal; word-wrap: break-word;"><?= $d['descripcion'] ?></td>
                                        <td colspan="1"><?= number_format($d['mano_de_obra'],2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            <?php endif; ?>
                            <tfoot>
                                <tr >
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>C$<?= number_format($factura['total'], 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        <a class="btn btn-warning" href="export_pdf_2.php?id=<?= $factura['id_factura'] ?>" target="_blank">
                                Vista Previa Formato
                        </a>
                        <a class="btn btn-warning" href="export_pdf_3.php?id=<?= $factura['id_factura'] ?>" target="_blank">
                                Exportar PDF (Solo datos)
                        </a>
                    	<hr>

                        <?php endwhile; ?>

                        <?php if ($res_facturas->num_rows === 0): ?>
                            <div class="alert alert-info text-center mt-5">No se encontraron facturas registradas.</div>
                        <?php endif; ?>
                  </div>  
                </div>
              </div>
            </div>
            
        <!-- content-wrapper ends -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
                </div>
              </div>
            </div>
          </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
   <!-- plugins:js -->
  <script src="../vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="../vendors/chart.js/Chart.min.js"></script>
  <script src="../js/jquery.cookie.js" type="text/javascript"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../js/off-canvas.js"></script>
  <script src="../js/hoverable-collapse.js"></script>
  <script src="../js/template.js"></script>
  <script src="../js/todolist.js"></script>
  <!-- endinject -->
</body>
</html>