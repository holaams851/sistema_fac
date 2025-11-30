<?php
include("../conexion.php");

$id = $_GET['id'];

$conn->query("DELETE FROM Detalle_Compra WHERE id_equipo = $id");

$conn->query("DELETE FROM Equipos WHERE id_equipo = $id");

header("Location: ../equipos.php?msg=eliminado");
exit;
?>