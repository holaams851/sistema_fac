<?php
include("../conexion.php"); // Carpeta anterior

if(isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $sql = "INSERT INTO Clientes (nombre, telefono, direccion) VALUES ('$nombre', '$telefono', '$direccion')";
    $conn->query($sql);
    header("Location: ../clientes.php"); // volver al listado
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Cliente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1 class="mb-4">Agregar Cliente</h1>
    <form action="agregar_cliente.php" method="POST">
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
