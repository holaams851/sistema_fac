<?php
include '../conexion.php';

$q = $_GET['term'] ?? '';
$q = $conn->real_escape_string($q);

$sql = "
    SELECT 
        e.id_equipo,
        e.nombre,
        e.num_serie,
        d.precio_unitario,
        d.cantidad,
        d.id_proveedor
    FROM Equipos e
    LEFT JOIN Detalle_Compra d ON e.id_equipo = d.id_equipo
    WHERE e.nombre LIKE '%$q%'
";

$res = $conn->query($sql);

$results = [];

while ($r = $res->fetch_assoc()) {
    $results[] = [
        'id_equipo' => $r['id_equipo'],
        'label' => $r['nombre'],   // autocomplete label
        'num_serie' => $r['num_serie'],
        'precio_unitario' => $r['precio_unitario'],
        'cantidad' => $r['cantidad'],
        'id_proveedor' => $r['id_proveedor']
    ];
}

echo json_encode($results);
?>
