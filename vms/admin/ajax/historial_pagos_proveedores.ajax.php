<?php
session_start();
error_reporting(0);
require_once "../include/config.php";
require_once "../include/checklogin.php";
check_login();
date_default_timezone_set('America/El_Salvador');
$fecha_actual=date("d-m-y h:i:s");
class obtenerHistorialPago{

 	/*=============================================
 	 OBTENER HISTORIAL
  	=============================================*/ 

    public $obtenerHistorialPago;

	public function obtenerHistorialPagos(){
        $query=("SELECT
            ep.id,
            ep.prov_id,
            p.prov_nombre,
            ep.doc_numero,
            ep.fecha, 
            ep.cargo, 
            ep.abono, 
            ep.saldo,
            ep.usuario,
            ep.sucursal,
            s.sucursal_nombre
            FROM extractosproveedores AS ep
            JOIN proveedores AS p ON ep.prov_id = p.id
            JOIN sucursales AS s ON ep.sucursal = s.id
            WHERE ep.prov_id = $this->id
            ORDER BY ep.fecha
        ");
        $query=pg_query($query);
        $query=pg_fetch_all($query);
        
        if(!$query){
            echo "Error al procesar la informacion 1, ".pg_last_error($db)."";
        }
        //pg_close();
        
        if($query){
            echo json_encode($query);
        }
        
    }

}

/*=============================================
ACTIVAR INSERTAR REGISTRO TRASLADO
=============================================*/ 
if(isset($_POST["obtenerDatosHistorial"])){
    $obtenerHistorialPago = new obtenerHistorialPago();
    $obtenerHistorialPago -> id = $_POST["id"];
    $obtenerHistorialPago -> obtenerHistorialPagos();
}