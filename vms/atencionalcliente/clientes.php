<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
$fecha_actual=date("d-m-y h:i:s");

if(isset($_POST['cuenta']))
{
    $cliente_id=$_REQUEST['cliente_id'];
    
    $consultaClientes=pg_query($db,"select nombre_cliente, doc_numero, fecha, cargo, abono, saldo
                            from extractosclientes where cliente_id='$cliente_id' order by id");
}



if(isset($_POST['submit']))
{//if del submit
    
    if($_REQUEST['patrones']=="[0-9-]{8}[0-9]{1}"){
        $tipodoc="DUI";
    }
    else if($_REQUEST['patrones']=="[0-9-]{4}[0-9-]{4}[0-9]{5}"){
        $tipodoc="Tarjeta de Identidad";
    }
    else if($_REQUEST['patrones']=="[0-9-]{4}[0-9-]{4}[0-9]{6}"){
        $tipodoc="RTN";
    }
    else if($_REQUEST['patrones']=="[0-9-]{6}[0-9]{1}"){
        $tipodoc="NRC";
    }
     
    
    else($tipodoc="Pasaporte");
    
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
    
    
    $query=("INSERT INTO clientes(cliente_nombre, cliente_tipodoc,cliente_nodoc,cliente_giro,cliente_dir1,
            cliente_dir2,cliente_tel,cliente_correo,esempresasn,darcreditosn,creado_por,fecha_creacion,cliente_saldos)
      VALUES ('$_REQUEST[nombre]','$tipodoc','$_REQUEST[docfinal]','$_REQUEST[giro]','$_REQUEST[dir1]','$_REQUEST[dir2]',
            '$_REQUEST[tel]','$_REQUEST[correo]','$empresa','$credito','$_SESSION[login]',to_date('$fecha_actual','dd/mm/yy'),0)");
    $query=pg_query($query);
    
    if(!$query){
        echo "<script>alert('Error al procesar la informacion posibles datos duplicados');</script>";
    }
    //pg_close();
    
    if($query){
        echo "<script>alert('Su informacion ha sido enviada');</script>";
    }
   
    
    
    echo "<script>window.location.href='clientes.php'</script>";
    
    
}//if del submit




?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Administrar Clientes</title>
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
									<h1 class="mainTitle">Agregar Clientes</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Agregar Clientes</span>
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
													<h5 class="panel-title">Agregar Clientes</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="productos" method="post" >
														<div class="form-group">
															<label for="inputData">
																DUI <strong>00000000-0</strong> Identidad 
																<strong>0000-0000-00000</strong> pasaporte 
																<strong>A000000</strong> NRC <strong>000000-0</strong> RTN <strong>0000-0000-000000</strong> 
															</label>
							<input id="numdoc" type="text" name="numdoc" class="form-control"  autocomplete="off" required placeholder="Numero de documento sin guiones">
														</div>		
														
														<div class="form-group">
															<label for="inputData">
																Tipo de Documento
															</label>
															<select id="patrones" name="patrones" class="form-control" onchange="establecerPatron()">
                                                              <option value="" selected>Seleccione Tipo de Documento</option>
                                                              <option value="[0-9-]{8}[0-9]{1}" >DUI</option>
                                                              <option value="[0-9-]{4}[0-9-]{4}[0-9]{5}">Tarjeta de Identidad</option>
                                                              <option value="[A-Z]{1}[0-9]{6}">Pasaporte</option>
                                                              <option value="[0-9-]{6}[0-9]{1}" >NRC</option>
                                                              <option value="[0-9-]{4}[0-9-]{4}[0-9]{6}">RTN</option>
                                                            </select>
							
														</div>
														
														<div class="form-group">
							
							<input type="hidden" id="docfinal" name="docfinal" class="form-control"  autocomplete="off" >
							</div>
														
																												
														<div class="form-group">
															<label for="inputData">
																Nombre del Cliente
															</label>
							<input id="nombre" type="text" required name="nombre" class="form-control"  autocomplete="off" placeholder="Nombre del Cliente" >
														</div>

							<div class="form-group">
							<label>
							<input type="checkbox" id="empresa" name="empresa" >Es empresa?</label>
							
							<label>
							<input type="checkbox" id="credito" name="credito" >Dar Credito?</label>
							</div>																				
							
								
										
														
							<div class="form-group">
							<label for="inputData">
							Giro
							</label>
							<input id="giro" type="text" name="giro" class="form-control"  autocomplete="off" placeholder="Giro si es empresa">
							</div>
													
							<div class="form-group">
							<label for="inputData">
							Direccion Linea 1
							</label>
							<input id="dir1" type="text" name="dir1" class="form-control"  autocomplete="off" placeholder="Direccion">
							</div>
														
							<div class="form-group">
							<label for="inputData">
							Direccion Linea 2
							</label>
							<input id="dir2" type="text" name="dir2" class="form-control"  autocomplete="off" placeholder="Direccion">
							</div>
														
							<div class="form-group">
							<label for="inputData">
							Telefono
							</label>
							<input id="tel" type="tel" name="tel" class="form-control"  autocomplete="off" placeholder="(codigo de pais)numero" required>
							</div>
										
							<div class="form-group">
							<label for="inputData">
							correo
							</label>
							<input id="correo" type="email" name="correo" class="form-control"  autocomplete="off" placeholder="oncorreo@yaju.com" required>
							</div>							
														
														
														
														
							<button id="submit" type="submit" name="submit" class="btn btn-o btn-primary">
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
									<h5 class="over-title margin-bottom-15">Administrar <span class="text-bold">Clientes</span></h5>
									
									<table id="example" class="display" style="width:100%">
										<thead>
											<tr>
												<th class="center">#</th>
												<th>Nombre</th>
												<th>Tipo Documento</th>
												<th>No Documento</th>
												<th>Telefono</th>
												<th>Correo</th>
												<th>Saldo</th>
																																
												<th>Movimientos</th>	
												<th>Edit</th>
											</tr>
										</thead>
										<tbody>
<?php
$sql=pg_query($db,"select * from clientes");
$cnt=1;
while($row = pg_fetch_object($sql))
{
?>

											<tr>
												<td class="center"><?php echo $cnt;?>.</td>
												<td class="hidden-xs"><?php echo $row->cliente_nombre;?></td>
													<td><?php echo $row->cliente_tipodoc;?></td>
												<td><?php echo $row->cliente_nodoc;?></td>
												<td><?php echo $row->cliente_tel;?></td>
												<td><?php echo $row->cliente_correo;?></td>
												<td><?php echo $row->cliente_saldos;?></td>
												<td>
												
											<form id="estadoCuentaForm" action="#" method="post">
    						<input type="hidden" name="cliente_id" value="<?php echo $row->id; ?>">
    						<button type="submit" class="btn btn-link" name="cuenta" align="center">
        					<i class="fa fa-table" aria-hidden="true"></i>
    						</button>
							</form>
								</td>
								<td>
												<div class="visible-md visible-lg hidden-sm hidden-xs">
							<a href="editarclientes.php?id=<?php echo $row->id;?>" class="btn btn-transparent btn-xs" 
							tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
							
							
													
													
	<!-- <a href="clientes.php?id=<?php //echo $row->id?>&del=delete" onClick="return confirm('Are you sure you want to delete?')"class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a> -->
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
		
		
		

<!-- poner el modal aqui -->
<script>
    $(document).ready(function() {
        <?php if (isset($_POST['cuenta'])) : ?>
            $('#myModal').modal('show');
        <?php endif; ?>
    });
</script>



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	

  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
      <div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Estado de cuenta Cliente</h5>         
                                                    
      </div>
   <div class="modal-body">
   <table id="example2" class="display" style="width:100%">
   <thead>
	<tr>
	
	<th>Nombre</th>
	<th>No Comprobante</th>
	<th>Fecha</th>
	<th>Cargo</th>
	<th>Abono</th>
	<th>Saldo</th>																				
							
	</tr>
	</thead>

   
   <tbody>
   <?php 
    
    
    while($row = pg_fetch_object($consultaClientes))
    {
?>
  <tr>
   <td><?php echo $row->nombre_cliente;?></td>
   <td><?php echo $row->doc_numero;?></td>
   <td><?php echo $row->fecha;?></td>
   <td><?php echo $row->cargo;?></td>
   <td><?php echo $row->abono;?></td>
   <td><?php echo $row->saldo;?></td>
   </tr>
   
   
   <?php }?>
   
   
   </tbody>
   
   
   </table>
   
   </div>

<div class="modal-footer">
 
 
</div>
</div>

</div>
</div>

<!-- fin del modal    -->									
		<script>
		new DataTable('#example2', {
   		info: false,
    	ordering: false,
    	paging: false
		});
		</script>
		
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>