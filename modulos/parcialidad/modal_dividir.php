  <?php
  /*Desarrollado por: CCA Consultores TI */
  /*Contacto: info@ccaconsultoresti.com */
  /*Actualizado: Octubre-2023*/
  include "../../conexion/conexion.php";
  include "../../funciones/funciones.php";
  $cnx =  Conectarse();

  $cadena = mysqli_query($cnx, "SELECT *
   FROM inventario 
   WHERE inv_id = '" . $_POST['inv_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
  $registros = mysqli_fetch_assoc($cadena);

  ?>

  <script>
    $(document).ready(function() {
      $("#form_enviar_maquila").submit(function() {
        //alert('editar');
        var formData = $(this).serialize();
        $.ajax({
          url: "parcialidad/inventario_dividir.php",
          type: 'POST',
          data: formData,
          success: function(result) {
            data = JSON.parse(result);
            //alert("Guardo el registro");
            alertas("#alerta-error_dividir", 'Listo!', data["mensaje"], 1, true, 5000);
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
      if (parseFloat(document.getElementById("txtEnvia").value) <= parseFloat(document.getElementById("txtKg").value) && parseFloat(document.getElementById("txtEnvia").value) != '0') {
        var val = document.getElementById("txtKg").value - document.getElementById("txtEnvia").value;

        document.getElementById("txtSobra").value = parseFloat(val).toFixed(2);;
        // document.getElementById("txtSobraH").value = val;
      } else {
        alert("La cantidad a enviar no puede igual a '0' o mayor a los kilos totales");
        document.getElementById("txtEnvia").value = '';
        document.getElementById("txtSobra").value = '';
      }

    }
  </script>


  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form_enviar_maquila" name="form_enviar_maquila">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Enviar a maquila</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label for="recipient-name" class="col-form-label">Kilos totales:</label>
              <input name="txtKg" type="text" class="form-control" id="txtKg" required placeholder="" value="<?php echo $registros['inv_kilos'] ?>" readonly="true">
            </div>
            <div class="col-md-4">
              <label for="recipient-name" class="col-form-label">* Maquila:</label>
              <select name="cbx_maquila" class="form-control" id="cbx_maquila" required="required">
                <option value="">Seleccionar maquila</option>
                <?php
                $cad_cbx =  mysqli_query($cnx, "SELECT prv_id,prv_nombre FROM proveedores	WHERE prv_mql = 'S' OR prv_mql = 'C' ORDER BY prv_nombre") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
                $reg_cbx =  mysqli_fetch_array($cad_cbx);

                do { ?>
                  <option value="<?php echo $reg_cbx['prv_id'] ?>"><?php echo $reg_cbx['prv_nombre'] ?></option>
                <?php
                } while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
                ?>
              </select>
            </div>

            <div class="col-md-3">
              <label for="recipient-name" class="col-form-label">* Cantidad a enviar:</label>
              <input name="txtEnvia" type="text" class="form-control" id="txtEnvia" required placeholder="Cantidad" value="" onkeyup="fnc_restaCantidad();" onkeypress="return isNumberKey(event, this);">
              <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['inv_id'] ?>" />
            </div>
            <div class="col-md-2">
              <label for="recipient-name" class="col-form-label">Sobrante:</label>
              <input name="txtSobra" type="text" class="form-control" id="txtSobra" required placeholder="" value="" readonly="true">
              <input name="hdd_param" type="hidden" id="hdd_param" value="<?php echo $_POST['inv_id']; ?>" />
            </div>
          </div>
          <div class="modal-footer" style="margin-top: 8%;">
            <!--mensajes-->
            <div class="alert alert-info hide" id="alerta-error_dividir" style="height: 40px;width: 250px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
              <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
              <strong>Titulo</strong> &nbsp;&nbsp;
              <span> Mensaje </span>
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button>
            <button class="btn btn-primary" type="submit" id="btn" name="btn"><img src="../iconos/guardar.png" alt=""> Guardar</button>
          </div>
        </div>
      </form>

    </div>
  </div>