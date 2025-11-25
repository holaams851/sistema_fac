<?php
include("conexion.php");
$result = $conn->query("SELECT * FROM Equipos");  
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Equipos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  
  <div class="container mt-5">
    <h1 class="mb-5">Equipos</h1>
    <h3 class="mb-5">Aquí puede registrar, editar y eliminar sus equipos.</h3>
    <a href="crud_equipos/agregar_equipo.php" class="btn btn-success mb-3">Agregar Equipo</a>
    <a href="proveedores.php" class="btn btn-secondary mb-3">Proveedores</a>
    <a href="index.php" class="btn btn-primary mb-3">Regresar</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio Unitario</th>
                    <th>Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT 
                    e.*,
                    p.nombre AS proveedor,
                    dc.precio_unitario
                    FROM Detalle_Compra dc
                    INNER JOIN Equipos e 
                        ON dc.id_equipo = e.id_equipo
                    INNER JOIN Proveedores p 
                        ON dc.id_proveedor = p.id_proveedor";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_equipo'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td>C$<?= $row['precio_unitario'] ?></td>
                         <td><?=$row['proveedor']?></td>
                        <td>
                            <a href="crud_equipos/editar_equipo.php?id=<?= $row['id_equipo'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="crud_equipos/eliminar_equipo.php?id=<?= $row['id_equipo'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
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
</html>
