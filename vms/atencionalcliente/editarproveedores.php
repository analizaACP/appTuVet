<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
$id=intval($_GET['id']);// get value
$fecha_actual=date("d-m-y h:i:s");
if(isset($_POST['submit']))
{
    $nombre=$_POST['nombreproveedor'];
    $cheque=$_POST['cheque'];
    $direccion1=$_POST['direccion1'];
    $direccion2=$_POST['direccion2'];
    $telefono=$_POST['telefono'];
    $correo=$_POST['correo'];
    $contacto=$_POST['contacto'];
    
    $query=("update proveedores set prov_nombrecheque='$cheque', prov_ldireccion1='$direccion1',prov_ldireccion2='$direccion2', prov_telefono='$telefono', prov_correo='$correo', prov_nombrecontacto='$contacto', modificada_por='$_SESSION[login]', fecha_modificacion=to_date('$fecha_actual','dd/mm/yy') where id='$id'");
    $query=pg_query($query);
    
    $_SESSION['msg']="Datos de proveedor actualizados !!";
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Editar Proveedores</title>
		
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
					
				<!-- end: TOP NAVBAR -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<!-- start: PAGE TITLE -->
						<section id="page-title">
							<div class="row">
								<div class="col-sm-8">
									<h1 class="mainTitle">Admin | Editar Proveedores</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Editar Proveedores</span>
									</li>
								</ol>
							</div>
						</section>
						<!-- end: PAGE TITLE -->
						<!-- start: BASIC EXAMPLE -->
						<div class="container-fluid container-fullw bg-white">
							<div class="row">
								<div class="col-md-12">
									
									<div class="row margin-top-30">
										<div class="col-lg-6 col-md-12">
											<div class="panel panel-white">
												<div class="panel-heading">
													<h5 class="panel-title">Editar Proveedores</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="dcotorspcl" method="post" >
														<div class="form-group">
															<label for="exampleInputEmail1">
																Nombre Proveedor
															</label>

	<?php 

$id=intval($_GET['id']);
//echo $id;
	$sql=pg_query($db,"select * from proveedores where id='$id'");
	while($row = pg_fetch_object($sql))
{														
	?>		<input type="text" name="nombreproveedor" class="form-control" disabled value="<?php echo $row->prov_nombre;?>" >
	
														</div>
												
										
	<div class="form-group">
	<label for="exampleInputEmail1">Emitir Cheque</label>
	<input type="text" name="cheque" autocomplete="off" class="form-control" value="<?php echo $row->prov_nombrecheque;?>" >
															
	</div>
										
	<div class="form-group">
	<label for="exampleInputEmail1">Direccion linea 1</label>
	<input type="text" name="direccion1" autocomplete="off" class="form-control" value="<?php echo $row->prov_ldireccion1;?>" >
															
	</div>									
										
	<div class="form-group">
	<label for="exampleInputEmail1">Direccion linea 2</label>
	<input type="text" name="direccion2" autocomplete="off" class="form-control" value="<?php echo $row->prov_ldireccion2;?>" >
															
	</div>									
										
	<div class="form-group">
	<label for="exampleInputEmail1">Telefono</label>
	<input type="text" name="telefono" autocomplete="off" class="form-control" value="<?php echo $row->prov_telefono;?>" placeholder="(codigo pais) numero">
															
	</div>									
			
	<div class="form-group">
	<label for="exampleInputEmail1">Correo</label>
	<input type="email" name="correo" class="form-control" value="<?php echo $row->prov_correo;?>" placeholder="uncorreo@correo.com">
															
	</div>
	
										
	<div class="form-group">
	<label for="exampleInputEmail1">Nombre Contacto</label>
	<input type="text" name="contacto" class="form-control" value="<?php echo $row->prov_nombrecontacto;?>" >
															<?php } ?>
	</div>													
														
														
														<button type="submit" name="submit" class="btn btn-o btn-primary">
															Actualizar
														</button>
													</form>
												</div>
											</div>
										</div>
											
											</div>
										</div>
									<div class="col-lg-12 col-md-12">
											<div class="panel panel-white">
												
												
											</div>
										</div>
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
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
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