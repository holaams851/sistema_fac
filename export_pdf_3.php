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

$day = date("d", strtotime($factura['fecha']));
$month = date("n", strtotime($factura['fecha']));
$year = date("y", strtotime($factura['fecha']));

$html = '
<style>
@page { 
    margin: 0;
    size: 700pt 522pt;
}
body {
    margin: 0;
    padding: 0;
    background-size: 700pt 522pt;
    font-family: sans-serif;
}

.field {
    position: absolute;
    font-size: 20px;
    color: #000;
}

#id				{ top: 155px; left: 540px; }
#day            { top: 180px; left: 740px; }
#month          { top: 180px; left: 795px; }
#year           { top: 180px; left: 850px; }
#name           { top: 225px; left: 115px; }
#address        { top: 265px; left: 130px; }
</style>

<div id="id" class="field">'.$id_factura.'</div>
<div id="day" class="field">'.$day.'</div>
<div id="month" class="field">'.$month.'</div>
<div id="year" class="field">'.$year.'</div>
<div id="name" class="field">'.$factura['nombre'].'</div>
<div id="address" class="field">'.$cliente['direccion'].'</div>
';

$startY = 340; // first row vertical position
$rowHeight = 25; // space between rows

// AGREGAR FILAS
$manoObra = 0;
$totalFactura = 0;

while($row = $items->fetch_assoc()) {
    $html .= '
    <div class="field" style="top: '.$startY.'px; left: 80px;">
        '.$row['cantidad'].'
    </div>
    <div class="field" style="top: '.$startY.'px; left: 125px;">
        '.$row['nombre_equipo'].'
    </div>
    <div class="field" style="top: '.$startY.'px; left: 710px;">
        '.$row['precio_unitario'].'
    </div>
    <div class="field" style="top: '.$startY.'px; left: 790px;">
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
    <div class="field" style="top: '.$startY.'px; left: 790px;">
        '.($manoObra).'
    </div>
    <div class="field" style="top: 575px; left: 790px;">
        '.($totalFactura).'
    </div>
';

$dompdf = new Dompdf();
$dompdf->set_option("isRemoteEnabled", true);
$dompdf->loadHtml($html);
$customPaper = array(0, 0, 700, 552); 
$dompdf->setPaper($customPaper, 'landscape');
$dompdf->render();
$dompdf->stream("factura_$id_factura.pdf", ["Attachment" => false]);
