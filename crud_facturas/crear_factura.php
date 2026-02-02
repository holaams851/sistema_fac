<?php include '../conexion.php'; 
// Obtener clientes
$clientes = $conn->query("SELECT id_cliente, nombre FROM Clientes");
$today = date('Y-m-d');

// Variables dummy para el layout
$meses = [];
$totales = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Factura</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../dashboard.css?v=<?php echo filemtime('../dashboard.css'); ?>"> 
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
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
                    <li class="nav-item"><a class="nav-link active" href="crear_factura.php"><span data-feather="plus-circle"></span> Crear Factura <span class="sr-only">(current)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="../ver_todas_facturas.php"><span data-feather="file"></span> Facturas</a></li>
                    <li class="nav-item"><a class="nav-link" href="../reportes.php"><span data-feather="bar-chart-2"></span> Reportes</a></li>
                </ul>
            </div>
        </nav>

        <main role="main" class="main-content px-4"> 
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">

                <h1 class="h2">Crear Factura</h1>
                <div class="profile-area">
                <a class="user-name" href="index.php">Admin</a> 
                    <img src="../logo.jpeg" alt="Foto de Perfil" class="profile-pic"> 
                </div>
            </div>

            <div class="container p-4 rounded shadow-sm">
                <form id="facturaForm" method="POST" action="guardar_factura.php">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Cliente:</label>
                            <select name="id_cliente" class="form-select form-control" required>
                                <option value="">Seleccionar cliente</option>
                                <?php while($c = $clientes->fetch_assoc()){ ?>
                                    <option value="<?= $c['id_cliente'] ?>"><?= $c['nombre'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Fecha:</label>
                            <input type="date" name="fecha" class="form-control" max="<?php echo $today; ?>" required>
                        </div>
                    </div>

                    <hr>
                    <h5>Equipos</h5>
                    <table class="table table-bordered" id="tablaEquipos">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <button type="button" class="btn btn-outline-primary mb-3" id="agregarEquipo">+ Agregar equipo</button>

                    <div class="row mt-4">
                        <div class="col-md-4 offset-md-8">
                            <label>Mano de obra:</label>
                            <input type="number" step="0.01" class="form-control" id="manoObra" name="mano_de_obra" value="0">
                        </div>
                    </div>

                     <div class="text-end mt-3">
                        <h4>Comisión Equipos: C$<span id="extraVista">0.00</span></h4>
                         <input type="hidden" name="extra" id="extra">
                    </div>

                    <div class="text-end mt-3">
                        <h4>Total: C$<span id="totalVista">0.00</span></h4>
                         <input type="hidden" name="total" id="total">
                    </div>

                    <button type="submit" class="btn btn-success">Guardar Factura</button>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace();

    $(document).ready(function() {
        // ... (Tu código JavaScript para la factura se mantiene igual)
        function actualizarTotal() {
            let total = 0;
            // suma de equipos
            $(".subtotal").each(function() {
                total += parseFloat($(this).val() || 0);
            });
            // mano de obra
            let manoObra = parseFloat($("#manoObra").val() || 0);
            let extra = 0.30 * total; // 30% extra
            total += manoObra;
            total += extra;

            $("#extraVista").text(extra.toFixed(2)); // mostrado
            $("#extra").val(extra.toFixed(2));

            $("#totalVista").text(total.toFixed(2)); // mostrado
            $("#total").val(total.toFixed(2));
        }

        function filaEquipo() {
            return `
                <tr>
                    <td><input type="text" class="form-control equipo" name="equipos[nombre][]" placeholder="Buscar equipo"></td>
                    <td><input type="number" class="form-control cantidad" name="equipos[cantidad][]" value="1" min="1"></td>
                    <td><input type="text" class="form-control precio" name="equipos[precio][]" readonly></td>
                    <td><input type="text" class="form-control subtotal" name="equipos[subtotal][]" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm eliminar">X</button></td>
                    <input type="hidden" name="equipos[id][]" class="id_item">
                </tr>`;
        }

        $("#agregarEquipo").click(function() {
            $("#tablaEquipos tbody").append(filaEquipo());
        });

        $("#manoObra").on("input", function() {
            actualizarTotal();
        });

        $(document).on("click", ".eliminar", function() {
            $(this).closest("tr").remove();
            actualizarTotal();
        });

        // Autocomplete para equipos
        $(document).on("focus", ".equipo", function() {
            $(this).autocomplete({
                source: "/crud_equipos/buscar_equipos.php",
                minLength: 1,
                select: function(event, ui) {
                    let row = $(this).closest("tr");
                    row.find(".id_item").val(ui.item.id_equipo);
                    row.find(".precio").val(ui.item.precio_unitario);
                    row.find(".cantidad").val(1);
                    row.find(".subtotal").val(ui.item.precio_unitario);
                    actualizarTotal();
                }
            });
        });

        $(document).on("input", ".cantidad", function() {
            let row = $(this).closest("tr");
            let cantidad = parseFloat(row.find(".cantidad").val() || 0);
            let precio = parseFloat(row.find(".precio").val() || 0);
            row.find(".subtotal").val((cantidad * precio).toFixed(2));
            actualizarTotal();
        });
    });
</script>
</body>
</html>