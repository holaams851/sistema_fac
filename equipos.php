<?php
include("conexion.php");
include("funciones.php");
$result = $conn->query("SELECT * FROM Equipos");  

// Variables dummy para el layout
$meses = [];
$totales = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Equipos</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link href="dashboard.css?v=7" rel="stylesheet">
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
            <li class="nav-item"><a class="nav-link active" href="equipos.php"><span data-feather="shopping-cart"></span> Equipos <span class="sr-only">(current)</span></a></li>
            <li class="nav-item"><a class="nav-link" href="crud_facturas/crear_factura.php"><span data-feather="plus-circle"></span> Crear Factura</a></li>
            <li class="nav-item"><a class="nav-link" href="ver_todas_facturas.php"><span data-feather="file"></span> Facturas</a></li>
            <li class="nav-item"><a class="nav-link" href="reportes.php"><span data-feather="bar-chart-2"></span> Reportes</a></li>
          </ul>
        </div>
      </nav>

      <main role="main" class="main-content px-4"> 
        
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2">Equipos</h1>
          <div class="profile-area">
              <span class="user-name">Usuario Admin</span>
              <img src="user_profile.jpg" alt="Foto de Perfil" class="profile-pic"> 
          </div>
        </div>
        
        <h3 class="mb-3">Aquí puede registrar, editar y eliminar sus equipos.</h3>
        <a href="crud_equipos/agregar_equipo.php" class="btn btn-success mb-3">Agregar Equipo</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio Unitario</th>
                    <th>Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT 
                    e.*,
                    p.nombre AS proveedor,
                    dc.precio_unitario
                    FROM Detalle_Compra dc
                    INNER JOIN Equipos e 
                        ON dc.id_equipo = e.id_equipo
                    INNER JOIN Proveedores p 
                        ON dc.id_proveedor = p.id_proveedor";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_equipo'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td>C$<?= $row['precio_unitario'] ?></td>
                         <td><?=$row['proveedor']?></td>
                        <td>
                            <a href="crud_equipos/editar_equipo.php?id=<?= $row['id_equipo'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="crud_equipos/eliminar_equipo.php?id=<?= $row['id_equipo'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
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