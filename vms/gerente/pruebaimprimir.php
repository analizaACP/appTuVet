<?php

include_once 'imprimirfactura.php';
if(isset($_POST['submit'])){
    $numero=$_REQUEST['nuevamarca'];
    
    imprimir($numero);
    
}


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
</head>
<body>

<form role="form" name="marcas" method="post" >

<input type="text" name="nuevamarca" class="form-control"  autocomplete="off" placeholder="Ingrese numero">

<button type="submit" name="submit" class="btn btn-o btn-primary">
															enviar
														</button>

</form>

</body>
</html>