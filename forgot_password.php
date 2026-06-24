<?php
include("conexion.php");
include("funciones.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$email = 'andreaaragon851@gmail.com';
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';
$mail->Username   = 'andreaaragon851@gmail.com';
$mail->Password   = 'mfug hqbq ckup qgjp';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;

$mail->setFrom('andreaaragon851@gmail.com');

$token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

$stmt = $conn->prepare(
    "UPDATE usuarios SET reset_token=?, reset_expires=? WHERE email=?"
);
$stmt->bind_param("sss", $token, $expires, $email);
$stmt->execute();

$link = "https://sistema-facturacion.infinityfree.me/reset_password.php?token=$token";

$mail->addAddress($email);
$mail->Subject = "Cambiar Contraseña";
$mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña:\n$link";

$mail->send();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="refresh" content="5;url=https://sistema-facturacion.infinityfree.me/" />
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
              <h4>Ahora le va a llegar un correo con las instrucciones para restablecer su contraseña.</h4>
              <h4>Revise su bandeja de correo: andreaaragon851@gmail.com</h4>
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


