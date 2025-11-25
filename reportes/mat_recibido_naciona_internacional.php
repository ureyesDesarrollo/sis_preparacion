<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include('../generales/menu.php');
?>

<link rel="stylesheet" href="../assets/css/estilos_generales.css">

<!--DATATABLES-->
<!-- <script src=../assets/datatable/jquery-3.5.1.js></script> -->
<script src=../assets/datatable/jquery.dataTables.min.js></script>
<script src=../assets/datatable/dataTables.bootstrap5.min.js></script>

<link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<!-- Buttons -->
<link rel="stylesheet" href="../assets/datatable/buttons.dataTables.min.css">
<script src="../assets/datatable/dataTables.buttons.min.js"></script>
<script src="../assets/datatable/buttons.bootstrap4.min.js"></script>
<script src="../assets/datatable/jszip.min.js"></script>
<script src="../assets/datatable/pdfmake.min.js"></script>
<script src="../assets/datatable/vfs_fonts.js"></script>
<script src="../assets/datatable/buttons.html5.min.js"></script>
<script src="../assets/datatable/buttons.print.min.js"></script>
<script src="../assets/datatable/buttons.colVis.min.js"></script>
<script src="../assets/datatable/ellipsis.js"></script>

<script>
    //modal equipos

    function abre_modal_cuero_procesos(mat_id, pro_id) {
        var datos = {
            "mat_id": mat_id,
            "pro_id": pro_id,
        }
        $.ajax({
            type: 'post',
            url: 'modal_cuero_preparacion.php',
            data: datos,
            //data: {nombre:n},
            success: function(result) {
                $("#modal_cuero_procesos").html(result);
                $('#modal_cuero_procesos').modal('show')
            }
        });
        return false;
    }

    function refresh() {
        location.reload();
    }

    function filtro_material_procesos() {
        var tipo_reporte = $("#slc_tipo_reporte").val();
        var hora_fin = $("#hora_fin").val();
        if (tipo_reporte != '') {
            var table = $('#dataTable').DataTable({
                //  "dom": 'Bfrtip',
                "responsive": true,
                "bDestroy": true,
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ )",
                    "sInfoPostFix": "",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                },
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
                            title: 'REPORTE POR TIPO DE MATERIAL RECIBIDO NACIONAL  E IMPORTACION ',
                            filename: 'Cuero entregado a preparación y destino_',

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
                            title: 'REPORTE POR TIPO DE MATERIAL RECIBIDO NACIONAL  E IMPORTACION ',
                            filename: 'Cuero entregado a preparación y destino_',
                            text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        //Botón para print
                        {
                            extend: 'print',
                            footer: true,
                            title: 'REPORTE POR TIPO DE MATERIAL RECIBIDO NACIONAL  E IMPORTACION ',
                            filename: 'Cuero entregado a preparación y destino_',
                            text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        }
                    ]
                },

                ajax: {
                    url: "mat_lis_recibido_naciona_internacional.php",
                    dataSrc: "",
                },
                columns: [{
                        data: "lote_fecha"
                    },
                    {
                        data: "mat_nombre",
                    },
                    {
                        data: "inv_kg_totales"
                    },
                ],
                "columnDefs": [{
                    "targets": 3, // Índice de la columna "qm_cant_entrega" (puede variar según tu configuración)
                    "className": "left-align" // Asigna una clase CSS a la columna
                }],
            });
        }
    }
</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container">
    <div class="row">
        <div class="col-sm-4 col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="submenu_catalogos.php" style="font-size: 14px;color: #000">Reportes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contabilidad</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4">
            <label for="recipient-name" class="col-form-label">Reporte</label><br>
            <select style="width: 430px;display:inline" type="text" class="form-control" id="slc_tipo_reporte" onchange="filtro_material_procesos()">
                <option value="">Selecciona</option>
                <option value="1">1 - REPORTE POR TIPO DE MATERIAL RECIBIDO NACIONAL E IMPORTACION</option>
                <option value="2">2- ENTRADAS POR MES (CUERO NACIONAL) EJERCICIO 2023</option>
                <option value="3">3- ENTRADAS POR MES POR TIPO DE MATERIAL (CUERO NACIONAL) EJERCICIO 2023</option>
                <option value="4">4- ENTRADAS ACUMULADA POR TIPO DE MATERIAL POR PROVEEDORE (CUERO NACIONAL)</option>
                <option value="5">5 - ENTRADA ACUMULADA MENSUAL POR PROVEEDOR ( CUERO NACIONAL )</option>
            </select>
        </div>
    </div>

    <div id="tab2" class="container" style="margin-top:2rem;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
        <table class="table  table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable" style="width: 100%;">
            <thead>
                <tr>
                    <th>% TIPO DE MATERIAL</th>
                    <th>TIPO MATERIAL COMPRADO</th>
                    <th>KILOS</th>
                </tr>
            </thead>
            <tbody>

            </tbody>

            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="modal fade" id="modal_cuero_procesos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
</div>
<style>
    .left-align {
        text-align: right;
    }
</style>
<?php include "../generales/pie_pagina.php"; ?>