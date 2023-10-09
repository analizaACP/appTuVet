document.addEventListener("DOMContentLoaded", function() {
    const selectElement = document.querySelector('select[name="tipodepago"]');
    const efectivoInput = document.querySelector('input[name="efectivo"]');
    const total02Input = document.querySelector('input[name="total02"]');
    const tarjetaInput = document.querySelector('input[name="tarjeta"]');
    const creditoInput = document.querySelector('input[name="credito"]');
	const recibidoInput = document.querySelector('input[name="recibido"]');
	const cambioInput = document.querySelector('input[name="cambio"]');
	const copiaefectivoInput = document.querySelector('input[name="copiaefectivo"]');
	const copiatarjetaInput = document.querySelector('input[name="copiatarjeta"]');
	const copiacreditoInput = document.querySelector('input[name="credito"]');
	
    selectElement.addEventListener("change", function() {
        if (this.value === "Efectivo") {
			var submitButton = document.getElementById("cobrar");
			submitButton.disabled = true;
            efectivoInput.value = total02Input.value;
            tarjetaInput.value = 0;
            creditoInput.value = 0;
            copiaefectivoInput.value=total02Input.value;
            copiatarjetaInput.value = 0;
            copiacreditoInput.value = 0;
            recibidoInput.value=0;
            cambioInput.value=0;
            
            recibidoInput.addEventListener("input",function(){
            	
            	const cambio = parseFloat(recibidoInput.value)-parseFloat(efectivoInput.value);
                cambioInput.value = (cambio >= 0 ? cambio : 0).toFixed(2);
                if (cambio >= 0) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
                
                
            });
            
        } else if (this.value === "Tarjeta") {
        	var submitButton = document.getElementById("cobrar");
            efectivoInput.value = 0;
            tarjetaInput.value = total02Input.value;
            creditoInput.value = 0;
            copiaefectivoInput.value=0;
            copiatarjetaInput.value = total02Input.value;
            copiacreditoInput.value = 0;
            recibidoInput.value=0;
            cambioInput.value=0;
            submitButton.disabled = false;
            
            
            
            
        } else if (this.value === "Tarjeta/Efectivo") {
            var submitButton = document.getElementById("cobrar");
			submitButton.disabled = true;
            creditoInput.value = 0;
            tarjetaInput.value = 0;
            recibidoInput.value=0;
            cambioInput.value=0;

            efectivoInput.addEventListener("input", function() {
                const diferencia = parseFloat(total02Input.value) - parseFloat(efectivoInput.value);
                tarjetaInput.value = (diferencia >= 0 ? diferencia : 0).toFixed(2);
                copiatarjetaInput.value = tarjetaInput.value;
                copiaefectivoInput.value=efectivoInput.value;
            });
            
            
            recibidoInput.addEventListener("input",function(){
            	var submitButton = document.getElementById("cobrar");
            	const cambio = parseFloat(recibidoInput.value)-parseFloat(efectivoInput.value);
            	cambioInput.value = (cambio >= 0 ? cambio : 0).toFixed(2);
                if (cambio >= 0) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
                
            });
            
        } else if (this.value === "Credito") {
        	var submitButton = document.getElementById("cobrar");
            efectivoInput.value = 0;
            tarjetaInput.value = 0;
            creditoInput.value = total02Input.value;
            recibidoInput.value=0;
            cambioInput.value=0;
            submitButton.disabled = false;
        }
        
        
        
        
    });
});


$(document).ready(function() {
    $('a[data-toggle="modal"]').on('click', function(event) {
        var link = $(this);
        var id = link.data('id');
        var nombreCliente = link.data('nombre');
        var total = link.data('total');
        var condicion = link.data('condicion');
        const opcionCredito = document.getElementById('opcionCredito');
        const opcionEfectivo=document.getElementById('opcionEfectivo');
        const opcionTarjeta=document.getElementById('opcionTarjeta');
        const opcionCombinacion=document.getElementById('opcionCombinacion');
        
        console.log('ID:', id);
        console.log('Nombre del Cliente:', nombreCliente);
        console.log('Total:', total);
        
        $('#exampleModalLabel').text('Cobrar la factura con id no. ' + id);
        $('#idFacturaInput').val(id);
        $('#nombreClienteInput').val(nombreCliente);
        $('#nombreClienteInput02').val(nombreCliente); 
        $('#totalInput').val(total); 
        $('#total02Input').val(total);
        $('#condicionInput').val(condicion);
        
        if (condicion === 'Contado ') { // Asegúrate de que el valor sea exactamente 'Contado'
            opcionCredito.disabled = true;
            opcionEfectivo.disabled= false;
            opcionTarjeta.disabled= false;
            opcionCombinacion.disabled= false;
            
            
        } else {
            opcionCredito.disabled = false;
            opcionEfectivo.disabled= true;
            opcionTarjeta.disabled= true;
            opcionCombinacion.disabled= true;
            
            
        }
    });
});




