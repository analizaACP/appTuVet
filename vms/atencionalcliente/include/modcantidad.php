<?php

session_start();
error_reporting(0);
include_once('include/config.php');
include_once('include/checklogin.php');
include_once('descuentos.php');
include_once('include/modcantidad.php');
check_login();

    //echo  "<script>alert('no se puede saber si es producto o servicio')</script>";


if (isset($_POST['modificar'])) {
    
    $registro=$_REQUEST['dataInput'];
    echo "$registro";
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
    
    if($suficiente>$nuevacantidad || $suficiente==$nuevacantidad){
        
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
    
}



?>




