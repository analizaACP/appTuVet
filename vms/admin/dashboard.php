<?php
session_start();
error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin  | Dashboard</title>
		
		<!--poner css aqui-->
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
		
		<!--fin poner css aqui-->
	
	</head>
	<body>
		<div id="app">		
<?php include('include/sidebar.php');?>
			<div class="app-content">
				
						<?php include('include/header.php');?>
						
				<!-- end: TOP NAVBAR -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<!-- start: PAGE TITLE -->
						<section id="page-title">
							<div class="row">
								<div class="col-sm-8">
									<h1 class="mainTitle">Admin | Dashboard</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Dashboard</span>
									</li>
								</ol>
							</div>
						</section>
						<!-- end: PAGE TITLE -->
						<!-- start: BASIC EXAMPLE -->
							<div class="container-fluid container-fullw bg-white">
							
							<div class="row">
							
							
								<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-money" aria-hidden="true"></i>

 </span>
											<h3 class="StepTitle">Ventas Totales</h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
    <a href="#">
        <?php
        //consulta para obtener el total de ventas
        $ventastotales = pg_query($db, "SELECT SUM(total_linea) AS total FROM hfacturas WHERE pagada_sn=1 AND anulada=0");
        $row = pg_fetch_object($ventastotales);
        ?>
         <?php echo "$ " . $row->total; ?>
    </a>
</p>

										</div>
									</div>
								</div>
								
								
								
								<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-tachometer" aria-hidden="true"></i>
 </span>
											
											
											<h3 class="StepTitle">Ventas de Hoy</h3>
										
											<p class="cl-effect-1" style="font-size: 15px;"">
												<a href="#">
<?php 
$fechaConsulta = date('Y-m-d');
$ventasdehoy = pg_query($db, "SELECT SUM(total_linea) AS total FROM hfacturas WHERE pagada_sn=1 AND anulada=0 and fecha_cobro='$fechaConsulta'");
$row = pg_fetch_object($ventasdehoy);
//echo $num_rows2;


?>
											<?php echo "$ " . $row->total; ?>		
												</a>
												
											</p>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-usd" aria-hidden="true"></i>
 </span>
											<h3 class="StepTitle">Ventas del Mes </h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
												<a href="#">
													 
                                                <?php 
                                                $primerDiaDelMes = date('Y-m-01');
                                                $ultimoDiaDelMes = date('Y-m-t');
                                                
                                                $ventasDelMes = pg_query($db, "SELECT SUM(total_linea) AS total FROM hfacturas WHERE pagada_sn=1 AND anulada=0 AND fecha_cobro BETWEEN '$primerDiaDelMes' AND '$ultimoDiaDelMes'");
                                                
                                                $row = pg_fetch_object($ventasDelMes);
                                                {
                                                ?>
											<?php echo "$ ".$row->total;  } ?>	
												</a>
												
											</p>
										</div>
									</div>
								</div>

									<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i><i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
 </span>
											<h3 class="StepTitle">Ventas Productos</h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
												<a href="#">
<?php 
$ventasproductos = pg_query($db, "select sum(total_linea) as ventasproductos from dfacturas d join productos p on cod_barra=prod_codbarra where prod_esinventariosn=1 
                                    and d.validado_sn=1;");
$row = pg_fetch_object($ventasproductos);

?>
											<?php echo "$ ".$row->ventasproductos;?>  
		
											</a>
											</p>
										</div>
									</div>
								</div>
								
								

<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-ambulance" aria-hidden="true"></i>
</i></span>
											<h3 class="StepTitle">Ventas Servicios</h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
												<a href="#">
<?php 
$ventasservicios = pg_query($db, "select sum(total_linea) as ventasservicios from dfacturas d join productos p on cod_barra=prod_codbarra where prod_esinventariosn=0 
                                    and d.validado_sn=1;");
$row = pg_fetch_object($ventasservicios);

?>
											<?php echo "$ ".$row->ventasservicios;?>  
 	
											</a>
											</p>
										</div>
									</div>
								</div>



			<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-money" aria-hidden="true"></i> </span>
											<h3 class="StepTitle">Margen $</h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
												
													<a href="#">
<?php 
$margen = pg_query($db, "select sum(total_linea)-sum(monto_costo) as margen from dfacturas where validado_sn=1 ;");
$row = pg_fetch_object($margen);

?>
											 <?php echo "$ ".$row->margen;?> 
												</a>
												
											</p>
										</div>
									</div>
								</div>

<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i><i class="fa fa-building-o" aria-hidden="true"></i> </span>
											<h3 class="StepTitle">Monto Inventario</h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
												
													<a href="#">
<?php 
$montoinventario=pg_query($db,"WITH CostoExistencias AS (
        SELECT p.prod_codbarra, SUM(e.existencia * p.prod_costopromedio) AS total_costo
        FROM existencias e
        JOIN productos p ON e.codbarra = p.prod_codbarra
        GROUP BY p.prod_codbarra
    )
    SELECT SUM(total_costo) AS costo_total
    FROM CostoExistencias");
$row = pg_fetch_object($montoinventario);

?>
											 <?php echo "$ ".$row->costo_total;?> 
												</a>
												
											</p>
										</div>
									</div>
								</div>
								
								<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-bell" aria-hidden="true"></i>
 </span>
											<h3 class="StepTitle">Clientes Atendidos</h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
												
													<a href="#">
<?php 
$query5=("SELECT FROM hfacturas where pagada_sn=1");
$query5=pg_query($query5);
$num_rows5 = pg_num_rows($query5);
?>	
											 <?php echo htmlentities($num_rows5);?>	
												</a>
												
											</p>
										</div>
									</div>
								</div>
								
								
								<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-shopping-cart" aria-hidden="true"></i>
 </span>
											<h3 class="StepTitle">Compras Totales</h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
												
													<a href="#">
<?php 
$comprastotales=pg_query($db,"select sum(compra_total) as totalcompras from comprasproductos");
$row = pg_fetch_object($comprastotales);
?>	
											 <?php echo "$ ".$row->totalcompras;?> 
												</a>
												
											</p>
										</div>
									</div>
								</div>
								
								<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-credit-card" aria-hidden="true"></i>

 </span>
											<h3 class="StepTitle">Compras Hoy</h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
												
													<a href="#">
<?php 
$comprasdehoy = pg_query($db, "select sum(compra_total) as comprashoy from comprasproductos where compra_fecha='$fechaConsulta'");
$row = pg_fetch_object($comprasdehoy);
?>	
											 <?php echo "$ ".$row->comprashoy;?>	
												</a>
												
											</p>
										</div>
									</div>
								</div>
								
								<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-money" aria-hidden="true"></i>
 </span>
											<h3 class="StepTitle">Saldos Proveedores</h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
												
													<a href="#">
<?php 
$query5=("SELECT FROM hfacturas where pagada_sn=1");
$query5=pg_query($query5);
$num_rows5 = pg_num_rows($query5);
?>	
											 <?php echo htmlentities($num_rows5);?>	
												</a>
												
											</p>
										</div>
									</div>
								</div>
								
								<div class="col-sm-3">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-money" aria-hidden="true"></i>
 </span>
											<h3 class="StepTitle">Saldos Clientes</h3>
											
											<p class="links cl-effect-1" style="font-size: 15px;">
												
													<a href="#">
<?php 
$query5=("SELECT FROM hfacturas where pagada_sn=1");
$query5=pg_query($query5);
$num_rows5 = pg_num_rows($query5);
?>	
											 <?php echo htmlentities($num_rows5);?>	
												</a>
												
											</p>
										</div>
									</div>
								</div>
								
								
								

							</div>
						</div>
			
					<!-- end: SELECT BOXES -->
						
					</div>
				</div>
			</div>
			<!-- start: FOOTER -->
	<?php include('include/footer.php');?>
			<!-- end: FOOTER -->
		
			<!-- start: SETTINGS -->
	<?php include('include/setting.php');?>
			
			<!-- end: SETTINGS -->
		</div>
		<!-- start: MAIN  -->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<!-- end: MAIN  -->
		<!-- start: THIS PAGE ONLY -->
		<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
		<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
		<script src="vendor/autosize/autosize.min.js"></script>
		<script src="vendor/selectFx/classie.js"></script>
		<script src="vendor/selectFx/selectFx.js"></script>
		<script src="vendor/select2/select2.min.js"></script>
		<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
		<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
		<!-- end: THIS PAGE ONLY -->
		<!-- start: CLIP-TWO JAVASCRIPTS -->
		<script src="assets/js/main.js"></script>
		<!-- start: JavaScript Event Handlers for this page -->
		<script src="assets/js/form-elements.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});
		</script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>