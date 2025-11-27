<?php
include("../conexion.php");
$id = $_GET['id'];

// Verificar si hay proveedores disponibles
$proveedores = $conn->query("SELECT * FROM Proveedores");

$sql = "SELECT * FROM Equipos WHERE id_equipo=$id";
$result = $conn->query($sql);
$equipo = $result->fetch_assoc();

if(isset($_POST['actualizar'])) {
    $nombre= $_POST['nombre'];
    $num_serie = $_POST['num_serie'] ?: "NULL";
    $cantidad = $_POST['cantidad'];
    $precio_unitario = $_POST['precio_unitario'];
    $id_proveedor = $_POST['id_proveedor'] ?: "NULL";
    $id_equipo= $_POST['id_equipo'];

    $sql = "UPDATE Equipo SET nombre='$nombre', num_serie ='$num_serie' WHERE id_equipo=$id";
    $conn->query($sql);
    header("Location: ../equipos.php"); // volver al listado
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Equipo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1 class="mb-4">Editar Equipo</h1>
    <form method="POST">
      <div class="col-lg-6 mb-4">
        <label>Nombre</label>
        <input type="text" name="nombre_equipo" class="form-control" value="<?= $equipo['nombre_equipo'] ?>" required minlength="3">
      </div>
      <div class="col-lg-6 mb-4">
        <label>Precio Unitario</label>
        <input type="number" step="0.1" name="precio_unitario" class="form-control" value="<?= $equipo['precio_unitario'] ?>" required>
      </div>
      <div class="col-lg-6 mb-4">
        <label>Proveedor</label>
        <select name="id_proveedor" class="form-select">
        <?php while($prov = $proveedores->fetch_assoc()) { ?>
            <option value="<?= $prov['id_proveedor'] ?>"><?= $prov['nombre'] ?></option>
          <?php } ?>
        </select>
      </div>
        <div class="col-lg-6 mb-4">
        <label>No. Serie</label>
        <input type="text" name="num_serie" class="form-control">
      </div>
      <button type="submit" name="actualizar" class="btn btn-success">Actualizar</button>
      <a href="../equipos.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
  <script>
      // Script para el tema oscuro/claro
      document.documentElement.setAttribute(
        "data-bs-theme",
        localStorage.getItem("theme") || "light"
      );
  </script>
</body>
</html>
