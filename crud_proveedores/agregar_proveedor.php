<?php
include("../conexion.php"); // Carpeta anterior
?>

<?php
if(isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO Proveedores (nombre, telefono) VALUES ('$nombre', '$telefono')";
    $conn->query($sql);
    header("Location: ../proveedores.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Proveedor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Agregar Proveedor</h1>
  <form method="POST">
    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Teléfono</label>
      <input type="tel" name="telefono" class="form-control" pattern="[0-9]{8,8}" required placeholder="Solo números">
    </div>
    <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
    <a href="../proveedores.php" class="btn btn-secondary">Cancelar</a>
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
