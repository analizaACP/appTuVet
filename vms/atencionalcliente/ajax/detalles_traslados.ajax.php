<?php
session_start();
error_reporting(0);
require_once "../include/config.php";
require_once "../include/checklogin.php";
check_login();
date_default_timezone_set('America/El_Salvador');
$fecha_actual=date("d-m-y h:i:s");
class obtenerDetallesTraslado{

 	/*=============================================
 	 OBTENER HISTORIAL
  	=============================================*/ 

    public $obtenerDetallesTraslado;

	public function obtenerDetallesTraslados(){
        $query=("SELECT
            dt.id,
            dt.cod_barra,
            p.prod_nombre,
            dt.cantidad,
            dt.status
            FROM dtraslados AS dt
            JOIN productos AS p ON dt.cod_barra = p.prod_codbarra
            WHERE dt.id_traslado = $this->id
            ORDER BY p.prod_nombre
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
ACTIVAR VER DETALLES TRASLADOS
=============================================*/ 
if(isset($_POST["obtenerDatosDetallesTraslado"])){
    $obtenerDetallesTraslado = new obtenerDetallesTraslado();
    $obtenerDetallesTraslado -> id = $_POST["id"];
    $obtenerDetallesTraslado -> obtenerDetallesTraslados();
}