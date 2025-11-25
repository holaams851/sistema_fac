<?php 
include("../conexion.php");
?>

<?php
if($_POST){
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $sql = "INSERT INTO Servicio (nombre, descripcion, precio) VALUES ('$nombre','$descripcion','$precio')";
    $conn->query($sql);
    header("Location: ../servicios.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Servicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Nuevo Servicio</h2>
        <form method="post">
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Precio</label>
                <input type="number" step="0.1" name="precio" class="form-control" required>
            </div>
            <button class="btn btn-success">Guardar</button>
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
