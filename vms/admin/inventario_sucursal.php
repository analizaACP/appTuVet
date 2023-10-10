<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
include_once('include/mysqlcon.php');
check_login();








?>
<!DOCTYPE html>	
<html lang="en">
	<head>
		<title>Admin | Inventarios por Sucursal</title>
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
		
		
		<script>

            

        </script>
		
	</head>
	<body>
	
	<script src="js/funciones.js"></script>
	
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
									<h1 class="mainTitle">Inventarios</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Agregar Productos</span>
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
													<h5 class="panel-title">Inventarios</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													
													
													<form role="form" name="marcas" method="post" action="inventario_excel.php" >
														<div class="form-group">
															<label for="inputData">
																Sucursal
															</label>
							<select name="sucursal" id="sucursal" class="form-control" >
															<?php 
															$query2="SELECT id, sucursal_nombre FROM sucursales order by sucursal_nombre";
															$consulta=pg_query($db,$query2);
															while($obj1=pg_fetch_object($consulta)){   ?>
															
															
    														<option value="<?php echo $obj1->id ?>"<?php if($obj1->prov_nombre=='Seleccione proveedor'){echo "selected";}?>><?php echo $obj1->sucursal_nombre;?></option>
   															
   															<?php }?>
   															</select>
														</div>
																												
														
														<button id="submit" type="submit" name="submit" class="btn btn-o btn-primary">
															Hoja de Calculo
														</button>
													</form>
													
													
	<!-- funcion para prevenir que al presionar la tecla enter se envie el formulario -->												
	<script src="js/funciones.js"></script>												
													
													
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
									<h5 class="over-title margin-bottom-15">Administrar <span class="text-bold">Inventarios</span></h5>
									
									<table id="example" class="display" style="width:100%">
										<thead>	
											<tr>	
												<th class="center">#</th>
												<th>Sucursal</th>
												<th>Codigo</th>
												<th>Nombre</th>
												<th>Marca</th>
												<th>Categoria</th>
												<th>Proveedor</th>
												<th>Costo</th>
												<th>Precio de Venta</th>
												<th>Existencia</th>
												
												<th>Descuento</th>
												<th>Foto</th>											
												
											</tr>
										</thead>
										<tbody>
<?php
$sql=pg_query($db,"select s.sucursal_nombre,p.prod_codbarra,p.prod_nombre,m.marca_nombre,c.cat_nombre, 
v.prov_nombre, p.prod_costopromedio, p.prod_preciopublico,e.existencia, 
p.prod_porcdescuento*100 as descuento
from productos p
join marcas m
on p.id_marca=m.id
join existencias e
on p.prod_codbarra=e.codbarra
join sucursales s
on e.id_sucursal=s.id
join categorias c
on p.id_categorias=c.id
join proveedores v
on m.proveedor_id=v.id
where p.prod_esinventariosn=1
order by sucursal_nombre
;");
$cnt=1;
while($row = pg_fetch_object($sql))
{
?>

											<tr>
												<td class="center"><?php echo $row->id;?>.</td>
												<td><?php echo $row->sucursal_nombre;?></td>
												<td><?php echo $row->prod_codbarra;?></td>
												<td><?php echo $row->prod_nombre;?></td>
												<td><?php echo $row->marca_nombre;?></td>
												<td><?php echo $row->cat_nombre;?></td>
												<td><?php echo $row->prov_nombre;?></td>
												<td><?php echo $row->prod_costopromedio;?></td>
												<td><?php echo $row->prod_preciopublico;?></td>
												<td><?php echo $row->existencia;?></td>
												
												
												<td><?php echo $row->descuento;?>%</td>
												
												
												<td>
												
												<div class="visible-md visible-lg hidden-sm hidden-xs">
												
							
	                  <!--   <a href="inventario_sucursal.php?id=<?php echo $row->prod_codbarra;?>&mostrar " class="btn btn-transparent btn-xs" 
												tooltip-placement="top" tooltip="Pict"><i class="fa fa-file-image-o" aria-hidden="true"></i></a> -->
	                   
	                   
	                   


	                   									
	                   
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
												</div>
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
						</div>
						<!-- end: BASIC EXAMPLE -->
						<!-- end: SELECT BOXES -->
						
					</div>
				</div>
			
			<!-- start: FOOTER -->
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
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});
		</script>
		
		
		<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // Este código se ejecutará cuando el DOM esté completamente cargado
        new DataTable('#example');
    });
</script>

		
		
		
			<script>
    $(document).ready(function() {
        <?php if(isset($_GET['mostrar'])) : ?>
            $('#kardexModal').modal('show');
        <?php endif; ?>
    });
</script>


<div class="modal fade" id="kardexModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	

  <div class="modal-dialog modal-dialog-scrollable" role="document">
     <div class="modal-content">
      <div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Imagen del producto</h5>         
                                                    
      </div>
   <div class="modal-body">
<?php
/*
echo $_REQUEST['id'];

// Establecer una conexión a la base de datos MySQL
$servername = "localhost";
$username = "adolfo";
$password = "ofloda01";
$database = "foto_productos";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener la foto
$id = $conn->real_escape_string($_REQUEST['id']);
$mostrarFoto = "SELECT imagen FROM imagenes WHERE nombre = '".$_REQUEST['id']."'";

$result = $conn->query($mostrarFoto);

while ($row = $result->fetch_assoc()) {
    ?>
    <div style="text-align: center;">
        <img src="data:image/jpg;base64,<?php echo base64_encode($row['imagen']); ?>" width="400" height="400" class="center">
    </div>
    <?php
}

// Cierra la conexión
$conn->close();
*/
?>



</div>


<div class="modal-footer">
 <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.location();">Cerrar</button>

 
</div>  
  

</div>
</div>
</div>

		
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>
