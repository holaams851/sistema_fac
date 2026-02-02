<?php
include '../conexion.php';
include '../funciones.php'; // Incluir para usar funciones si es necesario

$sql = "SELECT f.id_factura, c.nombre AS cliente, f.fecha, f.total 
        FROM Facturas f
        JOIN Clientes c ON f.id_cliente = c.id_cliente"; // Corregir Factura a Facturas y Cliente a Clientes
$result = $conn->query($sql);

// Variables dummy para el layout
$meses = [];
$totales = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Facturas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="../dashboard.css?v=7" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">

        <nav class="sidebar"> 
            <div class="sidebar-sticky">
                <a class="sidebar-title" href="../dashboard.php">ProService</a> 
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="../dashboard.php"><span data-feather="home"></span> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../clientes.php"><span data-feather="users"></span> Clientes</a></li>
                    <li class="nav-item"><a class="nav-link" href="../proveedores.php"><span data-feather="truck"></span> Proveedores</a></li>
                    <li class="nav-item"><a class="nav-link" href="../equipos.php"><span data-feather="shopping-cart"></span> Equipos</a></li>
                    <li class="nav-item"><a class="nav-link" href="crear_factura.php"><span data-feather="plus-circle"></span> Crear Factura</a></li>
                    <li class="nav-item"><a class="nav-link active" href="listar_facturas.php"><span data-feather="file"></span> Facturas <span class="sr-only">(current)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="../reportes.php"><span data-feather="bar-chart-2"></span> Reportes</a></li>
                </ul>
            </div>
        </nav>

        <main role="main" class="main-content px-4"> 
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Listado de Facturas</h1>
                <div class="profile-area">
                    <span class="user-name">Admin</span>
                    <img src="../logo.jpeg" alt="Foto de Perfil" class="profile-pic"> 
                </div>
            </div>

            <a href="crear_factura.php" class="btn btn-success mb-3">Nueva Factura</a>
            
            <table class="table table-striped mt-3">
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
        </main>
    </div>
</div>
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace();
</script>
</body>
</html>