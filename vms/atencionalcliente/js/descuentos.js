function handleCheckboxChange(rowId) {
  var checkbox = document.querySelector('input[type="checkbox"][data-row="' + rowId + '"]');
  
  if (checkbox.checked) {
    console.log("Se marcó el checkbox");

    // Realizar una consulta cuando se marca el checkbox
    fetch('descuentos.php?id=' + rowId + '&action=marcar')
      
  } else {
    console.log("Se desmarcó el checkbox");

    // Realizar una consulta cuando se desmarca el checkbox
    fetch('descuentos.php?id=' + rowId + '&action=desmarcar')
      
  }
}
