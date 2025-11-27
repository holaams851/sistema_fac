<?php
// Incluye conexión y funciones.
include '../conexion.php'; 
include '../funciones.php'; 

// --- Lógica de Procesamiento ---

$id_cliente = (int)($_POST['id_cliente'] ?? 0);
$fecha = $conn->real_escape_string($_POST['fecha'] ?? '');
$mano_de_obra = isset($_POST['mano_de_obra']) ? (float)$_POST['mano_de_obra'] : 0.0;

$no_equipos = empty($_POST['equipos']['id']) || count(array_filter($_POST['equipos']['id'])) == 0;

// 1. Manejo de errores de datos faltantes (Redirección)
if ($id_cliente <= 0 || empty($fecha)) {
    header("Location: crud_facturas/crear_factura.php?error=datos_faltantes");
    exit;
}

// 2. Obtención del nombre del cliente
$result_nombre = $conn->query("SELECT nombre FROM Clientes WHERE id_cliente = $id_cliente");
if ($result_nombre && $result_nombre->num_rows > 0) {
    $nombre_cliente = $conn->real_escape_string($result_nombre->fetch_assoc()['nombre'] ?? 'Cliente Desconocido');
} else {
    $nombre_cliente = 'Cliente no encontrado';
}

// 3. Manejo de error de equipos faltantes
if ($no_equipos) {
    header("Location: crud_facturas/crear_factura.php?error=no_equipos");
    exit;
}

// 4. Insertar Factura (Cabecera)
$sql_factura = "INSERT INTO Facturas (id_cliente, nombre, fecha) VALUES ($id_cliente, '$nombre_cliente', '$fecha')";
if (!$conn->query($sql_factura)) {
    // Si falla la inserción, redirigimos con error
    header("Location: crud_facturas/crear_factura.php?error=db_factura");
    exit;
}   

$id_factura = $conn->insert_id;

// 5. Insertar Detalle de Factura
if (!empty($_POST['equipos']['id'])) {
    $ids = $_POST['equipos']['id'];
    $nombres= $_POST['equipos']['nombre'];
    $cantidades = $_POST['equipos']['cantidad'];
    $precios = $_POST['equipos']['precio'];
    $subtotales = $_POST['equipos']['subtotal'];
    
    $total_final = 0;

    for ($i = 0; $i < count($ids); $i++) {
        $id_equipo = (int)$ids[$i];
        $nombre = $conn->real_escape_string($nombres[$i]);
        $cantidad = (float)$cantidades[$i];
        $precio = (float)$precios[$i];
        $subtotal = (float)$subtotales[$i];
        
        $total_final += $subtotal;
        $total_final += $mano_de_obra;

        if ($id_equipo > 0) {
            $sql_detalle = "INSERT INTO Detalle_Factura (id_factura, cantidad, precio_unitario, id_equipo, nombre_equipo, total, subtotal, mano_de_obra)
                             VALUES ('$id_factura', '$cantidad', '$precio', '$id_equipo', '$nombre', '$total_final', '$subtotal', '$mano_de_obra')";
            $conn->query($sql_detalle);
        }
    }
}
    
$conn->close();

// --- REDIRECCIÓN INMEDIATA AL DASHBOARD ---
header("Location: index.php?msg=factura_guardada");
exit;
?>