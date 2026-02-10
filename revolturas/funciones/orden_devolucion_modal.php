<style>
    .search-box {
        transition: all 0.3s ease;
    }

    .search-box:focus-within {
        transform: translateY(-2px);
    }

    .table-responsive {
        max-height: 400px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.2) rgba(0, 0, 0, 0.05);
    }

    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        transition: all 0.2s ease;
        min-width: 120px;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-outline-secondary {
        transition: all 0.2s ease;
    }

    .btn-outline-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .modal-content {
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
    }

    .input-group-text {
        transition: all 0.3s ease;
    }

    .input-group:focus-within .input-group-text {
        background-color: #e9ecef;
        color: #0d6efd;
    }

    .alert {
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
    }

    #no-results {
        background-color: #f8f9fa;
    }

    /* Estilos para el contenedor de alertas */
    #alert-container {
        max-height: 100vh;
        overflow-y: auto;
        pointer-events: none;
        /* Permite hacer clic a través del contenedor */
    }

    /* Estilos para cada alerta individual */
    #alert-container .alert {
        pointer-events: auto;
        /* Habilita interacción con las alertas */
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        margin-bottom: 0.75rem;
    }

    /* Efecto hover opcional */
    #alert-container .alert:hover {
        transform: translateX(-5px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.2);
    }
</style>

<div class="modal-dialog modal-fullscreen">
    <div class="modal-content border-0 shadow-lg">
        <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center">
                Generar orden de devolución
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
            <div class="row mb-4">
                <div class="col-md-10 mx-auto">
                    <div class="search-box">
                        <label for="fe_factura" class="form-label fw-semibold mb-2">Buscar factura</label>
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input name="fe_factura" type="text" class="form-control border-start-0 ps-0"
                                id="fe_factura" autocomplete="off" placeholder="Ingrese número de factura..."
                                aria-describedby="searchHelp" />
                            <button class="btn btn-outline-secondary border-start-0" type="button" id="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <small id="searchHelp" class="form-text text-muted mt-1 d-block">Ingrese al menos 3 caracteres para realizar la búsqueda</small>
                        <div class="alert alert-warning mt-3 py-2 d-flex align-items-center d-none" id="factura-alert" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <span class="alert-message"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="div_tabla_facturas" style="display: none;">
                <div class="col-md-12">
                    <div class="table-responsive rounded shadow-sm border">
                        <table class="table table-hover align-middle mb-0" id="tabla_facturas">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3"><i class="fas fa-file-invoice me-2"></i>Factura</th>
                                    <th class="py-3"><i class="fas fa-hashtag me-2"></i>Folio</th>
                                    <th class="py-3"><i class="far fa-calendar-alt me-2"></i>Fecha</th>
                                    <th class="py-3"><i class="fas fa-user-tie me-2"></i>Cliente</th>
                                    <th class="py-3 text-end"><i class="fa-solid fa-weight-scale me-2"></i>Cantidad</th>
                                    <th class="py-3"><i class="fas fa-box-open me-2"></i>Presentación</th>
                                    <th class="py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_facturas">
                                <!-- Contenido dinámico -->
                            </tbody>
                            <tfoot id="no-results" style="display: none;">
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted mb-2">No se encontraron resultados</h5>
                                            <p class="text-muted mb-0">Intente con otro término de búsqueda</p>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row mt-4" id="div_observaciones" style="display: none;">
                    <div class="col-md-10 mx-auto">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title d-flex align-items-center mb-3">
                                    <i class="fas fa-edit me-2 text-primary"></i>Observaciones
                                </h6>
                                <div class="form-floating">
                                    <textarea class="form-control" id="observaciones"
                                        placeholder="Ingrese observaciones relevantes"
                                        style="height: 100px"></textarea>
                                    <label for="observaciones">Observaciones</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer bg-light justify-content-end">
            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                <i class="fas fa-times me-2"></i> Cancelar
            </button>
            <button type="button" class="btn btn-outline-primary px-4 ms-2" id="btn_guardar_orden_devolucion" disabled>
                <i class="fas fa-save me-2"></i> Guardar
            </button>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        // Enfocar automáticamente al mostrar el modal
        $('#modal_orden_devolucion').on('shown.bs.modal', function() {
            localStorage.removeItem('devoluciones');
            $('#fe_factura').trigger('focus');
        });

        // Limpiar búsqueda
        $('#clearSearch').click(function() {
            $('#fe_factura').val('').trigger('focus');
            $('#div_tabla_facturas').hide(300);
            $('#factura-alert').addClass('d-none');
            $('#no-results').hide();
        });

        // Búsqueda con debounce
        let searchTimer;
        $('#fe_factura').on('input', function() {
            clearTimeout(searchTimer);
            const factura = $(this).val().trim();

            if (factura.length <= 2) {
                if (factura.length > 0) {
                    showAlertFactura('warning', 'Ingrese al menos 3 caracteres para buscar');
                } else {
                    $('#factura-alert').addClass('d-none');
                }
                $('#div_tabla_facturas').hide(300);
                return;
            }

            $('#factura-alert').addClass('d-none');
            showLoadingState();

            searchTimer = setTimeout(() => {
                obtenerFacturas(factura);
            }, 500);
        });

        // Función para mostrar estados de carga
        function showLoadingState() {
            $('#div_tabla_facturas').show(300);
            $('#tbody_facturas').html(`
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <h5 class="text-primary">Buscando facturas...</h5>
                    </div>
                </td>
            </tr>
        `);
        }

        // Mostrar campo de observaciones cuando se selecciona una factura
        $(document).on('facturaSeleccionada', function() {

        });

        // Función para mostrar alertas
        function showAlertFactura(type, message) {
            const alert = $('#factura-alert');
            alert.removeClass('alert-warning alert-info alert-danger d-none')
                .addClass(`alert-${type}`)
                .find('.alert-message').text(message);
            alert.removeClass('d-none');
        }

        $('#btn_guardar_orden_devolucion').click(function() {
            const devoluciones = JSON.parse(localStorage.getItem('devoluciones')) || [];
            if (devoluciones.length === 0) {
                showAlert('warning', 'No hay productos seleccionados para la orden de devolución');
                return;
            }

            guardar_orden(devoluciones);

        });
    });

    function guardar_orden(devoluciones) {
        const observaciones = $('#observaciones').val();
        const data = JSON.stringify({
            devoluciones: devoluciones,
            observaciones: observaciones
        });

        $.ajax({
            url: 'funciones/orden_devolucion_insertar.php',
            type: 'POST',
            contentType: 'application/json',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                $('#btn_guardar_orden_devolucion').prop('disabled', true);
                showAlert('info', 'Guardando orden de devolución, por favor espere...');
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Orden de devolución guardada exitosamente');
                    localStorage.removeItem('devoluciones');
                    setTimeout(() => {
                        $('#modal_orden_devolucion').modal('hide');
                    $('#dataTableOrdenesDevolucion').DataTable().ajax.reload();
                    },1000);
                } else {
                    showAlert('danger', response.message || 'Error al guardar la orden de devolución');
                }
            },
        });
    }

    function obtenerFacturas(factura) {
        $.ajax({
            url: 'funciones/obtener_por_factura.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                factura: factura
            }),
            dataType: 'json',
            beforeSend: function() {
                $('#no-results').hide();
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    renderFacturas(response.data);
                } else {
                    showNoResults(response.message || 'No se encontraron facturas con ese criterio');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener las facturas:", error);
                showErrorState();
            }
        });
    }

    function renderFacturas(facturas) {
        const tbody = $('#tbody_facturas');
        tbody.empty();
        facturas.forEach(factura => {
            tbody.append(`
        <tr class="factura-row align-middle" data-folio="${factura.rev_folio}" data-tipo="${factura.tipo_empaque}" data-ref="${factura.referencia_id}" data-max="${factura.fe_cantidad}">
            <td class="fw-semibold">${factura.fe_factura || 'N/A'}</td>
            <td>${factura.rev_folio || 'N/A'}</td>
            <td>${factura.fe_fecha}</td>
            <td>${factura.cte_nombre || 'Cliente no especificado'}</td>
            <td class="text-end">${factura.fe_cantidad}</td>
            <td>${factura.pres_descrip || 'Sin especificar'}</td>
            <td class="text-center align-middle">
                <div class="row g-1 justify-content-center align-items-center">
                    <div class="col-12 col-md-7 mb-2 mb-md-0">
                        <input type="number" min="1" max="${factura.fe_cantidad}" step="any" class="form-control input-cantidad-lote" style="width:100%; max-width:220px; display:none; transition:all 0.2s; font-size:1.1rem;" placeholder="Cantidad (máx: ${factura.fe_cantidad})" />
                    </div>
                    <div class="col-12 col-md-5 d-flex flex-wrap flex-md-nowrap justify-content-center justify-content-md-end gap-2">
                        <button type="button" class="btn btn-primary btn-seleccionar-lote" title="Seleccionar lote">
                            <i class="fas fa-check me-2"></i> Seleccionar
                        </button>
                        <button type="button" class="btn btn-success btn-confirmar-cantidad" style="display:none;" title="Agregar a devolución" disabled>
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-cancelar-cantidad" style="display:none;" title="Cancelar selección">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="invalid-feedback text-center" style="display:none;">Ingrese una cantidad válida mayor a 0 y menor o igual a ${factura.fe_cantidad}</div>
            </td>
        </tr>
    `);
        });

        // Mostrar input de cantidad con animación y UX mejorada
        $('.btn-seleccionar-lote').off('click').on('click', function() {
            const tr = $(this).closest('tr');
            tr.find('.btn-seleccionar-lote').hide();
            tr.find('.input-cantidad-lote').val('').show().addClass('animate__animated animate__fadeInRight').focus();
            tr.find('.btn-confirmar-cantidad, .btn-cancelar-cantidad').show();
            tr.find('.invalid-feedback').hide();
            tr.find('.btn-confirmar-cantidad').prop('disabled', true);
        });

        // Habilitar botón solo si la cantidad es válida y no excede el lote
        tbody.on('input', '.input-cantidad-lote', function() {
            const tr = $(this).closest('tr');
            const cantidad = parseFloat($(this).val());
            const max = parseFloat(tr.data('max'));
            if (!isNaN(cantidad) && cantidad > 0 && cantidad <= max) {
                tr.find('.btn-confirmar-cantidad').prop('disabled', false);
                tr.find('.invalid-feedback').hide();
                $(this).removeClass('is-invalid');
            } else {
                tr.find('.btn-confirmar-cantidad').prop('disabled', true);
                if (!isNaN(cantidad) && cantidad > max) {
                    tr.find('.invalid-feedback').text('No puede exceder la cantidad disponible en el lote (' + max + ')').show();
                    $(this).addClass('is-invalid');
                } else {
                    tr.find('.invalid-feedback').text('Ingrese una cantidad válida mayor a 0 y menor o igual a ' + max).show();
                    $(this).addClass('is-invalid');
                }
            }
        });

        // Cancelar selección
        $('.btn-cancelar-cantidad').off('click').on('click', function() {
            const tr = $(this).closest('tr');
            tr.find('.input-cantidad-lote').hide().val('');
            tr.find('.btn-confirmar-cantidad, .btn-cancelar-cantidad').hide();
            tr.find('.btn-seleccionar-lote').show();
            tr.find('.invalid-feedback').hide();
        });

        // Confirmar cantidad y guardar
        $('.btn-confirmar-cantidad').off('click').on('click', function() {
            const tr = $(this).closest('tr');
            const cantidad = parseFloat(tr.find('.input-cantidad-lote').val());
            const max = parseFloat(tr.data('max'));
            if (isNaN(cantidad) || cantidad <= 0 || cantidad > max) {
                tr.find('.input-cantidad-lote').addClass('is-invalid');
                tr.find('.invalid-feedback').text('No puede exceder la cantidad disponible en el lote (' + max + ')').show();
                return;
            }
            tr.find('.input-cantidad-lote').removeClass('is-invalid');
            tr.find('.invalid-feedback').hide();
            const lote = tr.data('folio');
            const tipo_empaque = tr.data('tipo');
            const referencia_id = tr.data('ref');
            const factura = $('#fe_factura').val().trim();
            let devoluciones = JSON.parse(localStorage.getItem('devoluciones')) || [];
            const indexFactura = devoluciones.findIndex(item => item.factura === factura);
            if (indexFactura !== -1) {
                const existe = devoluciones[indexFactura].empaques.some(empaque =>
                    empaque.lote === lote &&
                    empaque.tipo_empaque === tipo_empaque &&
                    empaque.referencia_id === referencia_id
                );
                if (existe) {
                    showAlert('warning', 'Este producto (empaque con mismo lote, tipo y referencia) ya fue agregado');
                    return;
                }
                devoluciones[indexFactura].empaques.push({
                    lote: lote,
                    tipo_empaque: tipo_empaque,
                    referencia_id: referencia_id,
                    cantidad: cantidad
                });
            } else {
                devoluciones.push({
                    factura: factura,
                    empaques: [{
                        lote: lote,
                        tipo_empaque: tipo_empaque,
                        referencia_id: referencia_id,
                        cantidad: cantidad
                    }]
                });
            }
            localStorage.setItem('devoluciones', JSON.stringify(devoluciones));
            $('#btn_guardar_orden_devolucion').prop('disabled', false);
            $('#div_observaciones').fadeIn(300);
            showAlert('success', `Producto del lote ${lote} agregado a la orden de devolución con cantidad: ${cantidad}`);
            tr.find('.input-cantidad-lote').val(cantidad).prop('disabled', true).css('background','#e9ffe9');
            tr.find('.btn-confirmar-cantidad, .btn-cancelar-cantidad').hide();
            tr.find('.btn-seleccionar-lote').hide();
        });
    }

    function showNoResults(message) {
        $('#tbody_facturas').empty();
        $('#no-results').show();
        $('#factura-alert').removeClass('d-none').addClass('alert-info').find('.alert-message').text(message);
    }

    function showErrorState() {
        $('#tbody_facturas').html(`
        <tr>
            <td colspan="7" class="text-center py-5">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger mb-2">Error al cargar los datos</h5>
                    <p class="text-muted">Por favor, intente nuevamente más tarde</p>
                </div>
            </td>
        </tr>
    `);
        $('#factura-alert').removeClass('d-none').addClass('alert-danger')
            .find('.alert-message').text('Error al realizar la búsqueda');
    }

    function showAlert(type, message, timeout = 5000) {
        // Crear el elemento de alerta si no existe
        let $alertContainer = $('#alert-container');
        if ($alertContainer.length === 0) {
            $alertContainer = $(`
            <div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 500px;">
                
            </div>
        `);
            $('body').append($alertContainer);
        }

        // Mapear tipos de Bootstrap a iconos de Font Awesome
        const icons = {
            'success': 'check-circle',
            'warning': 'exclamation-triangle',
            'danger': 'times-circle',
            'info': 'info-circle'
        };

        // Crear la alerta
        const alertId = 'alert-' + Date.now();
        const $alert = $(`
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show shadow" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-${icons[type] || 'info-circle'} me-2"></i>
                <div>${message}</div>
            </div>
            
        </div>
    `);

        // Agregar a contenedor
        $alertContainer.append($alert);

        // Mostrar con animación
        $alert.hide().fadeIn(300);

        // Ocultar automáticamente después del timeout
        if (timeout > 0) {
            setTimeout(() => {
                $alert.fadeOut(300, () => $(this).alert('close'));
            }, timeout);
        }
    }
</script>