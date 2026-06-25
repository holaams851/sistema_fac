<?php
include("../conexion.php"); 

$proveedores = $conn->query("SELECT * FROM Proveedores");

if(isset($_POST['guardar'])) {

    $nombre = $_POST['nombre'];
    $num_serie = !empty($_POST['num_serie']) ? $_POST['num_serie'] : NULL;
    // Se eliminan $cantidad y $id_equipo del POST ya que no están en el formulario
    $precio_unitario = $_POST['precio_unitario'];
    $id_proveedor = $_POST['id_proveedor'];

    try {
    // 1. Insertar en Equipos
    $sql_equipo = "INSERT INTO Equipos (nombre, num_serie) VALUES ('$nombre', " . ($num_serie === NULL ? 'NULL' : "'$num_serie'") . ")";
    $conn->query($sql_equipo);

    // Obtener el ID del equipo insertado
    $id_equipo = $conn->insert_id;

    // 2. Insertar en Detalle_Compra (cantidad por defecto 1)
    $sql_compra = "INSERT INTO Detalle_Compra (cantidad, precio_unitario, id_proveedor, id_equipo)
                    VALUES (1, '$precio_unitario', '$id_proveedor', '$id_equipo')";
    $conn->query($sql_compra);

    header("Location: ../equipos.php");
    exit();
    } catch (mysqli_sql_exception $e) {
       header("Location: agregar_equipo.php?error=1");
       exit;
    }

}

// Variables dummy para el layout
$meses = [];
$totales = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ProService</title>
  <style>
      html {
          font-size: 125%; /* Increases the default base font size */
      }
  </style>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png"/>
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo me-5" href="../dashboard.php"><img src="../logoCut.png" class="me-2" alt="logo"></a>
        <a class="navbar-brand brand-logo-mini" href="../dashboard.php"><img src="../logo.png" alt="logo"></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="ti-view-list"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../user.png" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="../index.php">
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
            <a class="nav-link" href="../dashboard.php">
              <i class="ti-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
             <a class="nav-link" href="../clientes.php">
              <i class="ti-user menu-icon"></i>
              <span class="menu-title">Clientes</span>
            </a>
          <li class="nav-item">
              <a class="nav-link" href="../proveedores.php">
              <i class="ti-truck menu-icon"></i>
              <span class="menu-title">Proveedores</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../equipos.php">
              <i class="ti-desktop menu-icon"></i>
              <span class="menu-title">Equipos</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="../crud_facturas/crear_factura.php">
              <i class="ti-pencil-alt menu-icon"></i>
              <span class="menu-title">Crear Factura</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="../ver_todas_facturas.php">
              <i class="ti-file menu-icon"></i>
              <span class="menu-title">Facturas</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="../reportes.php">
              <i class="ti-bar-chart menu-icon"></i>
              <span class="menu-title">Reportes</span>
            </a>
        </ul>
      </nav>
      <!-- partial -->
   <div class="main-panel">
        <div class="content-wrapper">
          
          <div class="row">
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                     <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                      <h4 class="font-weight-bold mb-0">Agregar Equipo</h4>
                    </div>

                     <?php if ($proveedores->num_rows == 0): ?>
                    <div class="alert alert-warning mt-3">
                      ⚠️ No hay proveedores registrados. Puedes agregar uno <a href="../proveedores.php">aquí</a>.
                    </div>
                  <?php else: ?>
                    <form action="agregar_equipo.php" method="POST" class="forms-sample">
                      <div class="form-group">
                        <label for="exampleInputUsername1">Nombre</label>
                        <input type="text" name="nombre" class="form-control" maxlength="64" required minlength="3" placeholder="Ingrese el nombre del equipo">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPrice1">Precio Unitario</label>
                        <input type="number" step="0.01" name="precio_unitario" class="form-control" required placeholder="Ingrese el precio unitario">
                      </div>
                      <div class="form-group">
                        <label for="exampleSelectProvider">Proveedor</label>
                        <select name="id_proveedor" class="form-select form-control">
                        <?php while($prov = $proveedores->fetch_assoc()) { ?>
                            <option value="<?= $prov['id_proveedor'] ?>"><?= $prov['nombre'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputSerie">No. Serie</label>
                        <input type="text" name="num_serie" class="form-control" placeholder="Ingrese el número de serie">
                      </div>
                      <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger mt-2">
                            El equipo ya existe.
                        </div>
                      <?php endif; ?>
                      <button type="submit" name="guardar" class="btn btn-primary me-2">Guardar</button>
                      <a href="../equipos.php" class="btn btn-light">Cancelar</a>
                    </form>
                  <?php endif; ?>

                  </div>  
                </div>
              </div>
          </div>
    </div>
  </div>

  <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
  <script>
    feather.replace();
  </script>
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