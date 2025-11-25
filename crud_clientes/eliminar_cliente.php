<?php
include("../conexion.php");

$id = $_GET['id'];

$conn->query("DELETE FROM Clientes WHERE id_cliente = $id");

echo "<script>
    alert('Cliente eliminado correctamente.');
    window.location.href = '../clientes.php';
</script>";
?>
