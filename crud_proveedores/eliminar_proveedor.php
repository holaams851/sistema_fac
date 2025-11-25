<?php
include("../conexion.php");

$id = $_GET['id'];

// ¿Este proveedor está usado en facturas?
$sql_check = "SELECT COUNT(*) AS total FROM Detalle_Compra WHERE id_proveedor=$id";
$res = $conn->query($sql_check);
$row = $res->fetch_assoc();

if ($row['total'] > 0) {
    echo "<script>
        alert('No se puede eliminar este proveedor porque está asociado a un equipo.');
        window.location.href = '../proveedores.php';
    </script>";
    exit;
}

$conn->query("DELETE FROM Proveedores WHERE id_proveedor = $id");

echo "<script>
    alert('Proveedor eliminado correctamente.');
    window.location.href = '../proveedores.php';
</script>";
?>
