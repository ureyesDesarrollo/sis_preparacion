<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
?>
<script>
  $(document).ready(function() {
    $("#cbxProveedor").change(function() {
      var id = $('#cbxProveedor').val();
      $('#FillTipo').load('extras/getInventario.php?id=' + id);
    });
  });

  $(document).ready(function() {
    $("#cbxProveedor").change(function() {
      $("#cbxProveedor option:selected").each(function() {
        id = $(this).val();
        $.post("extras/get_tipo_prov.php", {
          id: id
        }, function(data) {
          var campo = document.getElementById("cbxUbicacion");
          if (data == 'SI') {
            campo.required = false;
            campo.setAttribute("readonly", "true");
            campo.style.pointerEvents = "none";
          } else {
            campo.required = true;
            campo.removeAttribute("readonly");
            campo.style.pointerEvents = "auto";
          }
        });
      });
    })
  });

  function valida_cero() {
    var entrada = document.getElementById('txtKg').value;
    if (entrada <= '0') {
      alert('La cantidad a ingresar debe ser mayor que 0');
      var entrada = document.getElementById('txtKg').value = '';
    }
  }
</script>
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <form id="form">
      <div class="modal-header">
        <div class="col-md-11">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Alta inventario</h5>
        </div>
        <div class="col-md-1">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span>*</span> No. Ticket:</label>
            <input onchange="valida_ticket()" name="txtNoTicket" type="text" class="form-control" id="txtNoTicket" onkeypress="return isNumberKey(event, this);" maxlength="6" required placeholder="No. Ticket">
          </div>

          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span>*</span> Placas:</label>
            <input name="txtPlacas" type="text" class="form-control" id="txtPlacas" maxlength="9" required placeholder="Placas">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span>*</span> Camioneta:</label>
            <input name="txtCamioneta" type="text" class="form-control" id="txtCamioneta" maxlength="15" required placeholder="Camioneta">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span>*</span> Proveedor:</label>
            <!-- <span class="col-md-3">-->
            <select name="cbxProveedor" class="form-control" id="cbxProveedor" required="required">
              <option value="">Seleccionar Proveedor</option>
              <?php
              $cad_cbx =  mysqli_query($cnx, "SELECT * FROM proveedores WHERE prv_est = 'A' AND prv_mql = 'N' OR prv_mql = 'C' ORDER BY prv_nombre") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
              $reg_cbx =  mysqli_fetch_array($cad_cbx);

              do {

                if ($reg_cbx['prv_ban']  == '2') {
                  $estilo = "<span style='font-weight:bold'> (Maquila) </span>";
                } else if ($reg_cbx['prv_tipo'] == 'L' && $reg_cbx['prv_ban'] == 1) {
                  $estilo = "<span style='font-weight:bold'> (Especial) </span>";
                } else if ($reg_cbx['prv_tipo'] == 'E') {
                  $estilo = "<span style='font-weight:bold'> (Extranjero) </span>";
                } else {
                  $estilo = "<span style='font-weight:bold'> (Local) </span>";
                }
              ?>

                <?php
                if ($reg_cbx['prv_tipo'] == 'L' && $reg_cbx['prv_ban'] == '1') { ?>
                  <option style="background:#E6E6" value="<?php echo $reg_cbx['prv_id'] ?>"><?php echo $reg_cbx['prv_nombre'] . $estilo ?></option>
                <?php } elseif ($reg_cbx['prv_tipo'] == 'L') { ?>
                  <option style="background:#FFF" value="<?php echo $reg_cbx['prv_id'] ?>"><?php echo $reg_cbx['prv_nombre'] . $estilo ?></option>
                <?php }
                if ($reg_cbx['prv_tipo'] == 'E') { ?>
                  <option style="background:#F7FEA0" value="<?php echo $reg_cbx['prv_id'] ?>"><?php echo $reg_cbx['prv_nombre'] . $estilo ?></option>
              <?php }
              } while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
              ?>
            </select>
            <!--</span>-->
          </div>

          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span>*</span> Ubicación:</label>
            <!-- <span class="col-md-3">-->
            <select name="cbxUbicacion" class="form-control" id="cbxUbicacion" required="required">
              <option value="">Seleccionar ubicación</option>
              <?php
              $cad_cbx =  mysqli_query($cnx, "SELECT * FROM almacen_cajones WHERE ac_estatus = 'A' AND ac_ban = 'M' ORDER BY ac_descripcion") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
              $reg_cbx =  mysqli_fetch_array($cad_cbx);

              do {
              ?>
                <option value="<?php echo $reg_cbx['ac_id'] ?>"><?php echo $reg_cbx['ac_descripcion']; ?></option>
              <?php
              } while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
              ?>
            </select>
            <!--</span>-->
          </div>

        </div>


        <div id="FillTipo">

        </div>

      </div>
      <div class="modal-footer">
        <div class="col-sm-6 col-lg-7">
          <div class="alert alert-danger" id="alerta-ticket" style="height: 40px;display:none;text-align:left">
            <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
            <strong>Titulo</strong> &nbsp;&nbsp;
            <span> Mensaje </span>
          </div>
        </div>
        <!--mensajes-->
        <div class="alert alert-info hide" id="alerta-errorProvAlta" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
          <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
          <strong>Titulo</strong> &nbsp;&nbsp;
          <span> Mensaje </span>
        </div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button>
        <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
      </div>
    </form>
  </div>
</div>