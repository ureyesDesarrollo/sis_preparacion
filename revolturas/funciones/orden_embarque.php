<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/

include "../../seguridad/user_seguridad.php";

?>

<script>
    $(document).ready(function() {
        const formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
        $('#dataTableEmbarque').DataTable({
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
                        title: 'Listado Embarque',
                        filename: 'Listado_embarque_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Embarque',
                        filename: 'Listado_embarque_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Embarque',
                        filename: 'Listado_embarque_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/orden_embarque_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'fecha_creacion'
                },
                {
                    data: 'cliente_nombre'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<button class="btn btn-primary btn-facturar" data-id='${JSON.stringify(row.orden_id)}' title="Facturar"><i class="fa-solid fa-file-invoice"></i></button>`;
                    }
                },
            ]
        });

        $('#dataTableEmbarque').on('click', '.btn-facturar', function() {
            let idOrden = $(this).data('id');
            console.log(idOrden);
            abrir_modal_facturas(idOrden);
        });

    });
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        Ordenes de embarque
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" class="display" id="dataTableEmbarque" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Fecha solicitud</th>
                        <th>Cliente</th>
                        <th>Facturar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    </tr>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_orden_embarque" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<script>
    function abrir_modal_facturas(idOrden) {
        $.ajax({
            type: 'POST',
            url: 'funciones/facturas_empacado_modal.php',
            data: {
                oe_id: idOrden
            },
            success: function(result) {
                $('#modal_orden_embarque').html(result);
                $('#modal_orden_embarque').modal('show');
            }
        });
    }
</script>