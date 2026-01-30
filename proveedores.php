<?php
include("conexion.php");
include("funciones.php"); // Incluir funciones para usar si es necesario

// Variables para el menú lateral (opcional)
$meses = [];
$totales = [];
?>
  
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Proveedores</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="dashboard.css?v=<?php echo filemtime('dashboard.css'); ?>">
</head>
<body>
  
  <div class="container-fluid">
    <div class="row">
      
      <nav class="sidebar"> 
        <div class="sidebar-sticky">
          <a class="sidebar-title" href="dashboard.php">ProService</a>
          <ul class="nav flex-column">
            
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">
                <span data-feather="home"></span>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="clientes.php">
                <span data-feather="users"></span>
                Clientes
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link active" href="proveedores.php">
                <span data-feather="truck"></span>
                Proveedores <span class="sr-only">(current)</span>
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
          <h1 class="h2">Proveedores</h1>
          <div class="profile-area">
                <a class="user-name" href="index.php">Admin</a> 
                <img src="logo.jpeg" alt="Foto de Perfil" class="profile-pic"> 
            </div>
          </div>
        
        <h3 class="mb-3">Aquí puede registrar, editar y eliminar sus proveedores.</h3>
        <a href="crud_proveedores/agregar_proveedor.php" class="btn btn-success mb-3">Agregar Proveedor</a>

        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Teléfono</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Se asume que mostrarTabla está disponible si se incluye funciones.php
            // Si la función mostrarTabla NO está disponible, se usa el loop manual
            
            $sql = "SELECT * FROM Proveedores";
            $result = $conn->query($sql);
            
            // Si no usas mostrarTabla (o si no la has creado), usa este loop:
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id_proveedor']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['telefono']}</td>
                        <td>
                          <a href='crud_proveedores/editar_proveedor.php?id={$row['id_proveedor']}' class='btn btn-sm btn-warning'>Editar</a>
                          <a href='crud_proveedores/eliminar_proveedor.php?id={$row['id_proveedor']}' class='btn btn-sm btn-danger'>Eliminar</a>
                        </td>
                      </tr>";
            }
            // Fin del loop manual
            ?>
          </tbody>
        </table>
      </main>
    </div>
  </div>

  <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
  <script>
    feather.replace();
  </script>
</body>
</html>