<?php
require '../../conexion/conexion.php';
require '../funciones_procesos.php';
include('../../seguridad/user_seguridad.php');
header("Content-Type: text/html;charset=utf-8");

try {
  $cnx =  Conectarse();
  $pro_id = mysqli_real_escape_string($cnx, $_POST['pro_id']); //* Numero de proceso
  $pe_id = mysqli_real_escape_string($cnx, $_POST['pe_id']); //* ID de la etapa

  //* Obtener quimicos
  $query_quimicos = mysqli_query($cnx, "SELECT quimico_id, quimico_descripcion FROM quimicos 
  WHERE quimico_est = 'A' ORDER BY quimico_descripcion");
  $quimicos = [];
  while ($item = mysqli_fetch_assoc($query_quimicos)) {
    $quimicos[] = $item;
  }

  //* Quimicos por etapa y por proceso
  $query_quimicos_proceso = mysqli_query($cnx, "SELECT * FROM quimicos_etapas WHERE pro_id = '$pro_id' and pe_id = '$pe_id'");
  $quimicos_registrados = [];
  while ($row = mysqli_fetch_assoc($query_quimicos_proceso)) {
    $quimicos_registrados[] = $row;
  }

  //* Obtener el nombre de la etapa
  $query_nombre_etapa = mysqli_query($cnx, "SELECT pe_nombre,pe_descripcion FROM preparacion_etapas WHERE pe_id = '$pe_id'");
  $nombre_etapa = '';
  $etapa_descripcion = '';
  if ($query_nombre_etapa && mysqli_num_rows($query_nombre_etapa) > 0) {
    $datos_etapa = mysqli_fetch_assoc($query_nombre_etapa);
    $nombre_etapa = $datos_etapa['pe_nombre'];
    $etapa_descripcion = $datos_etapa['pe_descripcion'];
  }

  $TOTALFILAS = 7;

  //* Obtener si el proceso existe en procesos_auxiliar
  $query_proceso_aux = mysqli_query($cnx, "SELECT 1 FROM procesos_auxiliar WHERE pro_id = '$pro_id' LIMIT 1");
  $existe_proceso_aux = mysqli_num_rows($query_proceso_aux) > 0 ? true : false;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
} finally {
  mysqli_close($cnx);
}
?>

<div class="modal-dialog" role="document" style="width: 50%">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" style="text-align: center;font-size: 24px;text-transform: uppercase;">
        <img src="../iconos/matraz.png" alt="">
        QUÍMICOS <?= $nombre_etapa ?> (<?= $etapa_descripcion ?>)
        <img src="../iconos/matraz.png" alt="">
      </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body" id="cargar_quimicos">
      <form id="form_quimicos">
        <input type="hidden" value="<?= $pe_id ?>" id='pe_id' name="pe_id">
        <input type="hidden" value="<?= $pro_id ?>" id='pro_id' name="pro_id">

        <div class="row mb-2">
          <div class="col-md-3"><label>Fecha sistema:</label></div>
          <div class="col-md-4"><label>Tipo químico:</label></div>
          <div class="col-md-3"><label>Lote:</label></div>
          <div class="col-md-2"><label>Litros:</label></div>
        </div>

        <?php
        $i = 1;
        foreach ($quimicos_registrados as $q) {
        ?>
          <div class="row mb-2">
            <input type="hidden" name="quimico_id_existente<?= $i ?>" value="<?= $q['quim_id'] ?>">
            <div class="col-md-3">
              <input readonly type="text" class="form-control" name="txt_fecha<?= $i ?>" value="<?= $q['quim_fecha'] ?>">
            </div>
            <div class="col-md-4">
              <select class="form-control" name="cbx_quimico<?= $i ?>" disabled>
                <option value="">Seleccionar</option>
                <?php foreach ($quimicos as $q_op) : ?>
                  <option value="<?= $q_op['quimico_id'] ?>" <?= $q_op['quimico_id'] == $q['quimico_id'] ? 'selected' : '' ?>>
                    <?= $q_op['quimico_descripcion'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <input type="hidden" name="cbx_quimico<?= $i ?>" value="<?= $q['quimico_id'] ?>">
            </div>
            <div class="col-md-3">
              <input readonly type="text" class="form-control" name="txt_lote_quim<?= $i ?>" value="<?= $q['quim_lote'] ?>">
            </div>
            <div class="col-md-2">
              <input readonly type="text" class="form-control" name="txt_litro_quim<?= $i ?>" value="<?= $q['quim_litros'] ?>">
              <input type="hidden" name="litros_anterior<?= $i ?>" value="<?= $q['quim_litros'] ?>">
            </div>
          </div>
        <?php
          $i++;
        }

        while ($i <= $TOTALFILAS) {
        ?>
          <div class="row mb-2">
            <div class="col-md-3">
              <input type="date" class="form-control" name="txt_fecha<?= $i ?>" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-4">
              <select class="form-control" name="cbx_quimico<?= $i ?>">
                <option value="">Seleccionar</option>
                <?php foreach ($quimicos as $q_op) : ?>
                  <option value="<?= $q_op['quimico_id'] ?>"><?= $q_op['quimico_descripcion'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control" name="txt_lote_quim<?= $i ?>" placeholder="Lote">
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" name="txt_litro_quim<?= $i ?>" placeholder="Litros" maxlength="5" onkeypress="return isNumberKey(event, this);">
            </div>
          </div>
        <?php
          $i++;
        }
        ?>

        <div class="modal-footer">
          <div class="form-group col-md-7">
            <div class="alert alert-info hide" id="alerta" style="height: 30px">
              <div style="margin-top: -10px">
                <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                <strong>Titulo</strong> &nbsp;&nbsp;
                <span> Mensaje </span>
              </div>
            </div>
          </div>
          <div class="form-group col-md-5">
            <button type="button" class="btn btn-warning" onclick="solicitarAutorizacion()"> Solicitar actualización</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" <?= $existe_proceso_aux ? '' : 'disabled' ?>>Guardar</button>
          </div>
        </div>
      </form>

      <div id="formularioAutorizacionQuimicos" style="display: none;">
        <div class="card shadow-sm border-primary">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Autorización requerida</h5>
          </div>
          <div class="card-body">
            <form id="formAutorizacionQuimicos">
              <div class="form-group">
                <label for="clave">Clave de autorización</label>
                <input type="password" class="form-control" id="clave" placeholder="Ingresa la clave" required>
              </div>
              <div class="mt-4 d-flex justify-content-end">
                <button type="button" class="btn btn-primary mr-2" id="btnAutorizarQuimicos">Autorizar</button>
                <button type="button" class="btn btn-secondary" id="btnCancelarFinos">Cancelar</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $("#form_quimicos").submit(function(e) {
      e.preventDefault();

      let valid = true;
      let mensaje = '';

      // Recorremos todos los campos de litros que ya están registrados
      $("input[name^='txt_litro_quim']").each(function() {
        const inputName = $(this).attr('name');
        const match = inputName.match(/txt_litro_quim(\d+)/);
        if (match) {
          const index = match[1];
          const quimicoIdExistente = $(`input[name='quimico_id_existente${index}']`).val();
          console.log($(`input[name='quimico_id_existente${index}']`).val());

          if (quimicoIdExistente) {
            // Este es un registro ya guardado, comparamos el valor original con el actual
            const valorAnterior = parseFloat($(`input[name='litros_anterior${index}']`).val()) || 0;
            const valorNuevo = parseFloat($(this).val());

            console.log(valorAnterior);
            console.log(valorNuevo);

            // En caso de que luego habilites edición, puedes cambiar esto por otra fuente
            if (valorNuevo < valorAnterior) {
              valid = false;
              mensaje += `Fila ${index}: No se permite registrar un valor menor a ${valorAnterior} litros.\n`;
            }
          }
        }
      });

      if (!valid) {
        alertas("#alerta", 'Error!', mensaje, 3, true, 5000);
        return;
      }

      const formData = $(this).serialize();
      $.ajax({
        url: "quimicos/quimicos_insertar.php",
        type: 'POST',
        data: formData,
        success: function(result) {
          if (result['success'] == false) {
            alertas("#alerta", 'Error!', result["mensaje"], 3, true, 5000);
            return;
          }
          alertas("#alerta", 'Listo!', result["mensaje"], 1, true, 5000);
          const pe_id = document.getElementById('pe_id').value;
          const pro_id = document.getElementById('pro_id').value;
          cargarQuimicos('#cargar_quimicos', 'quimicos/get_quimicos.php?pro_id=' + pro_id + '&pe_id=' + pe_id);
        }
      });
    });

  });

  function cargarQuimicos(contenedor, url) {
    $(contenedor).load(url);
  }

  function solicitarAutorizacion() {
    $('#formularioAutorizacionQuimicos').show();
    $('#clave').focus();
  }

  function autorizar(clave) {
    if (clave) {
      $.ajax({
        url: "../revolturas/administrador/autorizacion_clave.php",
        type: "POST",
        dataType: "json",
        data: {
          usu_clave_auth: clave
        },
        success: function(response) {
          if (response.success) {
            $('#formAutorizacionQuimicos')[0].reset();
            $('#formularioAutorizacionQuimicos').hide();
            alert(response.success);
            $("input[name^='txt_litro_quim']").each(function() {
              $(this).removeAttr('readonly');
            });
          } else {
            alert("Error: " + response.error);
          }
        },
        error: function() {
          alert('Error en la validación de la clave');
        }
      });
    } else {
      alert("Por favor ingresa una clave de autorización.");
    }
  }

  $('#btnAutorizarQuimicos').on('click', function(e) {
    e.preventDefault();
    const clave = $('#clave').val();
    autorizar(clave);
  });
</script>