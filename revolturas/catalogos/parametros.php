<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
?>
<script>
    $(document).ready(function() {
        $('#dataTableParametros').DataTable({
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
                        title: 'Listado parametros',
                        filename: 'Listado_parametros_excel',

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
                        title: 'Listado parametros',
                        filename: 'Listado_parametros_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado parametros',
                        filename: 'Listado_parametros_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    }
                ]
            },
            ajax: {
                url: 'catalogos/parametros_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'rp_id'
                },
                {
                    data: 'rp_parametro'
                },
                {
                    data: 'rp_inicio'
                },
                {
                    data: 'rp_fin'
                },
                {

                    data: null,
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 47, 'upe_editar') == 1) { ?>
                            return '<a href="#"><i class=" btn-edit fa-regular fa-pen-to-square" data-id="' + row.rp_id + '"></i></a>';
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                }
            ]
        });

        $('#dataTableParametros').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            abrir_modal_parametros_actualizar(id);
            console.log('Editar registro con ID: ' + id);

        });
    });
</script>
<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Parametros</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableParametros" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Valor inicio</th>
                        <th>Valor fin</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>

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
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_parametros_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<script>
    function abrir_modal_parametros_actualizar(id) {
        console.log(`${id} desde el modal`);
        let data = {
            "id": id
        }

        $.ajax({
            type: 'POST',
            data: data,
            url: 'catalogos/parametros_modal_actualizar.php',
            success: function(result) {
                $('#modal_parametros_actualizar').html(result);
                $('#modal_parametros_actualizar').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>