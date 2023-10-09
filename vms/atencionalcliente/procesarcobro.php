<?php

session_start();
error_reporting(0);
include('include/checklogin.php');
check_login();
$fecha_actual = date("d-m-y h:i:s");
$hora_actual = date("H:i:s");

function imprimir($numerofact,$idFactura){
    //echo "ingresando a la funcion";
    $db = pg_connect("host=localhost port=5432 dbname=tuvet user=adolfo password=ofloda01");
    include('library/tcpdf.php');
    
    
    
    //consulta encabezado
    $numerofactura=$numerofact;
    $encabezado=pg_query($db,"select * from hfacturas h join clientes c on h.nombre_cliente=c.cliente_nombre where h.numero_impreso='$numerofactura'");
    
    $row = pg_fetch_object($encabezado);
    $fecha=$row->fecha_cobro;
    $nit=$row->cliente_nodoc;
    $cliente=$row->nombre_cliente;
    $direccion1=$row->cliente_dir1;
    $direccion2=$row->cliente_dir2;
    $giro=$row->cliente_giro;
    $subtotal=$row->subtotal;
    $gravado=$row->monto_gravado;
    $descuento=$row->descuentos;
    $impuesto=$row->impuesto;
    $exento=$row->monto_nogravado;
    $total=$row->total_linea;
    
    
    //echo "creabdo el pdf";
    
    $pdf = new TCPDF('P', 'mm', array(95, 230)); // 'P' para orientación vertical    //$pdf->setTempDir('/pdftemporal'); // Ruta temporal
    $pdf->SetMargins(5, 5, 5); // Márgenes de 10 mm en cada lado
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();
    
    $pdf->SetFont('courier', 8);
    
    $pdf->SetY(3);
    $pdf->Cell(83, 5, $numerofactura, 0, 1, 'C');
    
    //imprimir encabezado de la factura
    $pdf->SetY(22);
    $pdf->Cell(83, 5, $fecha." Id No. ".$nit, 0, 1, 'L');
    $pdf->Cell(83, 5, $cliente, 0, 1, 'L');
    $pdf->Cell(83, 5, $direccion1." ".$direccion2, 0, 1, 'L');
    //titulos 
    $pdf->SetY(40);
    $pdf->Cell(30, 5, "Nombre Producto", 0, 1, 'L');
    $pdf->Cell(30, 5, "Cantidad---Precio---Descuentos", 0, 1, 'L');
    $pdf->Cell(30, 5, "P. Neto---IVA---Total Linea", 0, 1, 'L');
    // imprimir detalle de la factura
    
    $detalle=pg_query($db,"select * from dfacturas d join productos p on d.cod_barra=p.prod_codbarra where id_hfactura='$idFactura'");
    $pdf->SetY(60);
    while ($row_detalle = pg_fetch_object($detalle)) {
        $nombreLinea = $row_detalle->prod_nombre;
        $cantLinea = $row_detalle->cantidad;
        $precioLinea = "$" . number_format($row_detalle->precio, 2);
        $descLinea = "$" . number_format($row_detalle->descuento, 2);
        $precioDescuento = "$" . number_format($row_detalle->precio - $row_detalle->monto_descuento, 2);
        $subtotLinea = "$" . number_format($row_detalle->subtotal, 2);
        $ivaLinea = "$" . number_format($row_detalle->iva, 2);
        $totLinea = "$" . number_format($row_detalle->total_linea, 2);
        
        $pdf->Cell(75, 5, $nombreLinea, 0, 0, 'L');
        $pdf->Ln();
        
        $pdf->Cell(30, 5, $cantLinea, 0, 0, 'L');
        $pdf->Cell(30, 5, $precioLinea, 0, 0, 'L');
        $pdf->Cell(30, 5, $descLinea, 0, 0, 'L');
        $pdf->Ln();
        
        $pdf->Cell(30, 5, $precioDescuento, 0, 0, 'L');
        $pdf->Cell(30, 5, $ivaLinea, 0, 0, 'L');
        $pdf->Cell(30, 5, $totLinea, 0, 1, 'L');
    }
    
    // Imprimir pie de la factura con números formateados
    $pdf->SetY(145);
    $pdf->Cell(45, 5, "SubTotal $" . number_format($subtotal, 2), 0, 0, 'L');
    $pdf->Cell(45, 5, "Desc $" . number_format($descuento, 2), 0, 1, 'L');
    $pdf->Ln();
    $pdf->Cell(45, 5, "Gravado $" . number_format($gravado, 2), 0, 0, 'L');
    $pdf->Cell(45, 5, "Impuesto $" . number_format($impuesto, 2), 0, 1, 'L');
    $pdf->Ln();
    $pdf->Cell(45, 5, "Exento $" . number_format($exento, 2), 0, 0, 'L');
    $pdf->Cell(45, 5, "Total $" . number_format($total, 2), 0, 1, 'L');
    
    
    
    $pdf->SetLineWidth(0.1); // Ancho de línea en milímetros
    
    $pdfContent = $pdf->Output('', 'S');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="FACTURA_' . $numerofactura . '.pdf"');
    
    echo $pdfContent;
    
   
}//fin de la funcion imprimir

$db = pg_connect("host=localhost port=5432 dbname=tuvet user=adolfo password=ofloda01");


$fecha_actual=date("d-m-y h:i:s");

if(isset($_POST['cobrar'])){
    $sepuedeprocesar = true;
    $facturaId = $_REQUEST['idFactura'];
    $facturaimprimir=$_REQUEST['factura'];
    $pagoefectivo=0;
    $pagotarjeta=0;
    $pagocredito=0;
    $tipodepago=$_REQUEST['tipodepago'];
    $sucursal=$_SESSION['sucursal'];
    $condicion=$_REQUEST['condicion'];
    $nombre=$_REQUEST['nombreCliente02'];
    
    
    switch($tipodepago){
        case "Efectivo":
            $pagoefectivo=$_REQUEST['copiaefectivo'];
            $pagotarjeta=0;
            $pagocredito=0;
            break;
        case "Tarjeta":
            $pagoefectivo=0;
            $pagotarjeta=$_REQUEST['copiatarjeta'];
            $pagocredito=0;
            break;
        case "Tarjeta/Efectivo":
            $pagoefectivo=$_REQUEST['copiaefectivo'];
            $pagotarjeta=$_REQUEST['copiatarjeta'];
            $pagocredito=0;
            break;
        case "Credito":
            $pagoefectivo=0;
            $pagotarjeta=0;
            $pagocredito=$_REQUEST['total02'];
            break;
    }
    $autorizacion=$_REQUEST['autorizacion'];
    $total=$_REQUEST['total02'];
    
    // Poner las cantidades de dfacturas en un array
    $cantidadbuscar = pg_query($db, "SELECT * FROM dfacturas WHERE id='$facturaId'");
    if($cantidadbuscar){
        while ($fila = pg_fetch_assoc($cantidadbuscar)) {
            $cantidadb = $fila['cantidad'];
            $codbarrab = $fila['cod_barra'];
            $sucursalb = $_SESSION['sucursal'];
            
            $cantidadcomparar = pg_query($db, "SELECT existencia FROM existencias WHERE id_sucursal='$sucursalb' AND codbarra='$codbarrab'");
            if($cantidadcomparar) {
                
                
                $row = pg_fetch_object($cantidadcomparar);
                $existencia = $row->existencia;
                if($existencia < $cantidadb){
                    $sepuedeprocesar = false;
                    //revisar si es servicio
                    $esservicio=pg_query($db, "SELECT prod_esinventariosn FROM productos WHERE prod_codbarra='$codbarrab'");
                    $row = pg_fetch_object($esservicio);
                    $esinventario=$row->prod_esinventariosn;
                    
                    
                    if($esinventario==0){
                        $sepuedeprocesar = true;
                    }
                    else{
                        break; // Si ya se detectó que no hay suficientes existencias, no es necesario seguir comparando.
                    }
                    }
            } else {
                //echo "Error en la consulta de existencias.";
            }
        }
    } else {
        //echo "Error en la conexión a la base de datos.";
    }
    
    
    $numeroimpreso=pg_query($db,"update hfacturas set numero_impreso='$facturaimprimir' where id='$facturaId'");
    if(!$numeroimpreso){
        $sepuedeprocesar=false;
        
    }
    
    
    
    if ($sepuedeprocesar) {
        
        //echo "El cobro puede ser procesado.";
        
        // sustituir los valores de la tabla hfacturas con los valores del formulario
        $actualizar=pg_query($db,"update hfacturas set numero_impreso='$facturaimprimir',
                                    numero_autorizacion='$autorizacion',pagada_sn=1, cobrada_por='$_SESSION[login]',
                                        pago_efectivo='$pagoefectivo',
                                        hora_final='$hora_actual',
                                        pago_tarjeta='$pagotarjeta',
                                        pago_credito='$pagocredito',
                                        fecha_cobro=to_date('$fecha_actual','dd/mm/yy') where id='$facturaId'");
        
        
        // sustituir los valores de la tabla dfacturas con los valores del formulario
        
        $validar=pg_query($db,"update dfacturas set validado_sn=1 where id_hfactura='$facturaId'");
        
        //restar las existencias de los productos en la tabla existencias
        
        $buscarcantidades = pg_query($db, "SELECT * FROM dfacturas WHERE id_hfactura='$facturaId'");
        //if($buscarcantidades){
          //  echo "<script>alert('cantidades comprobadas');</script>";
        //}
        while ($fila = pg_fetch_assoc($buscarcantidades)) {
            
            $restar = $fila['cantidad'];
            $barcode=$fila['cod_barra'];
            
            $servicio=pg_query($db, "SELECT prod_esinventariosn FROM productos WHERE prod_codbarra='$barcode'");
            $row = pg_fetch_object($servicio);
            $inventario=$row->prod_esinventariosn;
            
            if($inventario==1){
                $actualizarexistencias = pg_query($db, "UPDATE existencias SET existencia = existencia-$restar WHERE id_sucursal = '$sucursal' and codbarra='$barcode'");
                $kardex=pg_query($db,"insert into kardex (sucursal_id,fecha_transaccion,codbarra,doc_no,cant_ingreso,cant_salio, id_hfactura) values
                                                    ('$sucursal',to_date('$fecha_actual','dd/mm/yy'),'$barcode','$facturaimprimir',0,$restar,$facturaId)");
            
            if(!$actualizarexistencias){
                //echo "<script>alert('existencias NO actualizadas');</script>";
            }
            }
            
        }// while de las cantidades
        //si la factura es al credito cargar el saldo al cliente
        
        $consulta = pg_query($db, "SELECT SUM(cargo) as suma_cargo, SUM(abono) as suma_abono FROM extractosclientes WHERE nombre_cliente = '$nombre'");
        $resultado = pg_fetch_assoc($consulta);
        $versaldo = $resultado['suma_cargo'] - $resultado['suma_abono'];
        
        if($condicion=='Credito '){
            $verIdCliente=pg_query($db,"select id_cliente from hfacturas where id='$facturaId'");
            $row = pg_fetch_object($verIdCliente);
            $clienteId=$row->id_cliente;
            
            
            
            $cargo=pg_query($db,"insert into extractosclientes (doc_numero, fecha, cargo, abono, saldo, usuario, sucursal,nombre_cliente,cliente_id ) 
                            values('$facturaimprimir',to_date('$fecha_actual','dd/mm/yy'),'$pagocredito',
                                    0,$versaldo+$pagocredito,'$_SESSION[login]','$_SESSION[sucursal]','$nombre','$clienteId')");
            
            $saldocliente=pg_query($db,"update clientes set cliente_saldos=cliente_saldos+'$pagocredito' where cliente_nombre='$nombre'");
            
            
        }
        
             
        imprimir($facturaimprimir, $facturaId);
        
       echo "<script>window.location.href='cobros.php'</script>";
               
    } else {
        echo "<script>alert('Verifique existencias o numero de factura');</script>";
        echo "<script>window.location.href='cobros.php'</script>";
    }
    
    
       
}


 
?>




