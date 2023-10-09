<?php
require_once('tcpdf/tcpdf.php');

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();

// Establecer la información del documento (opcional)
$pdf->SetCreator('Tu Aplicación');
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Mi PDF');

// Agregar una página
$pdf->AddPage();

// Agregar contenido al PDF (puedes personalizarlo según tus necesidades)
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, '¡Hola, este es un PDF generado con TCPDF!', 0, 1);

// Generar el PDF en memoria
$pdfContent = $pdf->Output('', 'S');

// Enviar el PDF como respuesta al navegador para descarga
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="mi_pdf.pdf"');
echo $pdfContent;

?>
