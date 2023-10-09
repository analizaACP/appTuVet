<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
$fecha_actual=date("d-m-y h:i:s");
if(isset($_POST['submit']))
{//if del submit
    
}//if del submit


if(isset($_GET['del']))
{
  $query2=("delete from productos where id = '".$_REQUEST['id']."'");
  $query2=pg_query($query2);
  if(!$query2){
      echo "<script>alert('Error hay registros en otras tablas que dependen de este registro');</script>";
  }
  $_SESSION['msg']="datos eliminados !!";
  echo "<script>window.location.href='productos.php'</script>";
    
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Administrar Compras</title>
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
		<link href="vendor/DataTables/css/jquery.dataTables.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/plugins.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
		
		
		<script>

            

        </script>
		
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
									<h1 class="mainTitle">Administrar Compras</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Administrar Compras</span>
									</li>
								</ol>
							</div>
						</section>
						<!-- end: PAGE TITLE -->
						<!-- start: BASIC EXAMPLE -->
						<div class="container-fluid container-fullw bg-white">
								<div class="row">
									<div class="col-md-12">
										<a id="agregar-compra" type="buttom" name="agregar-compra" href="ingresarcompra.php" class="btn btn-o btn-primary margin-bottom-15">
											Agregar Compra
										</a>
										<h5 class="over-title margin-bottom-15">Administrar <span class="text-bold">Compras</span></h5>
										<table class="table table-hover" id="tabla_compras">
											<thead>
												<tr>
													<th class="center">#</th>
													<th>Factura</th>
													<th>Proveedor</th>
													<th>Condición</th>
													<th>Subtotal</th>
													<th>Monto Iva</th>
													<th>Monto Retención</th>
													<th>Total</th>
													<th>Fecha</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$sql=pg_query($db,"
												select cp.id,
												cp.proveedor_id,
												p.prov_nombre,
												cp.compra_condicion, 
												cp.compra_fecha, 
												cp.compra_nodoc, 
												cp.compra_subtotal, 
												cp.compra_montoiva,
												cp.compra_montoretencion,
												cp.compra_total 
												from comprasproductos as cp
												join proveedores as p on cp.proveedor_id=p.id
												ORDER BY cp.compra_fecha");
												$cnt=1;
												while($row = pg_fetch_object($sql)){
													?>

													<tr>
														<td class="center"><?php echo $cnt;?>.</td>
														<td><?php echo $row->compra_nodoc;?></td>
														<td><?php echo $row->prov_nombre;?></td>
														<td><?php echo $row->compra_condicion;?></td>
														<td><?php echo $row->compra_subtotal;?></td>
														<td><?php echo $row->compra_montoiva;?></td>
														<td><?php echo $row->compra_montoretencion;?></td>
														<td><?php echo $row->compra_total;?></td>
														<td><?php echo $row->compra_fecha;?></td>
													</tr>
													<?php 
													$cnt=$cnt+1;
												}?>
											</tbody>
										</table>
									</div>
								</div>
						</div>
						</div>
						<!-- end: BASIC EXAMPLE -->
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
		<script src="vendor/DataTables/jquery.dataTables.min.js"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<!-- start: CLIP-TWO JAVASCRIPTS -->
		<script src="assets/js/main.js"></script>
		<!-- start: JavaScript Event Handlers for this page -->
		<script src="assets/js/form-elements.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				Main.init();
				FormElements.init();
				$('#tabla_compras').DataTable({
					responsive: true
				});
			});
		</script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>