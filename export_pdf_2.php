<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'vendor/autoload.php';
include 'conexion.php';
use Dompdf\Dompdf;

$id_factura = (int)$_GET['id'];

$sql = $conn->query("SELECT * FROM Facturas WHERE id_factura = $id_factura");
$factura = $sql->fetch_assoc();

$items = $conn->query("SELECT * FROM Detalle_Factura WHERE id_factura = $id_factura");

$sql_datos = "SELECT c.telefono, c.direccion
        FROM Facturas f
        JOIN Clientes c ON f.id_cliente = c.id_cliente
        WHERE f.id_factura = $id_factura";

$result = $conn->query($sql_datos);
$cliente = $result->fetch_assoc();

// Load image as base64
$imgPath = $_SERVER['DOCUMENT_ROOT'] . "/invoice.jpeg";
$imgData = base64_encode(file_get_contents($imgPath));
$imgSrc = 'data:image/jpeg;base64,' . $imgData . '?v=' . time();

$day = date("d", strtotime($factura['fecha']));
$month = date("n", strtotime($factura['fecha']));
$year = date("y", strtotime($factura['fecha']));


$html = '
<style>
@page { 
    margin: 0;
    size: 604pt 396pt;
}
body {
    margin: 0;
    padding: 0;
    background-image: url("'.$imgSrc.'");
    background-size: 604pt 396pt;
    font-family: sans-serif;
}

.field {
    position: absolute;
    font-size: 14px;
    color: #000;
}

#day            { top: 140px; left: 640px; }
#month          { top: 140px; left: 695px; }
#year           { top: 140px; left: 740px; }
#name           { top: 180px; left: 100px; }
#address        { top: 210px; left: 110px; }
</style>

<div id="day" class="field">'.$day.'</div>
<div id="month" class="field">'.$month.'</div>
<div id="year" class="field">'.$year.'</div>
<div id="name" class="field">'.$factura['nombre'].'</div>
<div id="address" class="field">'.$cliente['direccion'].'</div>
';

$startY = 267; // first row vertical position
$rowHeight = 20; // space between rows

// AGREGAR FILAS
$manoObra = 0;
$totalFactura = 0;

while($row = $items->fetch_assoc()) {
    $html .= '
    <div class="field" style="top: '.$startY.'px; left: 70px;">
        '.$row['cantidad'].'
    </div>
    <div class="field" style="top: '.$startY.'px; left: 110px;">
        '.$row['nombre_equipo'].'
    </div>
    <div class="field" style="top: '.$startY.'px; left: 620px;">
        '.$row['precio_unitario'].'
    </div>
    <div class="field" style="top: '.$startY.'px; left: 687px;">
        '.($row['subtotal']).'
    </div>
    ';
    if ($manoObra == 0 && isset($row['mano_de_obra'])) {
        $manoObra = $row['mano_de_obra'];
    }
    if ($totalFactura == 0 && isset($row['total'])) {
        $totalFactura = $row['total'];
    }
    $startY += $rowHeight;
}

$html .= '
    <div class="field" style="top: '.$startY.'px; left: 110px;">
        Mano de Obra
    </div>
    <div class="field" style="top: '.$startY.'px; left: 687px;">
        '.($manoObra).'
    </div>
    <div class="field" style="top: 450px; left: 687px;">
        '.($totalFactura).'
    </div>
';


$dompdf = new Dompdf();
$dompdf->set_option("isRemoteEnabled", true);
$dompdf->loadHtml($html);
$customPaper = array(0, 0, 604, 396); 
$dompdf->setPaper($customPaper, 'landscape');
$dompdf->render();
$dompdf->stream("factura_{$id_factura}_" . time() . ".pdf", ["Attachment" => false]);
