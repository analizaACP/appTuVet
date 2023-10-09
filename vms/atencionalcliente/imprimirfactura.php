<?php



function imprimir($numerofact){

    $db = pg_connect("host=localhost port=5432 dbname=tuvet user=adolfo password=ofloda01");
include('library/tcpdf.php'); 

echo "<script>
        var newWindow = window.open('about:blank', '_blank');
        newWindow.document.write('<html><head><title>Factura</title></head><body>');
    </script>";

//consulta encabezado
$numerofactura=$numerofact;
$encabezado=pg_query($db,"select * from hfacturas h join clientes c on h.nombre_cliente=c.cliente_nombre where h.numero_impreso='$numerofactura'");

$row = pg_fetch_object($encabezado);
$fecha=$row->fecha_cobro;
$nit=$row->cliente_nodoc;
$cliente=$row->nombre_cliente;
$direccion1=$row->cliente_dir1;
$direccion2=$row->cliente_dir2;
$giro=$row->cliente_giro;
$subtotal=$row->subtotal;
$gravado=$row->monto_gravado;
$descuento=$row->descuentos;
$impuesto=$row->impuesto;
$exento=$row->monto_nogravado;
$total=$row->total_linea;



$pdf = new TCPDF();
$pdf->SetMargins(10, 10, 10); // Márgenes de 10 mm en cada lado
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage('L', 'A4');

$pdf->SetFont('courier', 9);

$pdf->SetY(15);
$pdf->Cell(83, 5, $numerofactura, 0, 1, 'C');

//imprimir encabezado de la factura
$pdf->SetY(25);
$pdf->Cell(83, 5, $fecha." Id No. ".$nit, 0, 1, 'L');
$pdf->Cell(83, 5, $cliente, 0, 1, 'L');
$pdf->Cell(83, 5, $direccion1." ".$direccion2, 0, 1, 'L');
// imprimir detalle de la factura

$detalle=pg_query($db,"select * from dfacturas d join productos p on d.cod_barra=p.prod_codbarra where id_hfactura='$numerofactura'");
$pdf->SetY(60);
while ($row_detalle = pg_fetch_object($detalle)) {
    $nombreLinea = $row_detalle->prod_nombre; // Cambia esto al nombre del campo en tu base de datos
    $cantLinea = $row_detalle->cantidad; // Cambia esto al nombre del campo en tu base de datos
    $precioLinea = $row_detalle->precio; // Cambia esto al nombre del campo en tu base de datos
    $descLinea=$row_detalle->descuento;
    $precioDescuento=$row_detalle->precio-$row_detalle->monto_descuento;
    $subtotLinea=$row_detalle->subtotal;
    $ivaLinea=$row_detalle->iva;
    $totLinea=$row_detalle->total_linea;
    
    
    
    $pdf->Cell(83, 5, $nombreLinea, 0, 0, 'L');
    $pdf->Cell(83, 5, $cantLinea, 0, 0, 'L');
    $pdf->Cell(83, 5, $precioLinea, 0, 1, 'L');
    
    
    $pdf->Cell(83, 5, $descLinea, 0, 0, 'L');
    $pdf->Cell(83, 5, $precioDescuento, 0, 0, 'L');
    $pdf->Cell(83, 5, $ivaLinea, 0, 0, 'L');
    $pdf->Cell(83, 5, $totLinea, 0, 1, 'L');
    
    
    
}






//imprimir pie de la factura
$pdf->SetY(150);
$pdf->Cell(83, 5, "SubTotal ".$subtotal, 0, 0, 'L');
$pdf->Cell(83, 5, "Desc ".$descuento, 0, 1, 'L');
$pdf->Cell(83, 5, "Gravado ".$gravado, 0, 0, 'L');
$pdf->Cell(83, 5, "Impuesto ".$impuesto, 0, 1, 'L');
$pdf->Cell(83, 5, "Exento ".$exento, 0, 0, 'L');
$pdf->Cell(83, 5, "Total ".$total, 0, 1, 'L');






$pdf->SetLineWidth(0.1); // Ancho de línea en milímetros


$pdf->Output('factura.pdf', 'I');
}

if(isset($_POST['submit'])){
    $numero=$_REQUEST['nuevamarca'];
    
    imprimir($numero);

}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
</head>
<body>

<form role="form" name="marcas" method="post" >

<input type="text" name="nuevamarca" class="form-control"  autocomplete="off" placeholder="Ingrese numero">

<button type="submit" name="submit" class="btn btn-o btn-primary">
															enviar
														</button>

</form>

</body>
</html>

