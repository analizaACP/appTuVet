$(document).ready(function() {
    $('#key').on('keyup', function() {
        var key = $(this).val();		
        var dataString = 'key='+key;
	$.ajax({
            type: "POST",
            url: "ajax.php",
            data: dataString,
            success: function(data) {
                //Escribimos las sugerencias que nos manda la consulta
                $('#suggestions').fadeIn(1000).html(data);
            }
        });
    });

    //Al hacer click en algua de las sugerencias
    $(document).on('click', '.suggest-element', function(){
        //Obtenemos el contenido del atributo 'data' de la sugerencia pulsada
        var suggestionData = $(this).attr('data');
        //Editamos el valor del input con data de la sugerencia pulsada
        $('#key').val(suggestionData);
        //Hacemos desaparecer el resto de sugerencias
        $('#suggestions').fadeOut(1000);
        document.getElementById('key').focus();
        return false;
    });
});