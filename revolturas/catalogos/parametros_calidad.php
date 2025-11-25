<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
?>

<script>
    //Bloom
    $(document).ready(function() {
        $('#dataTableBloom').DataTable({
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
                        title: 'Listado Bloom',
                        filename: 'Listado_bloom_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Bloom',
                        filename: 'Listado_bloom_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Bloom',
                        filename: 'Listado_bloom_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ]
            },
            ajax: {
                url: 'catalogos/parametros_calidad_bloom_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'blo_id'
                },
                {
                    data: 'blo_ini'
                },
                {
                    data: 'blo_fin'
                },
                {
                    data: 'blo_etiqueta'
                },
                {
                    data: 'blo_estatus',
                    render: function(data, type, row) {
                        if (data == 'A') {
                            data = 'Activo';
                        } else {
                            data = 'Baja';
                        }
                        return '<span>' + data + '</span>';
                    }
                },
                {
                    data: null,

                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 37, 'upe_editar') == 1) { ?>
                            return '<a href="#"><i class=" btn-edit fa-regular fa-pen-to-square" data-id="' + row.blo_id + '"></i></a>';
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                {
                    data: 'blo_estatus',
                    render: function(data, type, row) {
                        if (data == 'A') {
                            <?php if (fnc_permiso($_SESSION['privilegio'], 37, 'upe_borrar') == 1) { ?>
                                return '<a href="#"><i class="text-danger btn-baja fa-solid fa-trash" data-id="' + row.blo_id + '" data-etiqueta="' + row.blo_etiqueta + '"></i></a>';
                            <?php } else { ?>
                                return '';
                            <?php } ?>
                        } else {
                            return '';
                        }
                    }
                }
            ]
        });

        $('#dataTableBloom').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            abrir_modal_parametros_bloom_actualizar(id);
            console.log('Editar registro con ID: ' + id);

        });
        $('#dataTableBloom').on('click', '.btn-baja', function() {
            let id = $(this).data('id');
            let etiqueta = $(this).data('etiqueta');
            eliminar_bloom(id, etiqueta);
            console.log('Editar registro con ID: ' + id);
        });
    });

    //Viscosidad
    $(document).ready(function() {
        $('#dataTableViscosidad').DataTable({
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
                        title: 'Listado viscosidades',
                        filename: 'Listado_viscosidades_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado Visccosidades',
                        filename: 'Listado_viscosidades_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Viscosidades',
                        filename: 'Listado_viscosidades_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ]
            },
            ajax: {
                url: 'catalogos/parametros_calidad_viscosidad_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'vis_id'
                },
                {
                    data: 'vis_descrip'
                },
                {
                    data: 'vis_min_val'
                },
                {
                    data: 'vis_max_val'
                },
                {
                    data: 'vis_color',
                    render: function(data, type, row, meta) {
                        return '<div style="width: 20px; height: 20px; background-color:' + data + ';"></div>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 38, 'upe_agregar') == 1) { ?>
                            return '<a href="#"><i class=" btn-edit fa-regular fa-pen-to-square" data-id="' + row.vis_id + '"></i></a>';
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                /* {
                    data: null,
                    render: function(data, type, row) {
                        <?php if (fnc_permiso($_SESSION['privilegio'], 38, 'upe_borrar') == 1) { ?>
                            return '<a href="#"><i class="text-danger btn-baja fa-solid fa-trash" data-id="' + row.vis_id + '" data-desc="' + row.vis_descrip + '"></i></a>';
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                } */
            ]
        });

        $('#dataTableViscosidad').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            abrir_modal_parametros_vis_actualizar(id);
            console.log('Editar registro con ID: ' + id);

        });
        $('#dataTableViscosidad').on('click', '.btn-baja', function() {
            let id = $(this).data('id');
            let desc = $(this).data('desc');
            eliminar_vis(id, desc);
            console.log('Editar registro con ID: ' + id);
        });
    });
</script>
<style>
    .fixed-table-container {
        height: 500px;
        /* Ajusta esta altura según sea necesario */
        overflow-y: auto;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Bloom</li>
                </ol>
            </nav>
            <?php if (fnc_permiso($_SESSION['privilegio'], 37, 'upe_agregar') == 1) { ?>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_presentaciones_insertar" onclick="abrir_modal_parametros_bloom()"> <i class="fa fa-plus"></i> Agregar Bloom</button>
                </div>
            <?php } ?>
            <div class="container-fluid mt-3 fixed-table-container" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
                <div class="table-responsive mt-3">
                    <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableBloom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Bloom Inicio</th>
                                <th>Bloom Fin</th>
                                <th>Etiqueta</th>
                                <th>Estatus</th>
                                <th>Editar</th>
                                <th>Baja</th>
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
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Viscosidad</li>
                </ol>
            </nav>
            <?php if (fnc_permiso($_SESSION['privilegio'], 38, 'upe_agregar') == 1) { ?>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_presentaciones_insertar" onclick="abrir_modal_parametros_vis()"> <i class="fa fa-plus"></i> Agregar Viscosidad </button>
                </div>
            <?php } ?>
            <div class="container-fluid mt-3 fixed-table-container" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
                <div class="table-responsive mt-3">
                    <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableViscosidad" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Descripción</th>
                                <th>Valor Minimo</th>
                                <th>Valor Maximo</th>
                                <th>Phantome</th>
                                <th>Editar</th>
                                <!-- <th>Baja</th> -->
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
                                <!-- <td></td> -->
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
                                <!-- <td></td> -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_parametros_bloom_insertar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_parametros_bloom_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_parametros_viscosidad_insertar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_parametros_viscosidad_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>


<script>
    function abrir_modal_parametros_bloom() {
        $.ajax({
            type: 'POST',
            url: 'catalogos/parametros_calidad_bloom_modal_insertar.php',
            success: function(result) {
                $('#modal_parametros_bloom_insertar').html(result);
                $('#modal_parametros_bloom_insertar').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    function abrir_modal_parametros_bloom_actualizar(id) {
        console.log(`${id} desde el modal`);
        let data = {
            "id": id
        }

        $.ajax({
            type: 'POST',
            data: data,
            url: 'catalogos/parametros_calidad_bloom_modal_actualizar.php',
            success: function(result) {
                $('#modal_parametros_bloom_actualizar').html(result);
                $('#modal_parametros_bloom_actualizar').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });


    function eliminar_bloom(id, etiqueta) {
        let dataForm = {
            "blo_id": id
        };

        Swal.fire({
            title: "¿Seguro que deseas darlo de baja?",
            text: `Darás de baja ${etiqueta}`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, dar de baja!",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'catalogos/parametros_calidad_bloom_eliminar.php',
                    data: dataForm,
                    success: function(result) {
                        let res = JSON.parse(result);
                        if (res.success) {
                            Swal.fire({
                                title: "Dado de baja!",
                                text: `${res.success}`,
                                icon: "success"
                            });
                            $('#dataTableBloom').DataTable().ajax.reload();
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


    //Viscosidad
    function abrir_modal_parametros_vis() {
        $.ajax({
            type: 'POST',
            url: 'catalogos/parametros_calidad_vis_modal_insertar.php',
            success: function(result) {
                $('#modal_parametros_viscosidad_insertar').html(result);
                $('#modal_parametros_viscosidad_insertar').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    function abrir_modal_parametros_vis_actualizar(id) {
        console.log(`${id} desde el modal`);
        let data = {
            "id": id
        }

        $.ajax({
            type: 'POST',
            data: data,
            url: 'catalogos/parametros_calidad_vis_modal_actualizar.php',
            success: function(result) {
                $('#modal_parametros_viscosidad_actualizar').html(result);
                $('#modal_parametros_viscosidad_actualizar').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });


    function eliminar_vis(id, etiqueta) {
        let dataForm = {
            "vis_id": id
        };

        Swal.fire({
            title: "¿Seguro que deseas darlo de baja?",
            text: `Darás de baja ${etiqueta}`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, dar de baja!",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'catalogos/parametros_calidad_vis_eliminar.php',
                    data: dataForm,
                    success: function(result) {
                        let res = JSON.parse(result);
                        if (res.success) {
                            Swal.fire({
                                title: "Dado de baja!",
                                text: `${res.success}`,
                                icon: "success"
                            });
                            $('#dataTable').DataTable().ajax.reload();
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
</script>