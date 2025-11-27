<?php
// ARCHIVO: conexion.php (Configuración estándar de XAMPP)

$servername = "localhost"; // Servidor local
$username = "root";        // Usuario predeterminado de XAMPP
$password = "";            // Contraseña predeterminada (generalmente vacía)
$dbname = "sistema_facturacion"; // Nombre que le daremos a tu DB

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>