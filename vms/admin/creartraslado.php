<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
date_default_timezone_set('America/El_Salvador');
$fecha_actual=date("d-m-y h:i:s");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Administrar Traslados</title>
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
									<h1 class="mainTitle">Traslados</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Agregar Traslado</span>
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
										<div class="col-md-12">
											<div class="panel panel-white">
												<div class="panel-heading">
													<h5 class="panel-title">Agregar Traslado</h5>
												</div>
												<div class="panel-body">
                                                    <p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
                                                    <?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="compras" method="post" >   
                                                        <div class="form-group">
															<label for="inputData">
																Sucursal Salida
															</label><br>
															<select id="suc_salida" name="suc_salida" class="form-control" id_suc="" onchange="cargarDatoSucursal()">
							                                <?php 
															$query1="SELECT id,sucursal_nombre FROM sucursales order by sucursal_nombre";
															$consulta1=pg_query($db,$query1);
															$arraySuc = array();
															while($obj1=pg_fetch_object($consulta1)){
																$arraySuc[] = array(
																	'id' => $obj1->id,
																	'sucursal_nombre' => $obj1->sucursal_nombre
																);
																?>
																<option value="<?php echo $obj1->sucursal_nombre ?>"<?php if($obj1->sucursal_nombre=='Seleccione sucursal'){echo "selected";}?>><?php echo $obj1->sucursal_nombre;?></option>
																
   															
   															<?php  }
															$arrayS = json_encode($arraySuc);
															echo $arrayS;?>
   															
   															</select>
															<script type="text/javascript">
																var data_sucursales = <?php echo json_encode($arrayS);?>;
															</script>
															<notisuc_s style="color:red"></notisuc_s>
														</div>                         
                                                        
                                                        <div class="form-group">
															<label for="inputData">
																Sucursal Ingreso
															</label><br>
															<select id="suc_ingreso" name="suc_ingreso" class="form-control" >
							                                <?php 
															$query2="SELECT id,sucursal_nombre FROM sucursales order by sucursal_nombre";
															$consulta2=pg_query($db,$query2);
															while($obj1=pg_fetch_object($consulta2)){?>
																<option value="<?php echo $obj1->sucursal_nombre ?>"<?php if($obj1->sucursal_nombre=='Seleccione sucursal'){echo "selected";}?>><?php echo $obj1->sucursal_nombre;?></option>
																
   															
   															<?php  }
															?>
   															
   															</select>
															<notisuc_i style="color:red"></notisuc_i>
														</div>   		
                                                        
                                                        <div class="form-group">
                                                            <label for="inputData">
                                                                Productos
                                                            </label><br>
                                                            <table class="table table-hover" id="tabla_agregar_productos_compras">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="center">Seleccionar producto</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Opcion</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="center"  style="width: auto;">
                                                                            <?php 
                                                                                $productos="SELECT p.id, 
                                                                                p.prod_codbarra, 
                                                                                p.prod_nombre,
																				e.existencia
                                                                                FROM productos AS p
																				JOIN existencias AS e ON p.prod_codbarra=e.codbarra AND e.id_sucursal=$_SESSION[sucursal] 
																				WHERE p.activosn=1 AND e.existencia > 0
                                                                                ORDER BY p.prod_nombre";
                                                                                $consul_productos=pg_query($db,$productos);
                                                                            ?>
                                                                            <select id="productos" name="productos" class="form-control" >
                                                                                <?php 
                                                                                $array = array();
                                                                                while($obj1=pg_fetch_object($consul_productos)){
                                                                                    $array[] = array(
                                                                                        'id' => $obj1->id,
                                                                                        'prod_codbarra' => $obj1->prod_codbarra,
                                                                                        'prod_nombre' => $obj1->prod_nombre,
                                                                                        'markup' => $obj1->prod_markup,
                                                                                        'paga_iva' => $obj1->porcentaje_impuesto,
                                                                                        'prod_preciopublico' => $obj1->prod_preciopublico,
																						'prod_costopromedio' => $obj1->prod_costopromedio,
																						'prod_ultimocosto' => $obj1->prod_ultimocosto,
																						'cantidad_actual' => $obj1->existencia
                                                                                    );
                                                                                    ?>
                                                                                    <option value="<?php echo $obj1->prod_codbarra; ?>" <?php if($obj1->prod_nombre=='Seleccione producto'){echo "selected='selected'";}?>> 
																					<?php echo $obj1->prod_codbarra." - ".$obj1->prod_nombre."(".$obj1->existencia.")";?> </option>
                                                                                <?php  }
                                                                                    $arrayP = json_encode($array);
                                                                                    echo $arrayP;?>
                                                                            </select>
                                                                            <script type="text/javascript">
                                                                                var data_productos = <?php echo json_encode($arrayP);?>;
                                                                            </script>
                                                                        </td>
                                                                        <td style="width: 100px;"><input id="cantidad" type="number" name="cantidad" class="form-control" autocomplete="off" value="0" min="0" 
																		onChange="validarCantidadTraslado()" onKeyUp="validarCantidadTraslado()"></td>
                                                                        <td style="width: 70px;"><a id="btn-agregar-lista-compras" onClick="agregarListaProductos()">Agregar</a></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="inputData">
                                                                Lista de productos
                                                            </label><br>
                                                            <table class="table table-hover" id="tabla_lista_productos_compras" >
                                                                <thead>
                                                                    <tr>
                                                                        <th class="center">#</th>
                                                                        <th>Nombre</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Opcion</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                                <tfoot id="tfoot_compras">
                                                                </tfoot>
                                                            </table>
															<notipro style="color:red"></notipr>
                                                        </div>
                                                        <a id="btn_ingresar_compra" type="button" name="btn_ingresar_compra" class="btn btn-o btn-primary" onClick="insertarRegistro()">
                                                            Crear Traslado
                                                        </a>
													</form>			
                                                        </div>
                                                    </div>
                                                </div>
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
        <script src="js/traslados.js"></script>	
		<script>
			
			cargarDatoSucursal();</script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>