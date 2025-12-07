<?php
require 'vendor/autoload.php';
include 'conexion.php';
use Dompdf\Dompdf;

$id_factura = (int)$_GET['id'];

$sql = $conn->query("SELECT * FROM Facturas WHERE id_factura = $id_factura");
$factura = $sql->fetch_assoc();

$items = $conn->query("SELECT * FROM Detalle_Factura WHERE id_factura = $id_factura");

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
<tr><th>Equipo</th><th>Cant.</th><th>Precio Unitario</th><th>Subtotal</th></tr>';

// AGREGAR FILAS
$manoObra = 0;
$totalFactura = 0;

while ($row = $items->fetch_assoc()) {
    $html .= '
    <tr>
        <td>' . $row['nombre_equipo'] . '</td>
        <td>' . $row['cantidad'] . '</td>
        <td>C$ ' . number_format($row['precio_unitario'], 2) . '</td>
        <td>C$ ' . number_format($row['subtotal'], 2) . '</td>
    </tr>';

    if ($manoObra == 0 && isset($row['mano_de_obra'])) {
        $manoObra = $row['mano_de_obra'];
    }

    if ($totalFactura == 0 && isset($row['total'])) {
        $totalFactura = $row['total'];
    }
}


$html .= "
</table>

<h3>Mano de Obra: C$ " . number_format($manoObra, 2) . "</h3>
<h2>Total: C$ " . number_format($totalFactura, 2) . "</h2>
";


// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("factura_$id_factura.pdf", ["Attachment" => false]);
