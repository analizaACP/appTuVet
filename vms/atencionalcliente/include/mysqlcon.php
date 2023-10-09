<?php
$servername = "localhost";
$username = "adolfo";
$password = "ofloda01";
$database = "foto_productos";

// Crear una conexión a MySQL
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}



// Cerrar la conexión cuando hayas terminado
mysqli_close($conn);
?>
