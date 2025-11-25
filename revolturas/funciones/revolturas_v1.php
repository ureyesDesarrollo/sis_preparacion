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
        $('#dataTableRevolturas').DataTable({
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
                        title: 'Listado Revolturas',
                        filename: 'Listado_revolturas_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 6, 11, 12]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Revolturas',
                        filename: 'Listado_revolturas_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 6, 11, 12]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Revolturas',
                        filename: 'Listado_revolturas_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 6, 11, 12]
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/revolturas_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'rev_id'
                },
                {
                    data: 'rev_folio'
                },
                {
                    data: 'usu_nombre'
                },
                {
                    data: 'rev_fecha'
                },
                {
                    data: 'rev_kilos'
                },
                //Revolver
                {
                    data: 'rev_estatus',
                    render: function(data, type, row) {
                        <?php if ((fnc_permiso($_SESSION['privilegio'], 46, 'upe_editar') == 1) || ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || $_SESSION['privilegio'] == 23)) { ?>
                            if (data === '0') {
                                return '<a href="#"><i class="btn-revolver fas fa-blender" data-rev="' + row.rev_id + '" data-fol="' + row.rev_folio + '"></i></a>';
                            } else if (data === '1') {
                                return '<a href="#"><i class="btn-revolver fas fa-blender" data-rev="' + row.rev_id + '" data-fol="' + row.rev_folio + '" style="color: orange;" title="En proceso"></i></a>';
                            } else {
                                return '<a href="#"><i class="fas fa-blender" style="color: gray;" title="Revoltura terminada"></i></a>';
                            }
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                //Captura parametros
                {
                    data: 'rev_estatus',
                    render: function(data, type, row) {
                        <?php if ((fnc_permiso($_SESSION['privilegio'], 46, 'upe_editar') == 1) || $_SESSION['privilegio'] == 20 || $_SESSION['privilegio'] == 24) { ?>
                            if ((data == '2') && (row.cal_id != null || row.cal_id != '0')) {
                                return '<a href="#"><i class="btn-parametros fa-regular fa-pen-to-square" data-rev="' + row.rev_id + '"></i></a>';
                            } else {
                                if (data == '3') {
                                    return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Revoltura empacada"><i class="fa-regular fa-pen-to-square"></i></a>';
                                } else {
                                    return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Revoltura en proceso"><i class="fa-regular fa-pen-to-square"></i></a>';
                                }

                            }
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                //Calidad
                {
                    data: 'cal_descripcion',
                },
                //Consulta qr
                {
                    data: null,
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 46, 'upe_listar') == 1) { ?>
                            return '<a href="#"><i class="btn-qr fa-solid fa-qrcode" data-rev="' + row.rev_id + '"></i></a>';
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                // Orden de revoltura
                {
                    data: null,
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 46, 'upe_listar') == 1) { ?>
                            return '<a href="#"><i class="btn-orden fa-solid fa-receipt" data-rev="' + row.rev_id + '"></i></a>';
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                //Empacar
                {
                    data: 'rev_estatus',
                    render: function(data, type, row) {
                        <?php if ((fnc_permiso($_SESSION['privilegio'], 46, 'upe_editar') == 1) || ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || $_SESSION['privilegio'] == 23)) { ?>
                            if ((data == '2' || data == '3') && row.cal_id != null && row.cal_id != '0') {
                                return '<a href="#"><i class="btn-empacar fa-solid fa-boxes-packing" data-rev="' + row.rev_id + '"data-fol="' + row.rev_folio + '"></i></a>';

                            } else {
                                return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Revoltura en proceso"><i class="fa-solid fa-boxes-packing"></i></a>';
                            }
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                //Muetreo
                {
                    data: 'rev_estatus',
                    render: function(data, type, row) {
                        <?php if ((fnc_permiso($_SESSION['privilegio'], 46, 'upe_editar') == 1) || ($_SESSION['privilegio'] == 21 || $_SESSION['privilegio'] == 22 || $_SESSION['privilegio'] == 23)) { ?>
                            if (data == '3') {
                                return '<a href="#"><i class="btn-muestreo fas fa-weight" data-rev="' + row.rev_id + '"data-fol="' + row.rev_folio + '"></i></a>';
                            } else {
                                return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Revoltura en proceso"><i class="fas fa-weight"></i></a>';
                            }
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                /*  {
                     data: 'rev_estatus',
                     render: function(data, type, row) {
                         if (data == '3') {
                             <?php //if (fnc_permiso($_SESSION['privilegio'], 46, 'upe_editar') == 1) { 
                                ?>
                                 return '<a href="#"><i class="btn-vale fa-solid fa-arrow-right-from-bracket" data-rev="' + row.rev_id + '"></i></a>';
                             <?php //} 
                                ?>
                         } else {
                             return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Revoltura en proceso"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>';
                         }
                     }
                 }, */
                //Agregar factura
                {
                    data: 'rev_factura',
                    render: function(data, type, row) {
                        if (data == null) {
                            <?php if ($_SESSION['privilegio'] == 26 || $_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2) { ?>
                                if (row.rev_estatus == '3') {
                                    return '<a href="#"><i class="btn-factura fa-solid fa-file-invoice-dollar" data-rev="' + row.rev_id + '"></i></a>';
                                } else {
                                    return '<a href="#" style="color: gray" data-bs-toggle="tooltip" data-bs-placement="top" title="Revoltura en proceso"><i class="fa-solid fa-file-invoice-dollar"></i></a>';
                                }
                            <?php } else { ?>
                                return '';
                            <?php } ?>
                        } else {
                            return `<span>${data}</span>`;
                        }
                    }
                },
                //Estatus
                {
                    data: 'rev_estatus',
                    render: function(data, type, row) {
                        if (data == '0') {
                            return `<span>Sin revolver</span>`;
                        } else if (data == '1') {
                            return `<span>En Proceso</span>`;
                        } else if (data == '2' && (row.cal_id == null || row.cal_id == '0')) {
                            return `<span>Capturar parametros</span>`;
                        } else if (data == '2') {
                            return `<span>Terminada</span>`;
                        } else if (data == '3') {
                            return `<span>Empacada</span>`;
                        }
                    }
                }
            ]
        });

        $('#dataTableRevolturas').on('click', '.btn-revolver', function() {
            let rev_id = $(this).data('rev');
            let rev_folio = $(this).data('fol');
            abrir_modal_revoltura(rev_id, rev_folio);
        });


        $('#dataTableRevolturas').on('click', '.btn-parametros', function() {
            let rev_id = $(this).data('rev');
            abrir_modal_parametros_revoltura(rev_id);
        });

        $('#dataTableRevolturas').on('click', '.btn-orden', function() {
            let rev_id = $(this).data('rev');
            window.open('funciones/revolturas_orden.php?rev_id=' + rev_id, '_blank');
        });

        $('#dataTableRevolturas').on('click', '.btn-empacar', function() {
            let rev_id = $(this).data('rev');
            let rev_folio = $(this).data('fol');
            abrir_modal_empacado(rev_id, rev_folio);
        });

        $('#dataTableRevolturas').on('click', '.btn-qr', function() {
            let rev_id = $(this).data('rev');
            window.open('funciones/revolturas_generar_qr.php?rev_id=' + rev_id, '_blank');
        });

        $('#dataTableRevolturas').on('click', '.btn-muestreo', function() {
            let rev_id = $(this).data('rev');
            let rev_folio = $(this).data('fol');
            abrir_modal_muestreo(rev_id, rev_folio);
        });

        $('#dataTableRevolturas').on('click', '.btn-factura', function() {
            let rev_id = $(this).data('rev');
            abrir_modal_factura(rev_id);
        });


        $('#dataTableRevolturas').on('click', '.btn-vale', function() {
            let rev_id = $(this).data('rev');
            window.open('funciones/vale_salida.php?rev_id=' + rev_id, '_blank');
        });
    });
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Listado de revolturas</li>
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
        <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableRevolturas" style="width: 100%;">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Folio</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Kilos</th>
                    <th>Revolver</th>
                    <th>Parametros</th>
                    <th>Calidad</th>
                    <th>QR</th>
                    <th>Orden</th>
                    <th>Empacar</th>
                    <th>Muestreo</th>
                    <!-- <th>Vale de salida</th> -->
                    <th>Factura</th>
                    <th>Estatus</th>
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
                    <!-- <td></td> -->
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
                    <!-- <td></td> -->
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_revolturas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<div class="modal fade" id="modal_revolturas_param" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<div class="modal fade" id="modal_empacado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<div class="modal fade" id="modal_muestreo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_factura" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<script>
    function abrir_modal_revoltura(rev_id, rev_folio) {
        let dataForm = {
            'rev_id': rev_id,
            'rev_folio': rev_folio
        };

        console.log(dataForm);
        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/revolturas_modal_insertar.php',
            success: function(result) {
                $('#modal_revolturas').html(result);
                $('#modal_revolturas').modal('show');
            }
        });
    }

    function abrir_modal_parametros_revoltura(rev_id) {
        let dataForm = {
            'rev_id': rev_id
        };

        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/revolturas_modal_parametros.php',
            success: function(result) {
                $('#modal_revolturas_param').html(result);
                $('#modal_revolturas_param').modal('show');
            }
        });
    }

    function abrir_modal_empacado(rev_id, rev_folio) {
        let dataForm = {
            'rev_id': rev_id,
            'rev_folio': rev_folio
        };

        console.log(`Desde funcion abir modal empacado:`);
        console.log(dataForm);

        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/empacado_modal.php',
            success: function(result) {
                $('#modal_empacado').html(result);
                $('#modal_empacado').modal('show');
            }
        });
    }

    function abrir_modal_muestreo(rev_id, rev_folio) {
        let dataForm = {
            'rev_id': rev_id,
            'rev_folio': rev_folio
        };

        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/muestreo_modal.php',
            success: function(result) {
                $('#modal_muestreo').html(result);
                $('#modal_muestreo').modal('show');
            }
        });
    }

    function abrir_modal_factura(rev_id) {
        let dataForm = {
            'rev_id': rev_id
        };

        $.ajax({
            type: 'POST',
            data: dataForm,
            url: 'funciones/revolturas_modal_factura.php',
            success: function(result) {
                $('#modal_factura').html(result);
                $('#modal_factura').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>