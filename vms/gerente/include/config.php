<?php


//$db = pg_connect("host=localhost port=5432 dbname=tuvet user=adolfo password=ofloda01");


// Configuración de variables de entorno
$host = getenv('PGHOST');
$port = getenv('PGPORT');
$dbname = getenv('PGDATABASE');
$user = getenv('PGUSER');
$password = getenv('PGPASSWORD');


// Intentar establecer la conexión
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Verificar si la conexión se realizó con éxito
if ($db) {
    echo "¡Conexión exitosa! usando variables de entorno";
} else {
    die("No se pudo conectar a la base de datos.");
}

pg_close($db);
?>





