<?php

require_once '../../conexion/conexion.php';
include "../../seguridad/user_seguridad.php";
$conn = Conectarse();

$id_transporte = isset($_POST['id']) ? intval($_POST['id']) : 0;
function obtenerTransportePorId($conn, $id_transporte)
{
  $transporte = [
    'nombre' => ''
  ];

  if ($id_transporte > 0) {
    $sql = "SELECT * FROM rev_transportistas WHERE trans_id = $id_transporte";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
      throw new Exception('Error al consultar el transporte: ' . mysqli_error($conn));
    }
    if ($result && $row = mysqli_fetch_assoc($result)) {
      $transporte['id'] = $id_transporte;
      $transporte['nombre'] = $row['trans_nombre'];
    }
  }
  return $transporte;
}

$transporte = obtenerTransportePorId($conn, $id_transporte);

?>
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="modalAgregarTransporteLabel">Actualizar Transporte</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>
    <form id="formActualizarTransporte" autocomplete="off" novalidate>
      <div class="modal-body">
        <div class="row">
          <input type="hidden" name="trans_id" id="trans_id" value="<?php echo htmlspecialchars($id_transporte); ?>">
          <div class="col-md-12 mb-3">
            <label for="transporte_nombre_act" class="form-label">Nombre del Transporte</label>
            <input type="text" class="form-control" id="transporte_nombre_act" name="transporte_nombre_act" required value="<?php echo htmlspecialchars($transporte['nombre']); ?>">
            <div class="invalid-feedback">
              Por favor, ingrese el nombre del transportista.
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-outline-primary"><i class="fas fa-save me-2"></i>Actualizar</button>
        </div>
    </form>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#formActualizarTransporte').on('submit', function(e) {
      e.preventDefault();
      insertar_datos();
    });
  });

  function insertar_datos() {
    console.log($('#transporte_nombre_act').val());
    const nombre = $('#transporte_nombre_act').val().trim();
    const id = $('#trans_id').val();
    if (isNaN(id) || id <= 0) {
      showToast('danger', 'ID de transporte inválido.');
      return;
    }

    if (!nombre) {
      $('#transporte_nombre_act').addClass('is-invalid').focus();
      return;
    } else {
      $('#transporte_nombre_act').removeClass('is-invalid');
    }


    $.ajax({
      type: 'POST',
      url: 'catalogos/transportes_actualizar.php',
      data: JSON.stringify({
        id: id,
        nombre: nombre,
      }),
      success: function(response) {
        if (response.success) {
          showToast('success', response.message || 'Transporte actualizado');
          $('#dataTableTransportes').DataTable().ajax.reload();
        } else {
          showToast('danger', response.message || 'Error al actualizar tranporte');
        }
      },
      error: function(xhr, status, error) {
        let mensajeError = 'No se pudo conectar al servidor. Inténtalo de nuevo más tarde.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          mensajeError = xhr.responseJSON.message;
        }
        showToast('danger', mensajeError);
      }
    });
  }

  function showToast(type, message) {
    let $toastContainer = $('#toast-container');
    if ($toastContainer.length === 0) {
      $toastContainer = $('<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
      $('body').append($toastContainer);
    }
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-times-circle';
    const toastId = 'toast-' + Date.now();
    const $toast = $(`
        <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0 show mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body"><i class="fas ${icon} me-2"></i>${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `);
    $toastContainer.append($toast);
    const toast = new bootstrap.Toast($toast[0], {
      delay: 3000
    });
    toast.show();
    setTimeout(() => {
      $toast.fadeOut(400, function() {
        $(this).remove();
      });
    }, 3200);
  }
</script>
