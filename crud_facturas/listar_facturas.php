<?php
include '../conexion.php';
$sql = "SELECT f.id_factura, c.nombre AS cliente, f.fecha, f.total 
        FROM Factura f
        JOIN Cliente c ON f.id_cliente = c.id_cliente";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Facturas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Facturas</h2>
    <a href="crear_factura.php" class="btn btn-success mb-3">Nueva Factura</a>
    <a href="../index.php" class="btn btn-secondary mb-3">Volver</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id_factura'] ?></td>
                <td><?= $row['cliente'] ?></td>
                <td><?= $row['fecha'] ?></td>
                <td><?= $row['total'] ?></td>
                <td>
                    <a href="editar_factura.php?id=<?= $row['id_factura'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="eliminar_factura.php?id=<?= $row['id_factura'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<script>
      // Script para el tema oscuro/claro
      document.documentElement.setAttribute(
        "data-bs-theme",
        localStorage.getItem("theme") || "light"
      );
    </script>
</body>
</html>
