<?php
include("../conexion.php");

$id = $_GET['id'];

// ¿Este proveedor está usado en facturas?
$sql_check = "SELECT COUNT(*) AS total FROM detalle_factura WHERE id_servicio=$id";
$res = $conn->query($sql_check);
$row = $res->fetch_assoc();

if ($row['total'] > 0) {
    echo "<script>
        alert('No se puede eliminar este servicio porque está asociado a una factura.');
        window.location.href = '../servicios.php';
    </script>";
    exit;
}

$conn->query("DELETE FROM Servicio WHERE id_servicio = $id");

echo "<script>
    alert('Servicio eliminado correctamente.');
    window.location.href = '../servicios.php';
</script>";
?>
