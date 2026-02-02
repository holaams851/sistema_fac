<?php
include("../conexion.php"); // Carpeta anterior

if(isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Asegurarse de que las columnas coinciden con el SQL corregido: 'telefono' y 'direccion'
    $sql = "INSERT INTO Clientes (nombre, telefono, direccion) VALUES ('$nombre', '$telefono', '$direccion')";
    $conn->query($sql);
    header("Location: ../clientes.php"); // volver al listado
}

// Variables para el menú lateral (pueden dejarse vacías si no se usan)
$meses = [];
$totales = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Cliente</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="../dashboard.css?v=<?php echo filemtime('../dashboard.css'); ?>"> 
</head>
<body>
  <div class="container-fluid">
    <div class="row">

      <nav class="sidebar"> 
        <div class="sidebar-sticky">
          <a class="sidebar-title" href="../dashboard.php">ProService</a> 
          
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" href="../dashboard.php">
                <span data-feather="home"></span> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="../clientes.php">
                <span data-feather="users"></span> Clientes <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="../proveedores.php"><span data-feather="truck"></span> Proveedores</a></li>
             <li class="nav-item"><a class="nav-link" href="../equipos.php"><span data-feather="shopping-cart"></span> Equipos</a></li>
             <li class="nav-item"><a class="nav-link" href="crear_factura.php"><span data-feather="plus-circle"></span> Crear Factura</a></li>
             <li class="nav-item"><a class="nav-link" href="../ver_todas_facturas.php"><span data-feather="file"></span> Facturas</a></li>
             <li class="nav-item"><a class="nav-link" href="../reportes.php"><span data-feather="bar-chart-2"></span> Reportes</a></li>
          </ul>
        </div>
      </nav>

      <main role="main" class="main-content px-4"> 
        
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2">Agregar Cliente</h1>
          <div class="profile-area">
            <span class="user-name">Admin</span>
            <img src="../logo.jpeg" alt="Foto de Perfil" class="profile-pic"> 
          </div>
        </div>

        <form action="agregar_cliente.php" method="POST" class="mt-5">
          <div class="col-lg-6 mb-4">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required minlength="3">
          </div>
          <div class="col-lg-6 mb-4">
            <label>Teléfono</label>
            <input type="tel" name="telefono" class="form-control" pattern="[0-9]{8,8}" required placeholder="Solo números">
          </div>
          <div class="col-lg-6 mb-4">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control">
          </div>
          <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
          <a href="../clientes.php" class="btn btn-secondary">Cancelar</a>
        </form>
      </main>
    </div>
  </div>

  <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
  <script>
    feather.replace();
  </script>
</body>
</html>