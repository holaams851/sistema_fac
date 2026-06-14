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
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ProService</title>
  <style>
      html {
          font-size: 125%; /* Increases the default base font size */
      }
  </style>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../vendors/base/vendor.bundle.base.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo me-5" href="../dashboard.php"><img src="../logoCut.png" class="me-5" alt="logo"></a>
        <a class="navbar-brand brand-logo-mini" href="../dashboard.php"><img src="../logo.png" class="me-2" alt="logo"></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="ti-view-list"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../user.png" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="../index.php">
                <i class="ti-power-off text-primary"></i>
                Cerrar Sesión
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="ti-view-list"></span>
        </button>
      </div>
    </nav>
     <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="../dashboard.php">
              <i class="ti-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
             <a class="nav-link" href="../clientes.php">
              <i class="ti-user menu-icon"></i>
              <span class="menu-title">Clientes</span>
            </a>
          <li class="nav-item">
              <a class="nav-link" href="../proveedores.php">
              <i class="ti-truck menu-icon"></i>
              <span class="menu-title">Proveedores</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../equipos.php">
              <i class="ti-desktop menu-icon"></i>
              <span class="menu-title">Equipos</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="crear_factura.php">
              <i class="ti-pencil-alt menu-icon"></i>
              <span class="menu-title">Crear Factura</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="../ver_todas_facturas.php">
              <i class="ti-file menu-icon"></i>
              <span class="menu-title">Factura</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="../reportes.php">
              <i class="ti-bar-chart menu-icon"></i>
              <span class="menu-title">Reportes</span>
            </a>
        </ul>
      </nav>
      <!-- partial -->
       <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-10 grid-margin">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="font-weight-bold mb-0">Crear Factura</h4>
                </div>
              </div>
            </div>
          </div>
            <div class="row">
            <div class="col-md-10 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                     <form id="facturaForm" method="POST" action="guardar_factura.php">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Cliente:</h5>
                                <select name="id_cliente" class="form-select form-control" required>
                                    <option value="">Seleccionar cliente</option>
                                    <?php while($c = $clientes->fetch_assoc()){ ?>
                                        <option value="<?= $c['id_cliente'] ?>"><?= $c['nombre'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <h5>Fecha:</h5>
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
                            <h6>Comisión Equipos: C$<span id="extraVista">0.00</span></h6>
                             <input type="hidden" name="extra" id="extra">
                        </div>

                        <div class="text-end mt-3">
                            <h6>Margen de Ganancias: C$<span id="margenVista">0.00</span></h6>
                             <input type="hidden" name="margen" id="margen">
                        </div>

                        <div class="text-end mt-3">
                            <h4>Total: C$<span id="totalVista">0.00</span></h4>
                             <input type="hidden" name="total" id="total">
                        </div>

                        <button type="submit" class="btn btn-primary me-2">Guardar Factura</button>
                    </form>
                  </div>  
                </div>
              </div>
            </div>
            
        <!-- content-wrapper ends -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
                </div>
              </div>
            </div>
          </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
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

            let margen = manoObra + extra;
            
            $("#margenVista").text(margen.toFixed(2)); // mostrado
            $("#margen").val(margen.toFixed(2));

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
  <!-- plugins:js -->
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="../vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../js/off-canvas.js"></script>
  <script src="../js/hoverable-collapse.js"></script>
  <script src="../js/template.js"></script>
  <script src="../js/todolist.js"></script>
  <!-- endinject -->
</body>
</html>