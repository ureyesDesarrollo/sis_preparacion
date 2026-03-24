<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="modalAgregarTransporteLabel">Agregar Transporte</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>
    <form id="formAgregarTransporte" autocomplete="off" novalidate>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 mb-3">
            <label for="transporte_nombre" class="form-label">Nombre del Transporte</label>
            <input type="text" class="form-control" id="transporte_nombre" name="transporte_nombre" required>
            <div class="invalid-feedback">
              Por favor, ingrese el nombre del transporte.
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-outline-primary"><i class="fas fa-save me-2"></i>Guardar</button>
        </div>
    </form>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#formAgregarTransporte').on('submit', function(e) {
      e.preventDefault();
      insertar_datos();
    });
  });

  function insertar_datos() {
    const nombre = $('#transporte_nombre').val().trim();

    if (!nombre) {
      $('#transporte_nombre').addClass('is-invalid').focus();
      return;

    } else {
      $('#transporte_nombre').removeClass('is-invalid');
    }

    $.ajax({
      type: 'POST',
      url: 'catalogos/transportes_insertar.php',
      data: JSON.stringify({
        nombre: nombre
      }),
      success: function(response) {
        if (response.success) {
          showToast('success', response.message || 'Transporte agregado');
          $('#dataTableTransportes').DataTable().ajax.reload();
          $('#formAgregarTransporte')[0].reset();
        } else {
          showToast('danger', response.message || 'Error al agregar transporte');
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
