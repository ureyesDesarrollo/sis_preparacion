<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
?>
<div class="form-row">
  <div class="form-group col-md-3">
    <label for="inputPassword4"> </label>
    <table border="1" cellpadding="3" style="border:1px solid #e6e6e6">
      <tr>
        <td colspan="4" style="font-weight:bold">TIPO DE CORTE </td>
      </tr>
      <tr>
        <td>&nbsp;Molino 1</td>
        <td>&nbsp;Molino 2</td>
        <td>&nbsp;Molino 3</td>
        <td>&nbsp;Molino 4</td>
      </tr>
      <tr align="center">
        <td>
          <input name="cheMolino1" type="checkbox" id="cheMolino1" value="1" />
        </td>
        <td><input name="cheMolino2" type="checkbox" id="cheMolino2" value="1" /></td>
        <td><input name="cheMolino3" type="checkbox" id="cheMolino3" value="1" /></td>
        <td><input name="cheMolino4" type="checkbox" id="cheMolino4" value="1" /></td>
      </tr>
      <tr>
        <td>&nbsp;Molino 5 </td>
        <td align="center"><input name="cheMolino5" type="checkbox" id="cheMolino5" value="1" /></td>
      <!--<td>&nbsp;</td>
        <td>&nbsp;</td>-->
      </tr>
    </table>
    <br />
    <table border="1" cellpadding="3"  style="border:1px solid #e6e6e6">
      <tr style="font-weight:bold">
        <td>PILA</td>
        <td>pH</td>
        <td>TEMP</td>
        <td>CE</td>
      </tr>
      <tr>
        <td><select class="form-control" name="cbxPila" id="cbxPila" required>
          <option value="">Selecciona</option>
          <option value="1">Pila 1</option>
          <option value="2">Pila 2</option>
          <option value="3">Limpia</option>
        </select>
      </td>
      <td><input class="form-control" name="txtPh" type="text" id="txtPh" size="5" required/></td>
      <td><input class="form-control" name="txtTemp" type="text" id="txtTemp" size="5" required/></td>
      <td><input class="form-control" name="txtCe" type="text" id="txtCe" size="5" required/></td>
    </tr>
    <tr>
      <td>
        <select class="form-control"  name="cbxPila2" id="cbxPila2">
        <option value="">Selecciona</option>
        <option value="1">Pila 1</option>
        <option value="2">Pila 2</option>
        <option value="3">Limpia</option>
      </select>
    </td>
    <td><input class="form-control"  name="txtPh2" type="text" id="txtPh2" size="5"/></td>
    <td><input class="form-control"  name="txtTemp2" type="text" id="txtTemp2" size="5"/></td>
    <td><input class="form-control"  name="txtCe2" type="text" id="txtCe2" size="5"/></td>
  </tr>
</table>
</div>
</div>