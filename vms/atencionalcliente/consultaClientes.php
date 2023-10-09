<?php

session_start();
error_reporting(0);
include_once ('include/config.php');
include ('include/checklogin.php');
check_login();

$idRecibido = isset($_GET['id']) ? intval($_GET['id']) : 0;

$valorBuscar = 0+$idRecibido;
//echo "valor recibido ... ".$valorBuscar;

$pruebaConexion=pg_query($db,"insert into pruebas_conexion (resultado,entero) values ('$idRecibido','$valorBuscar')");


// consultaClientes.php


// ... (tu código actual)

// Después de obtener los datos del cliente
echo '<script>';
echo 'var datosCargados = true;';
echo '</script>';



?>
