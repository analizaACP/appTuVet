<?php 

session_start();
error_reporting(0);
include_once('include/config.php');
include_once('include/checklogin.php');

// ... Otras inclusiones y configuraciones necesarias ...





if(isset($_POST['action']) && $_POST['action'] === 'marcarDesmarcar') {//if del descuento
    $descSN=$_POST['descSN'];
    $rowId = $_POST['rowId'];
    $rowBarra=$_POST['barraId'];
    // Realizar la conexión a la base de datos y realizar la consulta según la acción
    
    if ($descSN == 1) {//rutina al desmarcar el descuento
        // Realizar consulta para desmarcar el descuento en la base de datos
        //echo "<script>alert('poniendole el 0 al descuento');</script>";
        
        //insertar el monto de descuento en el detalle de facturas
        $montodescuento=pg_query($db,"select * from productos where prod_codbarra='$rowBarra'");
        while($row = pg_fetch_object($montodescuento)){//inicia el while
            //$contarproductos+=1;
            $descuento=0;
            $impuestos=$row->porcentaje_impuesto;
            
        }//fin del while
        $desmarcar=pg_query($db,"update dfacturas set descuento=0, monto_descuento=$descuento*cantidad where id='$rowId'");
        
        if($impuestos>0){//if si producto paga iva
            $actualizartotal=pg_query($db,"update dfacturas set subtotal=(precio*cantidad)-monto_descuento,
                                        iva=((precio*cantidad)-monto_descuento)*$impuestos,
                                        total_linea=((precio*cantidad)-monto_descuento)+((precio*cantidad)-monto_descuento)*$impuestos,
                                        suma_gravado=((precio*cantidad)-monto_descuento)
                                        where id='$rowId'");
            
        }//fin del if si el producto paga iva
        
        else{//opcion si el producto no paga iva
            $actualizartotal=pg_query($db,"update dfacturas set subtotal=(precio*cantidad)-monto_descuento,
                                        iva=((precio*cantidad)-monto_descuento)*$impuestos,
                                        total_linea=((precio*cantidad)-monto_descuento)+((precio*cantidad)-monto_descuento)*$impuestos,
                                        suma_nogravado=((precio*cantidad)-monto_descuento)+((precio*cantidad)-monto_descuento)*$impuestos
                                        where id='$rowId'");
            
        }//fin si el producto no paga iva
        
        
        
        
        
    }// fin de la rutina al desmarcar el descuento
    
    else {// rutina al marcar el descuento
        // Realizar consulta para marcar el descuento en la base de datos
        
        
        //insertar el monto de descuento en el detalle de facturas
        $montodescuento=pg_query($db,"select * from productos where prod_codbarra='$rowBarra'");
        while($row = pg_fetch_object($montodescuento)){//inicia el while
            //$contarproductos+=1;
            $descuento=$row->descuento_unidad;
            $impuestos=$row->porcentaje_impuesto;
            
        }//fin del while
        
        $marcar=pg_query($db,"update dfacturas set descuento=1, monto_descuento=$descuento*cantidad where id='$rowId'");
        
        if($impuestos>0){//if si producto paga iva
            $actualizartotal=pg_query($db,"update dfacturas set subtotal=subtotal-monto_descuento,
                                        iva=(subtotal-monto_descuento)*$impuestos,
                                        total_linea=(subtotal-monto_descuento)+(subtotal-monto_descuento)*$impuestos,
                                        suma_gravado=(subtotal-monto_descuento)
                                        where id='$rowId'");
            
        }//fin del if si el producto paga iva
        
        else{//opcion si el producto no paga iva
            $actualizartotal=pg_query($db,"update dfacturas set subtotal=subtotal-monto_descuento,
                                        iva=(subtotal-monto_descuento)*$impuestos,
                                        total_linea=(subtotal-monto_descuento)+(subtotal-monto_descuento)*$impuestos,
                                        suma_nogravado=(subtotal-monto_descuento)+(subtotal-monto_descuento)*$impuestos
                                        where id='$rowId'");
            
        }//fin si el producto no paga iva
        
        
        
        
        
    }// fin de la rutina al marcar el descuento
    
    //sumar los subtotales del detalle de facturas y pasarlos al encabezado
    
    $sumasubtotales=("select sum(subtotal), sum(iva), sum(total_linea),
                        sum(monto_costo), sum(suma_nogravado), sum(suma_gravado), sum(monto_descuento) from dfacturas where id_hfactura='$_SESSION[idfactura]'");
    $sumasubtotales=pg_query($db,$sumasubtotales);
    //if(!$sumasubtotales){
      //  echo "<script>alert('consulta no ejecutada');</script>";
   // }
    if ($sumasubtotales) {//if si se ejecuta la consulta se definen las variables donde se almacena los totales
        $fila = pg_fetch_row($sumasubtotales);
        $suma_subtotal = $fila[0];
        $suma_iva=$fila[1];
        $suma_total=$fila[2];
        $suma_costo=$fila[3];
        $suma_sumanogravado=$fila[4];
        $suma_sumagravado=$fila[5];
        $suma_descuento=$fila[6];
        
    }// fin del if de las variables
    
    $guardarsubtotal=("update hfacturas set subtotal='$suma_subtotal', impuesto='$suma_iva',
        total_linea='$suma_total', costo='$suma_costo',monto_gravado='$suma_sumagravado',
        monto_nogravado='$suma_sumanogravado',descuentos=$suma_descuento
        where id='$_SESSION[idfactura]'");
    $guardarsubtotal=pg_query($db,$guardarsubtotal);
    
    
}//fin del if del descuento

?>

    


