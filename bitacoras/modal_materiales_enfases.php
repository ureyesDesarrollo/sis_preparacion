<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

?>
<script>
  $(document).ready(function() {

    $("#cbxMaterial").change(function() {
      $("#cbxMaterial option:selected").each(function() {
        mat_id = $(this).val();
        $.post("getInvTot.php", {
          mat_id: mat_id
        }, function(data) {
          $("#cbxKilosID").html(data);

        });
      });
    })

    $("#cbxKilosID").change(function() {
      $("#cbxKilosID option:selected").each(function() {
        inv_id = $(this).val();
        $.post("getFecha.php", {
          inv_id: inv_id
        }, function(data) {
          $("#txtFecha").val(data);
        });
      });
    })


    //$(document).ready(function() {
    $("#form_modal_en_fases").submit(function() {
      var formData = $(this).serialize();
      $.ajax({
        url: "inventario_agregar.php",
        type: 'POST',
        data: formData,
        beforeSend: function() {
          Swal.fire({
            /* title: 'Procesando', */
            allowOutsideClick: false,
            imageUrl: '../iconos/Loader.gif',
            /*  imageWidth: 100,
             imageHeight: 78, */
            showConfirmButton: false
          });
          confirmEnviar();
        },
        success: function(result) {

          data = JSON.parse(result);
          alertas("#alerta-errorAgregar", 'Listo!', data["mensaje"], 1, true, 5000);
          $('#form_modal_en_fases').each(function() {
            this.reset();
          });
          swal.close();

          setTimeout(location.reload(), 23000);
        }
      });
      return false;

    });
    //});



  });


  //Bloquear boton al agregar material
  function confirmEnviar() {

    form_modal_en_fases.btn_modal_en_fases.disabled = true;
    form_modal_en_fases.btn_modal_en_fases.value = "Enviando...";

    setTimeout(function() {
      form_modal_en_fases.btn_modal_en_fases.disabled = true;
      form_modal_en_fases.btn_modal_en_fases.value = "Guardar";
    }, 2000);

    var statSend = false;
    return false;
  }

  //abre modal dividir kilos
  function AbreModalEditarB(param) {
    //alert('aqui');
    var datos = {
      "inv_id": $("#cbxKilosID").val(),
      "param": param,
    }
    //alert($("#cbxKilosID"+param).val());
    $.ajax({
      type: 'post',
      url: 'modal_dividir_enfases.php',
      data: datos,
      //data: {nombre:n},
      success: function(result) {
        $("#upload-avatar").html(result);
        $('#upload-avatar').modal('show')
      }
    });
    return false;
  }


  function fnc_restaCantidad() {
    var val = document.getElementById("txtKg").value - document.getElementById("txtToma").value;

    document.getElementById("txtSobra").value = val;

  }

  function ponCantidad() {

    var combo = document.getElementById('cbxKilosID');
    var val = combo.options[combo.selectedIndex].text;
    document.getElementById('txtKilos').value = val;
  }
</script>
<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
  <div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="form_modal_en_fases" name="form_modal_en_fases">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Agregar kilos en fases</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label">Tipo material:</label>
            <select id="cbxMaterial" class="form-control" name="cbxMaterial">
              <option value="">Seleccionar</option>
              <?php
              $html = '';
              $cadena_mat =  mysqli_query($cnx, "SELECT * from materiales ");
              $registros =  mysqli_fetch_array($cadena_mat);
              do {
                $html .= "<option value='" . $registros['mat_id'] . "'>" . $registros['mat_nombre'] . "</option>";
              } while ($registros =  mysqli_fetch_array($cadena_mat));
              echo $html;
              ?>
            </select>
          </div>
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label">Kilos:</label>
            <select id="cbxKilosID" class="form-control" name="cbxKilosID" onchange="ponCantidad()">
              <option value="0">Seleccionar</option>
            </select>
            <input type="hidden" id="<?php echo "txtKilos" ?>" name="<?php echo "txtKilos" ?>">
          </div>
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">Fecha Entrada:</label>
            <input type="text" class="form-control" id="txtFecha" name="txtFecha" readonly="true" size="10">
          </div>
          <div class="col-md-2">
            <!--   <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $_POST['pro_id'] ?>"/>
          <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $_POST['pro_id'] ?>"/>-->
            <input type="hidden" name="hdd_id" id="hdd_id" value="<?php echo $reg_pro['pro_id'] ?>">

            <a id="<?php echo "btn2" . $i ?>" href="#" onClick="javascript:AbreModalEditarB()"><img src="../iconos/division.png" style="padding-top: 20px"></a>
          </div>
          <div class="modal-footer" style="margin-top: 8%;">
            <!--mensajes-->
            <div class="alert alert-info hide" id="alerta-errorAgregar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
              <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
              <strong>Titulo</strong> &nbsp;&nbsp;
              <span> Mensaje </span>
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();"><img src="../iconos/close.png" alt="">Cerrar</button>
            <button class="btn btn-primary" type="submit" id="btn_modal_en_fases" name="btn_modal_en_fases"><img src="../iconos/guardar.png" alt=""> Guardar</button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

<div class="modal" id="upload-avatar" tabindex="-1" role="dialog" aria-labelledby="upload-avatar-title" aria-hidden="true">
</div>