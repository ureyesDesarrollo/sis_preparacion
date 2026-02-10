<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalAgregarVendedorLabel">Agregar Vendedor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form id="formAgregarVendedor" autocomplete="off" novalidate>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="vendedor_nombre" class="form-label">Nombre del Vendedor</label>
                        <input type="text" class="form-control" id="vendedor_nombre" name="vendedor_nombre" required>
                        <div class="invalid-feedback">
                            Por favor, ingrese el nombre del vendedor.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vendedor_nomina" class="form-label">Número de Nómina</label>
                        <input type="text" class="form-control" id="vendedor_nomina" name="vendedor_nomina">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vendedor_comision" class="form-label">Comisión (%)</label>
                        <input type="number" class="form-control" id="vendedor_comision" name="vendedor_comision" min="0" max="100" step="0.01" required>
                        <div class="invalid-feedback">
                            Por favor, ingrese un porcentaje de comisión válido entre 0 y 100.
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
        $('#formAgregarVendedor').on('submit', function(e) {
            e.preventDefault();
            insertar_datos();
        });
    });

    function insertar_datos() {
        const nombre = $('#vendedor_nombre').val().trim();
        const nomina = $('#vendedor_nomina').val().trim();
        const comision = parseFloat($('#vendedor_comision').val());

        if (!nombre) {
            $('#vendedor_nombre').addClass('is-invalid').focus();
            return;

        } else {
            $('#vendedor_nombre').removeClass('is-invalid');
        }

        if (isNaN(comision) || comision < 0 || comision > 100) {
            $('#vendedor_comision').addClass('is-invalid').focus();
            return;
        } else {
            $('#vendedor_comision').removeClass('is-invalid');
        }

        $.ajax({
            type: 'POST',
            url: 'catalogos/vendedores_insertar.php',
            data: JSON.stringify({
                nombre: nombre,
                nomina: nomina,
                comision: comision
            }),
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message || 'Vendedor agregado');
                    $('#tabla_vendedores').DataTable().ajax.reload();
                    $('#formAgregarVendedor')[0].reset();
                } else {
                    showToast('danger', response.message || 'Error al agregar vendedor');
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