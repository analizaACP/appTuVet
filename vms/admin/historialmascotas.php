<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
//$id=intval($_GET['id']);// get value

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Historia Clinica Mascotas</title>
		
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
		 <style>
    /* Estilos para la tabla */
    table {
      border-collapse: collapse; /* Colapsa los bordes de las celdas */
      width: 100%; /* Ancho de la tabla */
    }

    /* Estilos para las filas */
    tr {
      border: 1px solid #ddd; /* Borde para cada fila */
    }

    /* Estilos para las celdas */
    td {
      border: 1px solid #ddd; /* Borde para cada celda */
      padding: 8px; /* Espaciado interno en cada celda */
    }

    /* Estilos para las celdas editables */
    td[contenteditable="true"] {
      background-color: #f2f2f2; /* Color de fondo para las celdas editables */
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
									<h1 class="mainTitle">Admin | Historia Clinica Mascotas</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Historia Clinica Mascotas</span>
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
													<h5 class="panel-title">Historia Clinica Mascotas</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="dcotorspcl" method="post" >
														<div class="form-group">
															<label for="exampleInputEmail1">
																Nombre de la Mascota
															</label>

	<?php 

$id=intval($_GET['id']);
	$sql=pg_query($db,"select nombre_mascota from mascotas where id=$id;");
	while($row = pg_fetch_object($sql))
{	
    
    //$proveedorid=$row->proveedor_id;
    //echo "el codigo del proveedor es ".$proveedorid;
	?>		<input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo $row->nombre_mascota;?>" >
	
													<?php }?>	</div>
						
			<div class="form-group">
			<input type="text" name="nombre" id="idmascota" class="form-control" value="<?php echo $id;?>" >
			</div>			
												
										
	<div class="form-group">
	<label for="exampleInputEmail1">Motivo Consulta
	<select name="motivo" id="motivo" class="form-control" >
  							<option value="Consulta">Consulta</option>
  							<option value="Baño">Baño</option>
  							<option value="Vacunas">Vacunas</option>
  							<option value="Desparasitacion" selected>Desparasitacion</option>
  							<option value="Corte y baño">Corte y Baño</option></select></label>
															
	
	
	<label for="inputData">Temperatura
	<input type="text" name="temperatura" id="temperatura" class="form-control" autocomplete="off" placeholder="Temperatura"></label>
	
	<label for="inputData">Peso
	<input type="text" name="peso" id="peso" class="form-control" autocomplete="off" placeholder="Libras"></label>
	
	
	</div>
	
				<div class="form-group">
				
				<label for="exampleFormControlTextarea1" class="form-label">Escriba los sintomas</label>
  <textarea name="sintomas" id="sintomas" class="form-control" id="exampleFormControlTextarea1" rows="4"></textarea>
				
				</div>		
				
				
				<div class="form-group">
				<label for="exampleInputEmail1">Analisis Realizados
	<select name="analisis[]" id="analisis" class="form-control" multiple>
  							<?php 
															$query2="SELECT id,nombre_analisis FROM analisis order by nombre_analisis";
															$consulta=pg_query($db,$query2);
															while($obj1=pg_fetch_object($consulta)){   ?>
															
															
    														<option value="<?php echo $obj1->id ?>"<?php if($obj1->nombre_analisis=='Seleccione proveedor'){echo "selected";}?>><?php echo $obj1->nombre_analisis;?></option>
   															
   															<?php }?></select></label>
				
				</div>
				
<div class="form-group">
				
				<label for="exampleFormControlTextarea1" class="form-label">Diagnostico</label>
  <textarea name="dianostico" id="diagnostico" class="form-control" id="exampleFormControlTextarea2" rows="4"></textarea>
				
				</div>	
						<h4>Receta</h4>		
						<div class="form-group">						
				<table id="miTabla">
    <tr>
      <td contenteditable="true"></td>
      <td contenteditable="true"></td>
    </tr>
  </table>
  <button type="button" onclick="agregarFila()">Agregar Fila</button>
				
				
				</div>		
				
				
				<div class="form-group">
				<label for="date">
																Fecha Proxima Cita
															</label>
				<input type="date" name="fecha" id="fecha" class="form-control" autocomplete="off">
				
				</div>
												
														
														<button type="button" name="submit" class="btn btn-o btn-primary" onclick="guardarDatos()">
															guardar Datos
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
		
		<script>
  // Definir la función guardarDatos() fuera de agregarFila()
  function guardarDatos() {
    // Obtener la tabla
    var tabla = document.getElementById("miTabla");

    // Obtener todas las filas de la tabla
    var filas = tabla.getElementsByTagName("tr");

    // Crear un arreglo para almacenar los datos de la tabla
    var datosTabla = [];

    // Recorrer las filas y obtener los datos de las celdas
    for (var i = 0; i < filas.length; i++) {
      var fila = filas[i];
      var celdas = fila.getElementsByTagName("td");
      var filaDatos = [];

      for (var j = 0; j < celdas.length; j++) {
        filaDatos.push(celdas[j].textContent);
      }

      datosTabla.push(filaDatos);
    }

    // Obtener los datos adicionales
    var idmascota=document.getElementById("idmascota").value;
    var nombre = document.getElementById("nombre").value;
    var motivo = document.getElementById("motivo").value;
    var temperatura = document.getElementById("temperatura").value;
    var peso = document.getElementById("peso").value;
    var sintomas = document.getElementById("sintomas").value;
    var analisis = Array.from(document.getElementById("analisis").selectedOptions).map(option => option.value);
    var diagnostico = document.getElementById("diagnostico").value;
    var fecha = document.getElementById("fecha").value;

    // Crear un objeto JSON que contenga todos los datos
    var datos = {
      tabla: datosTabla,
      idmascota: idmascota,
      nombre: nombre,
      motivo: motivo,
      temperatura: temperatura,
      peso: peso,
      sintomas: sintomas,
      diagnostico: diagnostico,
      fecha: fecha,
      analisis: analisis
    };

    // Enviar los datos al servidor mediante una solicitud AJAX (usando fetch, por ejemplo)
    fetch("guardar_historial.php", {
      method: "POST",
      body: JSON.stringify(datos),
      headers: {
        "Content-Type": "application/json"
      }
    })
    .then(response => response.text())
    .then(data => {
      alert(data); // Mostrar la respuesta del servidor (puedes personalizar esto)
    })
    .catch(error => {
      console.error("Error:", error);
    });
  }

  // Función para agregar una fila a la tabla
  function agregarFila() {
    // Obtener la tabla
    var tabla = document.getElementById("miTabla");

    // Crear una nueva fila
    var newRow = tabla.insertRow(tabla.rows.length);

    // Crear celdas en la nueva fila
    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);

    // Hacer las celdas editables
    cell1.contentEditable = true;
    cell2.contentEditable = true;

    // Puedes agregar valores iniciales si lo deseas
    cell1.innerHTML = "";
    cell2.innerHTML = "";
  }
</script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>