<?php
include("conexion.php");
include("funciones.php");
?>
  
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Clientes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  
  <div class="container mt-5">
    <h1 class="mb-5">Clientes</h1>
    <h3 class="mb-5">Aquí puede registrar, editar y eliminar a sus clientes.</h3>
    <a href="crud_clientes/agregar_cliente.php" class="btn btn-success mb-3">Agregar Cliente</a>
    <a href="index.php" class="btn btn-primary mb-3">Regresar</a>

  <?php
  $sql = "SELECT * FROM Clientes ORDER BY id_cliente ASC"; // ASC = ascendente
  $result = $conn->query($sql);
  mostrarTabla($result, ['id_cliente','nombre','telefono','direccion'], ['editar'=>"crud_clientes/editar_cliente.php",'eliminar'=>"crud_clientes/eliminar_cliente.php"]);
  ?>
  </div>

  <script>
      // Script para el tema oscuro/claro
      document.documentElement.setAttribute(
        "data-bs-theme",
        localStorage.getItem("theme") || "light"
      );
  </script>
</body>
</html>
