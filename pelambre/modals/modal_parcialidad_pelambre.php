<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Octubre-2023*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST);

$cadena = mysqli_query($cnx, "SELECT * FROM inventario WHERE inv_id = '" . $_POST['inv_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);

/*$suma = mysqli_query($cnx, "SELECT SUM(inv_kg_totales) as total FROM inventario WHERE inv_id_key ='" . $registros['inv_id'] . "' 
  ") or die(mysqli_error($cnx) . "Error: en consultar el inventario");

//$suma = mysqli_query($cnx, "SELECT SUM(inv_kg_totales) as total FROM inventario WHERE inv_id ='" . $registros['inv_id'] . "'  AND inv_enviado = '6' ") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
$reg_suma = mysqli_fetch_assoc($suma);
$res = $reg_suma['total'];
if (isset($reg_suma['total'])) {
  $res = $kg_final - $reg_suma['total'];
} else {
  $res = $kg_final;
}
*/
$cad_kg_fin = mysqli_query($cnx, "SELECT ip_kg_finales FROM inventario_pelambre WHERE inv_id = '" . $_POST['inv_id'] . "' and ip_ban = '1'") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
$reg_kg_fin = mysqli_fetch_assoc($cad_kg_fin);

$kg_final = $reg_kg_fin['ip_kg_finales']; //kilos pelambrados

$suma = mysqli_query($cnx, "SELECT SUM(inv_kg_totales) as total FROM inventario WHERE (inv_id = '" . $registros['inv_id'] . "' or inv_id_key = '" . $registros['inv_id'] . "')  AND inv_enviado = '2' ") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
$reg_suma = mysqli_fetch_assoc($suma);
$res = $kg_final - $reg_suma['total']; //kilos pendientes a descargar
?>

<script>
  $(document).ready(function() {
    $("#form_enviar_maquila").submit(function() {
      //alert('editar');
      var formData = $(this).serialize();
      $.ajax({
        url: "modals/parcialidad_pelambre.php",
        type: 'POST',
        data: formData,
        success: function(result) {
          data = JSON.parse(result);
          //alert("Guardo el registro");
          alertas_v5("#alerta-error_dividir", 'Listo!', data["mensaje"], 1, true, 5000);
          //$('#form').each (function(){this.reset();});  
        }
      });
      confirmEnviar2();
      return false;
    });
  });

  //Bloquear boton al dividir material
  function confirmEnviar2() {
    form_enviar_maquila.btn.disabled = true;
    form_enviar_maquila.btn.value = "Enviando...";

    setTimeout(function() {
      form_enviar_maquila.btn.disabled = true;
      form_enviar_maquila.btn.value = "Guardar";
    }, 2000);

    var statSend = false;
    return false;
  }


  function fnc_restaCantidad() {
    if (parseFloat(document.getElementById("txtEnvia").value) <= parseFloat(document.getElementById("txt_kg_pendientes").value) && parseFloat(document.getElementById("txtEnvia").value) != '0') {
      var val = document.getElementById("txt_kg_pendientes").value - document.getElementById("txtEnvia").value;

      document.getElementById("txtSobra").value = parseFloat(val).toFixed(2);;
      // document.getElementById("txtSobraH").value = val;
    } else {
      alert("La cantidad a enviar no puede ser igual a '0' o mayor a los kg finales");
      document.getElementById("txtEnvia").value = '';
      document.getElementById("txtSobra").value = '';
    }

  }
</script>

<div class="modal-dialog modal-xl">
  <div class="modal-content">
    <form id="form_enviar_maquila" name="form_enviar_maquila">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enviar a patio mp</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="container">
          <div class="row">
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="txtKg" class="col-form-label">Kg enviados a pelambre:</label>
              <input name="txtKg" type="text" class="form-control" id="txtKg" required placeholder="" value="<?php echo $registros['inv_kilos'] ?>" readonly>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="txt_kg_final" class="col-form-label">Kg finales pelambrados:</label>
              <input name="txt_kg_final" type="text" class="form-control" id="txt_kg_final" required placeholder="" value="<?php echo $kg_final ?>" readonly>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="txt_kg_pendientes" class="col-form-label">Kg pendientes a descargar:</label>
              <input name="txt_kg_pendientes" type="text" class="form-control" id="txt_kg_pendientes" required placeholder="" value="<?php echo $res ?>" readonly>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="txt_folio_interno" class="col-form-label"><span>*</span> Folio interno:</label>
              <input name="txt_folio_interno" type="text" class="form-control" id="txt_folio_interno" placeholder="Folio interno" required onkeypress="return isNumberKey(event, this);" value="<?php echo fnc_folio_mensual(); ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="cbx_cajon" class="col-form-label">* Cajón:</label>
              <select name="cbx_cajon" class="form-control" id="cbx_cajon" required>
                <option value="">Seleccionar</option>
                <?php
                $cad_cbx = mysqli_query($cnx, "SELECT * FROM almacen_cajones where ac_ban = 'M' ORDER BY ac_descripcion") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
                while ($reg_cbx = mysqli_fetch_array($cad_cbx)) {
                ?>
                  <option value="<?php echo $reg_cbx['ac_id'] ?>"><?php echo "Cajón " . $reg_cbx['ac_descripcion'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="txtEnvia" class="col-form-label">* Enviar a patio:</label>
              <input onkeypress="return isNumberKey(event, this);" name="txtEnvia" type="text" class="form-control" id="txtEnvia" required placeholder="Cantidad" value="" onkeyup="fnc_restaCantidad();">
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="txtSobra" class="col-form-label">Sobrante:</label>
              <input name="txtSobra" type="text" class="form-control" id="txtSobra" required placeholder="" value="" readonly>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
              <label for="hdd_id" class="col-form-label">Clave:</label>
              <input name="hdd_id" type="text" class="form-control" id="hdd_id" value="<?php echo $registros['inv_id'] ?>" readonly>
            </div>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-between" style="margin-top: 8%;">
          <!-- mensajes -->
          <div class="col-12 col-md-6 mb-3 p-0">
            <div id="alerta-error_dividir" class="alert alert-success d-none m-0">
              <strong class="alert-heading"></strong>
              <span class="alert-body"></span>
            </div>
          </div>
          <div class="d-flex">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" onclick="location.reload()">
              <img src="../iconos/close.png" alt=""> Cerrar
            </button>
            <button class="btn btn-primary" type="submit" id="btn" name="btn">
              <img src="../iconos/guardar.png" alt=""> Guardar
            </button>
          </div>
        </div>


      </div>
    </form>
  </div>
</div>