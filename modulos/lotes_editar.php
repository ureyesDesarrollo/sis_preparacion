<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

$hdd_id= $_POST['hdd_id'];
$folio= $_POST['folio'];
$mes= $_POST['mes'];

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM tarimas WHERE lote_id='$hdd_id'
  ") or die(mysqli_error()."Error: en consultar los tarimas");
$registros = mysqli_fetch_assoc($cadena);
$rows = mysqli_num_rows($cadena);

$cadena_lot = mysqli_query($cnx, "SELECT * FROM lotes WHERE lote_id='$hdd_id'
  ") or die(mysqli_error()."Error: en consultar los tarimas");
$reg_lot = mysqli_fetch_assoc($cadena_lot);

$i=1;
?>
<script src="../js/sweetalert2.all.min.js"></script>

<script>
  //Insertar
  $(document).ready(function()
  {
    $("#formCaptura").submit(function(){
      var formData = $(this).serialize();
      $.ajax({
        url: "captura_insertar.php",
        type: 'POST',
        data: formData,

        beforeSend: function () { 
          Swal.fire({
      //title: 'Sweet!',
      allowOutsideClick: false,
      //text: 'Modal with a custom image.',
      imageUrl: '../iconos/Loader.gif',
      //imageWidth: 400,
      //imageHeight: 200,
      //imageAlt: 'Custom image',
      //animation: false
      showConfirmButton:false
       })
        },

        success: function(result) {

          data = JSON.parse(result);
          alertas("#alerta-parametros", 'Listo!', data["mensaje"], 1, true, 5000);
          $('#formCaptura').each (function(){this.reset();}); 
          //setTimeout("location.reload()", 2000); 
          var hdd_id =  document.getElementById("hdd_id").value; 
          setTimeout(cargar2('#main2','lotes_editar_cargar.php?hdd_id='+hdd_id), 23000);
           swal.close();
        }
      });
      return false;
    });
  });




//agregado por CC 05-05-2020
function promBloom(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('bloom').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtBloom'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('bloom').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('bloom').value = '0.00';
   }
 }

}

function promVis(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('viscosidad').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtVisc'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('viscosidad').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('viscosidad').value = '0.00';
   }
 }
}

function promPh(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('ph').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtPhFin'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('phfinal').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('phfinal').value = '0.00';
   }
 }
}

function promTrans(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('trans').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtTrans'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('transparencia').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('transparencia').value = '0.00';
   }
 }
}


function promPorc(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('porcentaje').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtPorcenT'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('porcentaje').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('porcentaje').value = '0.00';
   }
 }
}

function promNtu(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('ntu').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtNtu'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('ntu').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('ntu').value = '0.00';
   }
 }
}

function promHum(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('humedad').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtHumedad'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('humedad').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('humedad').value = '0.00';
   }
 }
}

function promCen(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('cenizas').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtCenizas'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('cenizas').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('cenizas').value = '0.00';
   }
 }
}

function promRed(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('redox').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtRedox'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('redox').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('redox').value = '0.00';
   }
 }
}

function promCol(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('color').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtColor'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('color').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('color').value = '0.00';
   }
 }
}

function promGran(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('grano').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtGrano'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('grano').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('grano').value = '0.00';
   }
 }
}

/*function promOlor(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('olor').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtOlor'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('olor').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('olor').value = '0.00';
   }
 }
}
*/

function promParE(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('parE').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtPartExt'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('partE').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('partE').value = '0.00';
   }
 }
}

function promParI(param){
  var suma = 0;
  var cont = 0;
  var row = (document.getElementsByClassName('parI').length);

  for(var i = 1; i <= row; i++){
    var combo = document.getElementById('txtPartInd'+i).value;
    if (combo !== '') {
      cont++;
      document.getElementById('contadorE').value = cont;
      var suma = suma + parseFloat(combo);
      var res = suma / cont;
      document.getElementById('partI').value = parseFloat(res).toFixed(2);
    }
    if (isNaN(res)) {
     document.getElementById('partI').value = '0.00';
   }
 }
}

    //agregado por CC 01-05-2020
    function agregarRenglon(){
      var a = document.getElementById('contador').value ++;
      

      //a++;
      var tr = document.createElement('tr');
      //tr.setAttribute('class', 'row col-md-12');
      //tr.setAttribute('style', 'margin-top:-15px;margin-left:-15px');

      tr.innerHTML = 
      '<td> <a href="#" onclick="deleteRow(this)"><img src="../iconos/borrar.png" alt="" /></a></td>' +
      '<td>'+a+'</td>'+
      '<td>'+
      '<input class="form-control input-sm" style="width: 80px" type="hidden" id="txtid'+a+'" name="txtid'+a+'" value="0">'+

      '<input class="form-control input-sm renglon" style="width: 110px" type="text" id="txtFecha'+a+'" name="txtFecha'+a+'" readonly value="<?php echo date("Y-m-d");?>"></td>'+

      '<td><input class="form-control input-sm renglon" style="width: 80px" type="text" id="txtLimParam'+a+'" name="txtLimParam'+a+'" value=""></td>'+

      '<td>'+
      '<input class="form-control input-sm bloom" style="width: 80px" type="text" id="txtBloom'+a+'" name="txtBloom'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promBloom('+a+')" value="">'+
      '</td>'+


      '<td><input class="form-control input-sm viscosidad" style="width: 80px" type="text" id="txtVisc'+a+'" name="txtVisc'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promVis('+a+');"  value=""></td>'+


      '<td><input class="form-control input-sm ph" style="width: 80px" type="text" id="txtPhFin'+a+'" name="txtPhFin'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promPh('+a+');"  value=""></td>'+


      '<td><input class="form-control input-sm trans" style="width: 80px" type="text" id="txtTrans'+a+'" name="txtTrans'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promTrans('+a+');" value=""></td>'+

      ' <td><input class="form-control input-sm porcentaje" style="width: 80px" type="text" id="txtPorcenT'+a+'" name="txtPorcenT'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promPorc('+a+');" value=""></td>'+

      '<td><input class="form-control input-sm ntu" style="width: 80px" type="text" id="txtNtu'+a+'" name="txtNtu'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promNtu('+a+');" value=""></td>'+

      '<td><input class="form-control input-sm humedad" style="width: 80px" type="text" id="txtHumedad'+a+'" name="txtHumedad'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promHum('+a+');" value=""></td>'+

      '<td><input class="form-control input-sm cenizas" style="width: 80px" type="text" id="txtCenizas'+a+'" name="txtCenizas'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promCen('+a+');" value=""></td>'+

      '<td><input class="form-control input-sm redox" style="width: 80px" type="text" id="txtRedox'+a+'" name="txtRedox'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promRed('+a+');" value=""></td>'+

      '<td><input class="form-control input-sm color" style="width: 80px" type="text" id="txtColor'+a+'" name="txtColor'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promCol('+a+');" value=""></td>'+

      '<td><input class="form-control input-sm grano" style="width: 80px" type="text" id="txtGrano'+a+'" name="txtGrano'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promGran('+a+');" value=""></td>'+

      '<td><input class="form-control input-sm olor" style="width: 80px" type="text" id="txtOlor'+a+'" name="txtOlor'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promOlor('+a+');" value=""></td>'+

      '<td><input class="form-control input-sm parE" style="width: 80px" type="text" id="txtPartExt'+a+'" name="txtPartExt'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promParE('+a+');" value=""></td>'+


      '<td><input class="form-control input-sm parI" style="width: 80px" type="text" id="txtPartInd'+a+'" name="txtPartInd'+a+'" onkeypress="return isNumberKey(event, this);" onkeyup="promParI('+a+');" value=""></td>'+
      '<td>'+
      '<select class="form-control input-sm renglon" style="width: 80px" id="txtHidratacion'+a+'" name="txtHidratacion'+a+'" value="">'+
      '<option value="N/A">N/A</option>'+
      '<option value="MAL">MAL</option>'+
      '<option value="BIEN">BIEN</option>'+
      '<option value="REG">REG</option>'+
      '</select>'+
      '</td>'+

      '<td>'+
      '<select class="form-control input-sm aceptado" style="width: 70px" id="txtAcepRech'+a+'" name="txtAcepRech'+a+'" value="">'+
      '<option value="N/A">N/A</option>'+
      '<option value="SI">SI</option>'+
      '<option value="NO">NO</option>'+

      '</select>'+
      '</td>'+
      '</tr>';

      // document.getElementById('contadorE').value = a;
       //alert(b);

       document.getElementById('campos').appendChild(tr);document.getElementById('campos').appendChild(tr);
     }


   </script>

   <script>
    //AGREGADO POR CC 04-05-2020
    function cargar2(div, desde)
    {
      $(div).load(desde);
    }

//eliminar renglones agregados
function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}

//eliminar tarimas vacias
function fnc_baja(id){
  var respuesta = confirm("¿Deseas dar de baja este registro?");
  if (respuesta){
    $.ajax({
      url: 'tarimas_eliminar.php',
      data: 'id=' + id,
      type: 'post',
      success: function(result){
    //data = JSON.parse(result);
    var hdd_id =  document.getElementById("hdd_id").value; 
    setTimeout(cargar2('#main2','lotes_editar_cargar.php?hdd_id='+hdd_id), 23000);
  }
});
   //return false;
 }
}


</script>
<script type="text/javascript" src="../js/alerta.js"></script>
<link rel="stylesheet" href="../css/captura.css">
<!--<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">-->
  <div class="modal-dialog" role="document"  style="width: 95%;overflow:auto">
    <form id="formCaptura">
      <div class="modal-content">
        <div class="modal-header" style="height: 60px">
          <h3 style="text-align: center;font-weight: bold;color: #04B4BD;opacity: 0.5" class="modal-title" id="exampleModalLabel">Captura de parametros  (Lote: <?php echo "$hdd_id";?> / Folio: <?php echo "$folio"?> / Mes: <?php echo "$mes"; ?> /  Tarimas totales: <?php echo $rows  ?> )
          </h3>

          <input type="text" id="hdd_id" name="hdd_id" value="<?php echo $hdd_id ?>">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -30px">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body rol" id="main2">
          <center>  
            <table border="1" class="table table-striped" style="font-size: 11px;">
              <div style="background: #fff;height: 80px;position: fixed;margin-top: -15px;margin-left: -15px; width: 98%"></div>
              <thead style="text-align: center;">
                <tr>
                  <th id="bloque3"><div id="subencabezado"> </div></th>
                  <th id="bloque3">No.<div id="subencabezado"> </div></th>
                  <th id="bloque3">FECHA<div id="subencabezado"> </div></th>
                  <!--<th id="bloque3"><div id="subencabezado">LIMITES PARAMETROS</div></th>-->
                  <th id="bloque3"><div id="subencabezado">NUMERO DE TARIMA</div></th>
                  <th id="bloque3">BLOOM<div id="subencabezado"> MIN 100</div></th>
                  <th id="bloque3">VISC. <div id="subencabezado">MIN .15-16 MAX</div></th>
                  <th id="bloque3">PH FINAL <div id="subencabezado">5.5-6.0</div></th>
                  <th id="bloque3">TRANSPARENCIA <div id="subencabezado">MIN 15</div></th>
                  <th id="bloque3"> %T(620) <div id="subencabezado">70% MIN. </div></th>
                  <th id="bloque3"> NTU <div id="subencabezado">60 MIN </div></th>
                  <th id="bloque3"> HUMEDAD <div id="subencabezado">  12%MAX</div></th>
                  <th id="bloque3"> CENIZAS <div id="subencabezado">1.5%MAX </div></th>
                  <th id="bloque3"> REDOX <div id="subencabezado">30 PPM MAX </div></th>
                  <th id="bloque3"> COLOR <div id="subencabezado">3 MAX </div></th>
                  <th id="bloque3"> GRANO MALLA #45 <div id="subencabezado">40% MIN </div></th>
                  <th id="bloque3"> OLOR <div id="subencabezado">SIN OLOR EXTRAÑO </div></th>
                  <th id="bloque3"> PART. EXTRAÑAS <div id="subencabezado">0-25 MAX </div></th>
                  <th id="bloque3"> PART. IND 6,66% <div id="subencabezado">MAXIMO 6 GRANOS </div></th>
                  <th id="bloque3"> HIDRATACIÓN <div id="subencabezado">MAL-BIEN </div></th>
                  <th id="bloque3"> ACEPT. / RECH. <div id="subencabezado"> </div></th>
                </tr>
              </thead>
              <tbody id="campos">
                <?php 
                //$i= 1;
                 //$i= 0;
                do { 
                    //++$i;
                    //$i+=1;

                  ?>
                  <tr>
                    <!--<input type="text" id="hdd_id" name="hdd_id" value="<?php echo $hdd_id ?>">-->
                    <td style="color: #fff"><!--<a href="javascript:fnc_baja(<?=$registros['tarima_id']?>);"> <img src="../iconos/borrar.png"  alt=""></a>--></td>
                    <td><?php echo $i; ?></td>
                    <td>
                      <input style="text-align: center;" class="form-control input-sm activo" style="width: 80px" type="hidden" id="<?php echo 'txtid'.$i?>" name="<?php echo 'txtid'.$i?>" value="<?php if ($registros['tarima_id'] == ''){ echo "0"; }else{ echo '1'; } ?>">
                      <input class="form-control input-sm activo" style="width: 80px" type="hidden" id="<?php echo 'valor'.$i?>" name="<?php echo 'valor'.$i?>" value="<?php echo ($registros['tarima_id']); ?>">

                      <input class="form-control input-sm activo renglon" style="width:110px" type="text" id="<?php echo 'txtFecha'.$i?>" name="<?php echo "txtFecha".$i?>"  value="<?php if ($registros['tarima_fecha'] == ''){ echo date("Y-m-d"); }else{ echo $registros['tarima_fecha']; } ?>" readonly  placeholder="NA"></td>

                      <td><input class="form-control input-sm activo renglon" style="width: 80px" type="text" id="<?php echo 'txtLimParam'.$i?>" name="<?php echo 'txtLimParam'.$i?>" value="<?php if ($registros['tarima_lim_param'] == '0'){ echo ""; }else{ echo $registros['tarima_lim_param']; } ?>"  placeholder="NA"></td>

                      <td>
                        <input class="form-control input-sm activo bloom" style="width: 80px" type="text" id="<?php echo 'txtBloom'.$i?>" name="<?php echo 'txtBloom'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promBloom(<?php echo $i ?>);" value="<?php if ($registros['tarima_bloom'] == '0'){ echo ""; }else{ echo $registros['tarima_bloom']; } ?>" placeholder="NA">
                      </td>

                      <td><input class="form-control input-sm activo viscosidad" style="width: 80px" type="text" id="<?php echo 'txtVisc'.$i?>" name="<?php echo 'txtVisc'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promVis(<?php echo $i ?>);" value="<?php if ($registros['tarima_viscocidad'] == '0'){ echo ""; }else{  echo $registros['tarima_viscocidad']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo ph" style="width: 80px" type="text" id="<?php echo 'txtPhFin'.$i?>" name="<?php echo 'txtPhFin'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promPh(<?php echo $i ?>);"  value="<?php if ($registros['tarima_ph_final'] == '0'){ echo ""; }else{  echo $registros['tarima_ph_final']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo trans" style="width: 80px" type="text" id="<?php echo 'txtTrans'.$i?>" name="<?php echo 'txtTrans'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promTrans(<?php echo $i ?>);"  value="<?php if ($registros['tarima_transparencia'] == '0'){ echo ""; }else{  echo $registros['tarima_transparencia']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo porcentaje" style="width: 80px" type="text" id="<?php echo 'txtPorcenT'.$i?>" name="<?php echo 'txtPorcenT'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promPorc(<?php echo $i ?>);"  value="<?php if ($registros['tarima_porcen_t'] == '0'){ echo ""; }else{  echo $registros['tarima_porcen_t']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo ntu" style="width: 80px" type="text" id="<?php echo 'txtNtu'.$i?>" name="<?php echo 'txtNtu'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promNtu(<?php echo $i ?>);"  value="<?php if ($registros['tarima_ntu'] == '0'){ echo ""; }else{  echo $registros['tarima_ntu']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo humedad" style="width: 80px" type="text" id="<?php echo 'txtHumedad'.$i?>" name="<?php echo 'txtHumedad'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promHum(<?php echo $i ?>);"  value="<?php if ($registros['tarima_humedad'] == '0'){ echo ""; }else{  echo $registros['tarima_humedad']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo cenizas" style="width: 80px" type="text" id="<?php echo 'txtCenizas'.$i?>" name="<?php echo 'txtCenizas'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promCen(<?php echo $i ?>);"  value="<?php if ($registros['tarima_cenizas'] == '0'){ echo ""; }else{  echo $registros['tarima_cenizas']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo redox" style="width: 80px" type="text" id="<?php echo 'txtRedox'.$i?>" name="<?php echo 'txtRedox'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promRed(<?php echo $i ?>);"  value="<?php if ($registros['tarima_redox'] == '0'){ echo ""; }else{  echo $registros['tarima_redox']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo color" style="width: 80px" type="text" id="<?php echo 'txtColor'.$i?>" name="<?php echo 'txtColor'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promCol(<?php echo $i ?>);"  value="<?php if ($registros['tarima_color'] == '0'){ echo ""; }else{  echo $registros['tarima_color']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo grano" style="width: 80px" type="text" id="<?php echo 'txtGrano'.$i?>" name="<?php echo 'txtGrano'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promGran(<?php echo $i ?>);"  value="<?php if ($registros['tarima_grano'] == '0'){ echo ""; }else{  echo $registros['tarima_grano']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo olor" style="width: 80px" type="text" id="<?php echo 'txtOlor'.$i?>" name="<?php echo 'txtOlor'.$i?>" value="<?php if ($registros['tarima_olor'] == '0'){ echo ""; }else{  echo $registros['tarima_olor']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo parE" style="width: 80px" type="text" id="<?php echo 'txtPartExt'.$i?>" name="<?php echo 'txtPartExt'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promParE(<?php echo $i ?>);"  value="<?php if ($registros['tarima_part_ext'] == '0'){ echo ""; }else{  echo $registros['tarima_part_ext']; } ?>" placeholder="NA"></td>

                      <td><input class="form-control input-sm activo parI" style="width: 80px" type="text" id="<?php echo 'txtPartInd'.$i?>" name="<?php echo 'txtPartInd'.$i?>" onkeypress="return isNumberKey(event, this);" onkeyup="promParI(<?php echo $i ?>);"  value="<?php if ($registros['tarima_part_ind'] == '0'){ echo ""; }else{  echo $registros['tarima_part_ind']; } ?>" placeholder="NA"></td>
                      <td>
                        <select class="form-control input-sm activo hidratacion" style="width: 80px" id="<?php echo 'txtHidratacion'.$i ?>" name="<?php echo 'txtHidratacion'.$i ?>" value="">
                          <option value="N/A">N/A</option>
                          <option value="MAL">MAL</option>
                          <option value="BIEN">BIEN</option>
                          <option value="REG">REG</option>

                          <?php 
                          $cad_hidratacion = mysqli_query($cnx,"SELECT tarima_id,tarima_hidratacion from tarimas WHERE tarima_id = '$registros[tarima_id]'");
                          while($reg_hid =  mysqli_fetch_assoc($cad_hidratacion)) {
                           ?>
                           <option value="<?php echo mb_convert_encoding($reg_hid['tarima_hidratacion'], "UTF-8");  ?>" 
                            <?php if(mb_convert_encoding($reg_hid['tarima_id'], "UTF-8") == $registros['tarima_id']){ ?>selected="selected"<?php }?>><?php echo mb_convert_encoding($reg_hid['tarima_hidratacion'], "UTF-8");  ?></option>
                          <?php }?>

                        </select>
                      </td>
                      <td>
                        <select class="form-control input-sm activo aceptado" style="width: 70px" id="<?php echo 'txtAcepRech'.$i?>" name="<?php echo 'txtAcepRech'.$i?>" value="">
                          <option value="N/A">N/A</option>
                          <option value="SI">SI</option>
                          <option value="NO">NO</option>
                          <?php 
                          $cad_aceptado = mysqli_query($cnx,"SELECT tarima_id,tarima_aceptado from tarimas WHERE tarima_id = '$registros[tarima_id]'");
                          while($reg_aceptado =  mysqli_fetch_assoc($cad_aceptado)) {
                           ?>
                           <option value="<?php echo mb_convert_encoding($reg_aceptado['tarima_aceptado'], "UTF-8");  ?>" 
                            <?php if(mb_convert_encoding($reg_aceptado['tarima_id'], "UTF-8") == $registros['tarima_id']){ ?>selected="selected"<?php }?>><?php echo mb_convert_encoding($reg_aceptado['tarima_aceptado'], "UTF-8");  ?></option>
                          <?php }?>
                        </select>
                      </td>
                    </tr>

                    <?php 
                    $i += 1;
                  }while ($registros =  mysqli_fetch_array($cadena)); 
                  ?>


                  <?php 
                  $cont1=$i;
                  ?>
                  <input type="text" id="contador" value="<?php echo $cont1 ?>">

                </tbody>
                <tfoot>
                  <tr>
                    <td>
                      <input type="hidden" id="contadorE">
                    </td>
                    <td></td>
                    <td valign="center">Promedio:</td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="limite"  name="limite" placeholder="---" value=""></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="bloom"  name="bloom" value="<?php echo number_format(($reg_lot['lote_bloom']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="viscosidad"  name="viscosidad" value="<?php echo number_format(($reg_lot['lote_viscocidad']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="phfinal"  name="phfinal" value="<?php echo number_format(($reg_lot['lote_ph_final']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="transparencia"  name="transparencia" value="<?php echo number_format(($reg_lot['lote_transparencia']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="porcentaje"  name="porcentaje" value="<?php echo number_format(($reg_lot['lote_porcen_t']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="ntu"  name="ntu" value="<?php echo number_format(($reg_lot['lote_ntu']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="humedad"  name="humedad" value="<?php echo number_format(($reg_lot['lote_humedad']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="cenizas"  name="cenizas" value="<?php echo number_format(($reg_lot['lote_cenizas']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="redox" name="redox" value="<?php echo number_format(($reg_lot['lote_redox']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="color" name="color" value="<?php echo number_format(($reg_lot['lote_color']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="grano" name="grano" value="<?php echo number_format(($reg_lot['lote_grano']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="olor"  name="olor" value="" placeholder="---"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="partE" name="partE" value="<?php echo number_format(($reg_lot['lote_part_ext']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" type="text" id="partI" name="partI" value="<?php echo number_format(($reg_lot['lote_part_ind']), 2);  ?>"></td>
                    <td><input class="form-control input-sm" style="width: 80px;margin-top: -7px;" readonly="" value="N/A" type="text" id="hidratacion" name="hidratacion"></td>
                    <td><input class="form-control input-sm" style="width: 70px;margin-top: -7px;" readonly="" value="N/A" type="text" id="rechazado" name="rechazado"></td>
                  </tr>
                </tfoot>
              </table>
            </center>

          </div>
          <div class="modal-footer">
            <div class="form-group col-md-4">
              <div class="alert alert-info hide" id="alerta-parametros" style="height: 40px;width: 270px;text-align: left;z-index: 10;margin-top: -7px;;margin-bottom: 0px;position: fixed;z-index: 10">
                <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                <strong>Titulo</strong> &nbsp;&nbsp;
                <span> Mensaje </span>
              </div>
            </div>  
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-primary" style="padding: 2px;margin-top: -3px;height: 33px"  name="count_click" onclick="agregarRenglon()" ><img src="../iconos/add.png" alt=""> Agregar renglón </button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </form>
    </div>

<!--</div>-->