<?php

session_start();
error_reporting(0);
include_once ('include/config.php');
include ('include/checklogin.php');
check_login();

$fechaHoraActual = date("Y-m-d_H-i-s");

if(isset($_POST['submit'])){
    
    $codigoSucursal=$_REQUEST['sucursal'];
    $sql=pg_query($db,"select s.sucursal_nombre,p.prod_codbarra,p.prod_nombre,m.marca_nombre,c.cat_nombre,
v.prov_nombre, p.prod_costopromedio, p.prod_preciopublico,e.existencia,
p.prod_porcdescuento*100 as descuento
from productos p
join marcas m
on p.id_marca=m.id
join existencias e
on p.prod_codbarra=e.codbarra
join sucursales s
on e.id_sucursal=s.id
join categorias c
on p.id_categorias=c.id
join proveedores v
on m.proveedor_id=v.id
where p.prod_esinventariosn=1 and e.id_sucursal='$codigoSucursal'
order by sucursal_nombre
;");
    
    
    $reporte="inventario_" . $fechaHoraActual ." Sucursal No.".$codigoSucursal. ".xls";
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename='.$reporte);
    header('Pragma: no-cache');
    header('Expires: 0');
    
    echo '<table border="1">';
    echo '<tr>';
    echo '<th colspan="9">Reporte Inventario Sucursal</th>';
    echo '</tr>';
    
    echo '<tr>
        
    <th>Codigo</th>
    <th>Nombre</th>
    <th>Marca</th>
    <th>Categoria</th>
    <th>Proveedor</th>
    <th>Costo</th>
    <th>Precio de Venta</th>
    <th>Existencia</th>
    <th>Descuento %</th>
        
    </tr>';
    while($row = pg_fetch_object($sql)){
        
        echo '<tr>';
        echo '<td>'.$row->prod_codbarra.'</td>';
        echo '<td>'.$row->prod_nombre.'</td>';
        echo '<td>'.$row->marca_nombre.'</td>';
        echo '<td>'.$row->cat_nombre.'</td>';
        echo '<td>'.$row->prov_nombre.'</td>';
        echo '<td>'.$row->prod_costopromedio.'</td>';
        echo '<td>'.$row->prod_preciopublico.'</td>';
        echo '<td>'.$row->existencia.'</td>';
        echo '<td>'.$row->descuento.'</td>';
        
        
        echo '</tr>';
    }
    
    echo '</table>';
    
}

?>