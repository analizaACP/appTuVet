<?php

session_start();
error_reporting(0);
include_once ('include/config.php');
include ('include/checklogin.php');
check_login();
$fecha_actual=date("d-m-y h:i:s");



if(isset($_POST['anular'])){
    
    
    //marcar la factura como anulada
    
    $facturaAnular=$_SESSION['$anular'];
    
    echo "factura impresa ".$noFactura;
    
    $seleccionarHfactura=pg_query($db,"update hfacturas set anulada=1, anulada_por='$_SESSION[login]', 
                                        fecha_anulacion=to_date('$fecha_actual','dd/mm/yy') where id='$facturaAnular'");
   
    $seleccionarDfactura=pg_query($db,"update dfacturas set anulada_sn=1 where id_hfactura='$facturaAnular'");
    
    $actualizarKardex=pg_query($db,"UPDATE kardex SET anulada=1 WHERE id_hfactura='$facturaAnular'");
    
    
    //devolver las cantidades al inventario
    
    
    $buscarCantidades="select * from dfacturas where id_hfactura='$facturaAnular'";
    $result = pg_query($db, $buscarCantidades);
    
    if($result){
    while ($fila = pg_fetch_assoc($result)) {
        $codBarra=$fila['cod_barra'];
        $cantidad=$fila['cantidad'];
        $sucursal=$_SESSION['sucursal'];
       
        
        $query = pg_query($db,"UPDATE existencias SET existencia = existencia + $cantidad WHERE codbarra = '$codBarra' AND id_sucursal = '$sucursal';");
        
        if (!$query) {
            echo "<script>alert('error en la consulta');</script>";
        } else {
            echo "Consulta ejecutada con éxito.";
        }
        
        
    }
    }
    
    echo "<script>alert('factura ha sido anulado');</script>";
    
    
    

    }


echo "<script>window.location.href='dashboard.php'</script>";


?>