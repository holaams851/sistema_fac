<?php
include 'conexion.php';

$q = $_GET['term'] ?? '';
$q = $conn->real_escape_string($q);

$sql = "SELECT id_servicio, nombre, precio FROM Servicio WHERE nombre LIKE '%$q%'";
$res = $conn->query($sql);

$results = [];
while ($r = $res->fetch_assoc()) {
    $results[] = [
        'id_servicio' => $r['id_servicio'],
        'label' => $r['nombre'],
        'precio' => $r['precio']
    ];
}
echo json_encode($results);
?>
