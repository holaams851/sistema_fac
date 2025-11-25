<?php
include 'conexion.php';

// Obtener clientes
$clientes = $conn->query("SELECT id_cliente, nombre FROM Clientes");

$id_cliente = (int)($_POST['id_cliente'] ?? 0);
$nombre = $conn->query("SELECT nombre FROM Clientes WHERE id_cliente = $id_cliente")
                      ->fetch_assoc()['nombre'];
$fecha = $conn->real_escape_string($_POST['fecha'] ?? '');

$no_equipos   = empty($_POST['equipos']['id']) || count(array_filter($_POST['equipos']['id'])) == 0;

if(empty($_POST['id_cliente']) || empty($_POST['fecha'])) {
    die("Datos incompletos: cliente o fecha faltantes");
}
if ($no_equipos) {
    echo "<script>
        alert('Debe agregar al menos un equipo.');
        window.location.href = 'crud_facturas/crear_factura.php';
    </script>";
    exit;
}

$sql_factura = "INSERT INTO Facturas (id_cliente, nombre, fecha) VALUES ($id_cliente, '$nombre', '$fecha')";
if (!$conn->query($sql_factura)) {
    die("Error al guardar factura: " . $conn->error);
}

$id_factura = $conn->insert_id;

if (!empty($_POST['equipos']['id'])) {
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
            $sql_detalle = "INSERT INTO Detalle_Factura (id_factura, id_equipo, nombre_equipo, cantidad, precio_unitario, subtotal)
                            VALUES ('$id_factura', '$id_equipo', '$nombre', '$cantidad', '$precio', '$subtotal')";
            $conn->query($sql_detalle);
        }
    }
}
    
echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
        <h3>✅ Factura guardada con éxito</h3>
        <p>ID de factura: <strong>$id_factura</strong></p>
        <a href='index.php'>← Salir</a>
      </div>";

$conn->close();
?>
