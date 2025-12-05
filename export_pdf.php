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

// AGREGAR FILAS

$row = $items->fetch_assoc();
while ($row) {
    $html .= '
    <tr>
        <td>' . $row['nombre_equipo'] . '</td>
        <td>' . $row['cantidad'] . '</td>
        <td>C$ ' . number_format($row['precio_unitario'], 2) . '</td>
        <td>C$ ' . number_format($row['subtotal'], 2) . '</td>
    </tr>';
}

$html .= '
</table>

<h3>Mano de Obra: C$ ' . number_format($row['mano_de_obra'], 2) . '</h3>
<h2>Total: C$ ' . number_format($row['total'], 2) . '</h2>
';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("factura_$id_factura.pdf", ["Attachment" => false]);
