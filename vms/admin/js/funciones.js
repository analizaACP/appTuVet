//concatena nombre y presentacion

function concatenar() {

    campo1=document.getElementById('nombre').value;
    campo2=document.getElementById('presentacion').value;
	//campo3=document.getElementById('marca').value;
	
    final=campo1+" "+campo2+" ";//+campo3;

    document.getElementById('nombrebase').value=final;

 }

//mueve el foco de codigo de barra a nombre
document.getElementById('codbarra').addEventListener('keydown', inputCharacters);
	function inputCharacters(event) {
		if (event.keyCode == 13) {
    		document.getElementById('nombre').focus();
 			 }
		}
		
// apaga la tecla enter para que no se envie el formulario		
		document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
        if(e.keyCode == 13) {
          e.preventDefault();
        }
      }))
    });
    
    
    function validarFormulario() {
      var password1 = document.getElementById("password").value;
      var password2 = document.getElementById("repita").value;

      if (password1 !== password2) {
        alert("Las contraseñas no coinciden. Por favor, inténtalo de nuevo.");
        return false; // Evita que el formulario se envíe
      }
    }
    
function establecerPatron() {
        var campo = document.getElementById('numdoc');
        console.log(campo.value); 
        var patrones = document.getElementById('patrones');
        
        var patronSeleccionado = patrones.value;
        campo.pattern=null;
		console.log(patronSeleccionado); // 
        if (patronSeleccionado=="[0-9-]{4}[0-9-]{4}[0-9]{5}") {
            campo.pattern = patronSeleccionado;
            console.log("Campo con patrón asignado:", campo.value); 
            numeroSinGuiones=campo.value;
            var numeroConGuiones = numeroSinGuiones.replace(/(\d{4})(\d{4})(\d{5})/, '$1-$2-$3');
        	console.log("Número con guiones:", numeroConGuiones);
            document.getElementById('docfinal').value=numeroConGuiones;    
        } 
        
        else if (patronSeleccionado=="[0-9-]{8}[0-9]{1}") {
            campo.pattern = patronSeleccionado;
            console.log("Campo con patrón asignado:", campo.value); 
            numeroSinGuiones=campo.value;
            var numeroConGuiones = numeroSinGuiones.replace(/(\d{8})(\d{1})/, '$1-$2');
        	console.log("Número con guiones:", numeroConGuiones);
            document.getElementById('docfinal').value=numeroConGuiones;    
        }
        
        else if (patronSeleccionado=="[A-Z]{1}[0-9]{6}") {
            campo.pattern = patronSeleccionado;
            console.log("Campo con patrón asignado:", campo.value); 
            numeroSinGuiones=campo.value;
            var numeroConGuiones = numeroSinGuiones;
        	console.log("Número con guiones:", numeroConGuiones);
        	document.getElementById('docfinal').value=numeroConGuiones;
                
        } 
        
        else if (patronSeleccionado=="[0-9-]{6}[0-9]{1}") {
            campo.pattern = patronSeleccionado;
            console.log("Campo con patrón asignado:", campo.value); 
            numeroSinGuiones=campo.value;
            var numeroConGuiones = numeroSinGuiones.replace(/(\d{6})(\d{1})/, '$1-$2');
        	console.log("Número con guiones:", numeroConGuiones);
            document.getElementById('docfinal').value=numeroConGuiones;    
        }
        
        else if (patronSeleccionado=="[0-9-]{4}[0-9-]{4}[0-9]{6}") {
            campo.pattern = patronSeleccionado;
            console.log("Campo con patrón asignado:", campo.value); 
            numeroSinGuiones=campo.value;
            var numeroConGuiones = numeroSinGuiones.replace(/(\d{4})(\d{4})(\d{6})/, '$1-$2-$3');
        	console.log("Número con guiones:", numeroConGuiones);
            document.getElementById('docfinal').value=numeroConGuiones;    
        }
        
        
        else {
            campo.pattern = "";
        }
        
    }    
    
    



    
    
    


    
    
    

    