<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../conexion.php");

  $id = $_GET['id'];
  $sql = "SELECT * FROM Clientes WHERE id_cliente=$id";
  $result = $conn->query($sql);
  $cliente = $result->fetch_assoc(); 
  
if(isset($_POST['actualizar'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM Clientes WHERE id_cliente=$id";
    $result = $conn->query($sql);
    $cliente = $result->fetch_assoc(); 
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Asegurarse de que las columnas coinciden con el SQL corregido: 'telefono' y 'direccion'
    try {
        $sql = "UPDATE Clientes SET nombre='$nombre', telefono='$telefono', direccion='$direccion' WHERE id_cliente=$id";
        $conn->query($sql);
        header("Location: ../clientes.php");
    } catch (mysqli_sql_exception $e) {
       header("Location: editar_cliente.php?id=$id&error=1");
       exit;
    }
}

// Variables para el menú lateral (pueden dejarse vacías si no se usan)
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
        <a class="navbar-brand brand-logo me-5" href="../dashboard.php"><img src="../logoCut.png" class="me-5" alt="logo"></a>
        <a class="navbar-brand brand-logo-mini" href="../dashboard.php"><img src="../logo.png" class="me-2" alt="logo"></a>
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
                      <h4 class="font-weight-bold mb-0">Editar Cliente</h4>
                    </div>

                    <form method="POST" class="forms-sample">
                      <div class="form-group">
                        <label for="exampleInputUsername1">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required minlength="3" value="<?= $cliente['nombre'] ?>" placeholder="Ingrese el nombre del cliente">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPhone1">Teléfono</label>
                        <input type="tel" name="telefono" class="form-control"  value="<?= $cliente['telefono'] ?>" pattern="[0-9]{8,8}" required placeholder="Solo números">
                      </div>
                      <div class="form-group">
                        <label for="exampleTextarea1">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="<?= $cliente['direccion'] ?>" placeholder="Ingrese la dirección del cliente">
                      </div>
                      <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger mt-2">
                            El cliente ya existe o el teléfono ya está registrado.
                        </div>
                      <?php endif; ?>
                      <button type="submit" name="actualizar" class="btn btn-primary me-2">Actualizar</button>
                      <a href="../clientes.php" class="btn btn-light">Cancelar</a>
                    </form>

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