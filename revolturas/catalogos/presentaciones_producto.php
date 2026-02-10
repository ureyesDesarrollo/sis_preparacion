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
        $('#dataTable').DataTable({
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
                        title: 'Listado Presentaciones',
                        filename: 'Listado_presentaciones_excel',

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
                        title: 'Listado Presentaciones',
                        filename: 'Listado_presentaciones_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado Presentaciones',
                        filename: 'Listado_presentaciones_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ]
            },
            ajax: {
                url: 'catalogos/presentaciones_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'pres_id'
                },
                {
                    data: 'pres_descrip'
                },
                {
                    data: 'pres_kg'
                },
                {
                    data: 'pres_estatus',
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
                        <?php if (fnc_permiso($_SESSION['privilegio'], 36, 'upe_editar') == 1) { ?>
                            return '<a href="#"><i class=" btn-edit fa-regular fa-pen-to-square" data-id="' + row.pres_id + '"></i></a>';
                        <?php } else { ?>
                            return '';
                        <?php } ?>
                    }
                },
                {
                    data: 'pres_estatus',
                    render: function(data, type, row) {
                        if (data == 'A') {
                            <?php if (fnc_permiso($_SESSION['privilegio'], 36, 'upe_borrar') == 1) { ?>
                                return '<a href="#"><i class="text-danger btn-baja fa-solid fa-trash" data-id="' + row.pres_id + '" data-desc="' + row.pres_descrip + '"></i></a>';
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

        $('#dataTable').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            abrir_modal_prestaciones_actualizar(id);
            console.log('Editar registro con ID: ' + id);

        });
        $('#dataTable').on('click', '.btn-baja', function() {
            let id = $(this).data('id');
            let desc = $(this).data('desc');
            eliminar_presentacion(id, desc);
            console.log('Editar registro con ID: ' + id);
        });
    });
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Presentaciones</li>
                </ol>
            </nav>
        </div>
        <?php if (fnc_permiso($_SESSION['privilegio'], 36, 'upe_agregar') == 1) { ?>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary me-md-2" type="button" data-toggle="modal" data-target="#modal_presentaciones_insertar" onclick="abrir_modal_prestaciones()"> <i class="fa fa-plus"></i> Agregar Presentación</button>
            </div>
        <?php } ?>
    </div>
    <div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
        <div class="table-responsive mt-3">
            <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Kilos</th>
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
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="modal_presentaciones_insertar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<div class="modal fade" id="modal_presentaciones_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>

<script>
    function abrir_modal_prestaciones() {
        $.ajax({
            type: 'POST',
            url: 'catalogos/presentaciones_modal_insertar.php',
            success: function(result) {
                $('#modal_presentaciones_insertar').html(result);
                $('#modal_presentaciones_insertar').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    function abrir_modal_prestaciones_actualizar(id) {
        console.log(`${id} desde el modal`);
        let data = {
            "id": id
        }

        $.ajax({
            type: 'POST',
            data: data,
            url: 'catalogos/presentaciones_modal_actualizar.php',
            success: function(result) {
                $('#modal_presentaciones_actualizar').html(result);
                $('#modal_presentaciones_actualizar').modal('show');
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });


    function eliminar_presentacion(id, desc) {
        let dataForm = {
            "pres_id": id
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
                    url: 'catalogos/presentaciones_eliminar.php',
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