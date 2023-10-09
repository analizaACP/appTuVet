<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
$fecha_actual=date("d-m-y h:i:s");
if(isset($_POST['id_prov']))
{//if del submit
    // guardar monto
    //$query=("UPDATE extractosproveedores SET abono= abono+$_REQUEST[monto], saldo= saldo-$_REQUEST[monto] WHERE id=$_REQUEST[id_ep]");
	$query=("UPDATE proveedores SET saldo= saldo-$_REQUEST[monto] WHERE id=$_REQUEST[id_prov]");
	$query=pg_query($query);
	$query1=("INSERT INTO extractosproveedores(prov_id, doc_numero, fecha, cargo, abono, saldo, usuario, sucursal)
                VALUES ($_REQUEST[id_prov],'$_REQUEST[doc]',now(),0,$_REQUEST[monto], $_REQUEST[saldo], '$_SESSION[login]', $_SESSION[sucursal])");
    $query1=pg_query($query1);
    
    if(!$query){
        echo "<script>alert('Error al procesar la informacion ".pg_last_error($db)."');</script>";
    }
    pg_close();
    
    if($query){
        echo "<script>alert('Se agrego abono');</script>";
    }

	echo "<script>window.location.href='estado_cuenta_proveedores.php'</script>";

}//if del submit

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Estados De Cuenta Proveedores</title>
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
									<h1 class="mainTitle">Estado De Cuenta Proveedores</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Estado De Cuenta Proveedores</span>
									</li>
								</ol>
							</div>
						</section>
						<!-- end: PAGE TITLE -->
						<!-- start: BASIC EXAMPLE -->
						<div class="container-fluid container-fullw bg-white">
								<div class="row">
									<div class="col-md-12">
										<h5 class="over-title margin-bottom-15">Estado De Cuenta <span class="text-bold">Proveedores</span></h5>
										<table class="table table-hover" id="tabla_etproveedores">
											<thead>
												<tr>
													<th class="center">#</th>
													<th>Proveedor</th>
													<th>Saldo</th>
													<th>Opción</th> 
												</tr>
											</thead>
											<tbody>
												<?php
												$sql=pg_query($db,"
												SELECT p.id,
												p.prov_nombre,
												p.saldo
												FROM proveedores AS p where id<>'2'
												ORDER BY p.prov_nombre");
												$cnt=1;
												while($row = pg_fetch_object($sql)){
													?>

													<tr>
														<td class="center"><?php echo $cnt;?>.</td>
														<td><?php echo $row->prov_nombre;?></td>
														<td><?php echo $row->saldo;?></td>
														<td><?php 
															echo '<button class="btn btn-success btnHistorialPagos" style="margin-left:5px;margin-top:5px;margin-button:5px;" data-toggle="modal" data-target="#modalHistorialPagos" 
																onClick="cargarDatosModalHistorial('.$row->id.')">Historial</button>';
															if($row->saldo > 0){

																echo '<button class="btn btn-warning btnAgregarPago" style="margin-left:5px;margin-top:5px;margin-button:5px;" data-toggle="modal" data-target="#modalAgregarPago" 
																idProveedor="'.$row->id.'" extractoProveedor="'.$row->prov_nombre.'" extractoSaldo="'.$row->saldo.'"
																onClick="cargarDatosModal()">Pago</button>';
															}?>
														</td>
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
						<!--=====================================
						MODAL AGREGAR PAGO
						======================================-->

						<div id="modalAgregarPago" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<!--=====================================
									CABEZA DEL MODAL
									======================================-->
									<div class="modal-header">
										<h4 class="modal-title">Agregar Pago</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
									</div>
									<!--=====================================
									CUERPO DEL MODAL
									======================================-->
									<form id="formulario" role="form" method="post" enctype="multipart/form-data">

										<div class="modal-body">

											<div class="card-body">  
												<div class="form-group">
													<label for="inputData">
														Proveedor
													</label>
													<input id="id_prov" type="hidden" name="id_prov">
													<input id="proveedor" type="text" name="proveedor" class="form-control"  autocomplete="off" readonly>
												</div>  
												<div class="form-group">
													<label for="inputData">
														Saldo
													</label>
													<input id="saldo" type="number" name="saldo" class="form-control"  autocomplete="off" readonly >
												</div>  

												<div class="form-group">
													<label for="inputData">
														N° Comprobante:
													</label>
													<input id="doc" type="number" name="doc" class="form-control"  autocomplete="off" placeholder="Monto de Pago" autofocus required>
												</div>  

												<div class="form-group">
													<label for="inputData">
														Monto
													</label>
													<input id="monto" type="number" name="monto" class="form-control"  autocomplete="off" placeholder="Monto de Pago" autofocus required>
												</div>  		
											</div>
										</div>
									</form>

									<!--=====================================
									PIE DEL MODAL
									======================================-->
									<div class="modal-footer justify-content-between">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										<button type="submit" id="enviar" class="btn btn-primary">Guardar</button>
									</div>
									<?php
									?>
								</div>
							</div>
						</div>
						<!--=====================================
						MODAL VER HISTORIAL
						======================================-->

						<div id="modalHistorialPagos" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<!--=====================================
									CABEZA DEL MODAL
									======================================-->
									<div class="modal-header">
										<h4 class="modal-title">Historial Pagos</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
									</div>
									<!--=====================================
									CUERPO DEL MODAL
									======================================-->
									<form id="formulario" role="form" method="post" enctype="multipart/form-data">

										<div class="modal-body">

											<div class="card-body">  
											<table class="table table-hover" id="tabla_historial">
												<thead>
													<tr>
														<th>Fecha</th>
														<th>Factura</th>
														<th>Cargo</th>
														<th>Saldo</th>
														<th>Abono</th>
														<th>Usuario</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>		
											</div>
										</div>
										<!--=====================================
										PIE DEL MODAL
										======================================-->
										<div class="modal-footer justify-content-between">
											<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										</div>
										<?php
										?>
									</form>
								</div>
							</div>
						</div>
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
				$('#tabla_etproveedores').DataTable({
					responsive: true
				});
				
				$('#tabla_historial').DataTable({
					responsive: true
				});
			});
		</script>
        <script src="js/ecproveedores.js"></script>	
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>