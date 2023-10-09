    <?php
    // Obtener el valor del campo desde el formulario
    $campo = $_POST["key"];
    
    // Establecer la conexión con la base de datos PostgreSQL
    $db = pg_connect("host=localhost port=5432 dbname=tuvet user=adolfo password=ofloda01");
    
    // Verificar la conexión
    if (!$db) {
        die("Error en la conexión a la base de datos.");
    }
    
    // Preparar la consulta SQL
    $sql = "SELECT cliente_nodoc, cliente_nombre FROM clientes WHERE cliente_nodoc LIKE $1 OR cliente_nombre LIKE $2 ORDER BY cliente_nombre ASC LIMIT 10";
    $query = pg_prepare($db, "my_query", $sql);
    
    // Ejecutar la consulta con parámetros
    $result = pg_execute($db, "my_query", array("%$campo%", "%$campo%"));
    
    if (!$result) {
        die("Error en la consulta.");
    }
    
    $html = "";
    
    
    // Procesar los resultados
    while ($row = pg_fetch_assoc($result)) {
        $html .= "<li onclick=\"mostrar('" . $row["cliente_nombre"] . "','" . $row["cliente_nodoc"] . "')\">" . " - " . $row["cliente_nombre"] . "</li>";
    }
    
        // Cerrar la conexión a la base de datos
        //pg_close($db);
        
        echo json_encode($html, JSON_UNESCAPED_UNICODE);
        
        ?>
