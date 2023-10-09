<?php
session_start();
error_reporting(0);
require_once "../include/config.php";
require_once "../include/checklogin.php";
check_login();
date_default_timezone_set('America/El_Salvador');
$fecha_actual=date("d-m-y h:i:s");
class InsertarCompras{

 	/*=============================================
 	 INSERTAR COMPRA
  	=============================================*/ 

    public $insertarCompra;

	public function insertarRegistroCompra(){
        $ar = $this->general_factura;
        $arrayg= json_decode($ar,true);
        $query=("INSERT INTO comprasproductos(proveedor_id, compra_condicion, compra_fecha, compra_nodoc, compra_subtotal,
            compra_montoiva, compra_montoretencion, compra_total)
        VALUES ($this->proveedor,'$this->condicion',now(),'$this->factura',$arrayg[tsubtotal],
        $arrayg[tiva], $arrayg[tretencion], $arrayg[ttotal])");
        $query=pg_query($query);
        
        if(!$query){
            echo "Error al procesar la informacion 1, ".pg_last_error($db)."";
        }
        //pg_close();
        
        if($query){
            echo "Su informacion ha sido enviada general";
            if ($this->condicion == 'Credito') {
                $query6=("UPDATE proveedores SET saldo=saldo+$arrayg[ttotal] WHERE id=$this->proveedor");
                $query6=pg_query($query6);
                if(!$query6){
                    echo "Error al procesar la informacion 6, ".pg_last_error($db)."";
                }
                $query7=("INSERT INTO extractosproveedores(prov_id, doc_numero, fecha, cargo, abono, saldo, usuario, sucursal)
                VALUES ($this->proveedor,'$this->factura',now(),$arrayg[ttotal],0, $arrayg[ttotal], '$_SESSION[login]', $_SESSION[sucursal])");
                $query7=pg_query($query7);
                if(!$query7){
                    echo "Error al procesar la informacion 7, ".pg_last_error($db)."";
                }
            }
            $arl = $this->lista_productos;
            $arrayl= json_decode($arl,true);
            $queryuid = ("select max(id) as id from comprasproductos");
            $queryuid = pg_query($queryuid);
            $queryuid = pg_fetch_object($queryuid);
            if(!$queryuid){
                echo "Error al procesar la informacion i, ".pg_last_error($db)."";
            }
            for ($i=0; $i < count($arrayl); $i++) { 
                $codbarra = strval($arrayl[$i]['codbarra']);  
                $cantidad = intval($arrayl[$i]['cantidad']);            
                $bonificacion = intval($arrayl[$i]['bonificacion']);
                $cantidad_total = intval($arrayl[$i]['cantidad_total']);
                //$costo = floatval($arrayl[$i]['costo']);
                $subtotal = $arrayl[$i]['subtotal'];
                $costo = $subtotal/$cantidad_total;
                $iva = floatval($arrayl[$i]['iva']);
                $total = floatval($arrayl[$i]['total']);
                $precio_nuevo = floatval($arrayl[$i]['precio_nuevo']);
                $costo_promedio = floatval($arrayl[$i]['prod_costopromedio']);
                $cantidad_actual = $arrayl[$i]['cantidad_actual'];
                $porc_descuento = floatval($arrayl[$i]['prod_porcdescuento']);
                $desc_unidad = ($arrayl[$i]['precio_nuevo']*$arrayl[$i]['prod_porcdescuento']);
                
                $costopro = (($cantidad_actual*$arrayl[$i]['prod_costopromedio'])+($cantidad_total*$costo))/($cantidad_actual+$cantidad_total);

                $query2=("INSERT INTO comprasdetalle(compra_id, producto_codbarra, producto_cantidad, producto_bonificacion, producto_cantidadtotal,
                    producto_costo, producto_subtotal, producto_porciva, producto_totallinea, producto_costopromedio)
                    VALUES ($queryuid->id,'$codbarra',$cantidad,$bonificacion,$cantidad_total,$costo, $subtotal,$iva,$total,$costopro)");
                $query2=pg_query($query2);

                if(!$query2){
                    echo "Error al procesar la informacion 2, ".pg_last_error($db)."";
                }

                $query3=("UPDATE productos SET prod_costopromedio=$costopro, prod_ultimocosto=$costo, prod_preciopublico=$precio_nuevo, descuento_unidad=$desc_unidad WHERE prod_codbarra='$codbarra'");
                $query3=pg_query($query3);
                
                if(!$query3){
                    echo "Error al procesar la informacion 3, ".pg_last_error($db)."";
                }
                //pg_close();
                
                if ($query3) {
                    echo "Su informacion ha sido enviada especifico";
                    // crear la fila de existencias y puntos de reorden para cada sucursal segun las sucursales existentes
                    $query4=("UPDATE existencias SET existencia = existencia + $cantidad_total WHERE codbarra='$codbarra' AND id_sucursal=$_SESSION[sucursal]");
                    $query4=pg_query($query4);

                    if(!$query4){
                        echo "Error al procesar la informacion 4, ".pg_last_error($db)."";
                    }

                    $query5=("INSERT INTO kardex(sucursal_id, codbarra, doc_no, cant_ingreso, cant_salio, fecha_transaccion)
                            VALUES ($_SESSION[sucursal],'$codbarra','$this->factura',$cantidad_total,0,now())");
                    $query5=pg_query($query5);

                    if(!$query5){
                        echo "Error al procesar la informacion 5, ".pg_last_error($db)."";
                    }
                }
            }
        }
        
    }

}

/*=============================================
ACTIVAR INSERTAR REGISTRO COMPRA
=============================================*/ 
if(isset($_POST["insertarRegistroCompras"])){
    $insertarCompra = new InsertarCompras();
    $insertarCompra -> factura = $_POST["factura"];
    $insertarCompra -> proveedor = $_POST["proveedor"];
    $insertarCompra -> condicion = $_POST["condicion"];
    $insertarCompra -> general_factura = $_POST["general_factura"];
    $insertarCompra -> lista_productos = $_POST["lista_productos"];
    $insertarCompra -> insertarRegistroCompra();
}