$(function() {
  $('#productos').select2();
})
  
function cargarDatoSucursal() {
  var ar = JSON.parse(data_sucursales)
  var id = ''+$('#suc_salida').val()+'';
  for (let i = 0; i < ar.length; i++) {
    if (id == ar[i]['sucursal_nombre']) {
      $('#suc_salida').attr("id_suc", ar[i]['id']);
    }
  }
}
  
lista_productos_compras = [];
function agregarListaProductos() {
  var ar = JSON.parse(data_productos)
  var prod = $('#productos').val();
  if (lista_productos_compras.filter((item)=>item.codbarra==prod).length == 0){
    var id = 0; 
    for (let i = 0; i < ar.length; i++) {
      if (prod == ar[i]['prod_codbarra']) {
        id=ar[i]['id'];
        lista_productos_compras.push({
          'id':ar[i]['id'],
          'codbarra':ar[i]['prod_codbarra'],
          'nombre':ar[i]['prod_nombre'],
          'cantidad':Number($('#cantidad').val())
        });
      }
    }
    dtabla(lista_productos_compras);
  }else{
    alert('Producto ya se agrego previamente');
  }
}
  
general_factura = [];
function dtabla(ar) {
  $('table tr:not(:first-child)').remove();
  $('#tfoot_compras').html('');
  var cont = 0; var tcantidad = 0;
  for (let i = 0; i < ar.length; i++) { 
    cont += 1; tcantidad += Number(ar[i]['cantidad']); 
    document.getElementById('tabla_lista_productos_compras').insertRow(-1).innerHTML = ''+
    '<td>'+cont+
    '</td><td>'+ar[i]['nombre']+
    '</td><td>'+ar[i]['cantidad']+
    '<td>'+
      '<div class="visible-md visible-lg hidden-sm hidden-xs">'+
        '<a onClick="eliminarFila('+ar[i]['id']+')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>'+
          '</div>'+
          '<div class="visible-xs visible-sm hidden-md hidden-lg">'+
            '<div class="btn-group" dropdown is-open="status.isopen">'+
                '<button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" dropdown-toggle>'+
                    '<i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>'+
                '</button>'+
                '<ul class="dropdown-menu pull-right dropdown-light" role="menu">'+
                  '<li>'+
                      '<a onClick="eliminarFila('+ar[i]['id']+')">'+
                          'Eliminar'+
                      '</a>'+
                  '</li>'+
              '</ul>'+
          '</div>'+
        '</div>'+
    '</td>'+
    '';    
  }
  $('#tfoot_compras').append(''+
  '<tr>'+
    '<td colspan="2" style="text-align:right;">Total:</td>'+
    '<td>'+tcantidad+'</td>'+
    '<td></td>'+
  '</tr>'+
  '');
}
  
const eliminarFila = (idpro) => {
  
  const table = document.getElementById('tabla_lista_productos_compras')
  const rowCount = table.rows.length
  
  if (rowCount <= 1)
    alert('No se puede eliminar el encabezado');
  else
    lista_productos_compras = lista_productos_compras.filter((item)=>
    item.id!=idpro);
    table.deleteRow(rowCount -1);
    dtabla(lista_productos_compras);
}

function validarCantidadTraslado() {
  var array = JSON.parse(data_productos);
  for (let i = 0; i < array.length; i++) {
    if ($('#productos').val() == array[i]['prod_codbarra']) {
      if ($('#cantidad').val() > Number(array[i]['cantidad_actual'])) {
        $('#cantidad').val(0);
        alert('La cantidad sobrepasa la existencia');
      }
    }
  }
}
  
function insertarRegistro() {
  if ($('#suc_salida').val()=='') {
    alert("Por favor ingrese sucursal de salida"); // entonces procedemos a ingresar la alerta 
    $("#suc_salida").focus(); // Agregamos foco 
    $("notisuc_s").html("Ingrese sucursal");
    return false;
  }else{
    $("notisuc_s").html("");
  } 
  if ($('#suc_ingreso').val()=='') {
    alert("Por favor ingrese sucursal de entrada"); // entonces procedemos a ingresar la alerta 
    $("#suc_ingreso").focus(); // Agregamos foco 
    $("notisuc_i").html("Ingrese sucursal");
    return false;
  }else{
    $("notisuc_i").html("");
  } 
  if (lista_productos_compras.length == 0) {
    alert("Por agregar al menos un producto a la factura"); // entonces procedemos a ingresar la alerta 
    $("tabla_lista_productos_compras").focus(); // Agregamos foco
    $("notipro").html("Ingrese al menos un producto");
    return false;
  }else{
    $("notipro").html("");
  } 
  var texto = "¿Seguro que crear este traslado?! \n Presionar OK o Cancelar.";
  if (confirm(texto) == true) {
    var datos = new FormData();
    datos.append("salida", $('#suc_salida').val());
    datos.append("id_suc", $('#suc_salida').attr("id_suc"));
    datos.append("ingreso", $('#suc_ingreso').val());
    datos.append("lista_productos", JSON.stringify(lista_productos_compras));
    datos.append("insertarRegistroTraslados", 'ok');
    $.ajax({
      url:"ajax/traslados.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res){
        console.log(res);
        alert("Se ingreso el traslado correctamente");
        window.location.replace("traslados.php");
      }
    })
  }
}

function cargarDatosModalDetallesTraslado(id) {
  const table = $('#tabla_detalles_traslado').DataTable();
  table.clear();
  //$('#tfoot_compras').html('');
  var datos = new FormData();
  datos.append("id", id);
  datos.append("obtenerDatosDetallesTraslado", 'ok');
  $.ajax({
    url:"ajax/detalles_traslados.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    success: function(res){
      var data = JSON.parse(res);
      for (let i = 0; i < data.length; i++) {
        
        table.row.add([data[i]['prod_nombre'], data[i]['cantidad'], data[i]['status']]).draw(false);
        
      };
      
    }
  })
}

function pregunta(form) {
  if (confirm('¿Estas seguro de aprobar este traslado?')) {
    form.submit();
  }
}
function pregunta1(form) {
  if (confirm('¿Estas seguro de cancelar este traslado?')) {
    form.submit();
  }
}

function confirmar(but) {
  var button=$(but).attr('id');
  if (button == 'enviar') {
    pregunta(but.form);
  }else if (button == 'cancelar') {
    pregunta1(but.form);
  }
};