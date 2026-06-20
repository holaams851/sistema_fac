<?php
include("../conexion.php");
$id = $_GET['id'];

// Consultar el equipo y el detalle de compra
$sql = "SELECT e.*, dc.precio_unitario, dc.id_proveedor 
        FROM Equipos e 
        LEFT JOIN Detalle_Compra dc ON e.id_equipo = dc.id_equipo 
        WHERE e.id_equipo = $id";
$result = $conn->query($sql);
$equipo = $result->fetch_assoc();

// Verificar si hay proveedores disponibles
$proveedores_q = $conn->query("SELECT * FROM Proveedores");

if(isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre']; // Usar 'nombre' de PHP para el campo de texto
    $num_serie = !empty($_POST['num_serie']) ? "'".$_POST['num_serie']."'" : "NULL";
    $precio_unitario = $_POST['precio_unitario'];
    $id_proveedor = $_POST['id_proveedor'];

    // 1. Actualizar Equipos
    try{
    $sql_equipo = "UPDATE Equipos SET nombre='$nombre', num_serie = $num_serie WHERE id_equipo=$id";
    $conn->query($sql_equipo);

    // 2. Actualizar Detalle_Compra (asumiendo que solo hay un registro de compra por equipo)
    $sql_compra = "UPDATE Detalle_Compra SET 
                   precio_unitario='$precio_unitario', 
                   id_proveedor='$id_proveedor' 
                   WHERE id_equipo=$id";
    $conn->query($sql_compra);
    
    header("Location: ../equipos.php");
    exit;
    } catch (mysqli_sql_exception $e) {
       header("Location: editar_equipo.php?id=$id&error=1");
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
                      <h4 class="font-weight-bold mb-0">Editar Equipo</h4>
                    </div>
                    <form method="POST" class="forms-sample">
                      <div class="form-group">
                        <label for="exampleInputUsername1">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?= $equipo['nombre'] ?>" required minlength="3" placeholder="Ingrese el nombre del equipo">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPrice1">Precio Unitario</label>
                        <input type="number" step="10" name="precio_unitario" class="form-control" value="<?= $equipo['precio_unitario'] ?>" required placeholder="Ingrese el precio unitario">
                      </div>
                      <div class="form-group">
                        <label for="exampleSelectProvider">Proveedor</label>
                        <select name="id_proveedor" class="form-select form-control">
                        <?php while($prov = $proveedores_q->fetch_assoc()) { ?>
                          <option value="<?= $prov['id_proveedor'] ?>" <?= $prov['id_proveedor'] == $equipo['id_proveedor'] ? 'selected' : '' ?>>
                              <?= $prov['nombre'] ?>
                          </option>
                        <?php } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputSerie">No. Serie</label>
                        <input type="text" name="num_serie" class="form-control" value="<?= $equipo['num_serie'] ?>" placeholder="Ingrese el número de serie">
                      </div>
                      <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger mt-2">
                            El equipo ya existe.
                        </div>
                      <?php endif; ?>
                      <button type="submit" name="actualizar" class="btn btn-primary me-2">Actualizar</button>
                      <a href="../equipos.php" class="btn btn-light">Cancelar</a>
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