<?php
$host     = "sql302.infinityfree.com";     // aparece en el panel de InfinityFree 
$username = "if0_40376376";
$password = "Omigato123";
$database = "if0_40376376_sistema_facturacion";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

 