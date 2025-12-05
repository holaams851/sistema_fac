<?php
include '../conexion.php'; 
include '../funciones.php'; 

$id_cliente = (int)($_POST['id_cliente'] ?? 0);
$fecha = $conn->real_escape_string($_POST['fecha'] ?? '');
$mano_de_obra = isset($_POST['mano_de_obra']) ? (float)$_POST['mano_de_obra'] : 0.0;

// Validación
if ($id_cliente <= 0 || empty($fecha)) {
    header("Location: crear_factura.php?error=datos_faltantes");
    exit;
}

if (empty($_POST['equipos']['id']) || count(array_filter($_POST['equipos']['id'])) == 0) {
    header("Location: crear_factura.php?error=no_equipos");
    exit;
}

// Insertamos factura (cabecera)
$sql_factura = "INSERT INTO Facturas (id_cliente, fecha) VALUES ($id_cliente, $fecha)";
if (!$conn->query($sql_factura)) {
    header("Location: crear_factura.php?error=db_factura");
    exit;
}

$id_factura = $conn->insert_id;

// INSERTAR DETALLES
$ids = $_POST['equipos']['id'];
$nombres = $_POST['equipos']['nombre'];
$cantidades = $_POST['equipos']['cantidad'];
$precios = $_POST['equipos']['precio'];
$subtotales = $_POST['equipos']['subtotal'];

for ($i = 0; $i < count($ids); $i++) {

    $id_equipo = (int)$ids[$i];

    if ($id_equipo <= 0) continue;

    $nombre = $conn->real_escape_string($nombres[$i]);
    $cantidad = (float)$cantidades[$i];
    $precio = (float)$precios[$i];
    $subtotal = (float)$subtotales[$i];

    // Insertar línea individual
    $sql_detalle = "
        INSERT INTO Detalle_Factura 
        (id_factura, cantidad, precio_unitario, id_equipo, nombre_equipo, subtotal, mano_de_obra)
        VALUES 
        ($id_factura, $cantidad, $precio, $id_equipo, '$nombre', $subtotal, $mano_de_obra)
    ";

    $conn->query($sql_detalle);
}

$conn->close();

// Redirigir
header("Location: ../index.php?msg=factura_guardada");
exit;
?>
