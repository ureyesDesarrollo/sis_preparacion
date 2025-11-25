<div class="mt-4">
    <form id="formKardex" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="fecha_ini" class="form-label">Fecha inicio:</label>
            <input type="date" class="form-control" id="fecha_ini" name="fecha_ini" required>
        </div>
        <div class="col-md-4">
            <label for="fecha_fin" class="form-label">Fecha fin:</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Consultar</button>
        </div>
    </form>

    <div id="inventario"></div>

    <!-- Tabla -->
    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;" id="div">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" class="display" style="width: 100%;" id="tablaKardex">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Total Entrada</th>
                        <th>Total Salida</th>
                    </tr>
                </thead>
                <tfoot class="fw-bold">
                    <tr>
                        <td class="text-end">Totales:</td>
                        <td id="totalEntrada">0.00</td>
                        <td id="totalSalida">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#tablaKardex').hide();
        $('#div').hide();
    });
    let dataTableKardex;
    const formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
    $('#formKardex').on('submit', function(e) {
        $('#tablaKardex').show();
        $('#div').show();
        e.preventDefault();

        const fecha_ini = $('#fecha_ini').val();
        const fecha_fin = $('#fecha_fin').val();

        $.ajax({
            url: './reportes/reporte_kardex_listado.php',
            type: 'POST',
            data: {
                fecha_ini: fecha_ini,
                fecha_fin: fecha_fin
            },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.error) {
                    alert(data.message);
                    return;
                }

                let totalEntrada = 0;
                let totalSalida = 0;

                const rows = data.map(row => {
                    const entrada = parseFloat(row.kar_total_entrada).toFixed(2);
                    const salida = parseFloat(row.kar_total_salida).toFixed(2);

                    totalEntrada += parseFloat(entrada);
                    totalSalida += parseFloat(salida);

                    return {
                        kar_fecha: row.kar_fecha,
                        kar_total_entrada: formatter.format(entrada),
                        kar_total_salida: formatter.format(salida)
                    };
                });

                $('#totalEntrada').text(formatter.format(totalEntrada.toFixed(2)));
                $('#totalSalida').text(formatter.format(totalSalida.toFixed(2)));

                // Destruir si ya existe
                if ($.fn.DataTable.isDataTable('#tablaKardex')) {
                    dataTableKardex.clear().destroy();
                }

                dataTableKardex = $('#tablaKardex').DataTable({
                    data: rows,
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
                                title: 'Listado Entradas - salidas',
                                filename: 'Listado Entradas - salidas_excel',

                                //Aquí es donde generas el botón personalizado
                                text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                                exportOptions: {
                                    columns: [0, 1, 2]
                                }
                            },
                            {
                                //Botón para PDF
                                extend: 'pdf',
                                footer: true,
                                title: 'Listado Entradas - salidas',
                                filename: 'Listado Entradas - salidas_pfd',
                                text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                                exportOptions: {
                                    columns: [0, 1, 2]
                                }
                            },
                            //Botón para print
                            {
                                extend: 'print',
                                footer: true,
                                title: 'Listado Entradas - salidas',
                                filename: 'Listado Entradas - salidas_print',
                                text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                                exportOptions: {
                                    columns: [0, 1, 2]
                                }
                            }
                        ]
                    },
                    columns: [{
                            data: 'kar_fecha'
                        },
                        {
                            data: 'kar_total_entrada'
                        },
                        {
                            data: 'kar_total_salida'
                        }
                    ]
                });
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición AJAX:', error);
                alert('Error al consultar el Kardex.');
            }
        });

        $.ajax({
            url: './reportes/reporte_kardex_inventario.php',
            type: 'POST',
            data: {
                fecha_ini: fecha_ini,
                fecha_fin: fecha_fin
            },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.error) {
                    alert(data.message);
                    return;
                }

                const formatter = new Intl.NumberFormat('es-MX');

                $('#inventario').empty();
                $('#inventario').append(`
            <div class="d-flex justify-content-between">
                <p class="mb-0"><strong>Inventario al día (${data.inicio.kar_fecha}):</strong> ${formatter.format(data.inicio.kar_inventario)}</p>
                <p class="mb-0"><strong>Inventario al día (${data.fin.kar_fecha}):</strong> ${formatter.format(data.fin.kar_inventario)}</p>
            </div>
            `);
            }
        });
    });
</script>