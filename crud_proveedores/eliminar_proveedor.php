<?php
include("../conexion.php");

$id = $_GET['id'];

// 1. VALIDACIÓN: Verificar si este proveedor está asociado a un equipo
$sql_check = "SELECT COUNT(*) AS total FROM Detalle_Compra WHERE id_proveedor=$id";
$res = $conn->query($sql_check);
$row = $res->fetch_assoc();

if ($row['total'] > 0) {
    // Si está asociado, redirige con un parámetro de error (la alerta se manejaría en proveedores.php)
    // Para simplificar, regresaremos con un mensaje de error en el listado.
    header("Location: ../proveedores.php?error=asociado");
    exit;
}

// 2. ELIMINACIÓN
$conn->query("DELETE FROM Proveedores WHERE id_proveedor = $id");

// 3. REDIRECCIÓN INSTANTÁNEA sin alerta JS
header("Location: ../proveedores.php?msg=eliminado");
exit;
?>