<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
$fecha_actual=date("d-m-y h:i:s");
if(isset($_POST['submit']))
{
    
    //echo $_REQUEST[fullname];
    $query=("INSERT INTO sucursales(sucursal_nombre,sucursal_direccion,sucursal_tel,sucursal_cel,fecha_creacion,creada_por)
      VALUES ('$_REQUEST[sucursal]','$_REQUEST[direccion]','$_REQUEST[telefono]','$_REQUEST[celular]',to_date('$fecha_actual','dd/mm/yy'),'$_SESSION[login]')");
    $query=pg_query($query);
    if(!$query){
        
        echo "<script>alert('Error al procesar la informacion posibles datos duplicados');</script>";
    }
    
    
   
   
    
    
    if($query){
        
        echo "<script>alert('Su informacion ha sido enviada');</script>";
}

//agregar productos a sucursal recien creada
//seleccionar el maxId de sucursales
$query=("SELECT MAX(id) from sucursales");
$query=pg_query($query);
$maxID = pg_fetch_result($query, 0, 0);


//guardar todos los productos en un array
$productos = "SELECT * FROM productos"; // Reemplaza "nombre_de_tabla" con el nombre de tu tabla
$result = pg_query($db, $productos);

if ($result) {
    // Iterar sobre los resultados de la consulta utilizando pg_fetch_assoc
    while ($fila = pg_fetch_assoc($result)) {
        // Aquí puedes acceder a los campos de cada registro utilizando el array asociativo $fila
        // Por ejemplo:
        $codigo = $fila['prod_codbarra'];
        $existencias=("INSERT INTO existencias(id_sucursal,codbarra,existencia,anaquel)
        VALUES ('$maxID','$codigo',0,'no asignado')");
        $query=pg_query($existencias);
        
        $reorden=("INSERT INTO puntosdereorden(id_sucursal,codbarra,ptoreorden,fecha_creacion,creadopor)
        VALUES ('$maxID','$codigo',0,to_date('$fecha_actual','dd/mm/yy'),'$_SESSION[login]')");
        $query=pg_query($reorden);
        
        
        
    }
} else {
    echo "Error al realizar la consulta.";
}




//fin de agregar       
    echo "<script>window.location.href='sucursales.php'</script>";
}


?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Agregar Nueva Sucursal</title>
		<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
		<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
		<link href="vendor/DataTables/css/jquery.dataTables.min.css" rel="stylesheet" media="screen">
		
		
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
									<h1 class="mainTitle">Agregar Nueva Sucursal</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Agregar Nueva Sucursal</span>
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
													<h5 class="panel-title">Agregar Nueva Sucursal</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="proveedores" method="post"  autocomplete="off">
														<div class="form-group">
															<label for="inputData">
																Nombre Sucursal
															</label>
							<input type="text" name="sucursal" class="form-control"  placeholder="Ingrese nombre de la Sucursal" required>
														</div>
												<div class="form-group">
															<label for="inputData">
																Direccion
															</label>
							<input type="text" name="direccion" class="form-control"  placeholder="Direccion de la Sucursal" required>
														</div>
														<div class="form-group">
															<label for="inputData">
																Telefono Fijo
															</label>
							<input type="tel" name="telefono" class="form-control"  autocomplete="off" placeholder="(codigo pais) numero" required>
														</div>
														
														<div class="form-group">
															<label for="inputData">
																Telefono Celular
															</label>
							<input type="tel" name="celular" class="form-control"  autocomplete="off" placeholder="(codigo pais) numero" required>
														</div>
														
														
														
														<button type="submit" name="submit" class="btn btn-o btn-primary">
															Submit
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

									<div class="row">
								<div class="col-md-12">
									<h5 class="over-title margin-bottom-15">Administrar <span class="text-bold">Sucursales</span></h5>
									
									<table id="example" class="display" style="width:100%">
										<thead>
											<tr>
												<th class="center">#</th>
												
												<th>Nombre</th>
												<th>Codigo Sucursal</th>
												<th>Direccion</th>
												<th>Telefono</th>
												<th>Celular</th>
												<th>Accion</th>
																				
												
											</tr>
										</thead>
										<tbody>
<?php
$sql=pg_query($db,"select * from sucursales order by id");
$cnt=1;
while($row = pg_fetch_object($sql))
{
?>

											<tr>
												<td class="center"><?php echo $cnt;?>.</td>
												<td class="hidden-xs"><?php echo $row->sucursal_nombre;?></td>
												<td><?php echo $row->id;?></td>
												<td><?php echo $row->sucursal_direccion;?></td>
												<td><?php echo $row->sucursal_tel;?>
												<td><?php echo $row->sucursal_cel;?>
												
												</td>
												
												<td >
												<div class="visible-md visible-lg hidden-sm hidden-xs">
							<a href="editarsucursales.php?id=<?php echo $row->id;?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
													
	
												</div>
												<div class="visible-xs visible-sm hidden-md hidden-lg">
													<div class="btn-group" dropdown is-open="status.isopen">
														<button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" dropdown-toggle>
															<i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
														</button>
														<ul class="dropdown-menu pull-right dropdown-light" role="menu">
															<li>
																<a href="#">
																	Edit
																</a>
															</li>
															<li>
																<a href="#">
																	Share
																</a>
															</li>
															<li>
																<a href="#">
																	Remove
																</a>
															</li>
														</ul>
													</div>
												</div></td>
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
		<script type="text/javascript">
            new DataTable('#example');

		</script>
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