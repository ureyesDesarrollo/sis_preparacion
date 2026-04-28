<script>
    $(document).ready(function() {
        const formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        function inicializarSegunSeleccion() {
            let valor = $('input[name="tabla"]:checked').val();

            $('#tablaEmpacado, #tablaTarimas').hide();
            $(`#${valor}`).show();

            switch (valor) {
                case "tablaTarimas":
                    inizializarTabla('dataTableFacturasTarimas', 'reporte_facturas_tarimas_listado', [{
                            data: 'ft_fecha'
                        },
                        {
                            data: 'ft_tipo',
                            render: function(data, type, row) {
                                let tipos = {
                                    'F': row.ft_factura,
                                    'R': row.ft_factura,
                                    'V': row.ft_vale_salida
                                };

                                return tipos[data] || 'Desconocido';
                            }
                        },
                        {
                            data: 'tar_id'
                        },
                        {
                            data: 'pro_id',
                            render: function(data, type, row) {
                                let procesos = {
                                    '1': 'FINOS A',
                                    '2': 'FINOS B',
                                    '3': 'FINOS C',
                                };

                                return procesos[data] || data;
                            }
                        },
                        {
                            data: 'tar_folio'
                        },
                        {
                            data: 'tar_kilos'
                        },
                        {
                            data: 'cte_nombre',
                            render: function(data) {
                                return data ? data : 'Sin datos del cliente';
                            }
                        },
                        {
                            data: 'ft_tipo',
                            render: function(data) {
                                let tipos = {
                                    'F': 'Factura',
                                    'R': 'Remisión',
                                    'V': 'Vale de salida'
                                }

                                return tipos[data] || 'Desconocido';
                            }
                        },
                        {
                            data: 'ft_kilos_facturados',
                            render: function(data) {
                                return data ? `${parseFloat(data).toFixed(2)} kg` : '0.00 kg';
                            }
                        },
                        {
                            data: 'ft_factura',
                            render: function(data) {
                                return data ? data : 'Sin factura';
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                if (row.ft_tipo === 'V') {
                                    return `<button class="btn btn-primary btn-actualizar" data-id='${row.ft_vale_salida}' data-tipo='${row.ft_tipo}' data-cte='${row.cte_id ?? ''}' title="Actualizar"><i class="fa-solid fa-file-invoice"></i></button>`;
                                } else if (row.ft_tipo === 'F' || row.ft_tipo === 'R') {
                                    return `<button class="btn btn-primary btn-actualizar" data-id='${row.ft_factura}' data-tipo='${row.ft_tipo}' data-cte='${row.cte_id ?? ''}' title="Actualizar"><i class="fa-solid fa-file-invoice"></i></button>`;
                                }
                                return '';
                            },

                        }
                    ], 'Facturas_tarimas_listado');
                    break;

                case "tablaEmpacado":
                    inizializarTabla('dataTableFacturasEmpacado', 'reporte_facturas_listado', [{
                            data: 'fe_fecha'
                        },
                        {
                            data: 'fe_factura'
                        },
                        {
                            data: 'fe_cartaporte',
                            render: function(data) {
                                return data ? data : 'Sin cartaporte registrada';
                            }
                        },
                        {
                            data: 'pres_descrip'
                        },
                        {
                            data: 'fe_cantidad'
                        },
                        {
                            data: 'pres_kg',
                            render: function(data, type, row) {
                                return formatter.format(row.fe_cantidad * data);
                            }
                        },
                        {
                            data: 'cte_nombre',
                            render: function(data) {
                                return data ? data : 'Sin datos del cliente';
                            }
                        },
                        {
                            data: 'rev_folio'
                        },
                        {
                            data: 'fe_tipo',
                            render: function(data) {
                                return data === 'F' ? 'Factura' : 'Remisión';
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                console.log(row.fe_cartaporte);
                                if (row.fe_cartaporte === null || row.fe_cartaporte === '') {
                                    return `<button class="btn btn-primary btn-cartaporte" data-id='${row.fe_factura}' title="Facturar"><i class="fa-solid fa-file-invoice"></i></button>`;
                                }
                                return '';
                            },

                        }
                    ], 'Listado_facturas_empacado');
                    break;
            }
        }

        $('input[type="radio"][name="tabla"]').change(inicializarSegunSeleccion);

        inicializarSegunSeleccion();

        function inizializarTabla(id, url, columnas, titulo) {
            let indices = [];

            columnas.forEach((element, index) => {
                indices.push(index);
            });

            $(`#${id}`).DataTable({
                responsive: true,
                bDestroy: true,
                language: {
                    sProcessing: "Procesando...",
                    sLengthMenu: "Mostrar _MENU_ registros",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ningún dato disponible en esta tabla",
                    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ )",
                    search: "Buscar:",
                    zeroRecords: "No se encontraron registros coincidentes",
                    paginate: {
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                },
                order: [
                    [0, 'desc']
                ],
                "sDom": "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-5 'B><'col-sm-12 col-md-4'f>r>t<'row'<'col-md-4'i>><'row'p>",
                buttons: {
                    dom: {
                        button: {
                            className: 'btn'
                        },
                    },
                    buttons: [{
                            extend: 'excel',
                            footer: true,
                            title: titulo,
                            filename: `${titulo}_excel`,
                            text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                            exportOptions: {
                                columns: indices
                            }
                        },
                        {
                            extend: 'pdf',
                            footer: true,
                            title: titulo,
                            filename: `${titulo}_pdf`,
                            text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                            exportOptions: {
                                columns: indices
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            title: titulo,
                            filename: `${titulo}_print`,
                            text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                            exportOptions: {
                                columns: indices
                            }
                        }
                    ]
                },
                ajax: {
                    url: `reportes/${url}.php`,
                    dataSrc: ''
                },
                columns: columnas,
            });
        }

        $(document).on('click', '.btn-cartaporte', function() {
            let factura = $(this).data('id');
            console.log(factura);
            ingresar_cartaporte(factura);
        });

        // Variable para guardar los datos SAI del modal de actualizar
        let jsonDataSaiActualizar = null;
        // Guarda el tipo del modal actual para usarlo en el submit
        let tipoModalActual = null;
        // Datos del cliente para enviar al SAI
        let clienteSaiTipoVenta = '';
        let clienteSaiClasificacion = '';

        // Carga los datos del cliente desde el catálogo para usarlos al insertar en SAI
        function cargarDatosCliente(cteId) {
            if (!cteId) return;
            $.ajax({
                type: 'GET',
                url: 'catalogos/clientes_listado.php',
                success: function(data) {
                    let clientes = JSON.parse(data);
                    let cliente = clientes.find(c => c.cte_id == cteId);
                    if (cliente) {
                        clienteSaiTipoVenta = cliente.cte_tipo || '';
                        clienteSaiClasificacion = cliente.cte_clasificacion || '';
                    }

                    console.log(clienteSaiClasificacion);
                    console.log(clienteSaiTipoVenta);
                }
            });
        }

        $(document).on('click', '.btn-actualizar', function() {

            let valor = $(this).data('id');
            let tipo = $(this).data('tipo');
            let cteId = $(this).data('cte');

            // Guarda el tipo para usarlo al guardar
            tipoModalActual = tipo;
            jsonDataSaiActualizar = null;
            clienteSaiTipoVenta = '';
            clienteSaiClasificacion = '';
            $('#btnGuardarCambios').prop('disabled', false);

            // Carga tipo y clasificación del cliente para SAI (solo necesario en tipo V)
            if (tipo === 'V') {
                cargarDatosCliente(cteId);
            }

            let modal = new bootstrap.Modal(document.getElementById('modalActualizarFactura'));
            modal.show();

            $('#modalValeSalida').text(valor);

            $('#tablaTarimasBody').html(`
        <tr>
            <td colspan="6" class="text-center">
                Cargando...
            </td>
        </tr>
    `);

            $.ajax({
                url: 'reportes/consultar_tarimas_por_tipo.php',
                method: 'POST',
                data: {
                    ft_tipo: tipo,
                    valor: valor
                },
                dataType: 'json',
                success: function(response) {

                    if (response.status !== 'success') {
                        alert(response.message);
                        return;
                    }

                    let html = '';

                    if (response.total === 0) {
                        html = `
                            <tr>
                                <td colspan="6" class="text-center text-danger">
                                    No se encontraron tarimas.
                                </td>
                            </tr>
                        `;
                    } else {

                        response.data.forEach(function(tarima, index) {
                            // El input de factura es editable solo si está vacío y el tipo es F
                            let facturaVacia = !tarima.ft_factura || tarima.ft_factura.trim() === '';
                            let esEditable = facturaVacia && tipo === 'V';

                            html += `
        <tr>
            <td class="text-center">
                ${index + 1}
            </td>

            <td class="text-center fw-bold">
                ${tarima.tar_folio}
                <input type="hidden"
                       class="tar-id"
                       value="${tarima.tar_id}">
            </td>

            <td class="text-end">
                ${parseFloat(tarima.tar_kilos).toFixed(2)} kg
            </td>

            <td>
                <input type="number"
                       step="0.01"
                       min="0"
                       max="${tarima.tar_kilos}"
                       class="form-control form-control-sm input-kilos-facturados"
                       placeholder="0.00">
            </td>

            <td>
                <input type="text"
                       class="form-control form-control-sm input-factura ${esEditable ? 'input-factura-editable' : ''}"
                       value="${tarima.ft_factura ?? ''}"
                       ${esEditable ? '' : 'readonly'}>
            </td>

            <td class="text-center">
                ${tarima.tar_fecha ?? ''}
            </td>
        </tr>
    `;
                        });
                    }

                    $('#tablaTarimasBody').html(html);
                },
                error: function() {
                    alert("Error al consultar las tarimas.");
                }
            });
        });

        // Sincroniza el valor entre todos los inputs de factura editables
        $(document).on('input', '.input-factura-editable', function() {
            let nuevaFactura = $(this).val();
            $('.input-factura-editable').val(nuevaFactura);
        });

        // Consulta SAI con debounce solo en inputs de factura editables (vacíos, tipo F)
        let typingTimerSai;
        $(document).on('input', '.input-factura-editable', function() {

            clearTimeout(typingTimerSai);
            const factura = $(this).val().trim();

            $('#btnGuardarCambios').prop('disabled', true);
            jsonDataSaiActualizar = null;

            if (!factura) return;

            typingTimerSai = setTimeout(function() {
                consultarFacturaSaiActualizar(factura);
            }, 800);
        });

        // Consulta la factura en SAI y guarda los datos para insertar al guardar
        function consultarFacturaSaiActualizar(factura) {
            // Muestra loader dentro del modal
            $('#modalActualizarFactura .modal-body').css('position', 'relative');
            $('#loader-sai-actualizar').remove();
            $('#modalActualizarFactura .modal-body').append(`
                <div id="loader-sai-actualizar" style="
                    position: absolute;
                    z-index: 9999;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(255,255,255,0.75);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                ">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <div class="mt-3 fs-5 text-dark">Consultando Factura...</div>
                </div>
            `);

            $.ajax({
                type: 'GET',
                url: `lib/consultar_factura_sai.php?folio=${encodeURIComponent(factura)}`,
                dataType: 'json',
                success: function(response) {
                    $('#loader-sai-actualizar').remove();

                    if (response.success === false || response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error || 'La factura no existe en SAI.',
                            timer: 4000,
                            showConfirmButton: false
                        });
                        jsonDataSaiActualizar = null;
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Factura encontrada',
                        text: '',
                        timer: 4000,
                        showConfirmButton: false
                    });

                    // Guarda los datos SAI para usarlos al guardar
                    jsonDataSaiActualizar = response.data || response;
                    $('#btnGuardarCambios').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    $('#loader-sai-actualizar').remove();
                    let msg = 'No se pudo consultar la factura en SAI. ';
                    if (status === 'timeout') msg += 'La consulta tardó demasiado.';
                    else if (xhr.responseJSON && xhr.responseJSON.error) msg += xhr.responseJSON.error;
                    else msg += error;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg,
                        timer: 4000,
                        showConfirmButton: false
                    });
                    jsonDataSaiActualizar = null;
                }
            });
        }

        // Inserta en SAI
        function insertarRegistrosSai(data) {
            return new Promise(function(resolve, reject) {
                console.log(clienteSaiClasificacion);
                console.log(clienteSaiTipoVenta);
                data.FACTURA_CABECERA.TIPO_VENTA = clienteSaiTipoVenta;
                data.FACTURA_CABECERA.TIPO_CLIENTE = clienteSaiClasificacion;
                $.ajax({
                    url: 'funciones/sai/insertar.php',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr) {
                        reject(xhr.responseText || 'Error al registrar en SAI');
                    }
                });
            });
        }

        $(document).on('click', '#btnGuardarCambios', function() {

            let datos = [];
            let error = false;

            $('#tablaTarimasBody tr').each(function() {

                let tar_id = $(this).find('.tar-id').val();
                let kilosInput = $(this).find('.input-kilos-facturados');
                let facturaInput = $(this).find('.input-factura');

                let kilos = parseFloat(kilosInput.val());
                let factura = facturaInput.val().trim();
                let kilosTarima = parseFloat(
                    $(this).find('td:eq(2)').text()
                );

                if (!kilos || kilos <= 0) {
                    kilosInput.addClass('is-invalid');
                    error = true;
                    return;
                }

                datos.push({
                    tar_id: tar_id,
                    ft_kilos_facturados: kilos,
                    ft_factura: factura
                });

            });

            if (error) {
                alert("Verifica los kilos capturados.");
                return;
            }

            const guardar = function() {
                $.ajax({
                    url: 'reportes/actualizar_tarimas_facturas.php',
                    method: 'POST',
                    data: {
                        tarimas: JSON.stringify(datos)
                    },
                    success: function(response) {
                        alert("Actualización correcta.");
                        jsonDataSaiActualizar = null;
                        location.reload();
                    },
                    error: function() {
                        alert("Error al guardar.");
                    }
                });
            };

            // Solo inserta en SAI si el tipo es V y hay datos SAI disponibles
            if (tipoModalActual === 'V' && jsonDataSaiActualizar) {
                insertarRegistrosSai(jsonDataSaiActualizar)
                    .then(function() {
                        guardar();
                    })
                    .catch(function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error SAI',
                            text: err,
                            timer: 4000,
                            showConfirmButton: false
                        });
                    });
            } else {
                guardar();
            }
        });
    });
</script>
<div class="container-fluid">
    <h3 style="color: #007bff;">Facturas</h3>
    <div class="form-group">
        <label>Seleccione: </label>
        <label><input type="radio" name="tabla" value="tablaEmpacado" checked> Empacado</label>
        <label><input type="radio" name="tabla" value="tablaTarimas"> Tarimas</label>
    </div>
    <div class="row p-3">
        <div class="col-md-6">
            <button class="btn btn-sm btn-primary" id="AgregarNotaCredito">Agregar Nota de credito</button>
        </div>
    </div>
    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;" id="tabla">
        <div class="table-container" id="tablaEmpacado">
            <div class="table-responsive mt-3">
                <table id="dataTableFacturasEmpacado" class="table table-hover display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Factura</th>
                            <th>Carta porte</th>
                            <th>Presentación</th>
                            <th>Cantidad Facturada</th>
                            <th>Kilos</th>
                            <th>Cliente</th>
                            <th>Revoltura</th>
                            <th>Tipo</th>
                            <th>Captura cartaporte</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="table-container" id="tablaTarimas">
            <div class="table-responsive mt-3">
                <table id="dataTableFacturasTarimas" class="table table-hover display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Factura</th>
                            <th>T. Clave</th>
                            <th>Proceso</th>
                            <th>Tarima</th>
                            <th>Kilos</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Kilos Facturados</th>
                            <th>Factura</th>
                            <th>Actualizar</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalActualizarFactura" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Actualizar Tarimas - Vale:
                    <span id="modalValeSalida" class="fw-bold"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Folio</th>
                                <th>Kilos Tarima</th>
                                <th>Kilos Facturados</th>
                                <th>Factura</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="tablaTarimasBody"></tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button class="btn btn-primary"
                    id="btnGuardarCambios">
                    Guardar Cambios
                </button>
            </div>

        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#AgregarNotaCredito').on('click', function() {
            ingresar_nota_credito();
        });
    });
    async function ingresar_cartaporte(factura) {
        const {
            value: result
        } = await Swal.fire({
            title: `Ingresa la carta porte para la factura ${factura}`,
            input: "text",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            showLoaderOnConfirm: true,
            preConfirm: async (cartaporteInput) => {
                if (!cartaporteInput) {
                    Swal.showValidationMessage("Por favor ingresa una clave de autorización.");
                    return false;
                }

                try {
                    let response = await fetch("reportes/capturar_cartaporte.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `cartaporte=${encodeURIComponent(cartaporteInput)}&factura=${encodeURIComponent(factura)}`,
                    });

                    if (!response.ok) throw new Error("Error de conexión con el servidor");

                    let data = await response.json();

                    if (!data.success) {
                        throw new Error(data.error || "Error en la validación de la clave");
                    }

                    return {
                        ...data,
                        cartaporte: cartaporteInput
                    };
                } catch (error) {
                    Swal.showValidationMessage(error.message);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (!result) return;

        Swal.fire({
            icon: "success",
            title: "Carta porte registrada",
            text: `La carta porte ${result.cartaporte} ha sido guardada para la factura ${factura}.`,
            timer: 4000,
            showConfirmButton: false
        });

        $(`#dataTableFacturasEmpacado`).DataTable().ajax.reload(null, false);
    }

    async function ingresar_nota_credito() {
        const {
            value: result
        } = await Swal.fire({
            title: "Ingresa el folio de la nota de crédito",
            input: "text",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            showLoaderOnConfirm: true,
            preConfirm: async (notaCreditoInput) => {
                if (!notaCreditoInput) {
                    Swal.showValidationMessage("Por favor ingresa la nota de crédito.");
                    return false;
                }

                try {
                    let response = await fetch("reportes/capturar_nota_credito.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `nota_credito=${encodeURIComponent(notaCreditoInput)}`
                    });

                    if (!response.ok) {
                        throw new Error("Error de conexión con el servidor");
                    }

                    let data = await response.json();

                    if (!data.success) {
                        throw new Error(data.error || "Error al guardar la nota de crédito");
                    }

                    return {
                        ...data,
                        nota_credito: notaCreditoInput
                    };

                } catch (error) {
                    Swal.showValidationMessage(error.message);
                    return false;
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (!result) return;

        Swal.fire({
            icon: "success",
            title: "Nota de crédito registrada",
            text: `La nota de crédito ${result.nota_credito} ha sido guardada correctamente.`,
            confirmButtonText: "Aceptar"
        });
    }
</script>
