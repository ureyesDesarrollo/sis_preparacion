<?php include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

?>
<script>
    $(document).ready(function() {
        $('#dataTableCalidadesRangos').DataTable({
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
                        title: 'Listado calidades_rangos',
                        filename: 'Listado_calidades_rangos_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado calidades_rangos',
                        filename: 'Listado_calidades_rangos_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado calidades_rangos',
                        filename: 'Listado_calidades_rangos_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    }
                ]
            },
            ajax: {
                url: 'catalogos/calidades_rangos_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'cr_id'
                },
                {
                    data: 'blo_ini'
                },
                {
                    data: 'blo_fin'
                },
                {
                    data: 'vis_ini'
                },
                {
                    data: 'vis_fin'
                },
                {
                    data: 'cal_descripcion',
                    render: function(data, type, row) {
                        return '<div style="width: 50px; height: 50px; background-color:' + row.cal_color + '; display: flex; align-items: center; justify-content: center;">' + data + '</div>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 43, 'upe_editar') == 1) { ?>
                            return '<a href="#"><i class=" btn-edit fa-regular fa-pen-to-square" data-id="' + row.cr_id + '"></i></a>';
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                }
            ]
        });

        $('#dataTableCalidadesRangos').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            abrir_modal_rango_calidad_actualizar(id);
            console.log('Editar registro con ID: ' + id);

        });

    });
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Calidad Rango</li>
                </ol>
            </nav>
        </div>
        <?php if (fnc_permiso($_SESSION['privilegio'], 43, 'upe_agregar') == 1) { ?>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_calidades_rangos_insertar" onclick="abrir_modal_calidad_rangos()"> <i class="fa fa-plus"></i> Agregar Calidad Rango</button>
            </div>
        <?php } ?>
    </div>
    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableCalidadesRangos" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Bloom inicial</th>
                        <th>Bloom final</th>
                        <th>Viscosidad inicial</th>
                        <th>Viscosidad Final</th>
                        <th>Calidad</th>
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
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="modal_calidades_rangos_insertar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<div class="modal fade" id="modal_calidades_rangos_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<script>
    function abrir_modal_calidad_rangos() {
        $.ajax({
            type: 'POST',
            url: 'catalogos/calidades_rangos_modal_insertar.php',
            success: function(result) {
                $('#modal_calidades_rangos_insertar').html(result);
                $('#modal_calidades_rangos_insertar').modal('show');
            }
        });
    }

    function abrir_modal_rango_calidad_actualizar(id) {
        let data = {
            "id": id
        }

        $.ajax({
            type: 'POST',
            data: data,
            url: 'catalogos/calidades_rangos_modal_actualizar.php',
            success: function(result) {
                $('#modal_calidades_rangos_actualizar').html(result);
                $('#modal_calidades_rangos_actualizar').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>