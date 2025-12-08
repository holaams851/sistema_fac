<?php
require 'vendor/autoload.php';
include 'conexion.php';
use Dompdf\Dompdf;

$id_factura = (int)$_GET['id'];

$sql = $conn->query("SELECT * FROM Facturas WHERE id_factura = $id_factura");
$factura = $sql->fetch_assoc();

$items = $conn->query("SELECT * FROM Detalle_Factura WHERE id_factura = $id_factura");

$customPaper = array(0, 0, 612, 522); 
$dompdf->setPaper($customPaper, 'landscape');

$html = '
<style>
@page { margin: 0; }
body {
    margin: 0;
    padding: 0;
    background-image: url("http://sistema-facturacion.infinityfree.me/invoice.png");
    background-size: cover; /* or 100% 100% */
    font-family: sans-serif;
}

.field {
    position: absolute;
    font-size: 14px;
    color: #000;
}

/* You set positions here */
#invoice_number { top: 120px; left: 420px; }
#date           { top: 150px; left: 420px; }
#customer       { top: 200px; left: 120px; }

/* Example for item rows */
.item-row { position: absolute; left: 100px; font-size: 12px; }
</style>

<div id="invoice_number" class="field">'.$factura['id_factura'].'</div>
<div id="date" class="field">'.$factura['fecha'].'</div>
<div id="customer" class="field">'.$factura['cliente'].'</div>
';


// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("factura_$id_factura.pdf", ["Attachment" => false]);
