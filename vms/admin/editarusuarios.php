<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
$id=intval($_GET['id']);// get value
$fecha_actual=date("d-m-y h:i:s");


if(isset($_POST['submit']))
{
    $nombre=$_POST['nombrehidden'];
    $iniciales=$_POST['iniciales'];
    $password=$_POST['password'];
    $solicitud=$_POST['nuid'];
    $destinatario=$_POST['correo'];
    
    
    
    if($_POST['rol']=="Administrador"){// if del administrador
        $query = ("INSERT INTO administradores (admin_nombre, admin_iniciales, admin_passwd, admin_confpasswd, creadapor, fecha_creacion, activosn,id_solicitud) 
        VALUES ('$nombre', '$iniciales', '$password', '$password', '$_SESSION[login]', to_date('$fecha_actual','dd/mm/yy'),1,'$solicitud')");
        
    $query=pg_query($query);
    
    $query = "UPDATE solicitudcontrasenia SET procesadosn = 1 WHERE id = '$solicitud'";
    $query=pg_query($query);
    
    pg_close();
        
    if(!$query){
        
        echo "<script>alert('Su informacion NO ha sido enviada');</script>";
        
    }
    
    
    
    echo "<script>window.location.href='usuarios.php'</script>";
    }// fin del if del administrador
    
    
    
    if($_POST['rol']=="Contabilidad"){// if del contador
        $query = ("INSERT INTO contador (nombre, iniciales, passwd, confpasswd, creadapor, fecha_creacion, activosn,id_solicitud)
        VALUES ('$nombre', '$iniciales', '$password', '$password', '$_SESSION[login]', to_date('$fecha_actual','dd/mm/yy'),1,'$solicitud')");
        
        $query=pg_query($query);
        
        if(!$query){
            
            echo "<script>alert('Su informacion NO ha sido enviada');</script>";
            
        }
        
        $query = "UPDATE solicitudcontrasenia SET procesadosn = 1 WHERE id = '$solicitud'";
        $query=pg_query($query);
        
        pg_close();
        
        if(!$query){
            
            echo "<script>alert('Su informacion NO ha sido enviada');</script>";
            
        }
        
        echo "<script>window.location.href='usuarios.php'</script>";
    }// fin del if del contador
    
    
    if($_POST['rol']=="Atencion al cliente"){// if del atencion al cliente
        $query = ("INSERT INTO atencioncliente (atn_nombre, atn_iniciales, atn_passwd, atn_confpasswd, creadapor, fecha_creacion, activosn,id_solicitud)
        VALUES ('$nombre', '$iniciales', '$password', '$password', '$_SESSION[login]', to_date('$fecha_actual','dd/mm/yy'),1,'$solicitud')");
        
        $query=pg_query($query);
        
        if(!$query){
            
            echo "<script>alert('Su informacion NO ha sido enviada');</script>";
            
        }
        
        $query = "UPDATE solicitudcontrasenia SET procesadosn = 1 WHERE id = '$solicitud'";
        $query=pg_query($query);
        
        pg_close();
        
        if(!$query){
            
            echo "<script>alert('Su informacion NO ha sido enviada');</script>";
            
        }
        
        echo "<script>window.location.href='usuarios.php'</script>";
    }// fin del if del atencion al cliente
    
    
    
    if($_POST['rol']=="Gerente de Sucursal"){// if de gerente de sucursal
        $query = ("INSERT INTO gerentes (gerente_nombre, gerente_iniciales, gerente_passwd, gerente_confpasswd, creada_por, fecha_creacion, activosn,id_solicitud)
        VALUES ('$nombre', '$iniciales', '$password', '$password', '$_SESSION[login]', to_date('$fecha_actual','dd/mm/yy'),1,'$solicitud')");
        
        $query=pg_query($query);
        
        if(!$query){
            
            echo "<script>alert('Su informacion NO ha sido enviada');</script>";
            
        }
        
        $query = "UPDATE solicitudcontrasenia SET procesadosn = 1 WHERE id = '$solicitud'";
        $query=pg_query($query);
        
        pg_close();
        
        if(!$query){
            
            echo "<script>alert('Su informacion NO ha sido enviada');</script>";
            
        }
        
        echo "<script>window.location.href='usuarios.php'</script>";
    }// fin del if de gerente de sucursal
    
    
    
    if($_POST['rol']=="Medico Veterinario"){// if de mvz
        $query = ("INSERT INTO mvz (mvz_nombre, mvz_iniciales, mvz_passwd, mvz_confpasswd, creada_por, fecha_creacion, activosn,id_solicitud)
        VALUES ('$nombre', '$iniciales', '$password', '$password', '$_SESSION[login]', to_date('$fecha_actual','dd/mm/yy'),1,'$solicitud')");
        
        $query=pg_query($query);
        
        if(!$query){
            
            echo "<script>alert('Su informacion NO ha sido enviada');</script>";
            
        }
        
        $query = "UPDATE solicitudcontrasenia SET procesadosn = 1 WHERE id = '$solicitud'";
        $query=pg_query($query);
        
        pg_close();
        
        if(!$query){
            
            echo "<script>alert('Su informacion NO ha sido enviada');</script>";
            
        }
        
        echo "<script>window.location.href='usuarios.php'</script>";
    }// fin del if de mvz
    
    
    mail($destinatario,"Usuario sistema TuVet","Su usuario ha sido creado");
    
    
    
    
    
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Agregar Usuarios</title>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
									<h1 class="mainTitle">Admin | Agregar Usuarios</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Agregar Usuarios</span>
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
													<h5 class="panel-title">Agregar Usuarios</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="dcotorspcl" method="post" >
														<div class="form-group">
															<label for="exampleInputEmail1">
																Nombre Usuario
															</label>

	<?php 

$id=intval($_GET['id']);
	$sql=pg_query($db," select * from solicitudcontrasenia where id=$id;");
	while($row = pg_fetch_object($sql))
{	
    
       
	?>		
	<input id="nombre" type="text" name="nombre" class="form-control" value="<?php echo $row->nombreusuario;?>" disabled >
	<input id="nombrehidden" type="hidden" name="nombrehidden" class="form-control" value="<?php echo $row->nombreusuario;?>"  >
	</div>
	
	
	<div class="form-group">
	
	<input type="hidden" name="nuid" class="form-control" value="<?php echo $row->id;?>" >
															
	</div>
	
	
	
	
	<div class="form-group">
	<label for="exampleInputEmail1">Iniciales</label>
	<input type="text" name="iniciales02" class="form-control" value="<?php echo $row->iniciales;?>" disabled >
	<input type="hidden" name="iniciales" class="form-control" value="<?php echo $row->iniciales;?>" >														
	</div>
												
										
	<div class="form-group">
	<label for="exampleInputEmail1">Correo</label>
	<input type="text" name="correo02" class="form-control" value="<?php echo $row->correousuario;?>" disabled>
	<input type="hidden" name="correo" class="form-control" value="<?php echo $row->correousuario;?>" >														
	</div>
	
	<div class="form-group">
	<label for="exampleInputEmail1">Rol asignado</label>
	<input type="text" name="rol02" class="form-control" value="<?php if($row->rolusuario==1){echo "Administrador";}
												            else if($row->rolusuario==2){echo "Atencion al cliente";}
												            else if($row->rolusuario==3){echo "Contabilidad";}
												            else if($row->rolusuario==4){echo "Gerente de Sucursal";}
												            else if($row->rolusuario==5){echo "Medico Veterinario";}?>" disabled>
	
	<input type="hidden" name="rol" class="form-control" value="<?php if($row->rolusuario==1){echo "Administrador";}
												            else if($row->rolusuario==2){echo "Atencion al cliente";}
												            else if($row->rolusuario==3){echo "Contabilidad";}
												            else if($row->rolusuario==4){echo "Gerente de Sucursal";}
												            else if($row->rolusuario==5){echo "Medico Veterinario";}?>" >
															
	</div>
	
	
	<div class="form-group">
	<label for="exampleInputEmail1">Contraseña elegida por el usuario</label>
	<input type="password" name="password" class="form-control" value="<?php echo $row->passwd;?>" disabled>
	<input type="hidden" name="password" class="form-control" value="<?php echo $row->passwd;?>">														
	</div>
	
											
	<div class="form-group">
	
	
	
	
	<?php } ?>
	</div>													
														
														<button type="submit" name="submit" class="btn btn-o btn-primary" >
														
														
															Crear Usuario
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
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>
