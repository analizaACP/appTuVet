<?php

session_start();
error_reporting(0);
include_once ('include/config.php');
include ('include/checklogin.php');
check_login();

$fechaHoraActual = date("Y-m-d_H-i-s");

if(isset($_POST['submit'])){
    
    $fechaInicial = $_REQUEST['fechainicial'];
    $fechaFinal = $_REQUEST['fechafinal'];
    $sucursal=$_SESSION['sucursal'];
    
    $detalledeventas = pg_query($db, "SELECT
    h.fecha_creacion, h.fecha_cobro, h.id_sucursal, h.hora_inicio, h.hora_final, h.numero_impreso, h.nombre_cliente,
    d.cod_barra, d.cantidad, d.monto_costo, d.monto_descuento, d.total_linea,
    p.prod_nombre, p.prod_preciopublico,
    m.marca_nombre,
    c.cat_nombre,
    v.prov_nombre
FROM hfacturas h
JOIN dfacturas d ON h.id = d.id_hfactura
JOIN productos p ON d.cod_barra = p.prod_codbarra
JOIN marcas m ON p.id_marca = m.id
JOIN categorias c ON p.id_categorias = c.id
JOIN proveedores v ON m.proveedor_id = v.id
WHERE h.fecha_cobro BETWEEN '$fechaInicial' AND '$fechaFinal' and h.anulada<>1    
ORDER BY h.numero_impreso");
    
    $reporte="detalledeventas_" . $fechaHoraActual ." Sucursal ".$sucursal .".xls";
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename='.$reporte);
    header('Pragma: no-cache');
    header('Expires: 0');
    
    echo '<table border="1">';
    echo '<tr>';
    echo '<th colspan="17">Reporte Detalle de Ventas desde ' . $fechaInicial . ' hasta ' . $fechaFinal . '</th>';
    
    echo '</tr>';
    echo '<tr>
    <th>Fecha Creacion Factura</th>
    <th>Fecha Cobro</th>
    <th>Hora Inicio</th>
    <th>Hora Final</th>
    <th>Sucursal</th>
    <th>Numero Factura</th>
    <th>Cliente</th>
    <th>Codigo</th>
    <th>Nombre Producto</th>
    <th>Marca</th>
    <th>Categoria</th>
    <th>Proveedor</th>
    <th>Cantidad</th>
    <th>Precio Marcado</th>
    <th>Costo</th>
    <th>Descuentos</th>
    <th>Total Linea</th>
    </tr>';
    while($row = pg_fetch_object($detalledeventas)){
        
        echo '<tr>';
        echo '<td>'.$row->fecha_creacion.'</td>';
        echo '<td>'.$row->fecha_cobro.'</td>';
        echo '<td>'.$row->hora_inicio.'</td>';
        echo '<td>'.$row->hora_final.'</td>';
        echo '<td>'.$row->id_sucursal.'</td>';
        echo '<td>'.$row->numero_impreso.'</td>';
        echo '<td>'.$row->nombre_cliente.'</td>';
        echo '<td>'.$row->cod_barra.'</td>';
        echo '<td>'.$row->prod_nombre.'</td>';
        echo '<td>'.$row->marca_nombre.'</td>';
        echo '<td>'.$row->cat_nombre.'</td>';
        echo '<td>'.$row->prov_nombre.'</td>';
        echo '<td>'.$row->cantidad.'</td>';
        echo '<td>'.$row->prod_preciopublico.'</td>';
        echo '<td>'.$row->monto_costo.'</td>';
        echo '<td>'.$row->monto_descuento.'</td>';
        echo '<td>'.$row->total_linea.'</td>';
        
        echo '</tr>';
    }
    
    echo '</table>';
    
}

?>