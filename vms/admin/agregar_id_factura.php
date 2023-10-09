<?php

session_start();
error_reporting(0);
include_once('include/config.php');
include_once('include/checklogin.php');
check_login();
$fecha_actual=date("d-m-y h:i:s");
$hora_actual = date("H:i:s");
// crear la factura en la tabla hfacturas


if(isset($_POST['submit'])){
    
    $nombre=$_REQUEST['key'];
    $condicion=$_REQUEST['condicion'];
    $docCliente=$_REQUEST['key2'];
    
      
    
    $buscarcliente=pg_query($db,"select id, darcreditosn from clientes where cliente_nodoc='$docCliente'");
    $row = pg_fetch_object($buscarcliente);
    $darcredito=$row->darcreditosn;
    $idCliente=$row->id;
    
    
    
    //$_SESSION['credito']=$darcredito;
    
    if($condicion=='Contado' || $condicion=='Credito' && $darcredito==1){
        $crearfactura=("INSERT INTO hfacturas (fecha_creacion, id_sucursal, creada_por, nombre_cliente,condicion_pago, hora_inicio,id_cliente)
          VALUES (to_date('$fecha_actual','dd/mm/yy'),'$_SESSION[sucursal]','$_SESSION[login]','$nombre','$condicion','$hora_actual',$idCliente )");
        $crearfactura=pg_query($crearfactura);
        echo "<script>window.location.href='facturacion.php'</script>";
        if(!$crearfactura){
            echo "<script>alert('no se pudo crear la nueva factura');</script>";
        }
        pg_close();
        
    }
    else{echo "<script>alert('cliente no tiene credito habilitado');</script>";
    echo "<script>window.location.href='agregar_id_factura.php'</script>";
    
    }
    // seleccionar el id de la ultima factura creada
    //erramos la conexión a la base de datos
    
    // Ahora, $idfactura contiene el valor máximo de 'id' para la sucursal actual
    //echo "El valor máximo de idfactura es: " . $idfactura;
    
    
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Facturacion</title>


<link rel="stylesheet" href="css/font-awesome.min.css">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="css/styles.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<title>Admin | Administrar Productos</title>
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
<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
<?php echo htmlentities($_SESSION['msg']="");?></p>	
		
<dialog open style="width: 50%;">

<h4>Detalles del Cliente</h4>
    <div>
        <form role="form" name="productos" method="post" style="display: flex; flex-direction: column;">

           <div class="form-group">
                <label for="select">Condicion de Pago</label>
                <select name="condicion" id="condicion" class="form-control">
                    <option value="Contado" selected>Contado</option>
                    <option value="Credito">Credito</option>
                </select>
            </div>	
           
           <div class="form-group">
               
                <input class="form-control" type="hidden" name="key2" id="key2">
                
            </div>	
           <div class="form-group">
                <label for="key">Id del Cliente</label>
                <input class="form-control" type="text" name="key3" id="key3" placeholder="" autocomplete="off" disabled>
                
            </div>	 
           
            <div class="form-group">
                <label for="key">Nombre o ID del Cliente</label>
                <input class="form-control" type="text" name="key" id="key" placeholder="Nombre cliente" required autocomplete="off">
            	<ul id="lista" ></ul>
           
            </div>
            
            <div style="display: flex; justify-content: flex-end;">
            
            
                <button id="submit" type="submit" name="submit" class="btn btn-o btn-primary">Submit</button>
            </div>
			
        </form>

        <div id="suggestions"></div>
    </div>
</dialog>




<div class="col-lg-12 col-md-12">
<div class="panel panel-white">
</div>
</div>
<div class="row">
</div>
</div>
</div>
</div>
</div>
						<!-- end: BASIC EXAMPLE -->
						<!-- end: SELECT BOXES -->
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
document.getElementById("key").addEventListener("keyup", getCodigos)

function getCodigos() {

    let inputCP = document.getElementById("key").value
    let inputCP2=document.getElementById("key2").value
    let lista = document.getElementById("lista")

    if (inputCP.length > 0) {

        let url = "include/getNombres.php"
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

function mostrar(cliente_nombre, doc_cliente) {
    lista.style.display = 'none'
   // alert("CP: " + cp)
   document.getElementById("key").value = cliente_nombre;
   document.getElementById("key2").value = doc_cliente;
   document.getElementById("key3").value = doc_cliente;
}
	


</script>
		
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>
</body>
</html>

