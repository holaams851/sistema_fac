<?php
include 'conexion.php';

// --- Ventas por mes (Necesario si se incluye funciones.php o el sidebar) ---
// Variables dummy para el layout
$meses = [];
$totales = [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="dashboard.css" rel="stylesheet"> 
</head>
<body>
<div class="container-fluid">
    <div class="row">

        <nav class="sidebar"> 
          <div class="sidebar-sticky">
            <a class="sidebar-title" href="index.php">Toner & Más</a> 
            
            <ul class="nav flex-column">
              <li class="nav-item"><a class="nav-link" href="index.php"><span data-feather="home"></span> Dashboard</a></li>
              <li class="nav-item"><a class="nav-link" href="clientes.php"><span data-feather="users"></span> Clientes</a></li>
              <li class="nav-item"><a class="nav-link" href="proveedores.php"><span data-feather="truck"></span> Proveedores</a></li>
              <li class="nav-item"><a class="nav-link" href="equipos.php"><span data-feather="shopping-cart"></span> Equipos</a></li>
              <li class="nav-item"><a class="nav-link" href="crud_facturas/crear_factura.php"><span data-feather="plus-circle"></span> Crear Factura</a></li>
              <li class="nav-item"><a class="nav-link active" href="ver_todas_facturas.php"><span data-feather="file"></span> Facturas <span class="sr-only">(current)</span></a></li>
              <li class="nav-item"><a class="nav-link" href="reportes.php"><span data-feather="bar-chart-2"></span> Reportes</a></li>
            </ul>
          </div>
        </nav>

        <main role="main" class="main-content px-4"> 

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Facturas</h1>
                <div class="profile-area">
                    <span class="user-name">Admin</span>
                    <img src="logo.jpeg" alt="Foto de Perfil" class="profile-pic"> 
                </div>
            </div>

            <a href="crud_facturas/crear_factura.php" class="btn btn-success mb-3">Nueva Factura</a>

            <?php
            // Obtener facturas con su total
            $sql_facturas = "
                SELECT 
                    f.id_factura,
                    f.nombre,
                    f.fecha,
                    df.total AS total
                FROM Facturas f
                LEFT JOIN Detalle_Factura df ON df.id_factura = f.id_factura
                GROUP BY f.id_factura, f.nombre, f.fecha
                ORDER BY f.fecha DESC
            ";

            $res_facturas = $conn->query($sql_facturas);

            while ($factura = $res_facturas->fetch_assoc()):
                $id_factura = $factura['id_factura'];

                // Obtener detalle de cada factura
                $sql_detalle = "
                    SELECT 
                        df.id_detalle,
                        f.nombre AS nombre_cliente,
                        df.nombre_equipo,
                        df.cantidad,
                        df.precio_unitario,
                        df.subtotal,
                        df.mano_de_obra
                    FROM Detalle_Factura df 
                    JOIN Facturas f ON f.id_factura = df.id_factura
                    WHERE df.id_factura = $id_factura
                ";

                $res_detalle = $conn->query($sql_detalle);
            ?>

                <div class="mb-4 p-3 rounded shadow-sm" style="background-color: var(--bg-sidebar);">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h4 class="text-white">Factura #<?= $factura['id_factura'] ?> - Cliente: <?= $factura['nombre'] ?: "<i>Cliente eliminado</i>" ?> (<?= $factura['fecha'] ?>)</h4>
                        
                        <a href="crud_facturas/eliminar_factura.php?id=<?= $factura['id_factura'] ?>" 
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Estás seguro de eliminar esta factura?');">Eliminar</a>
                    </div>

                    <table class="table table-bordered table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th>Mano de Obra</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($d = $res_detalle->fetch_assoc()): ?>
                            <tr>
                                <td><?= $d['nombre_equipo']?></td>
                                <td><?= $d['cantidad'] ?></td>
                                <td>C$<?= number_format($d['precio_unitario'], 2) ?></td>
                                <td>C$<?= number_format($d['mano_de_obra'], 2) ?></td>
                                <td>C$<?= number_format($d['subtotal'], 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: var(--bg-dark);">
                                <th colspan="4" class="text-end">Total:</th>
                                <th>C$<?= number_format($factura['total'],2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    <a class="btn btn-warning" href="export_pdf.php?id=<?= $factura['id_factura'] ?>" target="_blank">
                            Exportar PDF
                    </a>
                </div>

            <?php endwhile; ?>
            
            <?php if ($res_facturas->num_rows === 0): ?>
                <div class="alert alert-info text-center mt-5">No se encontraron facturas registradas.</div>
            <?php endif; ?>

        </main>
    </div>
</div>

<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace();
</script>
</body>
</html>