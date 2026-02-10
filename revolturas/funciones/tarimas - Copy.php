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
        $('#dataTableTarimas').DataTable({
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
                        title: 'Listado Tarimas',
                        filename: 'Listado_tarimas_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 13, 14]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Tarimas',
                        filename: 'Listado_tarimas_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 13, 14]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Tarimas',
                        filename: 'Listado_tarimas_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 13, 14]
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/tarimas_listado.php',
                dataSrc: ''
            },
            rowCallback: function(row, data, index) {
                if (data.tar_fino === 'F') {
                    $('td', row).css('background-color', '#fffcda');
                }

                if (data.tar_kilos < 1000.00) {
                    $('td', row).css('background-color', '#DCDCDC');
                }

                if (data.tar_fino === 'F' && data.tar_kilos < 1000.00) {
                    $('td', row).css('background-color', '#d1e7dd');
                }
            },
            columns: [{
                    data: 'tar_id'
                },
                {
                    data: 'pro_id'
                },
                {
                    data: 'tar_folio',
                },
                {
                    data: 'tar_kilos'
                },
                {
                    data: 'rac_descripcion',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (row.niv_nivel === null || row.niv_posicion === null) {
                            return 'Sin posicion';
                        } else {
                            return row.niv_nivel + ' - ' + row.niv_posicion;
                        }
                    }
                },
                {
                    data: 'usu_nombre',
                },
                {
                    data: 'tar_fecha',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 41, 'upe_editar') == 1) { ?>
                            return '<a href="#"><i class="btn-move fa-solid fa-arrow-right-arrow-left" data-niv="' + row.niv_id + '" data-rac="' + row.rac_id + '" data-tar="' + row.tar_id + '" data-pro="' + row.pro_id + '" data-folio="' + row.tar_folio + '"></i></a>';
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },

                {
                    data: 'tar_rendimiento',
                    width: "30px",
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 41, 'upe_editar') == 1) { ?>
                            //Si el rendimiento es null que valida el estatus del proceso
                            if (data === null) {
                                if (row.lote_estatus === '3') {
                                    return '<a href="#"><i class="btn-rendimiento fa-solid fa-calculator" data-pro="' + row.pro_id + '" data-folio="' + row.tar_folio + '"data-tar="' + row.tar_id + '"></i></a>';
                                } else if (row.lote_estatus === '2') {
                                    return '<a href="#" style="pointer-events: none; color: gray"><i class="btn-rendimiento fa-solid fa-calculator" data-pro="' + row.pro_id + '" data-folio="' + row.tar_folio + '" disabled></i></a >';
                                }
                            } else {
                                // Muestra el rendimiento
                                let resultado = (data * 100).toFixed(2);
                                return '<span>' + resultado + '%</span>';
                            }

                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 41, 'upe_editar') == 1) { ?>
                            if (row.tar_estatus === '0') {
                                return '<a href="#"><i class="btn-parametros fa-regular fa-pen-to-square" data-tar="' + row.tar_id + '"></i></a>';
                            } else {
                                return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Tarima en almacén"><i class=" fa-regular fa-pen-to-square" data-tar="' + row.tar_id + '"></i></a>';

                                return '<button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top">Tooltip on top</button>';
                            }
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 41, 'upe_listar') == 1) { ?>
                            return '<a href="#"><i class="btn-qr fa-solid fa-qrcode" data-tar="' + row.tar_id + '"></i></a>';
                        <?php } ?>
                    }
                },
                {
                    data: 'tar_rechazado',
                    render: function(data, type, row) {
                        if (data === null) {
                            return '<span>Por capturar</span>';
                        } else {
                            if (data === 'R') {
                                return '<span>Si</span>';
                            } else {
                                return '<span>No</span>';
                            }
                        }
                    }

                },
                {
                    data: 'cal_descripcion',
                },
                {
                    data: 'tar_estatus',
                    render: function(data, type, row) {
                        if (data == '0') {
                            <?php if ($_SESSION['privilegio'] == 20 || $_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2) { ?>
                                if (row.cal_id != null && row.cal_id != '0') {
                                    return '<a href="#"><i class="btn-enviar fa-solid fa-warehouse" data-tar="' + row.tar_id + '"></i></a>';
                                } else {
                                    return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Falta capturar parametros"><i class="fa-solid fa-warehouse" data-tar="' + row.tar_id + '"></i></a>';
                                }
                            <?php } else { ?>
                                return '<a href="#" style="color: gray"><i class="fa-solid fa-warehouse"></i></a>';
                            <?php } ?>
                        } else {
                            if (data == '6') {
                                return 'Empacado';
                            } else {
                                return 'Enviado';
                            }
                        }

                    }
                }
            ]
        });


        $('#dataTableTarimas').on('click', '.btn-move', function() {
            let rac_id = $(this).data('rac');
            let niv_id = $(this).data('niv');
            let tar_id = $(this).data('tar');
            let pro_id = $(this).data('pro');
            let tar_folio = $(this).data('folio');
            abrir_modal_mover(niv_id, rac_id, tar_id, pro_id, tar_folio);
        });

        $('#dataTableTarimas').on('click', '.btn-rendimiento', function() {
            let pro_id = $(this).data('pro');
            let tar_folio = $(this).data('folio');
            let tar_id = $(this).data('tar');

            abrir_modal_rendimiento(pro_id, tar_folio, tar_id);
        });


        $('#dataTableTarimas').on('click', '.btn-parametros', function() {
            let tar_id = $(this).data('tar');
            abrir_modal_parametros(tar_id);
        });

        $('#dataTableTarimas').on('click', '.btn-qr', function() {
            let tar_id = $(this).data('tar');
            window.open('funciones/tarimas_generar_qr.php?tar_id=' + tar_id, '_blank');
        });

        $('#dataTableTarimas').on('click', '.btn-enviar', function() {
            let tar_id = $(this).data('tar');
            enviar_almacen(tar_id);
        });

    });

    function determinarCalidad() {
        let tar_bloom = $('#tar_bloom').val();
        let tar_viscosidad = $('#tar_viscosidad').val();

        let dataForm = {
            'tar_bloom': tar_bloom,
            'tar_viscosidad': tar_viscosidad
        }

        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_determinar_calidad.php',
            data: dataForm,
            success: function(response) {
                let res = JSON.parse(response);

                $('#cal_descripcion').val(res.calidad);
                $('#cal_id').val(res.cal_id);
            }
        });
    }
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Registro de tarimas</li>
                </ol>
            </nav>
        </div>
        <div>
            <div style="width: 20px; height: 20px; background-color: #d1e7dd; vertical-align: middle; display: inline-block; border: 1px solid #000;"></div>
            <span style="display: inline-block; margin-left: 10px; vertical-align: middle;"> Es fino y tiene menos de 1000 kilos</span>
            <div style="width: 20px; height: 20px; background-color: #fffcda; vertical-align: middle; display:inline-block; border: 1px solid #000;"></div>
            <span style="display: inline-block; margin-left: 10px; vertical-align: middle;"> Es fino</span>
            <div style="width: 20px; height: 20px; background-color: #DCDCDC; vertical-align: middle; display:inline-block; border: 1px solid #000;"></div>
            <span style="display: inline-block; margin-left: 10px; vertical-align: middle;"> Tiene menos de 1000 kilos</span>
        </div>
        <div class="row justify-content-end">
            <?php if (fnc_permiso($_SESSION['privilegio'], 41, 'upe_agregar') == 1) { ?>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_rack_insertar" onclick="abrir_modal_tarimas()">
                        <i class="fa fa-plus"></i> Agregar Tarimas
                    </button>
                </div>
            <?php } ?>
        </div>

    </div>

    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableTarimas" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Proceso</th>
                        <th>Folio</th>
                        <th>Kilos</th>
                        <th>Rack</th>
                        <th>Posición</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Mover</th>
                        <th>Rendimiento</th>
                        <th>Parametros</th>
                        <th>Código QR</th>
                        <th>Rechazado</th>
                        <th>Calidad</th>
                        <th>Enviar almacén</th>
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
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_tarimas_insertar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_tarimas_mover" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_tarimas_rendimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_tarimas_parametros" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<script>
    function abrir_modal_tarimas() {
        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_modal_insertar.php',
            success: function(result) {
                $('#modal_tarimas_insertar').html(result);
                $('#modal_tarimas_insertar').modal('show');
            }
        });
    }

    function abrir_modal_mover(niv_id, rac_id, tar_id, pro_id, tar_folio) {
        let dataForm = {
            'niv_id': niv_id,
            'rac_id': rac_id,
            'tar_id': tar_id,
            'pro_id': pro_id,
            'tar_folio': tar_folio
        }


        console.log(dataForm)
        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/tarimas_modal_mover_posicion.php',
            success: function(result) {
                $('#modal_tarimas_mover').html(result);
                $('#modal_tarimas_mover').modal('show');
            }
        });
    }

    function abrir_modal_rendimiento(pro_id, tar_folio, tar_id) {
        let dataForm = {
            'pro_id': pro_id,
            'tar_folio': tar_folio,
            'tar_id': tar_id
        };

        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/tarimas_modal_rendimiento.php',
            success: function(result) {
                $('#modal_tarimas_rendimiento').html(result);
                $('#modal_tarimas_rendimiento').modal('show');
            }
        });
    }

    function abrir_modal_parametros(tar_id) {
        let dataForm = {
            'tar_id': tar_id
        };

        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/tarimas_modal_parametros.php',
            success: function(result) {
                $('#modal_tarimas_parametros').html(result);
                $('#modal_tarimas_parametros').modal('show');
            }
        });
    }


    function enviar_almacen(id) {
        let dataForm = {
            "tar_id": id
        };

        Swal.fire({
            title: "¿Seguro que deseas enviar a almacén?",
            text: '',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'funciones/tarimas_enviar_almacen.php',
                    data: dataForm,
                    success: function(result) {
                        let res = JSON.parse(result);
                        if (res.success) {
                            Swal.fire({
                                title: "Enviado!",
                                text: `${res.success}`,
                                icon: "success"
                            });
                            $('#dataTableTarimas').DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                title: "Ocurrio un error!",
                                text: `${res.error}`,
                                icon: "error"
                            });
                        }
                    }
                });
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>