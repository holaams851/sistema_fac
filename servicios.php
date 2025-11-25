<?php
include("conexion.php");
$result = $conn->query("SELECT * FROM Servicio");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Servicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-5">Servicios</h2>
        <h3 class="mb-5">Aquí puede registrar, editar y eliminar los servicios que provee.</h3>
        <a href="crud_servicios/agregar_servicio.php" class="btn btn-success mb-3">Nuevo Servicio</a>
        <a href="index.php" class="btn btn-primary mb-3">Regresar</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_servicio'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td>C$<?= $row['precio'] ?></td>
                        <td>
                            <a href="crud_servicios/editar_servicio.php?id=<?= $row['id_servicio'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="crud_servicios/eliminar_servicio.php?id=<?= $row['id_servicio'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
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