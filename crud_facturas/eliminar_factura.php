<?php
include '../conexion.php';

$id_factura = (int)($_GET['id'] ?? 0);

// Se elimina die() y se usa redirección con mensaje de error si el ID es inválido
if ($id_factura <= 0) {
    header("Location: listar_facturas.php?error=invalid_id"); 
    exit;
}

// 1. Eliminar Detalle
$conn->query("DELETE FROM detalle_factura WHERE id_factura = $id_factura");

// 2. Eliminar Factura (Corregir nombre de tabla: Factura -> Facturas)
$conn->query("DELETE FROM Facturas WHERE id_factura = $id_factura");

// Redirección instantánea con mensaje de éxito
header("Location: listar_facturas.php?msg=eliminado");
exit;
?>