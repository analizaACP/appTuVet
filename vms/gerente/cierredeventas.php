<?php
session_start();
error_reporting(0);
include_once ('include/config.php');
include ('include/checklogin.php');
check_login();


if(isset($_POST['submit']))
{
    
    $fecha=$_REQUEST['fecha'];
    
    $cierre=pg_query($db,"SELECT cobrada_por,fecha_cobro, SUM(pago_efectivo) as efectivo, SUM(pago_tarjeta) as tarjeta, sum(pago_credito) as credito
                        FROM hfacturas WHERE fecha_cobro = '$fecha' and pagada_sn=1 and anulada=0
                        GROUP BY fecha_cobro, cobrada_por");

}


if(isset($_POST['imprimir']))
{
    
    $fecha=$_REQUEST['fecha'];
    $sucursal=$_SESSION['sucursal'];
    // nombre de la sucursal
    $obtenerNombreSucursal=pg_query($db,"select sucursal_nombre from sucursales where id='$sucursal'");
    $fila = pg_fetch_assoc($obtenerNombreSucursal);
    $nombreSucursal = $fila['sucursal_nombre'];
    $totalEfectivo=0;
    $totalTarjeta=0;
    $totalCredito=0;
    $total=0;
    
    include('library/tcpdf.php');
    $cierre=pg_query($db,"SELECT cobrada_por,fecha_cobro, SUM(pago_efectivo) as efectivo, SUM(pago_tarjeta) as tarjeta, sum(pago_credito) as credito
                        FROM hfacturas WHERE fecha_cobro = '$fecha' and pagada_sn=1 and anulada=0
                        GROUP BY fecha_cobro, cobrada_por");
    
    
    
    $pdf = new TCPDF('P', 'mm', array(95, 230)); // 'P' para orientación vertical    //$pdf->setTempDir('/pdftemporal'); // Ruta temporal
    $pdf->SetMargins(5, 5, 5); // Márgenes de 10 mm en cada lado
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();
    
    $pdf->SetFont('courier', 8);
    
    //imprimir encabezado del cierre
    
    $pdf->SetY(3);
    $pdf->Cell(30, 5, "Tu Vet Sucursal ".$nombreSucursal , 0, 1, 'L');
    $pdf->Cell(30, 5, "Cierre de Ventas" , 0, 1, 'L');
    $pdf->Cell(30, 5, "Fecha de Venta ".$fecha , 0, 1, 'L');
    $pdf->Cell(30, 5, "****DETALLE POR CAJERO **** " , 0, 1, 'L');
    //imprimir detalle del cierre
    
    $pdf->SetY(25);
    
    while($row = pg_fetch_object($cierre)){
        
        $cajero=$row->cobrada_por;
        $efectivo=  "$" . number_format($row->efectivo, 2);
        $tarjeta="$" . number_format($row->tarjeta, 2);
        $credito="$" . number_format($row->credito, 2);
        
        $totalEfectivo+=$row->efectivo;
        $totalTarjeta+=$row->tarjeta;
        $totalCredito+=$row->credito;
        
        $pdf->Cell(30, 5, "Cajero: ".$cajero, 0, 1, 'L');
        $pdf->Cell(30, 5, "Venta en Efectivo---- ".$efectivo, 0, 1, 'L');
        $pdf->Cell(30, 5, "Venta en Tarjeta----- ".$tarjeta, 0, 1, 'L');
        $pdf->Cell(30, 5, "Venta al Credito----- ".$credito, 0, 1, 'L');
        
        
        
        $pdf->Ln();
        
        
        
    }
    
    $granTotal=$totalCredito+$totalEfectivo+$totalTarjeta;
    $totalEfectivo="$".number_format($totalEfectivo,2);
    $totalTarjeta="$".number_format($totalTarjeta,2);
    $totalCredito="$".number_format($totalCredito,2);
    $granTotal="$".number_format($granTotal,2);
    
    $pdf->Cell(30, 5, "****SUMA DE TOTALES****** " , 0, 1, 'L');
    //imprimir pie del cierre
    $pdf->Cell(40, 5, "Venta Total Efectivo---- ", 0, 0, 'L');
    $pdf->Cell(40, 5, $totalEfectivo, 0, 1, 'R');
    $pdf->Cell(40, 5, "Venta Total Tarjeta----- ", 0, 0, 'L');
    $pdf->Cell(40, 5, $totalTarjeta, 0, 1, 'R');
    
    $pdf->Cell(40, 5, "Venta Total Credito----- ", 0, 0, 'L');
    $pdf->Cell(40, 5, $totalCredito, 0, 1, 'R');
    
    $pdf->Cell(40, 5, "Venta Total ------------ ", 0, 0, 'L');
    $pdf->Cell(40, 5, $granTotal, 0, 1, 'R');
    
    $pdf->Ln();
    
    
    
    
    
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(30, 5, "***FACTURAS PROCESADAS**** " , 0, 1, 'L');
    
    
    
    //imprimir listado de facturas
    $listadefacturas=pg_query($db,"select * from hfacturas where fecha_cobro='$fecha' and pagada_sn=1 and anulada=0");
    
    
    $pdf->Cell(30, 5, "no. efectivo tarjeta credito total" , 0, 1, 'L');
    
    while($row = pg_fetch_object($listadefacturas)){
        $numeroFactura = $row->numero_impreso;
        $pagoE = "$" . number_format($row->pago_efectivo, 2);
        $pagoT = "$" . number_format($row->pago_tarjeta, 2);
        $pagoC = "$" . number_format($row->pago_credito, 2);
        $totalLinea = "$" . number_format($row->total_linea, 2);
        $total=$total+$row->total_linea;
        
        
        $pdf->Cell(17, 5, $numeroFactura, 0, 0, 'L');
        $pdf->Cell(17, 5, $pagoE, 0, 0, 'R');
        $pdf->Cell(17, 5, $pagoT, 0, 0, 'R');
        $pdf->Cell(17, 5, $pagoC, 0, 0, 'R');
        $pdf->Cell(17, 5, $totalLinea, 0, 0, 'R');
        $pdf->Ln();
    }
    
    $total = "$" . number_format($total, 2);
    $pdf->Cell(40, 5, "Total Facturas --------- ".$total, 0, 1, 'L');
    
    $pdf->Ln();
    $pdf->Cell(30, 5, "***FACTURAS ANULADAS**** " , 0, 1, 'L');
    $facturasAnuladas=pg_query($db,"select * from hfacturas where fecha_anulacion='$fecha' and pagada_sn=1 and anulada=1");
    $pdf->Cell(30, 5, "no.    fecha emision     total" , 0, 1, 'L');
    while($row = pg_fetch_object($facturasAnuladas)){
        
        $numeroAnulada = $row->numero_impreso;
        $fechaAnulada=$row->fecha_cobro;
        $montoAnulado = "$" . number_format($row->total_linea, 2);
        
        $pdf->Cell(25, 5, $numeroAnulada, 0, 0, 'L');
        $pdf->Cell(25, 5, $fechaAnulada, 0, 0, 'L');
        $pdf->Cell(25, 5, $montoAnulado, 0, 0, 'R');
        $pdf->Ln();
        
    }
    
    $pdfContent = $pdf->Output('', 'S');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="cierreventas_' . $fecha . '.pdf"');
    
    echo $pdfContent;
    
    
    //$pdf->Output('/home/analiza/Documentos/cierreventas_' . $fecha . '.pdf', 'F');
    
}



?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Cierre de Ventas</title>
		<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script
	src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link href="vendor/DataTables/css/jquery.dataTables.min.css"
	rel="stylesheet" media="screen">
<link rel="stylesheet"
	href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
		<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
		<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
		<link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/plugins.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
		
		
    


		
	</head>
	<body>
<div id="app">		
<?php include('include/sidebar.php');?>
<div class="app-content">
<?php include('include/header.php');?>
<div class="main-content" >
<div class="wrap-content container" id="container">
						<!-- start: PAGE TITLE -->
<section id="page-title">
<div class="row">
<div class="col-sm-8">
<h1 class="mainTitle">Ventas del dia</h1>
</div>
<ol class="breadcrumb">
<li>
<span></span>
</li>
<li class="active">
<span>Cobrar</span>
</li>
</ol>
</div>
</section>
<div class="container-fluid container-fullw bg-white">
<div class="row">
<div class="col-md-12">

<div class="row margin-top-30">
										<div class="col-lg-6 col-md-12">
											<div class="panel panel-white">
												<div class="panel-heading">
<h5 class="over-title margin-bottom-15"><span class="text-bold">Ventas del Dia</span></h5>


<form id="formulario" role="form" name="marcas" method="post" >
        <div class="form-group">
			
		<input type="date" name="fecha" class="form-control"  autocomplete="off" required><br>
		
		<button type="submit" name="submit" class="btn btn-o btn-primary">
															Ver
														</button>
														
		<button type="submit" name="imprimir" class="btn btn-o btn-primary">
															PDF
														</button>												
														</div>
		
        </form>

<script>
    $(document).ready(function() {
        <?php if (isset($_POST['submit'])) : ?>
            $('#kardexModal').modal('show');
        <?php endif; ?>
    });
</script>

<div class="modal fade" id="kardexModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	

  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
     <div class="modal-content">
      <div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Ventas del dia por usuario</h5>
<?php echo $fecha;?>       
                                                    
      </div>
   <div class="modal-body">
<table id="example" class="display" style="width:100%">
      <thead>
            <tr>
                <th>Cobrador</th>
                <th>Fecha</th>
                <th>Efectivo</th>
                <th>Tarjeta</th>
                <th>Credito</th>
                <th>TOTAL</th>
                
            </tr>
      </thead>      
      <tbody>
      <?php 
      
      
      
      while($row = pg_fetch_object($cierre)){
      ?>
      <tr>
      <td><?php echo $row->cobrada_por;?></td>
      <td><?php echo $row->fecha_cobro;?></td>
      <td><?php echo $row->efectivo;?></td>
       <td><?php echo $row->tarjeta;?></td>
       <td><?php echo $row->credito;?></td>
      <td><?php echo $row->credito+$row->efectivo+$row->tarjeta;?></td>
      </tr>
      
      
      
      </tbody>      
            <?php }?>
        
      
      </table>
 

</div>


<div class="modal-footer">
 <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.location();">Cerrar</button>

 
</div>  
  
  
 
  

</div>
</div>
</div>


</div>
</div>
</div>
</div>
</div>
</div>

</div>
</div>
</div>
</div>
</div>			<!-- start: FOOTER -->
	<?php include('include/footer.php');?>
			<!-- end: FOOTER -->
		
			<!-- start: SETTINGS -->
	<?php include('include/setting.php');?>
			
			<!-- end: SETTINGS -->
		
		<!-- start: MAIN JAVASCRIPTS -->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<!-- end: MAIN JAVASCRIPTS -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
		<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
		<script src="vendor/autosize/autosize.min.js"></script>
		<script src="vendor/selectFx/classie.js"></script>
		<script src="vendor/selectFx/selectFx.js"></script>
		<script src="vendor/select2/select2.min.js"></script>
		<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
		<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<!-- start: CLIP-TWO JAVASCRIPTS -->
		<script src="assets/js/main.js"></script>
		<!-- start: JavaScript Event Handlers for this page -->
		<script src="assets/js/form-elements.js"></script>
		
	
		
		<script type="text/javascript">
            new DataTable('#example');

		</script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});
		</script>
		
		<script>
document.getElementById('formulario').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario

    // Realiza una solicitud AJAX para generar el PDF
    $.ajax({
        url: 'cierredeventas.php', // Reemplaza 'tu_script_php.php' con la URL adecuada
        method: 'POST', // Puedes usar POST si es necesario
        data: $(this).serialize(), // Envía los datos del formulario
        success: function() {
            // Abre el PDF en una nueva ventana o pestaña del navegador
            window.open('/home/analiza/Documentos/cierreventas_' . $fecha . '.pdf', '_blank');
        }
    });
});
</script>
		
		
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>
