<?php
include("conexion.php");

$token = $_GET['token'] ?? '';

$stmt = $conn->prepare("
    SELECT id
    FROM usuarios
    WHERE reset_token = ?
    AND reset_expires > NOW()
");

$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

if (!$user) {
    die("Token inválido o expirado.");
}

$user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        UPDATE usuarios
        SET password = ?,
            reset_token = NULL,
            reset_expires = NULL
        WHERE id = ?
    ");

    $stmt->bind_param("si", $hash, $user_id);
    $stmt->execute();

    echo "Contraseña actualizada correctamente.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ProService Admin</title>
  <style>
      html {
          font-size: 125%; /* Increases the default base font size */
      }
  </style>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="logo.png" alt="logo">
              </div>
                <form method="POST">
                    <input type="password" name="new_password" required>
                    <button type="submit">Cambiar Contraseña</button>
                    <a href="index.php" class="auth-link text-black">Volver al inicio de sesión</a>
                </form>
            </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../../vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>



