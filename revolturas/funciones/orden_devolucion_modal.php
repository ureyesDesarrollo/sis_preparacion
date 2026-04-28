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

    #alert-container {
        max-height: 100vh;
        overflow-y: auto;
        pointer-events: none;
    }

    #alert-container .alert {
        pointer-events: auto;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        margin-bottom: 0.75rem;
    }

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
                                    <th class="py-3"><i class="fas fa-hashtag me-2"></i>Folio / Lote</th>
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
        $('#modal_orden_devolucion').on('shown.bs.modal', function() {
            localStorage.removeItem('devoluciones');
            $('#fe_factura').trigger('focus');
        });

        $('#clearSearch').click(function() {
            $('#fe_factura').val('').trigger('focus');
            $('#div_tabla_facturas').hide(300);
            $('#factura-alert').addClass('d-none');
            $('#no-results').hide();
        });

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

            // Paso 1: preguntar si ya existe nota de crédito
            Swal.fire({
                title: '¿Se generó nota de crédito?',
                text: 'Antes de guardar, ¿ya se emitió un documento de devolución (nota de crédito)?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, tengo el folio',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Paso 2: pedir el folio
                    // Ocultar el modal de Bootstrap para que no interfiera con el input de Swal
                    $('#modal_orden_devolucion').modal('hide');

                    Swal.fire({
                        title: 'Folio de nota de crédito',
                        input: 'text',
                        inputLabel: 'Ingrese el folio del documento',
                        inputPlaceholder: 'Ej. N102',
                        inputAttributes: {
                            autocomplete: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#0d6efd',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Guardar',
                        cancelButtonText: 'Cancelar',
                        allowEnterKey: true,
                        inputValidator: (value) => {
                            if (!value || value.trim() === '') {
                                return 'Debe ingresar el folio de la nota de crédito';
                            }
                        }
                    }).then((folioResult) => {
                        if (folioResult.isConfirmed) {
                            guardar_orden(devoluciones, folioResult.value.trim());
                        } else {
                            // Canceló el folio — volver a mostrar el modal
                            $('#modal_orden_devolucion').modal('show');
                        }
                    });
                } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                    // Sin folio no permitir continuar y mostrar el modal
                    $('#modal_orden_devolucion').modal('show');
                    showAlert('warning', 'Debe proporcionar el folio de la nota de crédito para continuar');
                }
            });
        });
    });

    function guardar_orden(devoluciones, folio_nota_credito) {
        console.log(folio_nota_credito);
        const observaciones = $('#observaciones').val();
        const data = JSON.stringify({
            devoluciones: devoluciones,
            observaciones: observaciones,
            folio_nota_credito: folio_nota_credito // null si no se proporcionó
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
                    }, 1000);
                } else {
                    showAlert('danger', response.message || 'Error al guardar la orden de devolución');
                    $('#btn_guardar_orden_devolucion').prop('disabled', false);
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
            // Cantidad en kg que viene del servidor (fe_cantidad * pres_kg)
            const cantidadKg = parseFloat(factura.fe_cantidad_kg) || 0;
            const presKg = parseFloat(factura.pres_kg) || 1;
            const cantidadPzs = parseInt(factura.fe_cantidad) || 0;

            // Folio/lote según tipo
            const folio = factura.tipo_empaque === 'pe' ?
                (factura.pe_lote ? `<span class="badge bg-secondary">Lote: ${factura.pe_lote}</span>` : 'N/A') :
                (factura.rev_folio || 'N/A');

            const tipoBadge = factura.tipo_empaque === 'pe' ?
                '<span class="badge bg-warning text-dark ms-1" title="Producto Externo">Ext.</span>' :
                '';

            tbody.append(`
                <tr class="factura-row align-middle"
                    data-folio="${factura.rev_folio || factura.pe_lote || ''}"
                    data-tipo="${factura.tipo_empaque}"
                    data-ref="${factura.referencia_id}"
                    data-max-kg="${cantidadKg}"
                    data-pres-kg="${presKg}">
                    <td class="fw-semibold">${factura.fe_factura || 'N/A'}</td>
                    <td>${folio}${tipoBadge}</td>
                    <td>${factura.fe_fecha}</td>
                    <td>${factura.cte_nombre || 'Cliente no especificado'}</td>
                    <td class="text-end">
                        <span class="fw-semibold">${cantidadKg.toFixed(2)} kg</span>
                        <small class="text-muted d-block">${cantidadPzs} pza(s)</small>
                    </td>
                    <td>${factura.pres_descrip || 'Sin especificar'}</td>
                    <td class="text-center align-middle">
                        <div class="row g-1 justify-content-center align-items-center">
                            <div class="col-12 col-md-7 mb-2 mb-md-0">
                                <div class="input-group" style="display:none; max-width:220px; margin: 0 auto;">
                                    <input type="number" min="0.01" max="${cantidadKg}" step="0.01"
                                        class="form-control input-cantidad-lote"
                                        placeholder="Kg (máx: ${cantidadKg})" />
                                    <span class="input-group-text">kg</span>
                                </div>
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
                        <div class="invalid-feedback text-center" style="display:none;"></div>
                    </td>
                </tr>
            `);
        });

        // Mostrar input de cantidad
        $('.btn-seleccionar-lote').off('click').on('click', function() {
            const tr = $(this).closest('tr');
            tr.find('.btn-seleccionar-lote').hide();
            tr.find('.input-group').show();
            tr.find('.input-cantidad-lote').val('').focus();
            tr.find('.btn-confirmar-cantidad, .btn-cancelar-cantidad').show();
            tr.find('.invalid-feedback').hide();
            tr.find('.btn-confirmar-cantidad').prop('disabled', true);
        });

        // Validar cantidad en kg al escribir
        tbody.on('input', '.input-cantidad-lote', function() {
            const tr = $(this).closest('tr');
            const valor = parseFloat($(this).val());
            const maxKg = parseFloat(tr.data('max-kg'));

            if (!isNaN(valor) && valor > 0 && valor <= maxKg) {
                tr.find('.btn-confirmar-cantidad').prop('disabled', false);
                tr.find('.invalid-feedback').hide();
                $(this).removeClass('is-invalid');
            } else {
                tr.find('.btn-confirmar-cantidad').prop('disabled', true);
                const msg = (!isNaN(valor) && valor > maxKg) ?
                    `No puede exceder ${maxKg} kg disponibles` :
                    `Ingrese una cantidad válida mayor a 0 y menor o igual a ${maxKg} kg`;
                tr.find('.invalid-feedback').text(msg).show();
                $(this).addClass('is-invalid');
            }
        });

        // Cancelar selección
        $('.btn-cancelar-cantidad').off('click').on('click', function() {
            const tr = $(this).closest('tr');
            tr.find('.input-group').hide();
            tr.find('.input-cantidad-lote').val('');
            tr.find('.btn-confirmar-cantidad, .btn-cancelar-cantidad').hide();
            tr.find('.btn-seleccionar-lote').show();
            tr.find('.invalid-feedback').hide();
        });

        // Confirmar cantidad: convierte kg → piezas antes de guardar
        $('.btn-confirmar-cantidad').off('click').on('click', function() {
            const tr = $(this).closest('tr');
            const cantKg = parseFloat(tr.find('.input-cantidad-lote').val());
            const maxKg = parseFloat(tr.data('max-kg'));
            const presKg = parseFloat(tr.data('pres-kg'));

            if (isNaN(cantKg) || cantKg <= 0 || cantKg > maxKg) {
                tr.find('.input-cantidad-lote').addClass('is-invalid');
                tr.find('.invalid-feedback').text(`No puede exceder ${maxKg} kg disponibles`).show();
                return;
            }

            tr.find('.input-cantidad-lote').removeClass('is-invalid');
            tr.find('.invalid-feedback').hide();

            // Conversión kg → piezas para el backend
            const cantidadPiezas = Math.round((cantKg / presKg) * 1000) / 1000;

            const lote = tr.data('folio');
            const tipo_empaque = tr.data('tipo');
            const referencia_id = tr.data('ref');
            const factura = $('#fe_factura').val().trim();

            let devoluciones = JSON.parse(localStorage.getItem('devoluciones')) || [];
            const indexFactura = devoluciones.findIndex(item => item.factura === factura);

            if (indexFactura !== -1) {
                const existe = devoluciones[indexFactura].empaques.some(emp =>
                    emp.lote === lote &&
                    emp.tipo_empaque === tipo_empaque &&
                    emp.referencia_id === referencia_id
                );
                if (existe) {
                    showAlert('warning', 'Este producto ya fue agregado a la orden');
                    return;
                }
                devoluciones[indexFactura].empaques.push({
                    lote: lote,
                    tipo_empaque: tipo_empaque,
                    referencia_id: referencia_id,
                    cantidad: cantidadPiezas // ← piezas para el backend
                });
            } else {
                devoluciones.push({
                    factura: factura,
                    empaques: [{
                        lote: lote,
                        tipo_empaque: tipo_empaque,
                        referencia_id: referencia_id,
                        cantidad: cantidadPiezas // ← piezas para el backend
                    }]
                });
            }

            localStorage.setItem('devoluciones', JSON.stringify(devoluciones));
            $('#btn_guardar_orden_devolucion').prop('disabled', false);
            $('#div_observaciones').fadeIn(300);

            showAlert('success', `Agregado: ${cantKg} kg (${cantidadPiezas} pza(s)) del lote ${lote}`);

            // Feedback visual en la fila
            // POR esto:
            tr.find('.input-group').hide();
            tr.find('.btn-confirmar-cantidad, .btn-cancelar-cantidad').hide();
            tr.find('.btn-seleccionar-lote').hide();
            tr.find('.col-12.col-md-7').html(`
    <div class="text-center py-1">
        <span class="badge bg-success fs-6 px-3 py-2">
            <i class="fas fa-check me-2"></i>${cantKg} kg
        </span>
        <small class="text-muted d-block mt-1">${cantidadPiezas} pza(s)</small>
    </div>
`);
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
        let $alertContainer = $('#alert-container');
        if ($alertContainer.length === 0) {
            $alertContainer = $(`<div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; width: 500px;"></div>`);
            $('body').append($alertContainer);
        }

        const icons = {
            'success': 'check-circle',
            'warning': 'exclamation-triangle',
            'danger': 'times-circle',
            'info': 'info-circle'
        };

        const alertId = 'alert-' + Date.now();
        const $alert = $(`
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show shadow" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-${icons[type] || 'info-circle'} me-2"></i>
                    <div>${message}</div>
                </div>
            </div>
        `);

        $alertContainer.append($alert);
        $alert.hide().fadeIn(300);

        if (timeout > 0) {
            setTimeout(() => {
                $alert.fadeOut(300, function() {
                    $(this).remove();
                });
            }, timeout);
        }
    }
</script>
