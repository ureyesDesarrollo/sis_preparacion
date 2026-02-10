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
        $('#dataTableRacks').DataTable({
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
                        title: 'Listado racks',
                        filename: 'Listado_racks_excel',

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
                        title: 'Listado racks',
                        filename: 'Listado_racks_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado racks',
                        filename: 'Listado_racks_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ]
            },
            ajax: {
                url: 'catalogos/racks_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'rac_id'
                },
                {
                    data: 'rac_descripcion'
                },
                {
                    data: 'rac_zona'
                },
                {
                    data: 'rac_estatus',
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
                        <?php if (fnc_permiso($_SESSION['privilegio'], 39, 'upe_editar') == 1) { ?>
                            return '<a href="#"><i class=" btn-edit fa-regular fa-pen-to-square" data-id="' + row.rac_id + '"></i></a>';
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                {
                    data: 'rac_estatus',
                    render: function(data, type, row) {
                        if (data == 'A') {
                            <?php if (fnc_permiso($_SESSION['privilegio'], 39, 'upe_borrar') == 1) { ?>
                                return '<a href="#"><i class="text-danger btn-baja fa-solid fa-trash" data-id="' + row.rac_id + '" data-desc="' + row.rac_descripcion + '"></i></a>';
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

        $('#dataTableRacks').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            abrir_modal_rack_actualizar(id);
            console.log('Editar registro con ID: ' + id);

        });
        $('#dataTableRacks').on('click', '.btn-baja', function() {
            let id = $(this).data('id');
            let desc = $(this).data('desc');
            eliminar_rack(id, desc);
            console.log('Editar registro con ID: ' + id);
        });
    });
</script>
<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Racks</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <?php if (fnc_permiso($_SESSION['privilegio'], 39, 'upe_agregar') == 1) { ?>
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal_rack_insertar" onclick="abrir_modal_racks()">
                    <i class="fa fa-plus"></i> Agregar Rack
                </button>
            <?php } ?>
            <?php if (fnc_permiso($_SESSION['privilegio'], 40, 'upe_agregar') == 1) { ?>
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal_nivel_insertar" onclick="abrir_modal_nivel_pos()">
                    <i class="fa fa-plus"></i> Agregar Nivel - Posición
                </button>
            <?php } ?>

        </div>
    </div>

    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableRacks" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Zona</th>
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

<div class="modal fade" id="modal_racks_insertar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_racks_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_nivel_pos_insertar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<script>
    function abrir_modal_racks() {
        $.ajax({
            type: 'POST',
            url: 'catalogos/racks_modal_insertar.php',
            success: function(result) {
                $('#modal_racks_insertar').html(result);
                $('#modal_racks_insertar').modal('show');
            }
        });
    }

    function abrir_modal_nivel_pos() {
        $.ajax({
            type: 'POST',
            url: 'catalogos/nivel_posicion_modal_insertar.php',
            success: function(result) {
                $('#modal_nivel_pos_insertar').html(result);
                $('#modal_nivel_pos_insertar').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    function abrir_modal_rack_actualizar(id) {
        console.log(`${id} desde el modal`);
        let data = {
            "id": id
        }

        $.ajax({
            type: 'POST',
            data: data,
            url: 'catalogos/racks_modal_actualizar.php',
            success: function(result) {
                $('#modal_racks_actualizar').html(result);
                $('#modal_racks_actualizar').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });


    function eliminar_rack(id, desc) {
        let dataForm = {
            "rac_id": id
        };

        Swal.fire({
            title: "¿Seguro que deseas darlo de baja?",
            text: `Darás de baja ${desc}`,
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
                    url: 'catalogos/racks_eliminar.php',
                    data: dataForm,
                    success: function(result) {
                        let res = JSON.parse(result);
                        if (res.success) {
                            Swal.fire({
                                title: "Dado de baja!",
                                text: `${res.success}`,
                                icon: "success"
                            });
                            $('#dataTableRacks').DataTable().ajax.reload();
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