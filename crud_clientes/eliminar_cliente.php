<?php
// Incluye el archivo de conexión (debe estar un directorio arriba)
include("../conexion.php");

// Obtiene el ID del cliente a eliminar de la URL
$id = $_GET['id'];

// 1. Ejecuta la consulta de eliminación SQL
$conn->query("DELETE FROM Clientes WHERE id_cliente = $id");

// 2. Redirige al listado de clientes usando la función header() de PHP
// Esto es una redirección a nivel de servidor, es instantánea y no requiere la interacción del usuario.
header("Location: ../clientes.php");

// Es crucial usar exit; después de header() para detener la ejecución del script
// y asegurar que la redirección se complete de inmediato.
exit; 
?>