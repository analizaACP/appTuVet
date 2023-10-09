$(function() {
  $('#productos').select2();
})

function cargarDatosTabla() {
  var ar = JSON.parse(data_productos)
  var id = $('#productos').val();
  for (let i = 0; i < ar.length; i++) {
    if (id == ar[i]['prod_codbarra']) {
      $('#costo').val(0);
      $('#costo').attr("markup", ar[i]['markup']);
      $('#costo').attr("pagaiva", ar[i]['paga_iva']);
      $('#cantidad').val(0);
      $('#bonificacion').val(0);
      $('#cantidad_total').html(0);
      $('#subtotal').html(0);
      $('#prod_precioactual').html(ar[i]['prod_preciopublico']);
      $('#prod_precionuevo').val(0);
      $('#iva').html(0);
      $('#total').html(0);
    }
  }
}

lista_productos_compras = [];
function agregarListaProductos() {
  var ar = JSON.parse(data_productos)
  var prod = $('#productos').val();
  if (lista_productos_compras.filter((item)=>item.codbarra==prod).length == 0){
    var id = 0; var nombre= ''; var precio = 0; var iva = 0; var porcentaje_iva= 0;
    for (let i = 0; i < ar.length; i++) {
      if (prod == ar[i]['prod_codbarra']) {
        id=ar[i]['id'];
        nombre=ar[i]['prod_nombre'];
        markup=ar[i]['markup'];
        precio=ar[i]['prod_preciopublico'];
        porcentaje_iva= Number($('#costo').attr("pagaiva"));
        iva = (Number($('#costo').val())*Number($('#cantidad').val()))*porcentaje_iva;

        lista_productos_compras.push({
          'id':ar[i]['id'],
          'codbarra':ar[i]['prod_codbarra'],
          'nombre':ar[i]['prod_nombre'],
          'costo':Number($('#costo').val()),
          'cantidad':Number($('#cantidad').val()),
          'bonificacion':Number($('#bonificacion').val()),
          'cantidad_total':Number($('#cantidad').val())+Number($('#bonificacion').val()),
          'subtotal':Number($('#costo').val())*Number($('#cantidad').val()),
          'precio':ar[i]['prod_preciopublico'],
          'precio_nuevo':Number($('#prod_precionuevo').val()),
          'cantidad_actual':ar[i]['cantidad_actual'],
          'prod_porcdescuento':ar[i]['prod_porcdescuento'],
          'prod_costopromedio':ar[i]['prod_costopromedio'],
          'iva':iva,
          'total':(Number($('#costo').val())*Number($('#cantidad').val()))+iva
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
  var cont = 0; var tsubtotal = 0; var tcantidad = 0; var tiva = 0; var ttotal = 0; var tretencion = 0;
  for (let i = 0; i < ar.length; i++) { 
    cont += 1; tsubtotal += Number(ar[i]['subtotal']); tcantidad += Number(ar[i]['cantidad']); 
    tiva += Number(ar[i]['iva']); ttotal += Number(ar[i]['total']); 
    document.getElementById('tabla_lista_productos_compras').insertRow(-1).innerHTML = ''+
    '<td>'+cont+
    '</td><td>'+ar[i]['nombre']+
    '</td><td>'+ar[i]['costo'].toFixed(2)+
    '</td><td>'+ar[i]['cantidad']+
    '</td><td>'+ar[i]['bonificacion']+
    '</td><td>'+ar[i]['cantidad_total']+
    '</td><td>'+ar[i]['subtotal'].toFixed(2)+
    '</td><td>'+ar[i]['precio']+
    '</td><td>'+ar[i]['precio_nuevo'].toFixed(2)+
    '</td><td>'+ar[i]['iva'].toFixed(2)+
    '</td><td>'+ar[i]['total'].toFixed(2)+'</td>'+
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
  var checkBox = document.getElementById("retencion");
  if (checkBox.checked == true) {
    tretencion = tsubtotal*0.01;
  }
  $('#tfoot_compras').append(''+
  '<tr>'+
    '<td colspan="10" style="text-align:right;">Subtotal:</td>'+
    '<td>'+tsubtotal.toFixed(2)+'</td>'+
    '<td></td>'+
  '</tr>'+
  '<tr>'+
    '<td colspan="10" style="text-align:right;">IVA:</td>'+
    '<td>'+tiva.toFixed(2)+'</td>'+
    '<td></td>'+
  '</tr>'+
  '<tr>'+
    '<td colspan="10" style="text-align:right;">Retención:</td>'+
    '<td>'+tretencion.toFixed(2)+'</td>'+
    '<td></td>'+
  '</tr>'+
  '<tr>'+
    '<td colspan="10" style="text-align:right;">Total:</td>'+
    '<td>'+ttotal.toFixed(2)+'</td>'+
    '<td></td>'+
  '</tr>'+
  '');

  general_factura={
    'tsubtotal':tsubtotal,
    'tiva':tiva,
    'tretencion':tretencion,
    'ttotal':ttotal
  };
  
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

function cambiarPrecioVenta() {
  var cost = Number($('#costo').val());
  var cant = Number($('#cantidad').val());
  var boni = Number($('#bonificacion').val());
  var porcentaje_iva= Number($('#costo').attr("pagaiva"));
  var cant_total = cant+boni;
  $('#cantidad_total').html(cant_total)
  var markup = $('#costo').attr("markup");
  var iva = 0;
  iva = (Number($('#costo').val())*Number($('#cantidad').val()))*porcentaje_iva;
  $('#subtotal').html(cost*cant);
  var pre_nuevo = cost*(1+Number(markup));
  $('#prod_precionuevo').val(pre_nuevo.toFixed(2));
  $('#iva').html(iva.toFixed(2));
  $('#total').html(((cost*cant)+iva).toFixed(2));
}

function insertarRegistro() {
  if ($('#factura').val()=='') {
    alert("Por favor ingrese el número de factura"); // entonces procedemos a ingresar la alerta 
    $("#factura").focus(); // Agregamos foco 
    $("notif").html("Ingrese el número de factura");
    return false;
  }else{
    $("notif").html("");
  } 
  if ($('#proveedor').val()=='') {
    alert("Por favor elige un proveedor"); // entonces procedemos a ingresar la alerta 
    $("#proveedor").focus(); // Agregamos foco 
    $("notip").html("Seleccione un proveedor");
    return false;
  }else{
    $("notip").html("");
  } 
  if ($('#condicion').val()=='') {
    alert("Por favor elige una condición"); // entonces procedemos a ingresar la alerta 
    $("#condicion").focus(); // Agregamos foco 
    $("notic").html("Seleccione una condición");
    return false;
  }else{
    $("notic").html("");
  } 
  if (lista_productos_compras.length == 0) {
    alert("Por agregar al menos un producto a la factura"); // entonces procedemos a ingresar la alerta 
    $("tabla_lista_productos_compras").focus(); // Agregamos foco
    $("notipro").html("Ingrese al menos un producto a la factura");
    return false;
  }else{
    $("notipro").html("");
  } 
  var texto = "¿Seguro que desea ingresar esta compra?! \n Presionar OK o Cancelar.";
  if (confirm(texto) == true) {
    var datos = new FormData();
    datos.append("factura", $('#factura').val());
    datos.append("proveedor", $('#proveedor').val());
    datos.append("condicion", $('#condicion').val());
    datos.append("general_factura", JSON.stringify(general_factura));
    datos.append("lista_productos", JSON.stringify(lista_productos_compras));
    datos.append("insertarRegistroCompras", 'ok');
    $.ajax({
      url:"ajax/compras.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res){
        console.log(res);
        alert("Se ingreso el registro correctamente");
        window.location.replace("compras.php");
      }
    })
  }
}