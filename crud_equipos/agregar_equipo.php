<?php
include("../conexion.php"); 

$proveedores = $conn->query("SELECT * FROM Proveedores");

if(isset($_POST['guardar'])) {

    $nombre = $_POST['nombre'];
    $num_serie = !empty($_POST['num_serie']) ? $_POST['num_serie'] : NULL;
    // Se eliminan $cantidad y $id_equipo del POST ya que no están en el formulario
    $precio_unitario = $_POST['precio_unitario'];
    $id_proveedor = $_POST['id_proveedor'];

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
}

// Variables dummy para el layout
$meses = [];
$totales = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Equipo</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link href="../dashboard.css" rel="stylesheet"> 
</head>
<body>
  <div class="container-fluid">
    <div class="row">

      <nav class="sidebar"> 
        <div class="sidebar-sticky">
          <a class="sidebar-title" href="../index.php">Toner & Más</a> 
          
          <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="../index.php"><span data-feather="home"></span> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="../clientes.php"><span data-feather="users"></span> Clientes</a></li>
            <li class="nav-item"><a class="nav-link" href="../proveedores.php"><span data-feather="truck"></span> Proveedores</a></li>
            <li class="nav-item"><a class="nav-link active" href="../equipos.php"><span data-feather="shopping-cart"></span> Equipos <span class="sr-only">(current)</span></a></li>
            <li class="nav-item"><a class="nav-link" href="../crud_facturas/crear_factura.php"><span data-feather="plus-circle"></span> Crear Factura</a></li>
            <li class="nav-item"><a class="nav-link" href="../ver_todas_facturas.php"><span data-feather="file"></span> Facturas</a></li>
            <li class="nav-item"><a class="nav-link" href="../reportes.php"><span data-feather="bar-chart-2"></span> Reportes</a></li>
          </ul>
        </div>
      </nav>

      <main role="main" class="main-content px-4"> 
        
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2">Agregar Equipo</h1>
          <div class="profile-area">
              <span class="user-name">Admin</span>
              <img src="../logo.jpeg" alt="Foto de Perfil" class="profile-pic"> 
          </div>
        </div>
        
        <?php if ($proveedores->num_rows == 0): ?>
          <div class="alert alert-warning mt-3">
            ⚠️ No hay proveedores registrados. Puedes agregar uno <a href="../proveedores.php">aquí</a>.
          </div>
        <?php else: ?>
          <form action="agregar_equipo.php" method="POST" class="mt-5">
            <div class="col-lg-6 mb-4">
              <label>Nombre</label>
              <input type="text" name="nombre" class="form-control" required minlength="3">
            </div>
            <div class="col-lg-6 mb-4">
              <label>Precio Unitario</label>
              <input type="number" step="0.1" name="precio_unitario" class="form-control" required>
            </div>
            <div class="col-lg-6 mb-4">
              <label>Proveedor</label>
              <select name="id_proveedor" class="form-select form-control">
              <?php while($prov = $proveedores->fetch_assoc()) { ?>
                  <option value="<?= $prov['id_proveedor'] ?>"><?= $prov['nombre'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-lg-6 mb-4">
              <label>No. Serie</label>
              <input type="text" name="num_serie" class="form-control">
            </div>
            <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
            <a href="../equipos.php" class="btn btn-secondary">Cancelar</a>
          </form>
        <?php endif; ?>
      </main>
    </div>
  </div>
  <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
  <script>
    feather.replace();
  </script>
</body>
</html>