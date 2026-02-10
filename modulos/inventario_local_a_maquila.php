<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
?>
<div>
  <h4>Recepción local en maquila</h4>
</div>

<div class="col-md-4">
  <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Material:</label>
  <!--<span class="col-md-3">-->
  <select name="cbxMaterial" class="form-control" id="cbxMaterial" required="required">
    <option value="">Seleccionar Material</option>
    <?php
    $cad_cbx =  mysqli_query($cnx, "SELECT * FROM materiales WHERE mat_est = 'A' ORDER BY mat_nombre") or die(mysqli_error($cnx) . "Error: en consultar el material");
    $reg_cbx =  mysqli_fetch_array($cad_cbx);

    do { ?>
      <option value="<?php echo $reg_cbx['mat_id'] ?>"><?php echo $reg_cbx['mat_nombre'] ?></option>
    <?php
    } while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
    ?>
  </select>
  <!-- </span>-->
</div>
<div class="col-md-3">
  <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Clave compra:</label>
  <input name="txtClave_comp" type="text" class="form-control" id="txtClave_comp" placeholder="clave compra" required>
</div>

<div class="col-md-2">
  <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Total Cueros:</label>
  <input name="txtTotCueros" type="text" class="form-control" id="txtTotCueros" placeholder="Total cueros" required onkeypress="return isNumberKey(event, this);">
</div>

<div class="col-md-2">
  <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG Entrada:</label>
  <input name="txtKg" type="text" class="form-control" id="txtKg" placeholder="kilos entrada" required onChange="fnc_calculaTotalM()" onblur="valida_cero(this)" onkeypress="return isNumberKey(event, this);">
</div>

<div class="col-md-3">
  <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descuento por agua:</label>
  <input name="txtDAgua" type="text" class="form-control" id="txtDAgua" placeholder="descto agua" required onChange="fnc_calculaTotalM()" onkeypress="return isNumberKey(event, this);">
</div>

<div class="col-md-3">
  <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descuento por rendim:</label>
  <input name="txtDRendimiento" type="text" class="form-control" id="txtDRendimiento" placeholder="descto rendim" required onChange="fnc_calculaTotalM()" onkeypress="return isNumberKey(event, this);">
</div>


<div class="col-md-3">
  <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Prueba Secador:</label>
  <input name="txtSecador" type="text" class="form-control" id="txtSecador" placeholder="Secador" required onChange="fnc_calculaTotalM()" onkeypress="return isNumberKey(event, this);">
</div>


<div class="col-md-2">
  <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG TOTALES:</label>
  <input name="txtKgTotales" type="text" class="form-control" id="txtKgTotales" placeholder="kg totales" required readonly="readonly" onkeypress="return isNumberKey(event, this);">
</div>
<div class="col-md-3">
  <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Calidad</label>
  <select name="cbxCalidad" class="form-control" id="cbxCalidad" required="required">
    <option value="">Selecciona</option>
    <option value="P">Poco</option>
    <option value="N">Nada</option>
    <option value="M">Mucho</option>
    <option value="X" selected="selected">N/A</option>
  </select>
</div>
<li>Calidad no se usa, se queda en NA</li>
<br><br><br>
<h2>Fuera de uso</h2>