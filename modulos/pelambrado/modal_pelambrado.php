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

  <script>
    $(document).ready(function() {
      $("#form_pelambrado").submit(function() {
        //alert('editar');
        var formData = $(this).serialize();
        $.ajax({
          url: "pelambrado/inventario_dividir.php",
          type: 'POST',
          data: formData,
          success: function(result) {
            data = JSON.parse(result);
            //alert("Guardo el registro");
            alertas("#alerta-error_dividir", 'Listo!', data["mensaje"], 1, true, 5000);
            //$('#form').each (function(){this.reset();});  
            confirmEnviar_form_pelmbrado();
            //$("#listadoamaquila").load("inventario_listado_a_maquila.php");
            //refresh();
          }
        });

        return false;
      });
    });

    //Bloquear boton al dividir material
    function confirmEnviar_form_pelmbrado() {
      form_pelambrado.btn.disabled = true;
      form_pelambrado.btn.value = "Enviando...";

      setTimeout(function() {
        form_pelambrado.btn.disabled = true;
        form_pelambrado.btn.value = "Guardar";
      }, 2000);

      var statSend = false;
      return false;
    }



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


  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <form id="form_pelambrado" name="form_pelambrado">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Pelambrado</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <label for="recipient-name" class="col-form-label">Kilos totales:</label>
              <input name="txtKg" type="text" class="form-control" id="txtKg" required placeholder="" value="<?php echo $registros['inv_kilos'] ?>" readonly="true">
            </div>
            <!--   <div class="col-md-3">
              <label for="recipient-name" class="col-form-label">Equipo:</label>
              <select type="text" class="form-control" id="slc_equipo" name="slc_equipo" style="width:220px" required>
                <option value="">Selecciona</option>
                <?php
                $consulta =  mysqli_query($cnx, "SELECT * FROM equipos_preparacion WHERE ep_tipo = 'X'AND estatus = 'A' order by ep_descripcion");
                $reg_equipo = mysqli_fetch_assoc($consulta);
                do {
                ?>
                  <option value="<?php echo $reg_equipo['ep_id'] ?>">
                    <?php echo $reg_equipo['ep_descripcion']; ?>
                  </option>
                <?php  } while ($reg_equipo = mysqli_fetch_assoc($consulta));
                ?>
              </select>
            </div> -->
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