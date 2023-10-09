function cargarDatosModal() {
    $('#id_prov').val($('.btnAgregarPago').attr("idProveedor"));
    $('#proveedor').val($('.btnAgregarPago').attr("extractoProveedor"));
    $('#saldo').val($('.btnAgregarPago').attr("extractoSaldo"));
    $('#monto').val(0);
}

function pregunta() {
    if (confirm('¿Estas seguro de enviar este formulario?')) {
      document.getElementById('formulario').submit();
    }
  }

document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('enviar').addEventListener('click', function(e) {
    e.preventDefault();
    pregunta()
  });
});
  

function cargarDatosModalHistorial(id) {
  const table = $('#tabla_historial').DataTable();
  table.clear();
  //$('table tr:not(:first-child)').remove();
  //$('#tfoot_compras').html('');
  var datos = new FormData();
  datos.append("id", id);
  datos.append("obtenerDatosHistorial", 'ok');
  $.ajax({
    url:"ajax/historial_pagos_proveedores.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    success: function(res){
      var data = JSON.parse(res);
      for (let i = 0; i < data.length; i++) {
        
        table.row.add([data[i]['fecha'], data[i]['doc_numero'], data[i]['cargo'], data[i]['saldo'], data[i]['abono'],data[i]['usuario']]).draw(false);
        // document.getElementById('tabla_historial').insertRow(-1).innerHTML = ''+
        // '</td><td>'+data[i]['fecha']+'</td>'+
        // '</td><td>'+data[i]['doc_numero']+'</td>'+
        // '</td><td>'+data[i]['cargo']+'</td>'+
        // '</td><td>'+data[i]['saldo']+'</td>'+
        // '</td><td>'+data[i]['abono']+'</td>'+
        // '</td><td>'+data[i]['usuario']+'</td>'+
        // ''; 
      };
      
    }
  })
  
}