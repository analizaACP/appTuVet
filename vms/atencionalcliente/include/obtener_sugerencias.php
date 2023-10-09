<?php

include_once('config.php');

$nombre = $_GET['nombrecliente'];

// Consulta a la tabla de clientes
$query = "SELECT cliente_nombre FROM clientes WHERE cliente_nombre ILIKE '$nombre' LIMIT 10";
$result = pg_query($db, $query);

if (!$result) {
    
    echo json_encode([]);
    exit();
}

$sugerencias = array();

// Construir el array de sugerencias
while ($row = pg_fetch_assoc($result)) {
    $sugerencias[] = $row;
}

echo json_encode($sugerencias);
pg_close($db);
?>
