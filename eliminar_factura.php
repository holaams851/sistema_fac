<?php
include 'conexion.php';

$id_factura = (int)($_GET['id'] ?? 0);
if ($id_factura <= 0) {
    die("Factura inválida.");
}

$conn->query("DELETE FROM Detalle_Factura WHERE id_factura = $id_factura");

$conn->query("DELETE FROM Facturas WHERE id_factura = $id_factura");

header("Location: ver_todas_facturas.php");
exit;
?>
