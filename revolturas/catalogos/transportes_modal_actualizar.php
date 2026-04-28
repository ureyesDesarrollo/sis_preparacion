<?php

require_once '../../conexion/conexion.php';
include "../../seguridad/user_seguridad.php";

$conn = Conectarse();

$id_transporte = isset($_POST['id']) ? intval($_POST['id']) : 0;


/* ==========================================
OBTENER TRANSPORTE
========================================== */

function obtenerTransportePorId($conn, $id_transporte)
{
  $transporte = [
    'id' => 0,
    'nombre' => ''
  ];

  if ($id_transporte > 0) {

    $id_transporte = (int)$id_transporte;

    $sql = "SELECT *
            FROM rev_transportistas
            WHERE trans_id = $id_transporte";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
      throw new Exception('Error al consultar el transporte: ' . mysqli_error($conn));
    }

    if ($row = mysqli_fetch_assoc($result)) {
      $transporte['id'] = $row['trans_id'];
      $transporte['nombre'] = $row['trans_nombre'];
    }
  }

  return $transporte;
}


/* ==========================================
OBTENER PARAMETROS
========================================== */

function obtenerParametrosTransporte($conn, $id_transporte)
{
  $parametros = [];

  $sql = "SELECT *
          FROM transportistas_parametros
          WHERE transportista_id = $id_transporte
          ORDER BY id";

  $result = mysqli_query($conn, $sql);

  if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {
      $parametros[] = $row;
    }
  }

  return $parametros;
}


$transporte = obtenerTransportePorId($conn, $id_transporte);
$parametros = obtenerParametrosTransporte($conn, $id_transporte);

?>

<div class="modal-dialog modal-lg">
  <div class="modal-content">

    <div class="modal-header">
      <h5 class="modal-title">Actualizar Transporte</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>


    <form id="formActualizarTransporte" autocomplete="off" novalidate>

      <div class="modal-body">

        <input type="hidden" id="trans_id" value="<?= htmlspecialchars($id_transporte) ?>">


        <div class="mb-3">
          <label class="form-label">Nombre del Transporte</label>

          <input type="text"
            class="form-control"
            id="transporte_nombre_act"
            value="<?= htmlspecialchars($transporte['nombre']) ?>"
            required>

          <div class="invalid-feedback">
            Por favor ingrese el nombre del transportista
          </div>

        </div>


        <hr>


        <div class="row mb-2">

          <div class="col-md-8">
            <h6>Parámetros</h6>
          </div>

          <div class="col-md-4 text-end">
            <button type="button"
              class="btn btn-outline-primary btn-sm"
              id="agregarParametro">

              <i class="fas fa-plus"></i> Agregar

            </button>
          </div>

        </div>


        <div id="parametros_container">


          <?php foreach ($parametros as $p): ?>

            <div class="row mb-2 parametro-item">

              <input type="hidden"
                class="par_id"
                value="<?= $p['id'] ?>">


              <div class="col-md-5">

                <label class="form-label">Campo</label>

                <input type="text"
                  class="form-control campo"
                  value="<?= htmlspecialchars($p['campo']) ?>">

              </div>


              <div class="col-md-5">

                <label class="form-label">Etiqueta</label>

                <input type="text"
                  class="form-control etiqueta"
                  value="<?= htmlspecialchars($p['etiqueta']) ?>">

              </div>


              <div class="col-md-2 d-flex align-items-end">

                <button type="button"
                  class="btn btn-outline-danger eliminarParametro">

                  <i class="fas fa-trash"></i>

                </button>

              </div>

            </div>

          <?php endforeach; ?>


        </div>

      </div>


      <div class="modal-footer bg-light">

        <button type="button"
          class="btn btn-outline-secondary"
          data-bs-dismiss="modal">

          Cancelar

        </button>


        <button type="submit"
          class="btn btn-outline-primary">

          <i class="fas fa-save me-2"></i>Actualizar

        </button>

      </div>


    </form>

  </div>
</div>



<script>
  $(document).ready(function() {


    /* ================================
    SUBMIT FORM
    ================================ */

    $('#formActualizarTransporte').on('submit', function(e) {

      e.preventDefault();
      insertar_datos();

    });


    /* ================================
    AGREGAR PARAMETRO
    ================================ */

    $('#agregarParametro').on('click', function() {

      let html = `

<div class="row mb-2 parametro-item">

<input type="hidden" class="par_id" value="0">

<div class="col-md-5">

<label class="form-label">Campo</label>

<input type="text" class="form-control campo">

</div>


<div class="col-md-5">

<label class="form-label">Etiqueta</label>

<input type="text" class="form-control etiqueta">

</div>


<div class="col-md-2 d-flex align-items-end">

<button type="button"
class="btn btn-outline-danger eliminarParametro">

<i class="fas fa-trash"></i>

</button>

</div>

</div>
`;

      $('#parametros_container').append(html);

    });


    /* ================================
    ELIMINAR PARAMETRO
    ================================ */

    $(document).on('click', '.eliminarParametro', function() {

      $(this).closest('.parametro-item').remove();

    });


  });


  /* ==========================================
  INSERTAR DATOS
  ========================================== */

  function insertar_datos() {

    const nombre = $('#transporte_nombre_act').val().trim();
    const id = $('#trans_id').val();


    if (isNaN(id) || id <= 0) {

      showToast('danger', 'ID de transporte inválido');
      return;

    }


    if (!nombre) {

      $('#transporte_nombre_act').addClass('is-invalid').focus();
      return;

    } else {

      $('#transporte_nombre_act').removeClass('is-invalid');

    }


    let parametros = [];


    /* ================================
    RECORRER PARAMETROS
    ================================ */

    $('#parametros_container .parametro-item').each(function() {

      let par_id = $(this).find('.par_id').val();
      let campo = $(this).find('.campo').val().trim();
      let etiqueta = $(this).find('.etiqueta').val().trim();

      if (campo !== '' && etiqueta !== '') {

        parametros.push({

          id: par_id,
          campo: campo,
          etiqueta: etiqueta

        });

      }

    });


    /* ================================
    VALIDAR PARAMETROS
    ================================ */

    if (parametros.length === 0) {

      showToast('danger', 'Debe agregar al menos un parámetro');
      return;

    }


    /* ================================
    AJAX
    ================================ */

    $.ajax({

      type: 'POST',

      url: 'catalogos/transportes_actualizar.php',

      contentType: 'application/json',

      dataType: 'json',

      data: JSON.stringify({

        id: id,
        nombre: nombre,
        parametros: parametros

      }),


      success: function(response) {

        if (response.success) {

          showToast('success', response.message || 'Transporte actualizado');

          $('#dataTableTransportes').DataTable().ajax.reload();

        } else {

          showToast('danger', response.message || 'Error al actualizar');

        }

      },


      error: function(xhr) {

        let mensaje = 'Error al conectar con el servidor';

        if (xhr.responseJSON && xhr.responseJSON.message) {

          mensaje = xhr.responseJSON.message;

        }

        showToast('danger', mensaje);

      }

    });


  }



  /* ==========================================
  TOAST
  ========================================== */

  function showToast(type, message) {

    let $container = $('#toast-container');

    if ($container.length === 0) {

      $container = $('<div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999;"></div>');
      $('body').append($container);

    }

    const icon = type === 'success' ? 'fa-check-circle' : 'fa-times-circle';

    const toastId = 'toast-' + Date.now();

    const $toast = $(`
<div id="${toastId}" class="toast align-items-center text-bg-${type} border-0 show mb-2">

<div class="d-flex">

<div class="toast-body">

<i class="fas ${icon} me-2"></i>${message}

</div>

<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>

</div>

</div>
`);

    $container.append($toast);

    const toast = new bootstrap.Toast($toast[0], {
      delay: 3000
    });

    toast.show();

    setTimeout(function() {

      $toast.fadeOut(400, function() {

        $(this).remove();

      });

    }, 3200);

  }
</script>
