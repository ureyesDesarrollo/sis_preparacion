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
                [0, 'asc']
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
                url: 'funciones/orden_embarque_calidad_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'fecha_creacion'
                },
                {
                    data: 'cliente_nombre'
                },
                {
                    data: 'estado'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if(row.estado === 'PROCESO' || row.estado === 'COMPLETADA'){
                            return `<button class="btn btn-outline-success btn-liberar" data-id='${JSON.stringify(row.orden_id)}' title="Liberar"><i class="fa-solid fa-check"></i></button>`;
                        }else{
                            return `<button class="btn btn-outline-secondary" title="Orden aun no comenzada"><i class="fa-solid fa-check"></i></button>`;
                        }
                    }
                },
            ]
        });

        $('#dataTableEmbarque').on('click', '.btn-liberar', function() {
            let idOrden = $(this).data('id');
            console.log(idOrden);
            abrir_modal_liberar(idOrden);
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
                        <th>Estatus</th>
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
</div>
<div class="modal fade" id="modal_orden_embarque" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<!-- Segundo Modal (Modal de Liberación de Etiqueta) -->
<div class="modal fade" id="modalLiberarEtiqueta" tabindex="-1" aria-labelledby="modalLiberarEtiquetaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLiberarEtiquetaLabel">Liberar Etiqueta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-10">
                    <label for="">¿Cuantas etiquetas imprimira?</label>
                    <input type="number" class="form-control" id="cantidad_etiquetas" name="cantidad_etiquetas" value="1" min="1" max="10">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnConfirmarLiberacion">Confirmar Liberación</button>
            </div>
        </div>
    </div>
</div>

<script>
    function abrir_modal_liberar(idOrden) {
        $.ajax({
            type: 'POST',
            url: 'funciones/orden_embarque_calidad_modal.php',
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