<?php

include_once('include/config.php');
include_once('include/checklogin.php');
$fecha_actual=date("d-m-y h:i:s");
$idfactura=$_SESSION['idfactura'];

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


?>

