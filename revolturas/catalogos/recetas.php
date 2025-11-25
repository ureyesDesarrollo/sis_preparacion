<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../funciones/funciones.php";
include "../../conexion/conexion.php";
?>

<script>
    $(document).ready(function() {
        $('#dataTableRecetas').DataTable({
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
                        title: 'Listado recetas',
                        filename: 'Listado_recetas_excel',

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
                        title: 'Listado recetas',
                        filename: 'Listado_recetas_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado recetas',
                        filename: 'Listado_recetas_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ]
            },
            ajax: {
                url: 'catalogos/recetas_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'rre_id'
                }, {
                    data: 'cte_nombre'
                },
                {
                    data: 'rre_descripcion'
                },
                {
                    data: 'rre_estatus',
                    render: function(data) {
                        if (data === 'A') {
                            return 'Activo';
                        } else {
                            return 'Baja';
                        }
                    }
                },
                {
                    data: 'rre_estatus',
                    render: function(data, type, row) {
                        if (data === 'A') {
                            return generarBotones(row.rre_id, row.rre_descripcion, false);
                        } else {
                            return generarBotones(row.rre_id, row.rre_descripcion, true);
                        }
                    },
                    orderable: false
                }
            ]
        });

        function desabilitar(styles) {
            if (!styles) return '';
            const style = 'style="pointer-events: none;" disabled';
            return style;
        }

        function generarBotones(id, descripcion, isDisabled) {
            return `
            <?php if (fnc_permiso($_SESSION['privilegio'], 50, 'upe_listar') == 1) { ?>
        <button class="btn btn-primary btn-sm me-1 ver" ${desabilitar(isDisabled)} data-id="${id}" title="Ver">
            <i class="fa-solid fa-eye"></i>
        </button>
        <?php } ?>
        <?php if (fnc_permiso($_SESSION['privilegio'], 50, 'upe_editar') == 1) { ?>
        <button class="btn btn-warning btn-sm me-1 actualizar" ${desabilitar(isDisabled)} data-id="${id}" title="Actualizar">
            <i class="fa-solid fa-pen"></i>
        </button>
        <?php } ?>
        <?php if (fnc_permiso($_SESSION['privilegio'], 50, 'upe_borrar') == 1) { ?>
        <button class="btn btn-danger btn-sm borrar" ${desabilitar(isDisabled)} data-id="${id}" data-desc="${descripcion}" title="Borrar">
            <i class="fa-solid fa-trash"></i>
        </button>
        <?php } ?>
    `;
        }


        $('#dataTableRecetas').on('click', '.ver', function() {
            const recetaId = $(this).data('id');
            abrir_modal_ver_detalle(recetaId);
        });


        $('#dataTableRecetas').on('click', '.actualizar', function() {
            const recetaId = $(this).data('id');
            abrir_modal_actualizar(recetaId);
        });

        $('#dataTableRecetas').on('click', '.borrar', function() {
            const recetaId = $(this).data('id');
            const descripcion = $(this).data('desc');
            eliminar_receta(recetaId, descripcion);
        });
    });
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-mb-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Recetas</li>
                </ol>
            </nav>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <?php if (fnc_permiso($_SESSION['privilegio'], 50, 'upe_agregar') == 1) { ?>
                <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_recetas_insertar" onclick="abrir_modal_recetas()"> <i class="fa fa-plus"></i> Crear receta</button>
            <?php } ?>
        </div>
    </div>
</div>
<div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
    <div class="table-responsive mt-3">
        <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableRecetas" style="width: 100%;">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Cliente</th>
                    <th>Descripción</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
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

<div class="modal fade" id="modal_recetas_insertar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_recetas_detalle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_recetas_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>


<script>
    function abrir_modal_recetas() {
        $.ajax({
            type: 'POST',
            url: 'catalogos/recetas_modal_insertar.php',
            success: function(result) {
                $('#modal_recetas_insertar').html(result);
                $('#modal_recetas_insertar').modal('show');
            }
        });
    }


    function abrir_modal_ver_detalle(id) {
        $.ajax({
            type: 'POST',
            url: 'catalogos/recetas_detalle_modal.php',
            data: {
                'id_receta': id
            },
            success: function(result) {
                $('#modal_recetas_detalle').html(result);
                $('#modal_recetas_detalle').modal('show');
            }
        });
    }

    function abrir_modal_actualizar(id) {
        console.log(id);
        $.ajax({
            type: 'POST',
            url: 'catalogos/recetas_actualizar_modal.php',
            data: {
                'id_receta': id
            },
            success: function(result) {
                $('#modal_recetas_actualizar').html(result);
                $('#modal_recetas_actualizar').modal('show');
            }
        });
    }


    function eliminar_receta(id, desc) {
        let dataForm = {
            "id_receta": id
        };

        Swal.fire({
            title: "¿Seguro que deseas darla de baja?",
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
                    url: 'catalogos/recetas_eliminar.php',
                    data: dataForm,
                    success: function(result) {
                        let res = JSON.parse(result);
                        if (res.success) {
                            Swal.fire({
                                title: "Dado de baja!",
                                text: `${res.success}`,
                                icon: "success"
                            });
                            $('#dataTableRecetas').DataTable().ajax.reload();
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