<?php
include("conexion.php");
include("funciones.php");

$stmt = $conn->prepare(
    "SELECT id FROM users
     WHERE reset_token=?
     AND reset_expires > NOW()"
);

$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();
if ($stmt->num_rows === 0) {
    die("Token inválido o expirado");
}

$user_id = $result->fetch_assoc()['id'];
$hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "UPDATE users
     SET password=?,
         reset_token=NULL,
         reset_expires=NULL
     WHERE id=?"
);
$stmt->bind_param("ss", $hash, $user_id);
$stmt->execute();

?>

<form method="POST">
    <input type="password" name="new_password">
    <button type="submit">Cambiar Contraseña</button>
</form>