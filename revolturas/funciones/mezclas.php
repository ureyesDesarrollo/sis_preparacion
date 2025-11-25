<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
?>

<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
?>

<script>
    $(document).ready(function() {
        $('#dataTableMezclas').DataTable({
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
                        title: 'Listado mezclas',
                        filename: 'Listado_mezclas_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    if (column === 3) { // Columna de estatus
                                        if (data.includes('fa-mortar-pestle') && data.includes('style="color: orange;"')) {
                                            return 'En Proceso';
                                        } else if (data.includes('fa-mortar-pestle')) {
                                            return 'Sin Procesar';
                                        } else if (data.includes('status-completed')) {
                                            return 'Terminado';
                                        } else {
                                            return '';
                                        }
                                    }
                                    return data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado mezclas',
                        filename: 'Listado_mezclas_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    if (column === 3) { // Columna de estatus
                                        if (data.includes('fa-mortar-pestle') && data.includes('style="color: orange;"')) {
                                            return 'En Proceso';
                                        } else if (data.includes('fa-mortar-pestle')) {
                                            return 'Sin Procesar';
                                        } else if (data.includes('status-completed')) {
                                            return 'Terminado';
                                        } else {
                                            return '';
                                        }
                                    }
                                    return data;
                                }
                            }
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado mezclas',
                        filename: 'Listado_mezclas_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5],
                            format: {
                                body: function(data, row, column, node) {
                                    if (column === 3) { // Columna de estatus
                                        if (data.includes('fa-mortar-pestle') && data.includes('style="color: orange;"')) {
                                            return 'En Proceso';
                                        } else if (data.includes('fa-mortar-pestle')) {
                                            return 'Sin Procesar';
                                        } else if (data.includes('status-completed')) {
                                            return 'Terminado';
                                        } else {
                                            return '';
                                        }
                                    }
                                    return data;
                                }
                            }
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/mezclas_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'mez_id'
                },
                {
                    data: 'mez_folio'
                },
                {
                    data: 'mez_fecha'
                },
                {
                    data: 'mez_kilos'
                },
                {
                    data: 'mez_estatus',
                    render: function(data, type, row) {
                        <?php if ((fnc_permiso($_SESSION['privilegio'], 48, 'upe_editar') == 1) || ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || $_SESSION['privilegio'] == 24)) { ?>
                            if (data === '0') {
                                return '<a href="#"><i class="btn-mezclar fa-solid fa-mortar-pestle" data-mez="' + row.mez_id + '" data-fol="' + row.mez_folio + '"></i></a>';
                            } else if (data === '1') {
                                return '<a href="#"><i class="btn-mezclar fa-solid fa-mortar-pestle" data-mez="' + row.mez_id + '" data-fol="' + row.mez_folio + '" style="color: orange;" title="En proceso"></i></a>';
                            } else if (data === '2') {
                                return '<span class="status-completed">Terminado</span>';
                            } else {
                                return '';
                            }
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                {
                    data: 'mez_estatus',
                    render: function(data, type, row) {
                        <?php if ((fnc_permiso($_SESSION['privilegio'], 48, 'upe_editar') == 1) || ($_SESSION['privilegio'] == 20 || $_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || $_SESSION['privilegio'] == 24)) { ?>
                            if ((data == '2') && (row.cal_id === null || row.cal_id == '0')) {
                                return '<a href="#"><i class="btn-parametros fa-regular fa-pen-to-square" data-mez="' + row.mez_id + '"data-fol="' + row.mez_folio + '"></i></a>';
                            } else if (data == '0' || data == '1') {
                                return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Mezcla en proceso"><i class=" fa-regular fa-pen-to-square"></i></a>';
                            } else {
                                return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Parametros capturados"><i class=" fa-regular fa-pen-to-square"></i></a>';
                            }
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }

                },
                {
                    data: 'cal_descripcion',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 48, 'upe_listar') == 1) { ?>
                            return '<a href="#"><i class="btn-detalle fa-solid fa-receipt" data-mez="' + row.mez_id + '"data-fol="' + row.mez_folio + '"></i></a>';
                        <?php } ?>
                    }
                }

            ]
        });

        $('#dataTableMezclas').on('click', '.btn-mezclar', function() {
            let mez_id = $(this).data('mez');
            let mez_folio = $(this).data('fol');
            abrir_modal_mezcla(mez_id, mez_folio);
        });


        $('#dataTableMezclas').on('click', '.btn-parametros', function() {
            let mez_id = $(this).data('mez');
            let mez_folio = $(this).data('fol');
            abrir_modal_parametros_mezcla(mez_id, mez_folio);
        });

        $('#dataTableMezclas').on('click', '.btn-detalle', function() {
            let mez_id = $(this).data('mez');
            let mez_folio = $(this).data('fol');
            window.open('funciones/mezclas_detalle.php?mez_id=' + mez_id + '&mez_folio=' + mez_folio, '_blank');
        });

    });
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Listado de mezclas</li>
                </ol>
            </nav>
        </div>
        <!-- <div class="row justify-content-end">
            <?php if (fnc_permiso($_SESSION['privilegio'], 46, 'upe_agregar') == 1) { ?>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_rack_insertar" onclick="abrir_modal_revoltura()">
                        <i class="fa fa-plus"></i> Acción
                    </button>
                </div>
            <?php } ?>
        </div> -->
    </div>

</div>

<div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
    <div class="table-responsive mt-3">
        <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableMezclas" style="width: 100%;">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Kilos</th>
                    <th>Mezclar</th>
                    <th>Parametros</th>
                    <th>Calidad</th>
                    <th>Detalle</th>
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
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_mezclas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<div class="modal fade" id="modal_mezclas_param" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<script>
    function abrir_modal_mezcla(mez_id, mez_folio) {
        let dataForm = {
            'mez_id': mez_id,
            'mez_folio': mez_folio
        };

        console.log(dataForm);
        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/mezclas_modal_insertar.php',
            success: function(result) {
                $('#modal_mezclas').html(result);
                $('#modal_mezclas').modal('show');
            }
        });
    }

    function abrir_modal_parametros_mezcla(mez_id, mez_folio) {
        let dataForm = {
            'mez_id': mez_id,
            'mez_folio': mez_folio
        };

        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/mezclas_modal_parametros.php',
            success: function(result) {
                $('#modal_mezclas_param').html(result);
                $('#modal_mezclas_param').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>