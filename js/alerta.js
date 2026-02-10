/*function isNumberKey(evt){
var charCode = (evt.which) ? evt.which : event.keyCode
  if (charCode > 31 && charCode != 47 && (charCode < 45 || charCode > 57))
     return false;
   return true;
}*/

function isNumberKey(evt, input) {
  // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
  var key = window.Event ? evt.which : evt.keyCode;
  var chark = String.fromCharCode(key);
  var tempValue = input.value + chark;
  if (key >= 48 && key <= 57) {
    if (filter(tempValue) === false) {
      return true;
      //return false;
    } else {
      return true;
    }
  } else {
    if (key == 8 || key == 13 || key == 0) {
      return true;
    } else if (key == 46) {
      if (filter(tempValue) === false) {
        return false;
      } else {
        return true;
      }
    } else {
      return false;
    }
  }
}

function filter(val) {
  var preg = /^([0-9]+\.?[0-9]{0,2})$/;
  return (preg.test(val) === true);
}


function isNumberKeyFloat(evt, input) {
  // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
  var key = window.Event ? evt.which : evt.keyCode;
  var chark = String.fromCharCode(key);
  var tempValue = input.value + chark;
  if (key >= 48 && key <= 57) {
    if (filter(tempValue) === false) {
      return false;
    } else {
      return true;
    }
  } else {
    if (key == 8 || key == 13 || key == 0) {
      return true;
    } else if (key == 46) {
      if (filter(tempValue) === false) {
        return false;
      } else {
        return true;
      }
    } else {
      return false;
    }
  }
}

function valida_cero(input) {
  var valor = input.value;

  if (valor === "0") {
    alert("El valor ingresado es inválido");
    setTimeout(function () {
      input.value = ""; // Establecer el valor del campo como cadena vacía
      input.focus(); // Volver a enfocar el campo después de que se cierre la alerta
    }, 0);
    return false; // Valor inválido, retornar false
  }

  return true; // Valor válido, retornar true
}


function isNumberCP(evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
  return true;
}


function alertas(nombre, titulo, mensaje, tipo_alerta, cerrar, tiempo) {
  $(nombre).find(' strong ').text(titulo);
  $(nombre).find(' span ').html(mensaje);
  if (tipo_alerta > 4) {
    tipo_alerta = 4;
  }

  var clases = ["alert-primary", "alert-success", "alert-info", "alert-warning", "alert-danger"];
  clase_activa = $(nombre).attr("class").split(" ");
  if (clase_activa[1] != clases[tipo_alerta]) {
    $(nombre).removeClass(clase_activa[1]).addClass(clases[tipo_alerta]);
  }

  $(nombre).removeClass("hide");
  if (cerrar) {
    var t = setTimeout(function () {
      $(nombre).addClass("hide");
      clearTimeout(t);
    }, tiempo);
  }
}


/* alerta version 5 */
function alertas_v5(nombre, titulo, mensaje, tipo_alerta, cerrar, tiempo) {
  $(nombre).find('.alert-heading').text(titulo);
  $(nombre).find('.alert-body').html(mensaje);

  if (tipo_alerta > 4) {
    tipo_alerta = 4;
  }

  var clases = ["alert-primary", "alert-success", "alert-info", "alert-warning", "alert-danger"];
  clase_activa = $(nombre).attr("class").split(" ");

  if (clase_activa[1] != clases[tipo_alerta]) {
    $(nombre).removeClass(clase_activa[1]).addClass(clases[tipo_alerta]);
  }

  $(nombre).removeClass("hide d-none");

  if (cerrar) {
    var t = setTimeout(function () {
      $(nombre).addClass("hide d-none");
      clearTimeout(t);
    }, tiempo);
  }
}



//<!--http://stevehardie.com/2009/09/character-code-list-char-code/ -->