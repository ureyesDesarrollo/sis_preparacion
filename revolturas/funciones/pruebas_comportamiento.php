<?php
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
?>

<script>
    $(document).ready(function() {
        $('#dataTablePruebasComportamiento').DataTable({
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
                [2, 'desc']
            ],
            "sDom": "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-5 'B><'col-sm-12 col-md-4'f>r>t<'row'<'col-md-4'i>><'row'p>",
            buttons: {
                dom: {
                    button: {
                        className: 'btn'
                    },
                },
                buttons: [{
                        extend: 'excel',
                        footer: true,
                        title: 'Listado pruebas comportamiento',
                        filename: 'Listado_pruebas_comportamiento_excel',
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true,
                        title: 'Listado pruebas comportamiento',
                        filename: 'Listado_pruebas_comportamiento_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Listado pruebas comportamiento',
                        filename: 'Listado_pruebas_comportamiento_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ]
            },
            ajax: {
                url: 'funciones/pruebas_comportamiento_listado.php',
                dataSrc: ''
            },
            columns: [{
                    data: 'reporte_id'
                },
                {
                    data: 'titulo'
                },
                {
                    data: 'fecha'
                },
                {
                    data: 'total_muestras'
                },
                {
                    data: 'observaciones',
                    render: function(data) {
                        return data ? data : '';
                    }
                },
                {
                    data: 'marcas_pendientes',
                    render: function(data, type, row) {
                        let pendientes = parseInt(data || 0);

                        if (pendientes > 0) {
                            return '<a href="#"><span class="btn-marcas badge bg-warning text-dark" data-id="' + row.reporte_id + '" title="Asignar marcas">' + pendientes + ' pendiente(s)</span></a>';
                        }

                        return '<a href="#"><span class="btn-marcas badge bg-success" data-id="' + row.reporte_id + '" title="Ver marcas">Asignadas</span></a>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<a href="#"><i class="btn-detalle fa-solid fa-receipt" data-id="' + row.reporte_id + '" title="Ver detalle"></i></a>';
                    }
                }
            ]
        });

        $('#btnNuevaPruebaComportamiento').on('click', function() {
            abrir_modal_prueba_comportamiento();
        });

        $('#dataTablePruebasComportamiento').on('click', '.btn-detalle', function() {
            let reporte_id = $(this).data('id');
            window.open('funciones/pruebas_comportamiento_detalle.php?reporte_id=' + reporte_id, '_blank');
        });

        $('#dataTablePruebasComportamiento').on('click', '.btn-marcas', function() {
            let reporte_id = $(this).data('id');
            abrir_modal_marcas_prueba(reporte_id);
        });
    });

    function abrir_modal_marcas_prueba(reporte_id) {
        $.ajax({
            type: 'POST',
            data: {
                reporte_id: reporte_id
            },
            url: 'funciones/pruebas_comportamiento_modal_marcas.php',
            success: function(result) {
                $('#modal_pruebas_comportamiento_marcas').html(result);
                $('#modal_pruebas_comportamiento_marcas').modal('show');
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Error al abrir asignación de marcas');
            }
        });
    }

    function abrir_modal_prueba_comportamiento() {
        $.ajax({
            type: 'POST',
            url: 'funciones/pruebas_comportamiento_modal_alta.php',
            success: function(result) {
                $('#modal_pruebas_comportamiento').html(result);
                $('#modal_pruebas_comportamiento').modal('show');
            }
        });
    }
</script>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Pruebas de comportamiento</li>
                </ol>
            </nav>
        </div>

        <div class="col-md-5 text-end">
            <?php /* Ajusta el permiso cuando definas el ID del módulo */ ?>
            <?php if (fnc_permiso($_SESSION['privilegio'], 51, 'upe_agregar')) { ?>
                <button class="btn btn-primary" type="button" id="btnNuevaPruebaComportamiento">
                    <i class="fa fa-plus"></i> Nueva prueba
                </button>
            <?php } ?>
        </div>
    </div>
</div>

<div class="container-fluid" style="border: 1px solid #cccccc; border-radius: 10px; margin-bottom: 50px;">
    <div class="table-responsive mt-3">
        <table class="table table-hover" cellpadding="0" cellspacing="0" border="0" id="dataTablePruebasComportamiento" style="width: 100%;">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Muestras</th>
                    <th>Observaciones</th>
                    <th>Marcas</th>
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

<div class="modal fade" id="modal_pruebas_comportamiento" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>
<div class="modal fade" id="modal_pruebas_comportamiento_marcas" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
</div>