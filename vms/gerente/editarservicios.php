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
    
    $determinarporcimpuesto=("select * from impuestos where imp_id='$_REQUEST[impuestos]'");
    $determinarporcimpuesto=pg_query($determinarporcimpuesto);
    if(!$determinarporcimpuesto){
        echo "<script>alert('no se extrajo el porc de impuestos');</script>";
    }
    
    while($row = pg_fetch_object($determinarporcimpuesto)){
        $porcimpuesto=$row->imp_porc;
    }
    
    
    
    
    $sql01=pg_query($db,"select * from productos where id='$id'");
    while($row = pg_fetch_object($sql01))
    {
        $barra=$row->prod_codbarra;
    }
    
    $nombre=$_POST['nombre'];
    
    $marca=$_POST['marca'];
    $categoria=$_POST['categoria'];
    
    $descuento=(floatval($_REQUEST['descuento']))/100;
    
    $impuestos=$_REQUEST['impuestos'];
    $costoprom=(floatval($_REQUEST['costo']));
    $preciopub=(floatval($_REQUEST['precio']));
        
        
    if($_REQUEST['activo']=="on"){
        $activo=1;
    }
    else{
        $activo=0;
    }
    
    //$contacto=$_POST['contacto'];
    
    $query=("update productos set prod_nombre='$nombre', id_marca='$marca',id_categorias='$categoria', 
                prod_porcdescuento='$descuento',prod_costopromedio='$costoprom',prod_preciopublico='$preciopub',
                activosn='$activo',modificada_por='$_SESSION[login]',
                porcentaje_impuesto='$porcimpuesto',codigo_impuesto='$impuestos',
                fecha_modificacion=to_date('$fecha_actual','dd/mm/yy') where id='$id'");
    $query=pg_query($query);
    //echo "reorden".$reorden;
    
    if(!$query){
    echo "<script>alert('Datos NO actualizados');</script>";
    }
    
    //echo "<script>alert('Datos actualizados');</script>";
    //$_SESSION['msg']="Datos de producto actualizados !!";
    
    echo "<script>window.location.href='servicios.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Editar Servicios</title>
		
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
									<h1 class="mainTitle">Admin | Editar Servicios</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Editar Productos</span>
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
													<h5 class="panel-title">Editar Servicios</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="dcotorspcl" method="post" enctype="multipart/form-data">
														<div class="form-group">
															<label for="exampleInputEmail1">
																Codigo
															</label>

	<?php 

$id=intval($_GET['id']);
//echo $id;
	$sql=pg_query($db,"select * from productos where id='$id'");
	while($row = pg_fetch_object($sql))
{	//comienza 1
    $marcaid=$row->id_marca;
    $catid=$row->id_categorias;
    $barra=$row->prod_codbarra;
    $impuesto=$row->codigo_impuesto;
    //echo "la marca es ".$marcaid
    
	?>		
	<input type="text" name="codbarra" class="form-control" disabled value="<?php echo $row->prod_codbarra;?>" >
	
														</div>
												
										
	<div class="form-group">
	<label for="exampleInputEmail1">Nombre</label>
	<input type="text" name="nombre" autocomplete="off" class="form-control" value="<?php echo $row->prod_nombre;?>" >
	
	
	
															
	</div>
	
	<div class="form-group">
	
	
	
	<?php } //cierre 1?>
															
	</div>
	
										
	<div class="form-group">
	
	<label for="inputData">Marca</label><br>										
															
															
															<select name="marca" id="marca" class="form-control">
															<?php 
															$query2="SELECT id, marca_nombre FROM marcas order by marca_nombre ";
															$consulta=pg_query($db,$query2);
															
															while($obj1=pg_fetch_object($consulta)){   ?>
																																												
    														<option class="form-control" value="<?php echo $obj1->id ?> "<?php if($marcaid==$obj1->id){echo "selected";}?> ><?php echo $obj1->marca_nombre;?></option>
   															
   															<?php }?>
   															</select>	
	
	
	
	</div>		
	
															
										
	<div class="form-group">
	
	<label for="inputData">Categoria</label><br>										
															
															
															<select name="categoria" id="categoria" class="form-control">
															<?php 
															$query2="SELECT id, cat_nombre FROM categorias order by cat_nombre ";
															$consulta=pg_query($db,$query2);
															
															while($obj1=pg_fetch_object($consulta)){   ?>
																																												
    														<option class="form-control" value="<?php echo $obj1->id ?> "<?php if($catid==$obj1->id){echo "selected";}?> ><?php echo $obj1->cat_nombre;?></option>
   															
   															<?php }?>
   															</select>	
	
	
	
	</div>
	
	<div class="form-group">
							<label for="inputData">
																Impuestos
															</label><br>
															<select name="impuestos" id="impuestos">
															<?php 
															$query2="SELECT imp_id,imp_nombre FROM impuestos order by imp_nombre";
															$consulta=pg_query($db,$query2);
															while($obj1=pg_fetch_object($consulta)){   ?>
															
															
    														<option value="<?php echo $obj1->imp_id ?>"<?php if($obj1->imp_id==$obj1){echo "selected";}?>><?php echo $obj1->imp_nombre;?></option>
   															
   															<?php }?>
   															</select>
							<label><input type="checkbox" hidden="true" id="sumainv" name="sumainv" checked="checked"></label>
							</div>
	
	
	<?php 

//$id=intval($_GET['id']);
//echo $id;
	$sql=pg_query($db,"select * from productos where id='$id'");
	while($row = pg_fetch_object($sql))
{//comienza 2
    
    ?>	
	
	
	<div class="form-group">
							<label for="inputData">Costo
							<input type="number" name="costo" class="form-control" min="0" value="<?php echo (string)$row->prod_costopromedio;?>"></label>
							
							
							<label for="inputData">Precio al Publico
							<input type="number" name="precio" class="form-control" min="0" value="<?php echo (string)$row->prod_preciopublico;?>"></label>
	
	
	<?php } //cierre 2?>	
	
	
			
	</div>
	
	
	<?php 

//$id=intval($_GET['id']);
//echo $id;
	$sql=pg_query($db,"select * from productos where id='$id'");
	while($row = pg_fetch_object($sql))
{//comienza 4
    
    ?>
	
	<div class="form-group">
	
	
										
							<label for="inputData">Porcentaje de Descuento
							<input type="number" name="descuento" class="form-control" min="0" value="<?php echo (string)$row->prod_porcdescuento*100;?>" step="0.001"></label>
							
							<?php if($row->activosn==1){;?>
	
							<label>
							<input type="checkbox" id="activo" name="activo" checked="checked"> Activar/Desactivar Producto</label>
							<?php }else {;?>
							<label>
							<input type="checkbox" id="activo" name="activo"> Activar/Desactivar Producto</label>
							
							<?php }?>
	
	
	
	</div>
	<div class="form-group">
	
	
	<?php } //cierre 4?>
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