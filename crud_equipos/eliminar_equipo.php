<?php
include("../conexion.php");

$id = $_GET['id'];

// ¿Este equipo está usado en facturas?
$sql_check = "SELECT COUNT(*) AS total FROM Detalle_Factura WHERE id_equipo = $id";
$res = $conn->query($sql_check);
$row = $res->fetch_assoc();

if ($row['total'] > 0) {
    // Si está asociado, redirige con un mensaje de error
    header("Location: ../equipos.php?error=asociado");
    exit;
}

// Si no está en uso, procede a la eliminación
// 1. Eliminar de Detalle_Compra (depende de la tabla Equipos)
$conn->query("DELETE FROM Detalle_Compra WHERE id_equipo = $id");

// 2. Eliminar de Equipos
$conn->query("DELETE FROM Equipos WHERE id_equipo = $id");

// Redirección instantánea sin alerta JS
header("Location: ../equipos.php?msg=eliminado");
exit;
?>