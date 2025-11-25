<?php
include("../conexion.php");
$id = $_GET['id'];

$sql = "SELECT * FROM Servicio WHERE id_servicio=$id";
$result = $conn->query($sql);
$servicio = $result->fetch_assoc();

if(isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    $sql = "UPDATE Servicio SET nombre='$nombre', descripcion='$descripcion', precio='$precio' WHERE id_servicio=$id";
    $conn->query($sql);
    header("Location: ../servicios.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Servicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1>Editar Servicio</h1>
    <form method="POST">
      <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= $servicio['nombre'] ?>" required>
      </div>
      <div class="mb-3">
          <label>Descripción</label>
          <textarea name="descripcion" class="form-control"><?=$servicio['descripcion']?></textarea>
      </div>
      <div class="mb-3">
        <label>Precio</label>
        <input type="number" step="0.01" name="precio" class="form-control" value="<?= $servicio['precio'] ?>" required>
      </div>
      <button type="submit" name="actualizar" class="btn btn-success">Actualizar</button>
      <a href="../servicios.php" class="btn btn-secondary">Cancelar</a>
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
