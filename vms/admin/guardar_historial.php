<?php

session_start();
error_reporting(0);
include_once('include/config.php');
include('include/checklogin.php');
check_login();
$fecha_actual = date("Y-m-d");
//$contador=3;
// Verificar si se ha recibido una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decodificar los datos JSON enviados desde el cliente
    $data = json_decode(file_get_contents("php://input"), true);
    
    
    // Obtener los datos de la tabla
    $datosTabla = $data['tabla'];
    
    // Obtener los datos adicionales
    $idMascota=$data['idmascota'];
    $motivo = $data['motivo'];
    $temperatura = $data['temperatura'];
    $peso = $data['peso'];
    $sintomas = $data['sintomas'];
    $diagnostico = $data['diagnostico'];
    $fecha = $data['fecha'];
    $analisis = $data['analisis'];
    $medico=$_SESSION['login'];
    $sucursal=$_SESSION['sucursal'];
    
    
    // Insertar los datos adicionales en una tabla específica (ajusta el nombre de la tabla según tu estructura)
    $historial=pg_query($db,"INSERT INTO historial_mascotas (mascota_id,motivo_visita,fecha_visita,temperatura_mascota, peso_mascota,sintomas_mascota,diagnostico,proxima_cita,medico_atendio,sucursal_atendio) VALUES 
                                                            ('$idMascota','$motivo','$fecha_actual','$temperatura','$peso','$sintomas','$diagnostico','$fecha','$medico','$sucursal') RETURNING id");
    
    
    
    if (!$historial) {
        //pg_query($db, "ROLLBACK"); // Revertir la transacción en caso de error
        //die("Error al insertar historial principal: " . pg_last_error($db));
        //$contador=2;
    }
        
    // Obtener el ID generado por la inserción
    $historialID = pg_fetch_result($historial, 0,'id');
    
    // Insertar los datos de la tabla en otra tabla relacionada (ajusta el nombre de la tabla según tu estructura)
    foreach ($datosTabla as $fila) {
        
        $dato1 = $fila[0];
        $dato2 = $fila[1];
        
        $receta = pg_query($db,"INSERT INTO recetas (historial_id, medicamento, dosis) VALUES ('$historialID', '$dato1', '$dato2')");
        
        
        
        if (!$receta) {
            //pg_query($db, "ROLLBACK"); // Revertir la transacción en caso de error
            //die("Error al insertar datos de la receta: " . pg_last_error($db));
            //$contador=1;
        }
    }
    
    foreach ($analisis as $analisisID) {
        $sqlanalisis = pg_query($db,"INSERT INTO analisis_mascota (historial_id, analisis_id)
                  VALUES ($historialID, $analisisID)");
        
        
        
        if (!$sqlanalisis) {
           // pg_query($db, "ROLLBACK"); // Revertir la transacción en caso de error
            //die("Error al insertar datos de análisis: " . pg_last_error($db));
            //$contador=0;
        }
    }
    
    } 

    
    echo "<script>window.location.href('registromascotas.php');</script>";
?>
