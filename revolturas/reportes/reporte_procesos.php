<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2024*/
?>

<script>
    $(document).ready(function() {
        $('#dataTableProcesos').DataTable({
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
                        title: 'Listado Procesos',
                        filename: 'Listado_procesos_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Procesos',
                        filename: 'Listado_procesos_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Procesos',
                        filename: 'Listado_procesos_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                        }
                    }
                ]
            },
            ajax: {
                url: 'reportes/reporte_procesos_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'pro_id'
                },
                {
                    data: 'rendimiento'
                },
                {
                    data: 'tarimas'
                },
                {
                    data: 'nom'
                },
                {
                    data: 'vis'
                },
                {
                    data: 'bloom'
                },
                {
                    data: 'ph'
                },
                {
                    data: 'color'
                },
                {
                    data: 'olor'
                },
                {
                    data: 'redox'
                },
                {
                    data: 'humedad'
                },
                {
                    data: 'malla_30'
                },
                {
                    data: 'malla_45'
                },
                {
                    data: null,
                    render: function(row) {
                        return '<a href="#"><i class="btn-bitacora fa-solid fa-eye" data-pro="' + row.pro_id + '"></i></a>';

                    }
                }

            ]
        });


        $('#dataTableProcesos').on('click', '.btn-bitacora', function() {
            let pro_id = $(this).data('pro');
            console.log(pro_id);
            window.open('../bitacoras/formatos/bitacora_consulta.php?idx_pro=' + pro_id, '_blank');
        });
    });
</script>
<div class="container-fluid">
    <h3 style="color: #007bff;">Procesos</h3>
    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableProcesos" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Proceso</th>
                        <th>Rendimiento</th>
                        <th>No.tarimas</th>
                        <th>Material</th>
                        <th>Viscosidad</th>
                        <th>Bloom</th>
                        <th>PH</th>
                        <th>Color</th>
                        <th>Olor</th>
                        <th>Redox</th>
                        <th>Humedad</th>
                        <th>Malla 30</th>
                        <th>Malla 45</th>
                        <th>Bitacoras</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>