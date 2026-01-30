<?php
include("conexion.php");
include("funciones.php");

// Variables para el menú lateral (pueden dejarse vacías si no se usan)
$meses = [];
$totales = [];
?>
  
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Clientes</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link href="dashboard.css" rel="stylesheet"> 
</head>
<body>
  
  <div class="container-fluid">
    <div class="row">
      
      <nav class="sidebar"> 
        <div class="sidebar-sticky">
          <a class="sidebar-title" href="dashboard.php">ProService</a>
          
          <ul class="nav flex-column">
            
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <span data-feather="home"></span>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="clientes.php">
                <span data-feather="users"></span>
                Clientes <span class="sr-only">(current)</span>
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="proveedores.php"> 
                <span data-feather="truck"></span>
                Proveedores
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="equipos.php">
                <span data-feather="shopping-cart"></span>
                Equipos
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="crud_facturas/crear_factura.php">
                <span data-feather="plus-circle"></span>
                Crear Factura
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="ver_todas_facturas.php">
                <span data-feather="file"></span>
                Facturas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="reportes.php">
                <span data-feather="bar-chart-2"></span>
                Reportes
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <main role="main" class="main-content px-4"> 
        
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2">Clientes</h1>
         <div class="profile-area">
                <a class="user-name" href="index.php">Admin</a> 
                <img src="logo.jpeg" alt="Foto de Perfil" class="profile-pic"> 
            </div>
          </div>
        
        <h3 class="mb-3">Aquí puede registrar, editar y eliminar a sus clientes.</h3>
        <a href="crud_clientes/agregar_cliente.php" class="btn btn-success mb-3">Agregar Cliente</a>
        <?php
      $sql = "SELECT * FROM Clientes ORDER BY id_cliente ASC"; // ASC = ascendente
      $result = $conn->query($sql);
      mostrarTabla($result, ['id_cliente','nombre','telefono','direccion'], ['editar'=>"crud_clientes/editar_cliente.php",'eliminar'=>"crud_clientes/eliminar_cliente.php"]);
      ?>
      </main>
    </div>
  </div>

  <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
  <script>
    feather.replace()
  </script>
</body>
</html>