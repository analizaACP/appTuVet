<?php

session_start();

error_reporting(0);
$user_ip = $_SERVER['REMOTE_ADDR'];
include_once ('include/config.php');

if(isset($_POST['enviar']))
{
    
    
    
    $result = pg_query($db, "select * from atencioncliente where atn_iniciales='$_REQUEST[username]' and atn_passwd='$_REQUEST[password]'");
    if (!$result) {
        echo "consulta iniciales fallo";
        exit;
    }
    $result2 = pg_query($db, "select * from atencioncliente where atn_nombre='$_REQUEST[username]' and atn_passwd='$_REQUEST[password]'");
    if (!$result2) {
        echo "consulta nombre fallo";
        exit;
    }
    
    $num=0;
    while ($row = pg_fetch_row($result) || $row = pg_fetch_row($result2)) {
        $num+=1;
    
    $result3=pg_query($db, "select * from ippermitidas where ip_asignada='$user_ip'");
    if (!$result3) {
        echo "consulta ip fallo";
        exit;
    }
    
    $num2=0;
    while ($row1 = pg_fetch_row($result3) ) {
        $num2+=1;
        
    }
    
    $result4=pg_query("select id_sucursal from ippermitidas where ip_asignada='$user_ip'");
    if (!$result4) {
        echo "Ocurrió un error en el codigo de sucursal.\n";
        exit;
    }
    
    while ($row2 = pg_fetch_row($result4) ) {
        $sucursal=$row2[0];
        
    }
    
    
    
        
    }
    
    if($num>0 && $num2>0)
    {
        
        $extra="dashboard.php";//
        $_SESSION['login']=$_POST['username'];
        $_SESSION['id']=$num['id'];
        $_SESSION['sucursal']=$sucursal;
        $host=$_SERVER['HTTP_HOST'];
        $uri=rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
        header("location:http://$host$uri/$extra");
        exit();
    }

    else
    {
        $_SESSION['errmsg']="Usuario, contraseña o IP no permitida";
        $extra="index.php";
        $host  = $_SERVER['HTTP_HOST'];
        $uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
        header("location:http://$host$uri/$extra");
        exit();
    }
    
}



   
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Atencion al Cliente Login</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="" name="description" />
		<meta content="" name="author" />
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
		<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
		<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/plugins.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	</head>
	<body class="login">
		<div class="row">
			<div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
				<div class="logo margin-top-30">
				<h2>Ingreso Atencion al Cliente</h2>
				</div>

				<div class="box-login">
					<form class="form-login" method="post">
						<fieldset>
							<legend>
								Ingresar en su cuenta
							</legend>
							<p>
								Por Favor ingrese su nombre y password para ingresar.<br />
								<span style="color:red;"><?php echo htmlentities($_SESSION['errmsg']); ?><?php echo htmlentities($_SESSION['errmsg']="");?></span>
							</p>
							<div class="form-group">
								<span class="input-icon">
									<input type="text" class="form-control" name="username" placeholder="Usuario o Iniciales">
									<i class="fa fa-user"></i> </span>
							</div>
							<div class="form-group form-actions">
								<span class="input-icon">
									<input type="password" class="form-control password" name="password" autocomplete="false" placeholder="Contraseña"><i class="fa fa-lock"></i>
									 </span>
							</div>
							<div class="form-actions">
								
								<button type="submit" class="btn btn-primary pull-right" name="enviar">
									Ingresar <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
							
						</fieldset>
					</form>

					<div class="copyright">
						&copy; <span class="current-year"></span><span class="text-bold text-uppercase"> TuVet</span>. <span>2023</span>
					</div>
			
				</div>

			</div>
		</div>
		
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<script src="vendor/jquery-validation/jquery.validate.min.js"></script>
	
		<script src="assets/js/main.js"></script>

		<script src="assets/js/login.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				Login.init();
			});
		</script>
	
	</body>
	<!-- end: BODY -->
</html>