<script>
    $(document).ready(function() {
        $('#dataTableOrdenesDevolucion').DataTable({
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
                        className: 'btn' //Primary class for all buttons
                    },
                },
                buttons: [{
                        //Botón para Excel
                        extend: 'excel',
                        footer: true,
                        title: 'Listado ordenes_devolucion',
                        filename: 'Listado_ordenes_devolucion_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5],
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado ordenes_devolucion',
                        filename: 'Listado_ordenes_devolucion_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5],

                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado ordenes_devolucion',
                        filename: 'Listado_ordenes_devolucion_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5],

                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/orden_devolucion_listado.php',
                dataSrc: 'data'
            },
            columns: [{
                    data: 'od_id',
                },
                {
                    data: 'od_fecha'
                },

                {
                    data: 'cte_nombre'
                },
                {
                    data: 'lote',
                },
                {
                    data: 'factura',
                },
                {
                    data: 'od_motivo',
                },
                {
                    data: 'pres_descrip',
                },
                {
                    data: 'cantidad',
                    render: function(data, type, row) {
                        return `<span class="badge bg-secondary">${data}</span>`;
                    }
                },
                {
                    data: 'estado_lote',
                    render: function(data, type, row) {
                        const estadoConfig = {
                            'PENDIENTE': {
                                class: 'bg-warning',
                                icon: 'fas fa-clock',
                                tooltip: 'Esperando recepción'
                            },
                            'RECIBIDO': {
                                class: 'bg-info',
                                icon: 'fas fa-inbox',
                                tooltip: 'Lote recibido'
                            },
                            'EN ALMACEN': {
                                class: 'bg-success',
                                icon: 'fas fa-warehouse',
                                tooltip: 'Almacenado'
                            },
                            'PROCESO DE ANALISIS': {
                                class: 'bg-primary',
                                icon: 'fas fa-vials',
                                tooltip: 'En análisis'
                            },
                            'LIBERADA': {
                                class: 'bg-dark',
                                icon: 'fas fa-check-circle',
                                tooltip: 'Liberado para uso'
                            }
                        };

                        const config = estadoConfig[data] || {
                            class: 'bg-secondary',
                            icon: 'fas fa-question-circle',
                            tooltip: 'Estado desconocido'
                        };

                        return `<span class="badge ${config.class}" title="${config.tooltip}">
                                    <i class="${config.icon} me-1"></i>${data}
                                </span>`;
                    }

                },
                {
                    data: 'estado_lote',
                    render: function(data, type, row) {
                        if (data === 'PENDIENTE') {
                            return `<button class="btn btn-outline-primary btn-sm btn-recibir" data-id="${row.odd_id}" data-od="${row.od_id}">
                                <i class="fa-solid fa-arrow-down me-2"></i>Recibir
                            </button>`;
                        }
                        return '';
                    }
                },
                {
                    data: 'estado_lote',
                    render: function(data, type, row) {
                        if (data === 'EN ALMACEN' || data === 'PROCESO DE ANALISIS') {
                            return `<a href="#"><i class="btn-parametros fa-regular fa-pen-to-square" data-id="${row.odd_id}"</i></a>`;
                        }
                        return '';
                    }
                },
                {
                    data: 'cal_id',
                    render: function(data, type, row) {
                        if (data && row.estado_lote === 'PROCESO DE ANALISIS') {
                            return `<button class="btn btn-outline-success btn-sm btn-liberar" data-id="${row.odd_id}" data-od="${row.od_id}">
                                <i class="fa-solid fa-check me-2"></i>Liberar
                            </button>`;
                        }
                         return `<button class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="fa-solid fa-ban me-2"></i>No disponible
                            </button>`;
                    },
                    
                }

            ]
        });

        $('#dataTableOrdenesDevolucion').on('click', '.btn-recibir', function() {
            const odd_id = $(this).data('id');
            const od_id = $(this).data('od');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas recibir esta orden de devolución?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, recibir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) return;

                // Mostrar loading
                mostrarLoading();

                $.ajax({
                    url: 'funciones/orden_devolucion_recibir.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        odd_id,
                        od_id
                    }),
                    success: function(response) {
                        Swal.close();

                        if (response.success) {
                            Swal.fire('Recibida!', response.message, 'success');
                            $('#dataTableOrdenesDevolucion').DataTable().ajax.reload(null, false); // sin reiniciar la paginación
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close();
                        console.error("Error al recibir la orden:", error);
                        Swal.fire('Error!', 'Ocurrió un error al procesar la solicitud.', 'error');
                    }
                });
            });
        });


        $('#dataTableOrdenesDevolucion').on('click', '.btn-parametros', function() {
            console.log("Cargando parámetros para la orden de devolución con ID:", $(this).data('id'));
            $.ajax({
                url: 'funciones/orden_devolucion_parametros_modal.php',
                type: 'POST',
                data: {
                    odd_id: $(this).data('id')
                },
                dataType: 'html',
                success: function(data) {
                    $('#modal_orden_devolucion').html(data);
                    $('#modal_orden_devolucion').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar el modal:", error);
                }
            });
        });


        $('#dataTableOrdenesDevolucion').on('click', '.btn-liberar', function() {
            const odd_id = $(this).data('id');
            const od_id = $(this).data('od');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas liberar esta orden de devolución?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, liberar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) return;

                // Mostrar loading
                mostrarLoading();

                $.ajax({
                    url: 'funciones/orden_devolucion_regresar_lote.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        odd_id,
                        od_id
                    }),
                    success: function(response) {
                        Swal.close();

                        if (response.success) {
                            Swal.fire('Liberada!', response.message, 'success');
                            $('#dataTableOrdenesDevolucion').DataTable().ajax.reload(null, false); // sin reiniciar la paginación
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close();
                        console.error("Error al liberar la orden:", error);
                        Swal.fire('Error!', 'Ocurrió un error al procesar la solicitud.', 'error');
                    }
                });
            });
        });

        function mostrarLoading(titulo = 'Procesando...') {
            Swal.fire({
                title: titulo,
                text: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    });
</script>

<div class="container-fluid">
    <div id="alert-container" class="position-fixed top-0 end-0 p-3" style="z-index: 9999; width: 500px;"></div>
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Devoluciones</li>
                </ol>
            </nav>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_rack_insertar" onclick="abrir_modal_orden_devolucion()">
                <i class="fa fa-plus"></i> Generar Orden de Devolución
            </button>
        </div>
    </div>
</div>

<div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
    <div class="table-responsive mt-3">
        <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableOrdenesDevolucion" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Lote</th>
                    <th>Factura</th>
                    <th>Motivo</th>
                    <th>Presentación</th>
                    <th>Cantidad</th>
                    <th>Estatus</th>
                    <th>Recibir</th>
                    <th>Parametros</th>
                    <th>Liberar</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_orden_devolucion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">

</div>

<script>
    $(document).ready(function() {});

    function abrir_modal_orden_devolucion() {
        $.ajax({
            url: 'funciones/orden_devolucion_modal.php',
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $('#modal_orden_devolucion').html(data);
                $('#modal_orden_devolucion').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar el modal:", error);
            }
        });
    }
</script>