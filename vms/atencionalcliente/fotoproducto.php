<?php


// Verifica si se proporcionó un ID válido en la solicitud GET
if (isset($_GET['id'])) {
    $fotoId = $_GET['id'];
    
    // Llama a la función para obtener la imagen del producto
    $imagenProducto = obtenerImagenProducto($fotoId);
    
    if ($imagenProducto !== false) {
        // Si la imagen se obtuvo correctamente, envíala como respuesta
        echo '<div style="text-align: center;">
                  <img src="data:image/jpg;base64,' . base64_encode($imagenProducto) . '" width="200" height="200" class="center"/>
              </div>';
    } else {
        echo "No se pudo obtener la imagen del producto.";
    }
} else {
    echo "No se proporcionó un ID válido.";
}

// Función para obtener la imagen del producto
function obtenerImagenProducto($conexion, $fotoId) {
    
    $db = pg_connect("host=localhost port=5432 dbname=tuvet user=adolfo password=ofloda01");
    
    // Ejecuta la consulta SQL para obtener la imagen del producto
    $query = "SELECT foto FROM productos WHERE id = $fotoId";
    $resultado = pg_query($db, $query);
    
    if ($resultado) {
        $fila = pg_fetch_assoc($resultado);
        return $fila['foto'];
    } else {
        echo "error";
        return false; // No se pudo obtener la imagen
    }
}
?>
