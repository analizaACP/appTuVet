<?php
$db = pg_connect("host=localhost port=5432 dbname=tuvet user=adolfo password=ofloda01");

$html = '';
$nombre = $_POST['key']; // Aquí se debe usar $_POST['key'] en lugar de $key

// Utiliza pg_query() para realizar la consulta en PostgreSQL
$query = "SELECT id, cliente_nombre FROM clientes WHERE cliente_nombre ILIKE '%$nombre%' LIMIT 5";
$result = pg_query($db, $query); // Pasa la conexión $db como primer parámetro

if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        // Corrige la concatenación del elemento <a> en el código HTML
        $html .= '<div><a class="suggest-element" data="' . htmlentities($row['cliente_nombre'], ENT_QUOTES | ENT_IGNORE, "UTF-8") . '">' . htmlentities($row['cliente_nombre'], ENT_QUOTES | ENT_IGNORE, "UTF-8") . '</a></div>';
    }
} else {
    echo "<p><b>Error al ejecutar la consulta.</b></p>";
}


pg_free_result($result);
pg_close($db);

echo $html; // Muestra el resultado del HTML generado


?>

