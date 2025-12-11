<?php
session_start();
include("conexion.php");

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

// check if user exists
$sql = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
$sql->bind_param("s", $usuario);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        // login OK
        $_SESSION['usuario'] = $usuario;
        header("Location: dashboard.php");  // redirect to your dashboard
        exit;
    } else {
        echo "Contraseña incorrecta";
    }
} else {
    echo "Usuario no encontrado";
}
?>
