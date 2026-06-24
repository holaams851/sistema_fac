<?php
require 'vendor/autoload.php';
include 'conexion.php';

use setasign\Fpdi\Fpdi; // Remove if using plain FPDF
use FPDF;

$id_factura = (int)$_GET['id'];

$sql = $conn->query("SELECT * FROM Facturas WHERE id_factura = $id_factura");
$factura = $sql->fetch_assoc();

$items = $conn->query("
    SELECT
        df.cantidad,
        df.nombre_equipo,
        df.precio_unitario,
        df.subtotal,
        df.total
    FROM Detalle_Factura df
    WHERE df.id_factura = $id_factura
    AND (df.cantidad != 0 OR df.precio_unitario != 0)
");

$details = $conn->query("
    SELECT
        df.mano_de_obra,
        df.descripcion,
        df.total
    FROM Detalle_Factura df
    WHERE df.id_factura = $id_factura
    AND (df.mano_de_obra != 0 OR df.descripcion != '-')
");

$sql_datos = "
SELECT c.telefono, c.direccion
FROM Facturas f
JOIN Clientes c ON f.id_cliente = c.id_cliente
WHERE f.id_factura = $id_factura
";

$result = $conn->query($sql_datos);

mysqli_set_charset($conn, "utf8mb4");

$cliente = $result->fetch_assoc();

$day   = date("d", strtotime($factura['fecha']));
$month = date("n", strtotime($factura['fecha']));
$year  = date("y", strtotime($factura['fecha']));

$pdf = new FPDF('L', 'pt', [604, 396]);
$pdf->SetAutoPageBreak(false);

$pdf->AddPage();

$pdf->Image(
    $_SERVER['DOCUMENT_ROOT'] . '/invoice.jpeg',
    0,
    0,
    604,
    396
);

$pdf->SetFont('Arial', '', 14);

/* FECHA */

$pdf->SetXY(475, 110);
$pdf->Cell(20, 0, $day);

$pdf->SetXY(520, 110);
$pdf->Cell(20, 0, $month);

$pdf->SetXY(550, 110);
$pdf->Cell(20, 0, $year);

/* CLIENTE */

$pdf->SetXY(90, 140);
$pdf->Cell(
    300,
    0,
    mb_convert_encoding($factura['nombre'], 'ISO-8859-1', 'UTF-8')
);

$pdf->SetXY(90, 165);
$pdf->Cell(
    400,
    0,
    mb_convert_encoding($cliente['direccion'], 'ISO-8859-1', 'UTF-8')
);

/* DETALLE */

$startY = 208;
$rowHeight = 15;

$totalFactura = 0;

while ($row = $items->fetch_assoc()) {

    $pdf->SetXY(30, $startY);
    $pdf->Cell(30, 0, $row['cantidad']);

    $pdf->SetXY(70, $startY);
    $pdf->Cell(
        450,
        0,
        mb_convert_encoding($row['nombre_equipo'], 'ISO-8859-1', 'UTF-8')
    );

    $pdf->SetXY(460, $startY);
    $pdf->Cell(50, 0, $row['precio_unitario']);

    $pdf->SetXY(520, $startY);
    $pdf->Cell(50, 0, $row['subtotal']);

    if ($totalFactura == 0 && isset($row['total'])) {
        $totalFactura = $row['total'];
    }

    $startY += $rowHeight;
}

/* MANO DE OBRA Y DESCRIPCIÓN */

while ($row = $details->fetch_assoc()) {

    $pdf->SetXY(70, $startY - 6);

    $descripcion = mb_convert_encoding(
    $row['descripcion'],
    'ISO-8859-1',
    'UTF-8'
    );

    $pdf->MultiCell(390, 14, $descripcion);

    $pdf->SetXY(520, $startY);
    $pdf->Cell(50, 0, $row['mano_de_obra']);

    if ($totalFactura == 0 && isset($row['total'])) {
        $totalFactura = $row['total'];
    }
    
    $startY += $rowHeight;
    
}

/* TOTAL */

$pdf->SetXY(520, 345);
$pdf->Cell(50, 0, $totalFactura);

$pdf->Output(
    'I',
    "factura_{$id_factura}.pdf"
);