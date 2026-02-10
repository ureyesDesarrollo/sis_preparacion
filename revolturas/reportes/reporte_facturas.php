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
                            data: 'ft_factura'
                        },
						{
							data: 'tar_id'
						},
						{
							data: 'pro_id'
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
                                return data === 'F' ? 'Factura' : 'Remisión';
                            }
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
                            className: 'btn' //Primary class for all buttons
                        },
                    },
                    buttons: [{
                            //Botón para Excel
                            extend: 'excel',
                            footer: true,
                            title: titulo,
                            filename: `${titulo}_excel`,

                            //Aquí es donde generas el botón personalizado
                            text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                            exportOptions: {
                                columns: indices
                            }
                        },
                        {
                            //Botón para PDF
                            extend: 'pdf',
                            footer: true,
                            title: titulo,
                            filename: `${titulo}_pdf`,
                            text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                            exportOptions: {
                                columns: indices
                            }
                        },
                        //Botón para print
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
    });
</script>
<div class="container-fluid">
    <h3 style="color: #007bff;">Facturas</h3>
    <div class="form-group">
        <label>Seleccione: </label>
        <label><input type="radio" name="tabla" value="tablaEmpacado" checked> Empacado</label>
        <label><input type="radio" name="tabla" value="tablaTarimas"> Tarimas</label>
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
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
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

                    // Retornamos tanto la respuesta como el cartaporte ingresado
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

        // Si se canceló o hubo error, no hacer nada
        if (!result) return;

        // Mostrar confirmación con el cartaporte capturado
        Swal.fire({
            icon: "success",
            title: "Carta porte registrada",
            text: `La carta porte ${result.cartaporte} ha sido guardada para la factura ${factura}.`,
            timer: 4000,
            showConfirmButton: false
        });

        $(`#dataTableFacturasEmpacado`).DataTable().ajax.reload(null, false);
    }
</script>