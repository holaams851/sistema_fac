<?php
require 'vendor/autoload.php';
include 'conexion.php';
use Dompdf\Dompdf;

// Get invoice ID from URL
$id_factura = (int)$_GET['id'];

// Load invoice from DB
$sql = $conn->query("SELECT * FROM Facturas WHERE id_factura = $id_factura");
$factura = $sql->fetch_assoc();

// load items
$items = $conn->query("SELECT * FROM Detalle_Factura WHERE id_factura = $id_factura");

// HTML template
$html = '
<style>
body { font-family: sans-serif; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
h1 { text-align: center; }
</style>

<h2>Factura #' . $factura['id_factura'] . '</h2>
<p><strong>Fecha:</strong> ' . $factura['fecha'] . '</p>
<p><strong>Cliente:</strong> ' . $factura['nombre'] . '</p>

<table>
<tr><th>Equipo</th><th>Cant.</th><th>Precio Unitario</th><th>Mano de Obra</th><th>Subtotal</th></tr>';

while ($row = $items->fetch_assoc()) {
    $html .= '
    <tr>
        <td>' . $row['nombre_equipo'] . '</td>
        <td>' . $row['cantidad'] . '</td>
        <td>$' . $row['precio_unitario'] . '</td>
        <td>$' . $row['mano_de_obra'] . '</td>
        <td>$' . $row['subtotal'] . '</td>
    </tr>
    <h3>Total: $' . $row['total'] . '</h3>';
}

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("factura_$id_factura.pdf", ["Attachment" => true]);
