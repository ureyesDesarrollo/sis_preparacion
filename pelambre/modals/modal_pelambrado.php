  <?php
  /*Desarrollado por: Ca & Ce Technologies */
  /*Contacto: mc.munoz.rz@gmail.com */
  /*21 - Agosto - 2018*/
  include "../../conexion/conexion.php";
  include "../../funciones/funciones.php";


  $cnx =  Conectarse();

  $cadena = mysqli_query($cnx, "SELECT *
   FROM inventario 
   WHERE inv_id = '" . $_POST['inv_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
  $registros = mysqli_fetch_assoc($cadena);

  ?>
  <script type="text/javascript" src="../js/alerta.js"></script>
  <script>
    $(document).ready(function() {
      $("#form_pelambrado").submit(function() {
        var btn = $("#btn");
        btn.prop("disabled", true);
        btn.html('<img src="../iconos/guardar.png" alt=""> Enviando...');
        //alert('editar');
        var formData = $(this).serialize();
        $.ajax({
          url: "modals/inventario_dividir.php",
          type: 'POST',
          data: formData,
          success: function(result) {
            data = JSON.parse(result);
            //alert("Guardo el registro");
            alertas_v5("#alerta-error_dividir", 'Listo!', data["mensaje"], 1, true, 5000);
            setTimeout(function() {
              btn.prop("disabled", false);
              btn.html('<img src="../iconos/guardar.png" alt="">Guardar');
            }, 2000);
          },
          error: function(xhr, status, error) {
            alert("Hubo un error: " + error);

            setTimeout(function() {
              btn.prop("disabled", false);
              btn.html('<img src="../iconos/guardar.png" alt="">Guardar');
            }, 2000);
          }
        });

        return false;
      });
    });





    function fnc_restaCantidad() {
      if (parseFloat(document.getElementById("txt_pelambrado").value) <= parseFloat(document.getElementById("txtKg").value) && parseFloat(document.getElementById("txt_pelambrado").value) != '0') {
        var val = document.getElementById("txtKg").value - document.getElementById("txt_pelambrado").value;

        document.getElementById("txtSobra").value = parseFloat(val).toFixed(2);;
        // document.getElementById("txtSobraH").value = val;
      } else {
        alert("La cantidad a enviar no puede igual a '0' o mayor a los kilos totales");
        document.getElementById("txt_pelambrado").value = '';
        document.getElementById("txtSobra").value = '';
      }

    }

    function fnc_validad_0() {
      var pelambrado = parseFloat(document.getElementById("txt_pelambrado").value);
      var kilos = parseFloat(document.getElementById("txtKg").value);

      if (pelambrado <= kilos) {
        alert('La cantidad pelambrada debe ser mayor a ' + kilos);
        document.getElementById("txt_pelambrado").value = '';
      }
    }
  </script>

  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="form_pelambrado" name="form_pelambrado">
        <div class="modal-header">
          <h5 class="modal-title">Pelambrado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <label for="recipient-name" class="col-form-label">Kilos totales:</label>
              <input name="txtKg" type="text" class="form-control" id="txtKg" required placeholder="" value="<?php echo $registros['inv_kilos'] ?>" readonly="true">
            </div>

            <div class="col-md-4">
              <label for="recipient-name" class="col-form-label">Pelambrar:</label>
              <input name="txt_pelambrado" onkeypress="return isNumberKey(event, this);" type="text" class="form-control" id="txt_pelambrado" onkeyup="fnc_restaCantidad()" required placeholder="Cantidad" value="">
              <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['inv_id'] ?>" />
            </div>
            <div class="col-md-4">
              <label for="recipient-name" class="col-form-label">Sobrante:</label>
              <input name="txtSobra" type="text" class="form-control" id="txtSobra" required placeholder="" value="" readonly="true">
              <input name="hdd_param" type="hidden" id="hdd_param" value="<?php echo $_POST['param']; ?>" />
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between align-items-center flex-wrap">
          <div class="col-12 col-md-6 mb-3">
            <div id="alerta-error_dividir" class="alert alert-success d-none m-0">
              <strong class="alert-heading"></strong>
              <span class="alert-body"></span>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload()"><img src="../iconos/close.png" alt="">Cerrar</button>
            <button class="btn btn-primary" type="submit" id="btn" name="btn"><img src="../iconos/guardar.png" alt=""> Guardar</button>
          </div>
        </div>
      </form>
    </div>
  </div>