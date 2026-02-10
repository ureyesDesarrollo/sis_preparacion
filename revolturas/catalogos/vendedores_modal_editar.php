<?php

require_once '../../conexion/conexion.php';
include "../../seguridad/user_seguridad.php";
$conn = Conectarse();

$id_vendedor = isset($_POST['ven_id']) ? intval($_POST['ven_id']) : 0;
$vendedor = [
    'nombre' => '',
    'nomina' => '',
    'comision' => ''
];

function obtenerVendedorPorId($conn, $id_vendedor) {
    $vendedor = [
        'nombre' => '',
        'nomina' => '',
        'comision' => ''
    ];
    if ($id_vendedor > 0) {
        $sql = "SELECT ven_nombre, ven_numero_nomina, ven_porcentaje_comision FROM rev_vendedores WHERE ven_id = $id_vendedor";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            throw new Exception('Error al consultar el vendedor: ' . mysqli_error($conn));
        }
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $vendedor['id'] = $id_vendedor;
            $vendedor['nombre'] = $row['ven_nombre'];
            $vendedor['nomina'] = $row['ven_numero_nomina'];
            $vendedor['comision'] = $row['ven_porcentaje_comision'];
        }
    }
    return $vendedor;
}

$vendedor = obtenerVendedorPorId($conn, $id_vendedor);
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalAgregarVendedorLabel">Actualizar Vendedor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form id="formActualizarVendedor" autocomplete="off" novalidate>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="ven_id" id="ven_id" value="<?php echo htmlspecialchars($id_vendedor); ?>">
                    <div class="col-md-4 mb-3">
                        <label for="vendedor_nombre" class="form-label">Nombre del Vendedor</label>
                        <input type="text" class="form-control" id="vendedor_nombre" name="vendedor_nombre" required value="<?php echo htmlspecialchars($vendedor['nombre']); ?>">
                        <div class="invalid-feedback">
                            Por favor, ingrese el nombre del vendedor.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vendedor_nomina" class="form-label">Número de Nómina</label>
                        <input type="text" class="form-control" id="vendedor_nomina" name="vendedor_nomina" value="<?php echo htmlspecialchars($vendedor['nomina']); ?>">
                        <div class="invalid-feedback">
                            Por favor, ingrese el número de nómina del vendedor.
                    </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="vendedor_comision" class="form-label">Comisión (%)</label>
                        <input type="number" class="form-control" id="vendedor_comision" name="vendedor_comision" min="0" max="100" step="0.01" required value="<?php echo htmlspecialchars($vendedor['comision']); ?>">
                        <div class="invalid-feedback">
                            Por favor, ingrese un porcentaje de comisión válido entre 0 y 100.
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
        $('#formActualizarVendedor').on('submit', function(e) {
            e.preventDefault();
            insertar_datos();
        });
    });

    function insertar_datos() {
        const nombre = $('#vendedor_nombre').val().trim();
        const nomina = $('#vendedor_nomina').val().trim();
        const comision = parseFloat($('#vendedor_comision').val());
        const id = $('#ven_id').val();
        if (isNaN(id) || id <= 0) {
            showToast('danger', 'ID de vendedor inválido.');
            return;
        }

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
            url: 'catalogos/vendedores_actualizar.php',
            data: JSON.stringify({
                id: id,
                nombre: nombre,
                nomina: nomina,
                comision: comision
            }),
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message || 'Vendedor actualizado');
                    $('#tabla_vendedores').DataTable().ajax.reload();
                    $('#formAgregarVendedor')[0].reset();
                } else {
                    showToast('danger', response.message || 'Error al actualizar vendedor');
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