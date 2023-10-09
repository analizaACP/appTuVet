<?php
session_start();
error_reporting(0);
include_once('include/config.php');
include_once('include/checklogin.php');
include_once('descuentos.php');

check_login();

// Preparamos la consulta para saber cual es la ultima factura generada por este usuario en esta sucursal
$obtenerId=("SELECT id AS max_id, nombre_cliente, condicion_pago FROM hfacturas 
WHERE id_sucursal='$_SESSION[sucursal]' AND creada_por='$_SESSION[login]' 
AND id = (SELECT MAX(id) FROM hfacturas WHERE id_sucursal='$_SESSION[sucursal]' 
AND creada_por='$_SESSION[login]');");


$obtenerId=pg_query($obtenerId);
// Obtenemos el resultado
$row = pg_fetch_assoc($obtenerId);

$idfactura = $row['max_id'];
$_SESSION['idfactura']=$idfactura;

$clientenombre=$row['nombre_cliente'];
$condicion=$row['condicion_pago'];
//$variable="Ultimo id generado ".$idfactura;
//$valor_para_alert = htmlspecialchars($variable, ENT_QUOTES, 'UTF-8');
//echo "<script>alert('$valor_para_alert');</script>";
$fecha_actual=date("d-m-y h:i:s");


if (isset($_POST['modificar'])) {
    
    $registro=$_REQUEST['dataInput'];
    
    $nuevacantidad=(int)$_REQUEST['qty'];
    //poner el codigo de barra en una variable
    $consulta01=pg_query($db,"select cod_barra from dfacturas where id='$registro'");
    while($row = pg_fetch_object($consulta01)){
        $estecodigobarra=$row->cod_barra;
    }
    
    // poner el impuesto de ese codigo de barra en una variable
    $consulta02=pg_query($db,"select * from productos where prod_codbarra='$estecodigobarra'");
    while($row = pg_fetch_object($consulta02)){
        $porcimpuesto=$row->porcentaje_impuesto;
        $estecosto=$row->prod_costopromedio;
        $esinventario=$row->prod_esinventariosn;
        if($porcimpuesto>0){
            $verdadero=true;
        }
        else{
            $verdadero=false;
        }
    }
    
    $comprobarexistencias=pg_query($db,"select * from existencias where id_sucursal='$_SESSION[sucursal]' and codbarra='$estecodigobarra'");
    
    //if($comprobarexistencias){
    //  echo "<script>alert('existencias comprobadas);</script>";
    //}
    while($row = pg_fetch_object($comprobarexistencias)){
        $suficiente=$row->existencia;
        
    }
    
    if($suficiente>$nuevacantidad || $suficiente==$nuevacantidad || $esinventario==0){
        
        if($nuevacantidad>0 && $verdadero){
            $modificar=pg_query($db,"update dfacturas set cantidad='$nuevacantidad' where id='$registro' and descuento=0");
            
            $modificar2=pg_query($db,"update dfacturas set subtotal=precio*cantidad,iva=(precio*cantidad)*$porcimpuesto,
                                    total_linea=(precio*cantidad)+(precio*cantidad)*$porcimpuesto,
                                    monto_costo=cantidad*$estecosto,
                                    suma_gravado=(precio*cantidad)+(precio*cantidad)*$porcimpuesto
                                    where id='$registro' and descuento=0");
            
        }
        
        if($nuevacantidad>0 && !$verdadero){
            $modificar=pg_query($db,"update dfacturas set cantidad='$nuevacantidad' where id='$registro' and descuento=0");
            
            $modificar2=pg_query($db,"update dfacturas set subtotal=precio*cantidad,iva=(precio*cantidad)*$porcimpuesto,
                                    total_linea=(precio*cantidad)+(precio*cantidad)*$porcimpuesto,
                                    monto_costo=cantidad*$estecosto,
                                    suma_nogravado=(precio*cantidad)+(precio*cantidad)*$porcimpuesto
                                    where id='$registro' and descuento=0");
            
        }
        
        
    }
    
    $sumasubtotales=("select sum(subtotal), sum(iva), sum(total_linea), sum(monto_costo), sum(suma_nogravado), sum(suma_gravado) from dfacturas where id_hfactura='$idfactura'");
    $sumasubtotales=pg_query($db,$sumasubtotales);
    if ($sumasubtotales) {
        $fila = pg_fetch_row($sumasubtotales);
        $suma_subtotal = $fila[0];
        $suma_iva=$fila[1];
        $suma_total=$fila[2];
        $suma_costo=$fila[3];
        $suma_sumanogravado=$fila[4];
        $suma_sumagravado=$fila[5];
        
    }
    
    $guardarsubtotal=("update hfacturas set subtotal='$suma_subtotal', impuesto='$suma_iva',
        total_linea='$suma_total', costo='$suma_costo',monto_gravado='$suma_sumagravado',monto_nogravado='$suma_sumanogravado'
        where id='$idfactura'");
    $guardarsubtotal=pg_query($db,$guardarsubtotal);
    // Manejar la lógica de modificación aquí
    // Por ejemplo, obtener el valor de nuevaCantidad: $_POST['nuevaCantidad']
    // Realizar consultas y actualizaciones necesarias
    
    $contarregistros=pg_query($db,"select count(*) from dfacturas where id_hfactura='$idfactura';");
    $row = pg_fetch_row($contarregistros);
    $totalregistros = $row[0];
    
    $guardarlineas=pg_query($db,"update hfacturas set lineas_factura='$totalregistros' where id='$idfactura'");
    
    
}


if(isset($_POST['submit']))
{//comienza el primer if
    
    // variables a recuperar de la tabla productos
    $cuenta=0; //si este resultado es cero el producto no existe
    $barcode=$_POST['codigo'];
    
    // ***********revisar si es producto o servicio
    //$contarproductos=0;
    $productoOservicio=pg_query($db,"select * from productos where prod_codbarra='$barcode'");
    if(!$productoOservicio){
        //echo "<script>alert('no se puede saber si es producto o servicio');</script>";
    }
    while($row = pg_fetch_object($productoOservicio)){
        $contarproductos+=1;
        $esproducto=$row->prod_esinventariosn;
    }
    if($esproducto==0){
        //echo "<script>alert('ES SERVICIO');</script>";
            $resultado=pg_query($db,"select * from
            productos where
            prod_codbarra='$barcode' and activosn=1");
    
    }
    else{
    $resultado=pg_query($db,"select * from 
        productos join existencias on 
        prod_codbarra='$barcode' and codbarra='$barcode' and id_sucursal='$_SESSION[sucursal]' and existencia>0 and activosn=1");//agregar condicion cantidad mayor que cero
    }
        if($resultado){
            //echo "<script>alert('seleccion join exitosa');</script>";
        }
    
        //****************fin de revisar si es producto o servicio
        
        
        while($row = pg_fetch_object($resultado)){
            $cuenta+=1;
            $numerofactura=$idfactura;
            $codigobarra=$row->prod_codbarra;
            $cantidad=1;
            $precio=$row->prod_preciopublico;
            $costo=$row->prod_costopromedio;
            $porcIva=$row->porcentaje_impuesto;
            $existencias=$row->existencia;          
            $iva=$precio*$row->porcentaje_impuesto;
            $gravado=0;
            $nogravado=0;
            
            if($iva==0){
                $gravado=0;
                $nogravado=$precio;
            }
            else{
                $gravado=$precio;
                $nogravado=0;
            }
            
            
        }
    
       if($cuenta>0){   //linea 75
            
            //verificar si existe factura con ese articulo y sumarlo a la cantidad
            
            $verificar=pg_query($db,"select * from dfacturas where id_hfactura='$numerofactura' 
                                        and cod_barra='$codigobarra' and descuento=0"); //si el descuento esta marcado 1 no ejecuta la consulta
            $cuenta02=0;
            while($row = pg_fetch_object($verificar)){
                $cuenta02+=1;
                if($row->cantidad < $existencias || $esproducto==0){
                    $sumar=$row->cantidad;
                }
                else{
                    $sumar=0;
                }
                //$precio02=$row->precio;
                //$iva02=$row->iva;
                
            }
            if($cuenta02>0 && $sumar!=0){  // actualizar
                //echo "<script>alert('ya existe una factura con ese codigoo y ese articulo');</script>";
                $actualizar=pg_query($db,"update dfacturas set cantidad=$sumar+1, subtotal=($sumar+1)*precio,
                                            iva=(($sumar+1)*precio)*$porcIva,
                                            total_linea=(($sumar+1)*precio)+((($sumar+1)*precio)*$porcIva),
                                            monto_costo=monto_costo+$costo,
                                            suma_nogravado=suma_nogravado+$nogravado,
                                            suma_gravado=suma_gravado+$gravado
                                            where cod_barra='$codigobarra' and id_hfactura='$numerofactura'");
                if(!$actualizar){
                    echo "<script>alert('no se actualizo la cantidad');</script>";
                }
            }
            
            else{
                $guardar=("insert into dfacturas (fecha,id_hfactura,cod_barra,cantidad,precio,iva,subtotal,
                total_linea,validado_sn,monto_costo,suma_nogravado, suma_gravado)
            values (to_date('$fecha_actual','dd/mm/yy'),'$idfactura','$barcode','$cantidad','$precio','$iva',
            $precio*$cantidad,$precio+$iva,0,'$costo',$nogravado,$gravado)");
                $guardar=pg_query($guardar);
                
                if(!$guardar){
                    echo "<script>alert('no se guardaron los datos');</script>";
                }
            }
                
       // sumar los subtotales y guardarlas en el encabezado
            
       $sumasubtotales=("select sum(subtotal), sum(iva), sum(total_linea), sum(monto_costo), sum(suma_nogravado), sum(suma_gravado) from dfacturas where id_hfactura='$idfactura'");
       $sumasubtotales=pg_query($db,$sumasubtotales);
       if ($sumasubtotales) {
           $fila = pg_fetch_row($sumasubtotales);
           $suma_subtotal = $fila[0];
           $suma_iva=$fila[1];
           $suma_total=$fila[2];
           $suma_costo=$fila[3];
           $suma_sumanogravado=$fila[4];
           $suma_sumagravado=$fila[5];
           
       }
       
        $guardarsubtotal=("update hfacturas set subtotal='$suma_subtotal', impuesto='$suma_iva', 
        total_linea='$suma_total', costo='$suma_costo',monto_gravado='$suma_sumagravado',monto_nogravado='$suma_sumanogravado'  
        where id='$idfactura'");
        $guardarsubtotal=pg_query($db,$guardarsubtotal);
        
        
        $contarregistros=pg_query($db,"select count(*) from dfacturas where id_hfactura='$idfactura';");
        $row = pg_fetch_row($contarregistros);
        $totalregistros = $row[0];
        
        $guardarlineas=pg_query($db,"update hfacturas set lineas_factura='$totalregistros' where id='$idfactura'");
        
        
        
        
            
        }//cierre de linea 75
        
        
        else{
            echo "<script>alert('producto no existe o no hay existencias');</script>";
        }
        
        
    pg_close();
    
    
    
     echo "<script>window.location.href='facturacion.php'</script>";
}//termina el primer if



if(isset($_GET['del']))
{
    $query2=("delete from dfacturas where id = '".$_REQUEST['id']."'");
    $query2=pg_query($query2);
    if(!$query2){
        echo "<script>alert('Error hay registros en otras tablas que dependen de este registro');</script>";
    }
    
    $sumasubtotales=("select sum(subtotal), sum(iva), sum(total_linea), sum(monto_costo), 
                        sum(suma_nogravado), sum(suma_gravado), sum(monto_descuento) 
                        from dfacturas where id_hfactura='$idfactura'");
    $sumasubtotales=pg_query($db,$sumasubtotales);
    if ($sumasubtotales) {
        $fila = pg_fetch_row($sumasubtotales);
        $suma_subtotal = $fila[0];
        $suma_iva=$fila[1];
        $suma_total=$fila[2];
        $suma_costo=$fila[3];
        $suma_sumanogravado=$fila[4];
        $suma_sumagravado=$fila[5];
        $suma_descuentos=$fila[6];
        
    }
    
    $guardarsubtotal=("update hfacturas set subtotal='$suma_subtotal', impuesto='$suma_iva',
                        total_linea='$suma_total', costo='$suma_costo',monto_gravado='$suma_sumagravado',
                        monto_nogravado='$suma_sumanogravado',descuentos='$suma_descuentos'
                        where id='$idfactura'");
    $guardarsubtotal=pg_query($db,$guardarsubtotal);
    
    $contarregistros=pg_query($db,"select count(*) from dfacturas where id_hfactura='$idfactura';");
    $row = pg_fetch_row($contarregistros);
    $totalregistros = $row[0];
    
    $guardarlineas=pg_query($db,"update hfacturas set lineas_factura='$totalregistros' where id='$idfactura'");
    
    $revisar=pg_query($db,"select * from dfacturas where id_hfactura='$idfactura'");
    $contador01=0;
    while($row = pg_fetch_object($revisar)){
        $contador01+=1;
    }
    
    if($contador01==0){
        //echo "<script>alert('ya no quedan datos en dfacturas');</script>";
        $guardarsubtotal=("UPDATE hfacturas SET subtotal=0, impuesto=0,
        total_linea=0, costo=0,monto_gravado=0,monto_nogravado=0, descuentos=0
        WHERE id='$idfactura'");
        $guardarsubtotal=pg_query($db,$guardarsubtotal);
        
        
        $contarregistros=pg_query($db,"select count(*) from dfacturas where id_hfactura='$idfactura';");
        $row = pg_fetch_row($contarregistros);
        $totalregistros = $row[0];
        
        $guardarlineas=pg_query($db,"update hfacturas set lineas_factura='$totalregistros' where id='$idfactura'");
    }
    
    
    //$_SESSION['msg']="datos eliminados !!";
    
    
    echo "<script>window.location.href='facturacion.php'</script>";
    
}// fin del if(del)

    



?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Facturacion</title>
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
        
        <!-- CSS del cuadro de Subtotales -->
        <style>
    /* Estilos CSS para el div con clase "panel-subtotales" y sus elementos hijos */
    .panel-subtotales {
        /* Estilos para el div con clase "panel-subtotales" */
        background-color: #f0f0f0;
        padding: 10px;
        border: 1px solid #ccc;
        /* Configuración del tamaño y alineación */
        width: 500px; /* Tamaño del div */
        margin-left: auto; /* Alinear a la derecha */
        text-align: left; /* Alinear el contenido al centro dentro del div */
    }

    .panel-subtotales h4 {
        /* Estilos para los elementos h3 dentro del div con clase "panel-subtotales" */
        margin-bottom: 5px;
        color: #333;
        display: inline-block; /* Mostrar en la misma línea */
    }
    .panel-subtotales input {
        /* Estilos para los elementos input dentro del div con clase "panel-subtotales" */
        display: inline-block; /* Mostrar en la misma línea */
        text-align: right; /* Alinear el contenido a la derecha */
        float: right; /* Alinear a la derecha */
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
									<h1 class="mainTitle">Nueva Factura</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span><?php echo $_SESSION['login']?></span>
									</li>
									<li class="active">
										<span>Facturando</span>
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
													
													<h3><strong>Nombre del Cliente:<?php echo " ".$clientenombre;?> </strong></h3>
													
													<h3>Condicion de Pago: <?php echo " ".$condicion;?></h3>
													<h3>Referencia Numero:<?php echo " ".$idfactura;?></h3>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
								<?php echo htmlentities($_SESSION['msg']="");?></p>	
													<form role="form" name="productos" method="post" >
														<div class="form-group">
 <label for="inputData">
 Codigo del Producto:</label>
 <input id="codigo" type="text" name="codigo" class="form-control"  autocomplete="off" placeholder="Ingrese el codigo del producto" autofocus>
 </div>
        
        <button id="submit" type="submit" name="submit" hidden="true" >
															Submit
														</button>
 													</form>
													
	<!-- funcion para prevenir que al presionar la tecla enter se envie el formulario -->												
													
													
													
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
									<h5 class="over-title margin-bottom-15"><span class="text-bold">Detalle de la Factura </span></h5>
									
									<table class="table table-hover" id="sample-table-1">
										<thead>
											<tr>
												<th class="center">#</th>
												<th>Producto</th>
												<th>Existencia</th>
												<th>Cantidad</th>
												<th>Precio U</th>
												<th>Descuento</th>
												<th>Monto Desc.</th>
												<th>subtotal</th>	
												<th>IVA</th>
												
												<th>total Linea</th>
																							
												
											</tr>
										</thead>
										<tbody>
<?php
$sql=pg_query($db,"select prod_codbarra, dfacturas.id, prod_nombre, cantidad,prod_porcdescuento, precio, subtotal, iva, descuento, total_linea,monto_descuento, existencia from productos
                    join dfacturas on id_hfactura='$idfactura' and cod_barra=prod_codbarra
                    join existencias on id_sucursal='$_SESSION[sucursal]' and codbarra=prod_codbarra order by productos.prod_nombre");
$cnt=1;
while($row = pg_fetch_object($sql))
{
?>

											<tr>
												<td class="center"><?php echo $cnt;?>.</td>
												<!-- <td class="hidden-xs"><?php echo $row->id;?></td> -->
												<td style="font-size:120%"><?php echo $row->prod_nombre;?></td>
												<td align="center"><?php echo $row->existencia;?></td>
												<td><input type="text" disabled value="<?php echo $row->cantidad;?>"style="width: 100px; text-align:center;font_size:100%"/></td>
												<td align="center"><?php echo "$".$row->precio;?></td>
												<td align="center">
  <form action="" method="post">
    <input type="hidden" name="rowId" value="<?php echo $row->id; ?>">
    <input type="hidden" name="barraId" value="<?php echo $row->prod_codbarra; ?>">
    <input type="hidden" name="descSN" value="<?php echo $row->descuento; ?>">
    <input type="hidden" name="action" value="marcarDesmarcar">
    <label for="descuentoCheckbox"><strong><?php echo ($row->prod_porcdescuento*100);?>%</strong></label>
<input type="checkbox" id="descuentoCheckbox" <?php if($row->descuento == 1){ ?>checked<?php } ?> onchange="this.form.submit()">

  </form>
</td>
												<td align="center"><?php echo "$".$row->monto_descuento;?></td>
												<td align="center"><?php echo "$".$row->subtotal;?></td>
												<td align="center"><?php echo "$".$row->iva;?></td>
												<td style="font-size:120%" align="center"><?php echo "$".$row->total_linea;?></td>
												<td>
												<div class="visible-md visible-lg hidden-sm hidden-xs">
												
												
												
												
												
							<<!-- Diálogo con clase "miDialogo" y atributo personalizado "data-id" -->


<!-- Enlaces con clase "abrirDialogo" y atributo personalizado "data-id" -->
<a href="#" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit" onclick="openDialog(<?php echo $row->id; ?>)"><i class="fa fa-pencil"></i></a>





	<a href="facturacion.php?id=<?php echo $row->id?>&del=delete" onClick="return confirm('Seguro quiere borrar esta linea?')"class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
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
<?php 									
$totales=pg_query($db,"select * from hfacturas where id='$idfactura'");
if(!$totales){
    echo "<script>alert('no se pudo consultar la tabla de totales');</script>";
}

$contador=1;
while($row = pg_fetch_object($totales))
{
    ?>


									
<div class="panel-subtotales">
																					
														
	<h4>Sub-Total</h4>
<input type="text" name="muestra" style="font-size:120%; font-weight:bold;" disabled value="$<?php echo number_format($row->subtotal + $row->descuentos, 2); ?>"><br><br>

	<h4>Descuentos</h4><input type="text" name="muestra2" disabled style="font-size:120%; font-weight:bold;" value="$<?php echo $row->descuentos;?>"><br><br>
	<h4>Total con Descuento</h4><input type="text" name="muestra3" style="font-size:120%; font-weight:bold;" disabled value="$<?php echo $row->subtotal;?>"><br><br>
	<h4>Monto Gravado</h4><input type="text" name="muestra4" disabled style="font-size:120%; font-weight:bold;" value="$<?php echo $row->monto_gravado;?>"><br><br>
	<h4>Monto Exento</h4><input type="text" name="muestra5" disabled style="font-size:120%; font-weight:bold;" value="$<?php echo $row->monto_nogravado;?>"><br><br>
	<h4>Impuestos</h4><input type="text" name="muestra6" disabled style="font-size:120%; font-weight:bold;" value="$<?php echo $row->impuesto;?>"><br><br>
	<h4>TOTAL</h4><input type="text" name="muestra7	" disabled style="font-size:120%; font-weight:bold;" value="$<?php echo $row->total_linea;?>"><br><br>
	
	<form role="form" name="guardar" id="guardar" method="post" action="agregar_id_factura.php">
    <button type="submit" name="Guardar" class="btn btn-o btn-primary">Guardar y Nueva</button>
</form>

	
	
										
</div>
												
									
									
<?php 
$contador=$contador+1;
 }?>									
									
									
									
									
									
									
									
									
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
		<!-- Script para manejar el diálogo -->
    
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});
		</script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
<dialog id="dataDialog">
    <form role="form" name="modificar" id="dataForm" method="post" >
        <!-- Aquí colocarías los campos para ingresar datos -->
        <input hidden="true" type="text" name="dataInput" id="dataInput">
        <div class="form-group">
            <label for="inputData">Ingrese Cantidad
                <input type="number" name="qty" class="form-control" autocomplete="off" min="0" value="0">
            </label>
        </div>
        <!-- Otros campos aquí -->

        <button type="submit" name="modificar" class="btn btn-o btn-primary">Modificar</button>
        <button type="button" class="btn btn-secondary" onclick="closeDialog()">Cancelar</button>
    </form>

    <div id="tableResult">
        <!-- Aquí se mostrarían los resultados de la consulta -->
    </div>

    	
</dialog>


<script>
    function openDialog(id) {
        var dialog = document.getElementById("dataDialog");
        dialog.showModal();
        
        // Puedes usar el valor de 'id' para lo que necesites en el formulario
        document.getElementById("dataInput").value = id;
    }
    
    function closeDialog() {
        var dialog = document.getElementById("dataDialog");
        dialog.close();
    }
    
    
</script>
	</body>
</html>

<?php 




?>





