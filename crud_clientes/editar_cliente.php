<?php
include("../conexion.php");
$id = $_GET['id'];

$sql = "SELECT * FROM Clientes WHERE id_cliente=$id";
$result = $conn->query($sql);
$cliente = $result->fetch_assoc();

if(isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $sql = "UPDATE Clientes SET nombre='$nombre', telefono='$telefono', direccion='$direccion' WHERE id_cliente=$id";
    $conn->query($sql);
    header("Location: ../clientes.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Cliente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1>Editar Cliente</h1>
    <form method="POST">
      <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= $cliente['nombre'] ?>" required>
      </div>
      <div class="mb-3">
        <label>Teléfono</label>
        <input type="tel" name="telefono" class="form-control" pattern="[0-9]{8,8}" required placeholder="Solo números">
      </div>
      <div class="mb-3">
        <label>Dirección</label>
        <input type="text" name="direccion" class="form-control" value="<?= $cliente['direccion'] ?>">
      </div>
      <button type="submit" name="actualizar" class="btn btn-success">Actualizar</button>
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
