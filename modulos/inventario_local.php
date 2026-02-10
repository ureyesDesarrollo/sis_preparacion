<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/


?>
<script>
  $(document).ready(function() {

    $("#txt_folio_interno").val(<?php fnc_folio_mensual() ?>);

  });
</script>
<style>

</style>
<div class="row" style="color: #4AB5B9">
  <div class="col-md-12">
    <h4>Recepci&oacute;n Local</h4>
  </div>
</div>
<div class="row">
  <div class="col-md-2">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Folio interno:</label>
    <input name="txt_folio_interno" type="text" class="form-control" id="txt_folio_interno" placeholder="Folio interno" required onkeypress="return isNumberKey(event, this);" value="">
  </div>
  <div class="col-md-4">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Origen/ Material:</label>
    <!--<span class="col-md-3">-->
    <select name="cbxMaterial" class="form-control" id="cbxMaterial" required="required">
      <option value="">Seleccionar Material</option>
      <?php
      $cad_cbx =  mysqli_query($cnx, "SELECT t.mt_descripcion, m.mat_id,m.mat_nombre FROM materiales  as m
      inner join materiales_tipo as t on(m.mt_id = t.mt_id)
       WHERE m.mat_est = 'A' and t.mt_est = 'A' ORDER BY m.mat_nombre") or die(mysqli_error($cnx) . "Error: en consultar el material");
      $reg_cbx =  mysqli_fetch_array($cad_cbx);

      do { ?>
        <option value="<?php echo $reg_cbx['mat_id'] ?>"><?php echo $reg_cbx['mt_descripcion'] . '/ ' . $reg_cbx['mat_nombre'] ?></option>
      <?php
      } while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
      ?>
    </select>
    <!-- </span>-->
  </div>

  <div class="col-md-2">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> KG Entrada:</label>
    <input name="txtKg" type="text" class="form-control" id="txtKg" placeholder="Kilos" required onchange="fnc_calculaTotal()" onblur="valida_cero(this)" onkeypress="return isNumberKey(event, this);">
  </div>

  <div class="col-md-3">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Prueba secador:</label>
    <input name="txtSecador" type="text" class="form-control" id="txtSecador" placeholder="Secador" required onkeypress="return isNumberKey(event, this);" value="">
  </div>
  <div class="col-md-2">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Descto. agua:</label>
    <input name="txtDAgua" type="text" class="form-control" id="txtDAgua" placeholder="Descuento" required onkeypress="return isNumberKey(event, this);" onchange="fnc_calculaTotal()">
  </div>
  <div class="col-md-2">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> % Descarne:</label>
    <input name="txtDescarne" type="text" class="form-control" id="txtDescarne" placeholder="Descarne" required onkeypress="return isNumberKey(event, this);" value="" onchange="fnc_calculaTotal()">
  </div>
  <div class="col-md-3">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Descto. rendimiento:</label>
    <input name="txtDRendimiento" type="text" class="form-control" id="txtDRendimiento" placeholder="Descto. rendimiento" required="required" onchange="fnc_calculaTotal()" onkeypress="return isNumberKey(event, this);" value="">
  </div>
  <div class="col-md-3">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Prueba rendimiento:</label>
    <input name="txt_prueba_redimiento" type="text" class="form-control" id="txt_prueba_redimiento" placeholder="Prueba rendimiento" required="required" onkeypress="return isNumberKey(event, this);" value="">
  </div>
  <div class="col-md-2">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Kg totales:</label>
    <input name="txtKgTotales" type="text" class="form-control" id="txtKgTotales" placeholder="Kilos" required readonly="readonly">
  </div>
  <div class="col-md-3">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Estado</label>
    <select name="cbx_estado" class="form-control" id="cbx_estado" required="required">
      <!--<option value=""  disabled>Estado</option>-->
      <option value="X" selected="selected">N/A</option>
      <option value="F">Fresco</option>
      <option value="E">Encalado</option>
    </select>
  </div>
  <div class="col-md-3">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Calidad</label>
    <select name="cbxCalidad" class="form-control" id="cbxCalidad" required="required">
      <option value="">Selecciona</option>
      <!--  <option value="P">Poco</option>
      <option value="N">Nada</option>
      <option value="M">Mucho</option>
      <option value="X" selected="selected">N/A</option> -->


      <option value="5">5-Excelente</option>
      <option value="4">4-Muy buena</option>
      <option value="3">3-Buena</option>
      <option value="1">2-Regular</option>

    </select>
  </div>
  <div class="col-md-3">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Fecha y hora entrada:</label>
    <input name="txt_hora_entrada" type="datetime-local" class="form-control" id="txt_hora_entrada" required>
  </div>
  <div class="col-md-3">
    <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Fecha y hora salida:</label>
    <input name="txt_hora_salida" type="datetime-local" class="form-control" id="txt_hora_salida" required>
  </div>
</div>


<div class="row" style="margin-top: 3rem;">
  <div class="col-md-12">
    <label for="recipient-name" class="col-form-label">Notas: </label><br>
    <ol>
      <li>Actualmente no se se realizan pruebas de: secador, % de descarne, descuento de rendimiento, prueba de rendimiento. Se coloca 0.
      </li>
      <!-- <li>Calidad no se usa, se queda en NA</li> -->
      <li>Estado no se usa, se queda en NA</li>
      <li>Kg totales se calcula de (Kg de entrada - descuento de agua - % descarne - descuento de rendimiento)
      </li>
    </ol>
  </div>
</div>