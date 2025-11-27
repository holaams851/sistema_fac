<?php
include("../conexion.php");

$id = $_GET['id'];

// ¿Este equipo está usado en facturas?
$sql_check = "SELECT COUNT(*) AS total FROM Detalle_Factura WHERE id_equipo = $id";
$res = $conn->query($sql_check);
$row = $res->fetch_assoc();

if ($row['total'] > 0) {
    echo "<script>
        alert('No se puede eliminar este equipo porque está asociado a facturas.');
        window.location.href = '../equipos.php';
    </script>";
    exit;
}

$conn->query("DELETE FROM Equipos WHERE id_equipo = $id");

echo "<script>
    alert('Equipo eliminado correctamente.');
    window.location.href = '../equipos.php';
</script>";
?>
