<?php
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
$imgPath = $_SERVER['DOCUMENT_ROOT'] . "/invoice.png";
$imgData = base64_encode(file_get_contents($imgPath));
$imgSrc = 'data:image/png;base64,' . $imgData;

$day = date("d", strtotime($factura['fecha']));
$month = date("n", strtotime($factura['fecha']));
$year = date("y", strtotime($factura['fecha']));

$html = '
<style>
@page { 
    margin: 0;
    size: 612pt 522pt;
}
body {
    margin: 0;
    padding: 0;
    background-image: url("'.$imgSrc.'");
    background-size: 612pt 522pt;
    font-family: sans-serif;
}

.field {
    position: absolute;
    font-size: 14px;
    color: #000;
}

#day            { top: 220px; left: 625px; }
#month          { top: 220px; left: 695px; }
#year           { top: 220px; left: 765px; }
#name           { top: 215px; left: 105px; }
#address        { top: 240px; left: 115px; }
#number         { top: 267px; left: 115px; }
</style>

<div id="day" class="field">'.$day.'</div>
<div id="month" class="field">'.$month.'</div>
<div id="year" class="field">'.$year.'</div>
<div id="name" class="field">'.$factura['nombre'].'</div>
<div id="address" class="field">'.$cliente['direccion'].'</div>
<div id="number" class="field">'.$cliente['telefono'].'</div>
';

$startY = 320; // first row vertical position
$rowHeight = 25; // space between rows

// AGREGAR FILAS
$manoObra = 0;
$totalFactura = 0;

while($row = $items->fetch_assoc()) {
    $html .= '
    <div class="field" style="top: '.$startY.'px; left: 90px;">
        '.$row['cantidad'].'
    </div>
    <div class="field" style="top: '.$startY.'px; left: 125px;">
        '.$row['nombre_equipo'].'
    </div>
    <div class="field" style="top: '.$startY.'px; left: 640px;">
        '.$row['precio_unitario'].'
    </div>
    <div class="field" style="top: '.$startY.'px; left: 730px;">
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
    <div class="field" style="top: '.$startY.'px; left: 125px;">
        Mano de Obra
    </div>
    <div class="field" style="top: '.$startY.'px; left: 730px;">
        '.($manoObra).'
    </div>
    <div class="field" style="top: 583px; left: 730px;">
        '.($totalFactura).'
    </div>
';

$dompdf = new Dompdf();
$dompdf->set_option("isRemoteEnabled", true);
$dompdf->loadHtml($html);
$customPaper = array(0, 0, 612, 552); 
$dompdf->setPaper($customPaper, 'landscape');
$dompdf->render();
$dompdf->stream("factura_$id_factura.pdf", ["Attachment" => false]);
