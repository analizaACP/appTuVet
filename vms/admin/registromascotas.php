<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();

if(isset($_POST['submit']))
{
    
    $valorSeleccionado = $_POST["raza"];
    list($idRaza, $nombreEspecie) = explode(',', $valorSeleccionado);
    $fecha_actual = date("Y-m-d H:i:s");
    $nombreMascota=$_REQUEST['nombremascota'];
    $idProp=$_REQUEST['idduenio'];
    $generoMascota=$_REQUEST['genero'];
    $edadMascota=$_REQUEST['edadmascota'];
    $pelajeMascota=$_REQUEST['pelajemascota'];
    $colorMascota=$_REQUEST['colormascota'];
    $sizeMascota=$_REQUEST['size'];
    $caracMascota=$_REQUEST['caracteristicasmascota'];
    $estadoMascota=$_REQUEST['reproductivo'];
    $chipMascota=$_REQUEST['chipmascota'];
    $habitatMascota=$_REQUEST['habitatmascota'];
    $dietaMascota=$_REQUEST['dietamascota'];
    $activoSn=1;
    $creador=$_SESSION['login'];
    $sucursal=$_SESSION['sucursal'];
    
    //echo $_REQUEST[fullname];
    $sql = "INSERT INTO mascotas (nombre_mascota, propietario_id, especie, raza_id, edad, fecha_creacion, sexo, tipo_pelo, color, tamanio,
                                   caracteristicas, estado_reproductivo, chip, habitat, dieta, activa_sn, usuario_crea,sucursal_crea ) 
                VALUES ($1, $2, $3,$4, $5, $6,$7,$8,$9,$10, $11, $12, $13, $14, $15, $16, $17, $18)";
    $query = pg_prepare($db, "insert_query", $sql);
    
    // Ejecutar la consulta con parámetros
    $result = pg_execute($db, "insert_query", array($nombreMascota, $idProp,$nombreEspecie,$idRaza,$edadMascota,$fecha_actual, $generoMascota, 
                                                    $pelajeMascota,$colorMascota, $sizeMascota,$caracMascota,$estadoMascota,$chipMascota,$habitatMascota,$dietaMascota,$activoSn,$creador,$sucursal));
    
    if (!$result) {
        die("Error al realizar la inserción.");
    }
    
    echo "<script>window.location.href='registromascotas.php'</script>";
}

if(isset($_GET['del']))
{
    
    
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Registro Mascotas</title>
		<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
		<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
		
		
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
		<link href="vendor/DataTables/css/jquery.dataTables.min.css" rel="stylesheet" media="screen">
		<style>
    /* Estilos para el div de sugerencias */
    #lista {
        position: absolute;
        z-index: 9999;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 4px;
    }

    /* Estilos para cada elemento de sugerencia */
    .suggest-element {
        cursor: pointer;
        padding: 4px 8px;
    }

    .suggest-element:hover {
        background-color: #f5f5f5;
    }
</style>
		
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
									<h1 class="mainTitle">Registro MAscotas</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Registro MAscotas</span>
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
													<h5 class="panel-title">Registro MAscotas</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="proveedores" method="post" >
														<div class="form-group">
															<label for="inputData">
																Nombre de la Mascota
															</label>
							<input type="text" name="nombremascota" class="form-control"  autocomplete="off" placeholder="Ingrese nombre de la mascota">
														</div>
												
														<div class="form-group">
															<label for="inputData">
																Nombre del Propietario
															</label>
							<input type="text" name="nombreduenio" id="nombreduenio" class="form-control"  autocomplete="off" placeholder="Ingrese nombre o No de id del Propietario" required>
							<ul id="lista" ></ul>
														</div>
														
														
														<div class="form-group">
															
							<input type="hidden" name="idduenio" id="idduenio" class="form-control"  autocomplete="off" placeholder="este campo es oculto">
														</div>
														
														
														<div class="form-group">
															<label for="inputData">
																Raza de la mascota
															
															<select name="raza" id="raza" class="form-control">
    <?php 
    $query2 = "SELECT id, nombre_raza, nombre_especie FROM razas ORDER BY nombre_raza";
    $consulta = pg_query($db, $query2);
    while ($obj1 = pg_fetch_object($consulta)) { ?>
        <option value="<?php echo $obj1->id . ',' . $obj1->nombre_especie; ?>" <?php if ($obj1->nombre_raza == 'Seleccione raza') { echo "selected"; } ?>>
            <?php echo $obj1->nombre_raza . ' (' . $obj1->nombre_especie . ')'; ?>
        </option>
    <?php } ?>
</select>
</label>
<label for="inputData">
																Edad de la mascota
															
							<input type="text" name="edadmascota" class="form-control" autocomplete="off" placeholder="Edad"></label>
														
															
														</div>
														
														<div class="form-group">
														<label for="select">
																Sexo Mascota
															
							<select name="genero" class="form-control" >
  							<option value="Femenino">Hembra</option>
  							<option value="Masculino">Macho</option>
  							<option value="" selected>Genero de la Mascota</option>
							</select></label>	
														
															<label for="inputData">
																Pelaje      
															
							<input type="text" name="pelajemascota" class="form-control" autocomplete="off" placeholder="tipo de pelaje"></label>
														</div>
														
														<div class="form-group">
														
														<label for="inputData">
																Tamaño
															
							<select name="size" class="form-control" >
  							<option value="Mini">Mini</option>
  							<option value="Pequeño">Pequeño</option>
  							<option value="Mediano">Mediano</option>
  							<option value="Grande">Grande</option>
  							<option value="Gigante">Gigante</option>
  							<option value="" selected>tamaño de la Mascota</option>
							</select></label>
															<label for="inputData">
																Color
															
							<input type="text" name="colormascota" class="form-control" autocomplete="off" placeholder="color" required></label>
														
															
														</div>
														
													
														
									<div class="form-group">
															<label for="inputData">
																Estado Reproductivo
															
							<select name="reproductivo" class="form-control" >
  							<option value="Fertil">Fertil</option>
  							<option value="Castrado">Castrado</option>
  							<option value="Esterilizado">Esterilizado</option>
  							<option value="" selected>estado reproductivo</option>
							</select></label>
														
															<label for="inputData">
																Chip
															
							<input type="text" name="chipmascota" class="form-control" autocomplete="off" placeholder="si, no, o numero de chip"></label>
														</div>		
														
														
											<div class="form-group">
															<label for="inputData">
																Habitat
															
							<input type="text" name="habitatmascota" class="form-control" autocomplete="off" placeholder="Habitat" required></label>
														
															<label for="inputData">
																Dieta
															
							<input type="text" name="dietamascota" class="form-control" autocomplete="off" placeholder="que se harta el perro" required></label>
														</div>	
							<div class="form-group">
															<label for="inputData">
																Caracteristicas o señas particulares
															</label>
							<input type="text" name="caracteristicasmascota" class="form-control"  autocomplete="off" placeholder="Señas particulares">
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
									<h5 class="over-title margin-bottom-15">Administrar <span class="text-bold">Proveedores</span></h5>
									
									<table id="example" class="display" style="width:100%">	
										<thead>
											<tr>
												<th class="center">#</th>
												<th>Propietario</th>
												<th>Nombre</th>
												<th>Raza</th>
												<th>Especie</th>
												<th>Telefono</th>
												<th>Historial</th>
												
												
											</tr>
										</thead>
										<tbody>
<?php
$sql=pg_query($db,"select 
m.id,
c.cliente_nombre as propietario, 
m.nombre_mascota as mascota,
r.nombre_raza as raza,
r.nombre_especie as especie,
c.cliente_tel as telefono
from mascotas m 
join clientes c 
on m.propietario_id=c.id
join razas r
on m.raza_id=r.id; ");
$cnt=1;
while($row = pg_fetch_object($sql))
{
?>

											<tr>
												<td class="center"><?php echo $cnt;?>.</td>
												<td class="hidden-xs"><?php echo $row->propietario;?></td>
												<td><?php echo $row->mascota;?></td>
												<td><?php echo $row->raza;?>
												<td><?php echo $row->especie;?>
												<td><?php echo $row->telefono;?>
												</td>
												
												<td >
												<div class="visible-md visible-lg hidden-sm hidden-xs">
							<a href="historialmascotas.php?id=<?php echo $row->id;?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
													
	<a href="registromascotas.php?id=<?php echo $row->id?>&del=delete" onClick="return confirm('Are you sure you want to delete?')"class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
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
		<script>
document.getElementById("nombreduenio").addEventListener("keyup", getCodigos)

function getCodigos() {

    let inputCP = document.getElementById("nombreduenio").value
    
    let lista = document.getElementById("lista")

    if (inputCP.length > 0) {

        let url = "include/getDuenio.php"
        let formData = new FormData()
        formData.append("key", inputCP)

        fetch(url, {
            method: "POST",
            body: formData,
            mode: "cors" //Default cors, no-cors, same-origin
        }).then(response => response.json())
            .then(data => {
                lista.style.display = 'block'
                lista.innerHTML = data
            })
            .catch(err => console.log(err))
    } else {
        lista.style.display = 'none'
    }
}

function mostrar(cliente_nombre, id_cliente) {
    lista.style.display = 'none'
   // alert("CP: " + cp)
   document.getElementById("nombreduenio").value = cliente_nombre;
   document.getElementById("idduenio").value = id_cliente;
   
}
	


</script>
		
		
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>
