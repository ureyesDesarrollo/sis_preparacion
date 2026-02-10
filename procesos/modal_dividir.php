  <?php
  /*Desarrollado por: Ca & Ce Technologies */
  /*Contacto: mc.munoz.rz@gmail.com */
  /*21 - Agosto - 2018*/
  include "../seguridad/user_seguridad.php";
  include "../conexion/conexion.php";
  include "../funciones/funciones.php";
  $cnx =  Conectarse();

  $cadena = mysqli_query($cnx, "SELECT *
   FROM inventario 
   WHERE inv_id = '" . $_POST['inv_id'] . "'") or die(mysql_error() . "Error: en consultar el inventario");
  $registros = mysqli_fetch_assoc($cadena);

  ?>

  <script>
    $(document).ready(function() {
      $("#formModal").submit(function() {

        var formData = $(this).serialize();
        $.ajax({
          url: "inventario_dividir.php",
          type: 'POST',
          data: formData,
          success: function(result) {
            data = JSON.parse(result);
            alertas("#alerta-errorTipoEditar", 'Listo!', data["mensaje"], 1, true, 5000);
            var inv = document.getElementById("hdd_id").value;
            var parametro = document.getElementById("hdd_param").value;
            var material = document.getElementById("hdd_mat").value;

            return kilos(parametro, inv, material);
          }
        });
        confirmEnviar2();
        return false;
      });
    });

    function fnc_restaCantidad() {

      var cantidad_toma = parseFloat(document.getElementById("txtToma").value);
      var kilos = parseFloat(document.getElementById("txtKg").value);

      if (cantidad_toma < kilos && cantidad_toma != 0) {
        var val = kilos - cantidad_toma;

        document.getElementById("txtSobra").value = val;
      } else if (cantidad_toma > kilos) {
        alert("La cantidad a tomar debe ser menor a " + kilos);
        document.getElementById("txtToma").value = '';
        document.getElementById("txtSobra").value = '';
      } else if (cantidad_toma == kilos) {
        alert("No puede parcializar cantidad igual a " + kilos);
        document.getElementById("txtToma").value = '';
        document.getElementById("txtSobra").value = '';
      } else {
        alert("No puede parcializar cantidad en 0");
        document.getElementById("txtToma").value = '';
        document.getElementById("txtSobra").value = '';

      }

    }
  </script>


  <div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="formModal" name="formModal">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Dividir kilos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">Kilos totales:</label>
            <input name="txtKg" type="text" class="form-control" id="txtKg" required placeholder="" value="<?php echo $registros['inv_kg_totales'] ?>" readonly="true">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label">Cantidad a tomar:</label>
            <input name="txtToma" onKeyPress="return isNumberKey(event, this);" type="text" class="form-control" id="txtToma" required placeholder="Cantidad" value="" onchange="fnc_restaCantidad();">
            <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['inv_id'] ?>" />
            <input type="hidden" name="hdd_mat" id="hdd_mat" value="<?php echo $registros['mat_id'] ?>">
          </div>
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">Sobrante:</label>
            <input name="txtSobra" type="text" class="form-control" id="txtSobra" required placeholder="" value="" readonly="true">
            <input name="hdd_param" type="hidden" id="hdd_param" value="<?php echo $_POST['param']; ?>" />
          </div>
          <div class="modal-footer" style="margin-top: 8%;">
            <!--mensajes-->
            <div class="alert alert-info hide" id="alerta-errorTipoEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
              <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
              <strong>Titulo</strong> &nbsp;&nbsp;
              <span> Mensaje </span>
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <img src="../iconos/close.png" alt="">Cerrar</button>
            <button class="btn btn-primary" type="submit" id="btn" name="btn"><img src="../iconos/guardar.png" alt=""> Guardar</button>
          </div>
        </div>
      </form>

    </div>
  </div>