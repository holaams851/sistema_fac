<?php
include("../conexion.php"); 

$proveedores = $conn->query("SELECT * FROM Proveedores");

if(isset($_POST['guardar'])) {

    $nombre = $_POST['nombre'];
    $num_serie = !empty($_POST['num_serie']) ? $_POST['num_serie'] : NULL;
    $cantidad = $_POST['cantidad'] ?? 1;
    $precio_unitario = $_POST['precio_unitario'];
    $id_proveedor = $_POST['id_proveedor'];

    // 1. Insertar en Equipos
    $sql_equipo = "INSERT INTO Equipos (nombre, num_serie) VALUES ('$nombre', '$num_serie')";
    $conn->query($sql_equipo);

    // Obtener el ID del equipo insertado
    $id_equipo = $conn->insert_id;

    // 2. Insertar en Detalle_Compra
    $sql_compra = "INSERT INTO Detalle_Compra (cantidad, precio_unitario, id_proveedor, id_equipo)
                    VALUES ('$cantidad', '$precio_unitario', '$id_proveedor', '$id_equipo')";
    $conn->query($sql_compra);

    header("Location: ../equipos.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Equipo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1 class="mb-4">Agregar Equipo</h1>
<?php if ($proveedores->num_rows == 0): ?>
    <div class="alert alert-warning mt-3">
      ⚠️ No hay proveedores registrados. Puedes agregar uno <a href="../proveedores.php">aquí</a>.
    </div>
<?php else: ?>
    <form action="agregar_equipo.php" method="POST">
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
      <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
      <a href="../equipos.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
<?php endif; ?>
  <script>
      // Script para el tema oscuro/claro
      document.documentElement.setAttribute(
        "data-bs-theme",
        localStorage.getItem("theme") || "light"
      );
  </script>
</body>
</html>
