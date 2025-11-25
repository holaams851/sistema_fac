<?php
include("conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Proveedores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1 class="mb-5">Proveedores</h1>
  <h3 class="mb-5">Aquí puede registrar, editar y eliminar sus proveedores.</h3>
  <a href="crud_proveedores/agregar_proveedor.php" class="btn btn-success mb-3">Agregar Proveedor</a>
  <a href="equipos.php" class="btn btn-primary mb-3">Regresar</a>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Teléfono</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM Proveedores";
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['id_proveedor']}</td>
                  <td>{$row['nombre']}</td>
                  <td>{$row['telefono']}</td>
                  <td>
                    <a href='crud_proveedores/editar_proveedor.php?id={$row['id_proveedor']}' class='btn btn-sm btn-warning'>Editar</a>
                    <a href='crud_proveedores/eliminar_proveedor.php?id={$row['id_proveedor']}' class='btn btn-sm btn-danger'>Eliminar</a>
                  </td>
                </tr>";
      }
      ?>
    </tbody>
  </table>
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
