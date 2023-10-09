<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
$fecha_actual=date("d-m-y h:i:s");
if(isset($_POST['id']))
{//if del submit
    // guardar monto
    $query=("UPDATE htraslado SET estado= 'Aprobado', usuario_autoriza= '$_SESSION[login]' WHERE id=$_REQUEST[id]");
    $query=pg_query($query);
    
    if(!$query){
        echo "<script>alert('Error al procesar la informacion ".pg_last_error($db)."');</script>";
    }
    pg_close();
    
    if($query){
        echo "<script>alert('Se aprobo el traslado');</script>";
    }

	echo "<script>window.location.href='traslados.php'</script>";

}//if del submit

if(isset($_POST['cancelar']))
{//if del submit
    // guardar monto
    $query=("UPDATE htraslado SET estado= 'Cancelado', usuario_autoriza= '$_SESSION[login]' WHERE id=$_REQUEST[cancelar]");
    $query=pg_query($query);
    
    if(!$query){
        echo "<script>alert('Error al procesar la informacion ".pg_last_error($db)."');</script>";
    }
    //pg_close();

	$query1=("UPDATE dtraslados SET status= 'Cancelado' WHERE id_traslado=$_REQUEST[cancelar]");
    $query1=pg_query($query1);
    
    if(!$query1){
        echo "<script>alert('Error al procesar la informacion 1".pg_last_error($db)."');</script>";
    }
    //pg_close();

	$query2=("SELECT
            dt.id,
            dt.cod_barra,
            dt.cantidad
            FROM dtraslados AS dt
            WHERE dt.id_traslado = $_REQUEST[cancelar]
        ");
    $query2=pg_query($query2);
    
    if(!$query2){
        echo "<script>alert('Error al procesar la informacion 2".pg_last_error($db)."');</script>";
    }

	while ($a = pg_fetch_assoc($query2)) {
		$codbarra = $a['cod_barra'];
		$cantidad = $a['cantidad'];
		$query3=("UPDATE existencias SET existencia = existencia + $cantidad WHERE codbarra='$codbarra' AND id_sucursal='$_REQUEST[id_sucursal]'");
		$query3=pg_query($query3);
		
		if(!$query3){
			echo "<script>alert('Error al procesar la informacion ".pg_last_error($db)."');</script>";
		}
		//pg_close();
	}
    
	//pg_close();
    if($query){
        echo "<script>alert('Se cancelo el traslado');</script>";
    }

	echo "<script>window.location.href='traslados.php'</script>";

}//if del submit 
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
									<h1 class="mainTitle">Administrar Traslados</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Administrar Traslados</span>
									</li>
								</ol>
							</div>
						</section>
						<!-- end: PAGE TITLE -->
						<!-- start: BASIC EXAMPLE -->
						<div class="container-fluid container-fullw bg-white">
							<div class="row">
								<div class="col-md-12">
									<a id="agregar-compra" type="buttom" name="agregar-compra" href="creartraslado.php" class="btn btn-o btn-primary margin-bottom-15">
										Agregar Traslado
									</a>
									<h5 class="over-title margin-bottom-15">Administrar <span class="text-bold">Traslados</span></h5>
									
									<table class="table table-hover" id="tabla_compras">
										<thead>
											<tr>
												<th class="center">#</th>
												<th>Sucursal Salida</th>
												<th>Sucursal Ingreso</th>
												<th>Usuario Crea</th>
												<th>Usuario Recibe</th>
												<th>Fecha Enviado</th>
												<th>Fecha Recibido</th>
												<th>Estado</th>
												<th>Opción</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$sql=pg_query($db,"
											select t.id,
											t.fecha_enviado,
											t.fecha_recibido,
											s.id AS id_origen,
											t.suc_origen, 
											t.suc_destino, 
											t.usuario_crea, 
											t.usuario_recibe,
											t.estado
											from htraslado as t
											LEFT JOIN sucursales as s ON t.suc_origen = s.sucursal_nombre
											ORDER BY t.fecha_enviado");
											$cnt=1;
											while($row = pg_fetch_object($sql)){
												?>

												<tr>
													<td class="center"><?php echo $cnt;?>.</td>
													<td><?php echo $row->suc_origen;?></td>
													<td><?php echo $row->suc_destino;?></td>
													<td><?php echo $row->usuario_crea;?></td>
													<td><?php echo $row->usuario_recibe;?></td>
													<td><?php echo $row->fecha_enviado;?></td>
													<td><?php echo $row->fecha_recibido;?></td>
													<td><?php echo $row->estado;?></td>
													<td>
														<?php 
															echo '<button class="btn btn-success btnDetallesTraslado" data-toggle="modal" data-target="#modalDetallesTraslado" 
															onClick="cargarDatosModalDetallesTraslado('.$row->id.')">Detalles</button>';
															if ($row->estado == '' || $row->estado == 'Pendiente'){
																echo '<form id="formulario" role="form" method="post" enctype="multipart/form-data">
																<input type="hidden" name="id" value="'.$row->id.'">
																<button class="btn btn-warning btnAprobarTraslado" style="margin-left:5px;margin-top:5px;margin-button:5px;" type="button" id="enviar" onClick="confirmar(this)">
																Aprobar</button>
																</form>';
																echo '<form id="formulario" role="form" method="post" enctype="multipart/form-data">
																<input type="hidden" name="cancelar" value="'.$row->id.'">
																<input type="hidden" name="id_sucursal" value="'.$row->id_origen.'">
																<button class="btn btn-danger btnCancelarTraslado" style="margin-left:5px;margin-top:5px;margin-button:5px;" type="button" id="cancelar" onClick="confirmar(this)">
																Cancelar</button>
																</form>';
															}
														?>
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
						MODAL DETALLES TRASLADOS
						======================================-->

						<div id="modalDetallesTraslado" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<!--=====================================
									CABEZA DEL MODAL
									======================================-->
									<div class="modal-header">
										<h4 class="modal-title">Detalle Traslado</h4>
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
											<table class="table table-hover" id="tabla_detalles_traslado">
												<thead>
													<tr>
														<th>Producto</th>
														<th>Cantidad</th>
														<th>Estado</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>		
											</div>
										</div>
									</form>
									<!--=====================================
									PIE DEL MODAL
									======================================-->
									<div class="modal-footer justify-content-between">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
									</div>
									<?php
									?>
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
				$('#tabla_compras').DataTable({
					responsive: true
				});
				$('#tabla_detalles_traslado').DataTable({
					responsive: true
				});
			});
		</script>
		<script src="js/traslados.js"></script>	
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>