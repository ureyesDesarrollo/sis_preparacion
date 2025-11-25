<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
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
    $(document).ready(function() {
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
                        title: 'Movimiento equipos',
                        filename: 'Listado_movimiento_estatus_excel',

                        //Aquí es donde generas el botón personalizado
                        text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        //Botón para PDF
                        extend: 'pdf',
                        footer: true,
                        title: 'Movimiento equipos ',
                        filename: 'Listado_movimiento_estatus_pdf',
                        text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    //Botón para print
                    {
                        extend: 'print',
                        footer: true,
                        title: 'Movimiento equipos ',
                        filename: 'Listado_movimiento_estatus_print',
                        text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    }
                ]
            },
            ajax: {
                url: "movimientos_estatus_listado.php",
                dataSrc: "",
            },
            columns: [{
                    data: "bce_id"
                },
                {
                    data: "usu_usuario"
                },
                {
                    data: "bce_fecha",
                    //width: "100px"
                },

                {
                    data: "estatus_actual"
                },
                {
                    data: "estatus_nuevo"
                },
                {
                    data: "ep_descripcion"
                },
                {
                    data: "bce_ot",

                },
                {
                    data: "bce_descripcion"
                },
            ],
        });
    });


    function refresh() {
        location.reload();
    }
</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4 col-md-7 ">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="submenu_funciones.php" style="font-size: 14px;color: #000">Reportes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Movimiento de estatus</li>
                </ol>
            </nav>
        </div>
    </div>




    <div class="container-fluid" style="margin-top:2rem;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
        <table class="table  table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable" style="width: 100%;">
            <thead>
                <tr>
                    <th>Ren</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Est. Actual</th>
                    <th>Est. Nuevo</th>
                    <th>Equipo</th>
                    <th>Orden trabajo</th>
                    <th>Comentarios</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<style>
    .left-align {
        text-align: right;
    }
</style>
<?php include "../generales/pie_pagina.php"; ?>