<?php
include '../conexion.php'; 
include '../funciones.php'; 

$id_factura = ($_POST['id_factura'] ?? '');
$id_factura = sprintf("%04d", $id_factura); // Formatear a 4 dígitos con ceros a la izquierda
if($id_factura === '0000' || !is_numeric($id_factura) || (int)$id_factura <= 0 || (int)$id_factura > 9999) {
    header("Location: crear_factura.php?error=invalid_id");
    exit;
}
$id_cliente = (int)($_POST['id_cliente'] ?? 0);
$fecha = $conn->real_escape_string($_POST['fecha'] ?? '');
$total = isset($_POST['total']) ? (float)$_POST['total'] : 0.0;
$no_equipos = empty($_POST['equipos']['id']) || count(array_filter($_POST['equipos']['id'])) == 0;
$no_desc = empty($_POST['descripciones']['descripcion']) || count(array_filter($_POST['descripciones']['descripcion'])) == 0;

// 1. Manejo de errores de datos faltantes (Redirección)
if ($id_cliente <= 0 || empty($fecha)) {
    header("Location: crear_factura.php?error=datos_faltantes");
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
if ($no_equipos && $no_desc) {
    header("Location: crear_factura.php?error=no_equipos_desc");
    exit;
}

// 4. Insertar Factura (Cabecera)
try {
    $sql_factura = "INSERT INTO Facturas (id_factura, id_cliente, nombre, fecha) VALUES ('$id_factura', $id_cliente, '$nombre_cliente', '$fecha')";
    if (!$conn->query($sql_factura)) {
        // Si falla la inserción, redirigimos con error
        header("Location: crear_factura.php?error=db_factura");
        exit;
    }   
} catch (mysqli_sql_exception $e) {
    header("Location: crear_factura.php?error=db_factura");
    exit;
}


// 5. Insertar Detalle de Factura
if (!empty($_POST['equipos']['id']) && !empty($_POST['descripciones']['descripcion'])) {
    $ids = $_POST['equipos']['id'];
    $nombres= $_POST['equipos']['nombre'];
    $cantidades = $_POST['equipos']['cantidad'];
    $precios = $_POST['equipos']['precio'];
    $subtotales = $_POST['equipos']['subtotal'];
    $descripciones = $_POST['descripciones']['descripcion'];
    $manos_de_obra = $_POST['descripciones']['mano_de_obra'];


    for ($i = 0; $i < count($ids); $i++) {
        $id_equipo = (int)$ids[$i];
        $nombre = $conn->real_escape_string($nombres[$i]);
        $cantidad = (float)$cantidades[$i];
        $precio = (float)$precios[$i];
        $subtotal = (float)$subtotales[$i];
        $descripcion= $conn->real_escape_string($descripciones[$i]);
        $mano_de_obra = isset($manos_de_obra[$i]) ? (float)$manos_de_obra[$i] : 0.0;

        if ($id_equipo > 0) {
            $sql_detalle = "INSERT INTO Detalle_Factura (id_factura, cantidad, precio_unitario, id_equipo, nombre_equipo, subtotal, mano_de_obra, descripcion)
                             VALUES ('$id_factura', '$cantidad', '$precio', '$id_equipo', '$nombre', '$subtotal', '$mano_de_obra', '$descripcion')";
            $conn->query($sql_detalle);
        }
    }
} else if (!empty($_POST['descripciones']['descripcion'])) {
    $descripciones = $_POST['descripciones']['descripcion'];
    $manos_de_obra = $_POST['descripciones']['mano_de_obra'];

    for ($i = 0; $i < count($descripciones); $i++) {
        $descripcion = $conn->real_escape_string($descripciones[$i]);
        $mano_de_obra = isset($manos_de_obra[$i]) ? (float)$manos_de_obra[$i] : 0.0;

        if (!empty($descripcion)) {
            $sql_detalle = "INSERT INTO Detalle_Factura (id_factura, cantidad, precio_unitario, id_equipo, nombre_equipo, subtotal, mano_de_obra, descripcion)
                             VALUES ('$id_factura', 0, 0, NULL, '-', 0, '$mano_de_obra', '$descripcion')";
            $conn->query($sql_detalle);
        }
    }
} else if (!empty($_POST['equipos']['id'])) {
    $ids = $_POST['equipos']['id'];
    $nombres= $_POST['equipos']['nombre'];
    $cantidades = $_POST['equipos']['cantidad'];
    $precios = $_POST['equipos']['precio'];
    $subtotales = $_POST['equipos']['subtotal'];

    for ($i = 0; $i < count($ids); $i++) {
        $id_equipo = (int)$ids[$i];
        $nombre = $conn->real_escape_string($nombres[$i]);
        $cantidad = (float)$cantidades[$i];
        $precio = (float)$precios[$i];
        $subtotal = (float)$subtotales[$i];

        if ($id_equipo > 0) {
            $sql_detalle = "INSERT INTO Detalle_Factura (id_factura, cantidad, precio_unitario, id_equipo, nombre_equipo, subtotal, mano_de_obra, descripcion)
                             VALUES ('$id_factura', '$cantidad', '$precio', '$id_equipo', '$nombre', '$subtotal', 0, '-')";
            $conn->query($sql_detalle);
        }
    }
}

$conn->query("UPDATE Detalle_Factura SET total = $total WHERE id_factura = '$id_factura'");
$conn->close();

// --- REDIRECCIÓN INMEDIATA AL DASHBOARD ---
header("Location: ../index.php?msg=factura_guardada");
exit;
?>