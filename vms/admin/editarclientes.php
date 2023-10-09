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

    
    if($_REQUEST['empresa']=="on"){
        $empresa=1;
    }
    else{
        $empresa=0;
    }
    
    if($_REQUEST['credito']=="on"){
        $credito=1;
    }
    else{
        $credito=0;
    }
    
    $nombre=$_POST['nombre'];
    
    
    
    $query=("update clientes set cliente_nombre='$nombre'
                ,cliente_dir1='$_REQUEST[dir1]',cliente_tel='$_REQUEST[tel]'
                ,cliente_correo='$_REQUEST[correo]',cliente_dir2='$_REQUEST[dir2]'
                ,cliente_giro='$_REQUEST[giro]',esempresasn='$empresa',darcreditosn='$credito' ,usuario_modifica='$_SESSION[login]', 
                fecha_modificacion=to_date('$fecha_actual','dd/mm/yy') where id='$id'");
    $query=pg_query($query);
    
    $_SESSION['msg']="Datos de cliente actualizados !!";
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Editar Clientes</title>
		
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
									<h1 class="mainTitle">Admin | Editar Clientes</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Editar Marcas</span>
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
													<h5 class="panel-title">Editar Clientes</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="dcotorspcl" method="post" >
														<div class="form-group">
															<label for="exampleInputEmail1">
																DUI <strong>00000000-0</strong> Identidad 
																<strong>0000-0000-00000</strong> pasaporte 
																<strong>A000000</strong> NRC <strong>000000-0</strong> RTN <strong>0000-0000-000000</strong>
															</label>

	<?php 

$id=intval($_GET['id']);
	$sql=pg_query($db,"select * from clientes where id=$id;");
	while($row = pg_fetch_object($sql))
{	
    
    //$proveedorid=$row->proveedor_id;
    //echo "el codigo del proveedor es ".$proveedorid;
	?>		<input type="text" name="numdoc" class="form-control" disabled value="<?php echo $row->cliente_nodoc;?>" >
	
														</div>
												
										
	<div class="form-group">
	<label for="exampleInputEmail1">Tipo Documento</label>
	<input type="text" name="tipodoc" disabled class="form-control" value="<?php echo $row->cliente_tipodoc;?>" >
															
	</div>
	
											
	<div class="form-group">
	<label for="exampleInputEmail1">Nombre del Cliente</label>
	<input type="text" name="nombre" class="form-control" value="<?php echo $row->cliente_nombre;?>" >
															
	</div>															
	
							<div class="form-group">
							<?php if($row->esempresasn==1){;?>
	
							<label>
							<input type="checkbox" id="empresa" name="empresa" checked> Es Empresa?</label>
							<?php }else {;?>
							<label>
							<input type="checkbox" id="empresa" name="empresa"> Es Empresa?</label>
							
							<?php }?>
							
							<?php if($row->darcreditosn==1){;?>
	
							<label>
							<input type="checkbox" id="credito" name="credito" checked> Aplica Credito?</label>
							<?php }else {;?>
							<label>
							<input type="checkbox" id="credito" name="credito"> Aplica Credito?</label>
							
							<?php }?>
							
							
	</div>
	
	<div class="form-group">
	<label for="exampleInputEmail1">Giro</label>
	<input type="text" name="giro" class="form-control" value="<?php echo $row->cliente_giro;?>" >
															
	</div>
	
	<div class="form-group">
	<label for="exampleInputEmail1">Direccion Linea 1</label>
	<input type="text" name="dir1" class="form-control" value="<?php echo $row->cliente_dir1;?>" >
															
	</div>
	
	<div class="form-group">
	<label for="exampleInputEmail1">Direccion Linea 2</label>
	<input type="text" name="dir2" class="form-control" value="<?php echo $row->cliente_dir2;?>" >
															
	</div>
	
	<div class="form-group">
	<label for="exampleInputEmail1">Telefono</label>
	<input type="tel" name="tel" class="form-control" value="<?php echo $row->cliente_tel;?>" >
															
	</div>
	
	
	<div class="form-group">
	<label for="exampleInputEmail1">Correo</label>
	<input type="text" name="correo" class="form-control" value="<?php echo $row->cliente_correo;?>" >
															
	</div>
	
	
	<div class="form-group">
	
	
	
	
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