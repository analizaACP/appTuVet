<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
$fecha_actual=date("d-m-y h:i:s");
if(isset($_POST['submit']))
{//if del submit
    
    $determinarporcimpuesto=("select * from impuestos where imp_id='$_REQUEST[impuestos]'");
    $determinarporcimpuesto=pg_query($determinarporcimpuesto);
    if(!$determinarporcimpuesto){
        echo "<script>alert('no se extrajo el porc de impuestos');</script>";
    }
    
    while($row = pg_fetch_object($determinarporcimpuesto)){
        $porcimpuesto=$row->imp_porc;
    }
    
    
    $result=("select * from sucursales");
    $result=pg_query($result);
    $filas=pg_num_rows($result);// variable filas sirve para conocer la cantidad de sucursales para crear puntos de reorden y existencias
    
    
    $costoprom=(floatval($_REQUEST['costo']));
    $preciopub=(floatval($_REQUEST['precio']));
    
    $descuento=(floatval($_REQUEST['descuento']))/100;
    //$puntoreorden=(int)$_REQUEST['reorden'];
    $impuestos=$_REQUEST['impuestos'];
    
    $descuentounidad=$preciopub*$descuento;
    
    
    if($_REQUEST['sumainv']=="on"){
        $sumainv=1;
    }
    else{
        $sumainv=0;
    }
    
    
    $query=("INSERT INTO productos(prod_codbarra, prod_nombre,id_marca,id_categorias,prod_costopromedio,prod_preciopublico,
                            prod_porcdescuento,fecha_creacion,creado_por,
                            prod_esinventariosn,activosn,descuento_unidad,
                            sucursal_crea,porcentaje_impuesto, codigo_impuesto)
      VALUES ('$_REQUEST[codbarra]','$_REQUEST[nombrebase]','$_REQUEST[marca]',
                '$_REQUEST[categoria]','$costoprom','$preciopub',
            '$descuento',to_date('$fecha_actual','dd/mm/yy'),'$_SESSION[login]',
            '$sumainv',1,'$descuentounidad','$_SESSION[sucursal]','$porcimpuesto','$impuestos')");
    $query=pg_query($query);
    
    if(!$query){
        echo "<script>alert('Error al procesar la informacion posibles datos duplicados');</script>";
    }
    //pg_close();
    
    if($query){
        echo "<script>alert('Su informacion ha sido enviada');</script>";
    }
    //echo "<script>window.location.href='productos.php'</script>";
    
    // crear la fila de existencias y puntos de reorden para cada sucursal segun las sucursales existentes
    
    // seleccionar el ultimo id de productos
    $query=("SELECT prod_codbarra FROM productos ORDER BY id DESC LIMIT 1;");
    $query=pg_query($query);
    $codbarra = pg_fetch_result($query, 0, 0);
    
    //enviar todas las sucursales a un array
    
    $sucursales = "SELECT * FROM sucursales";
    $result = pg_query($db, $sucursales);
    if ($result) {
        // Iterar sobre los resultados de la consulta utilizando pg_fetch_assoc
        while ($fila = pg_fetch_assoc($result)) {
            // Aquí puedes acceder a los campos de cada registro utilizando el array asociativo $fila
            
            //$sucursal = $fila['id'];
            
            
            
            
        }
    }
    
    
    
    
    // guardar el punto de reorden de la sucursal actual
   /*
    $query=("UPDATE puntosdereorden SET ptoreorden='$puntoreorden' where codbarra='$_REQUEST[codbarra]' and id_sucursal='$_SESSION[sucursal]'");
    $query=pg_query($query);
    
    if(!$query){
        echo "<script>alert('Error al procesar la informacion posibles datos duplicados');</script>";
    }
    pg_close();
    
    if($query){
        echo "<script>alert('punto de reorden actualizado para esta sucursal');</script>";
    }
    
    if($consulta04){
        echo "<script>alert('producto creado para todas las sucursales');</script>";
    }
    */
    echo "<script>window.location.href='servicios.php'</script>";
    
    
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
		<title>Admin | Servicios</title>
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
									<h1 class="mainTitle">Agregar Servicios</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Agregar Servicios</span>
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
													<h5 class="panel-title">Servicios</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													
													<form role="form" name="productos" method="post" >
														<div class="form-group">
															<label for="inputData">
																Codigo
															</label>
							<input id="codbarra" type="text" name="codbarra" class="form-control"  autocomplete="off" placeholder="Ingrese el codigo del producto" autofocus>
														</div>
														
														
														
												<div class="form-group">
															<label for="inputData">
																Nombre
															</label>
							<input id="nombre" type="text" required name="nombre" class="form-control"  autocomplete="off" placeholder="Nombre principal del producto">
														</div>
														<!-- script para que el lector de codigo de barra capture el codigo y salte al campo nombre -->
														<script src="js/funciones.js"></script>
														
														
														
														<div class="form-group">
															<label for="inputData">
																Presentacion
															</label>
							<input id="presentacion" type="text" required name="presentacion" class="form-control"  autocomplete="off" placeholder="Unidad de venta del producto">
														</div>
														
											<!-- inicia seleccionar la marca del producto -->			
	<div class="form-group">
    <label for="inputData">
        Marca
    </label><br>
    <select id="marca" name="marca" class="form-control" onchange="concatenar()">
        <?php 
        $query2 = "SELECT id, marca_nombre FROM marcas ORDER BY marca_nombre";
        $consulta = pg_query($db, $query2);

        while ($obj1 = pg_fetch_object($consulta)) {
            $id = $obj1->id;
            $marca_nombre = $obj1->marca_nombre;
            $selected = ($marca_nombre == 'Seleccione marca') ? "selected" : "";

            // Agregamos la opción al select
            echo '<option value="' . $id . '" ' . $selected . '>' . $marca_nombre . '</option>';
        }
        ?>
    </select>
</div>

														
														
											<!-- termina seleccionar la marca del producto -->	
											
											
																				
														
							<div class="form-group">
							
							<input type="hidden" id="nombrebase" name="nombrebase" class="form-control"  autocomplete="off" >
							</div>
														
											<!-- inicia seleccionar la categoria del producto -->			
														
														<div class="form-group">
															<label for="inputData">
																Categoria
															</label><br>
															<select name="categoria" id="categoria" class="form-control">
							                                <?php 
															$query2="SELECT id,cat_nombre FROM categorias order by cat_nombre";
															$consulta=pg_query($db,$query2);
															while($obj1=pg_fetch_object($consulta)){?>
																<option value="<?php echo $obj1->id ?>"<?php if($obj1->cat_nombre=='seleccione categoria'){echo "selected";}?>><?php echo $obj1->cat_nombre;?></option>
   															
   															<?php }?>
   															</select>
							
							
														</div>
											<!-- termina seleccionar la categoria del producto -->	
											
											
													
							<div class="form-group">
							<label for="inputData">
																Impuestos
															</label><br>
															<select name="impuestos" id="impuestos">
															<?php 
															$query2="SELECT imp_id,imp_nombre FROM impuestos order by imp_nombre";
															$consulta=pg_query($db,$query2);
															while($obj1=pg_fetch_object($consulta)){   ?>
															
															
    														<option value="<?php echo $obj1->imp_id ?>"<?php if($obj1->imp_nombre=='Seleccione impuesto'){echo "selected";}?>><?php echo $obj1->imp_nombre;?></option>
   															
   															<?php }?>
   															</select>
   															
							<label><input type="checkbox" hidden="true" id="sumainv" name="sumainv" > </label>
							</div>
														
							
							<div class="form-group">
							<label for="inputData">Costo
							<input type="number" name="costo" class="form-control" autocomplete="off" min="0" value="0" step="0.01"></label>
							
							<label for="inputData">Precio al Publico Sin IVA
							<input type="number" name="precio" class="form-control" autocomplete="off" min="0" value="0"></label>
							</div>
							
														
							
										
														
							<div class="form-group">
							
							<label for="inputData">Porcentaje de Descuento
							<input type="number" name="descuento" class="form-control" autocomplete="off" min="0" value="0" step="0.001"></label>
							</div>							
														
														
														
														<button id="submit" type="submit" name="submit" class="btn btn-o btn-primary">
															Submit
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
									<h5 class="over-title margin-bottom-15">Administrar <span class="text-bold">Productos</span></h5>
									
									<table id="example" class="display" style="width:100%">
										<thead>
											<tr>
												<th class="center">#</th>
												<th>Codigo</th>
												<th>Nombre</th>
												<th>Marca</th>
												<th>Categoria</th>
												<th>Proveedor</th>
												<th>Precio de Venta</th>
												<th>Acciones</th>											
												
											</tr>
										</thead>
										<tbody>
<?php
$sql=pg_query($db,"select productos.id,prod_codbarra ,prod_preciopublico, prod_nombre, marca_nombre, cat_nombre, prov_nombre 
                    from productos 
                    join marcas on id_marca=marcas.id 
                    join categorias on id_categorias=categorias.id 
                    join proveedores on marcas.proveedor_id=proveedores.id 
                    where productos.activosn=1 and prod_esinventariosn=0
                    order by id;");
$cnt=1;
while($row = pg_fetch_object($sql))
{
?>

											<tr>
												<td class="center"><?php echo $cnt;?>.</td>
												<td><?php echo $row->prod_codbarra;?></td>
												<td class="hidden-xs"><?php echo $row->prod_nombre;?></td>
												<td><?php echo $row->marca_nombre;?></td>
												<td><?php echo $row->cat_nombre;?></td>
												<td><?php echo $row->prov_nombre;?></td>
												<td><?php echo $row->prod_preciopublico;?></td>
												
												
												
												<td>
												<div class="visible-md visible-lg hidden-sm hidden-xs">
							<a href="editarservicios.php?id=<?php echo $row->id;?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
													
	<a href="productos.php?id=<?php echo $row->id?>&del=delete" onClick="return confirm('Are you sure you want to delete?')"class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
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
