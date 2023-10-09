<?php
session_start();
error_reporting(0);
//include_once('include/completar.php');
include_once('include/config.php');
include('include/checklogin.php');

check_login();
$fecha_actual=date("d-m-y h:i:s");
if(isset($_POST['submit']))
{
    
    //echo $_REQUEST[fullname];
    $query=("INSERT INTO marcas (marca_nombre,marca_paisorigen,proveedor_id,creadapor,fecha_creacion,activosn,sucursal_crea)
      VALUES ('$_REQUEST[nuevamarca]','$_REQUEST[pais]','$_REQUEST[proveedor]','$_SESSION[login]',to_date('$fecha_actual','dd/mm/yy'),1,'$_SESSION[sucursal]' )");
    $query=pg_query($query);
    if(!$query){
        echo "<script>alert('Error al procesar la informacion posibles datos duplicados');</script>";
    }
    pg_close();
    if($query){
        echo "<script>alert('Su informacion ha sido enviada');</script>";
    }
    echo "<script>window.location.href='nuevafactura.php'</script>";
}


?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Facturacion</title>
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
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		<script>
$(document).ready(function() {
    $('#key').on('keyup', function() {
        var key = $(this).val();		
        var dataString = 'key='+key;
	$.ajax({
            type: "POST",
            url: "include/completar.php",
            data: dataString,
            success: function(data) {
                //Escribimos las sugerencias que nos manda la consulta
                $('#suggestions').fadeIn(1000).html(data);
            }
        });
    });

    //Al hacer click en algua de las sugerencias
    $(document).on('click', '.suggest-element', function(){
        //Obtenemos el contenido del atributo 'data' de la sugerencia pulsada
        var suggestionData = $(this).attr('data');
        //Editamos el valor del input con data de la sugerencia pulsada
        $('#key').val(suggestionData);
        //Hacemos desaparecer el resto de sugerencias
        $('#suggestions').fadeOut(1000);
        
        return false;
    });
}); 
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
						
						<!-- end: PAGE TITLE -->
						<!-- start: BASIC EXAMPLE -->
						<div class="container-fluid container-fullw bg-white">
							<div class="row">
								<div class="col-md-12">
									
									<div class="row margin-top-30">
										<div class="col-lg-6 col-md-12">
											<div class="panel panel-white">
												<div class="panel-heading">
													<h5 class="panel-title">Nueva Factura</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="marcas" method="post" >
														<div class="form-group">
															<label for="inputData">
																Cliente
															</label>
							<input type="text" name="key" id="key" class="form-control"  
							autocomplete="off" placeholder="Nombre del Cliente">
							 <ul class="hidden" role='listbox' aria-label='Clientes'></ul>
														</div>
														
														<div class="form-group">
															<label for="inputData">
																Aplicar Promocion
															</label>
							<input type="text" name="promo" class="form-control"  autocomplete="off" placeholder="Promociones Vigentes">
														</div>
												
														<div class="form-group">
															<label for="inputData">
																Condicion de Compra
															</label><br>
															<select name="condicion" id="condicion" class="form-control">
															
    														<option value="1">Credito</option>
    														<option value="1" selected>Contado</option>
   															
   															
   															</select>
														</div>
														
																												
														<button type="submit" name="submit" class="btn btn-o btn-primary">
															Crear Factura
														</button>
													</form>
												</div>
											</div>
										</div>
													
											</div>
										</div>
									
									<div class="container-fluid container-fullw bg-white">
							<div class="row">
								<div class="col-md-12">
									
									
										<div class="col-lg-6 col-md-12">
											<div class="panel panel-white">
												
												<div class="panel-body">
								
													<form role="form" name="marcas" method="post" >
														<div class="form-group">
															<label for="inputData">
																Codigo del Producto
															</label>
							<input type="text" name="codigo" class="form-control"  autocomplete="off" placeholder="Codigo del Producto">
														</div>
														
														
														
														
														
														<button type="submit" hidden="true" name="codigo">buscar</button>
															
													</form>
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
									<h5 class="over-title margin-bottom-15">Detalle <span class="text-bold">Facturas</span></h5>
									
									<table class="table table-hover" id="sample-table-1">
										<thead>
											<tr>
												<th class="center">#</th>
												<th>Nombre Producto</th>
												<th>Existencias</th>
												<th>Precio</th>
												<th>Cantidad</th>
												<th>Descuento</th>
												<th>Precio</th>
												<th>Precio Final</th>
												<th>Subtotal</th>
												<th>Impuesto</th>
												<th>Total</th>
												
											</tr>
										</thead>
										<tbody>
<?php
$sql=pg_query($db,"select marcas.id, marca_nombre, marca_paisorigen, prov_nombre from marcas join proveedores on proveedor_id=proveedores.id;");
$cnt=1;
while($row = pg_fetch_object($sql))
{
?>

											<tr>
												<td class="center"><?php echo $cnt;?>.</td>
												<td><?php echo $row->prov_nombre;?></td>
												<td><?php echo $row->prov_nombre;?></td>
												<td><?php echo $row->prov_nombre;?></td>
												<td><input type="number" name="cantidad" autocomplete="off" placeholder="Cantidad"></td>
												<td><input type="checkbox" name="descuento" autocomplete="off" placeholder="Cantidad"></td>
												<td><?php echo $row->prov_nombre;?></td>
												
												
												
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
		<script src="js/funciones02.js"></script>
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
