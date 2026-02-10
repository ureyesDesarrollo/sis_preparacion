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
    /*Manipular el formulario*/
    $(document).ready(function() {
        $("#form_quimicos_almacen").submit(function() {
            var formData = $(this).serialize();
            $.ajax({
                url: "almacen_quimicos_agregar.php",
                type: 'POST',
                data: formData,
                success: function(result) {
                    data = JSON.parse(result);
                    alertas("#alerta-equipos", 'Listo!', data["mensaje"], 1, true, 5000);
                    document.getElementById('alerta-equipos').style.display = 'block';
                    $('#form_quimicos_almacen').each(function() {
                        this.reset();
                    });
                }
            });
            return false;
        });

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
                "order": [
                    [0, 'desc'] // Ordenar por la primera columna en orden ascendente
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
                            title: 'Listado bitacoras',
                            filename: 'Listado_bitacoras_excel',

                            //Aquí es donde generas el botón personalizado
                            text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            }
                        },
                        {
                            //Botón para PDF
                            extend: 'pdf',
                            footer: true,
                            title: 'Listado bitacoras',
                            filename: 'Listado_bitacoras_pdf',
                            text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            }
                        },
                        //Botón para print
                        {
                            extend: 'print',
                            footer: true,
                            title: 'Listado bitacoras',
                            filename: 'Listado_bitacoras_print',
                            text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            }
                        }
                    ]
                },
                ajax: {
                    url: "bistacora_listado_new.php",
                    dataSrc: "",
                },
                columns: [{
                        data: "pro_id",
                        width: "30px"
                    },
                    {
                        data: "pt_descripcion"
                    },
                    {
                        data: "ope",
                    },
                    {
                        data: "sup"
                    },
                    {
                        data: "pro_total_kg"
                    },
                    {
                        data: "pro_fe_carga"
                    },
                    {
                        data: "pro_hr_inicio"
                    },
                    {
                        data: "pro_hr_fin"
                    },
                    {
                        data: function(row, type, set, meta) {
                            if (row.pro_estatus === '1') {
                                return 'En proceso';
                            }
                            if (row.pro_estatus === '2') {
                                return 'Terminado';
                            }
                        }
                    },
                    {
                        render: function(data, type, row, meta) {
                            var horas_calculadas;
                            var horas_calculadas;

                            // Intenta convertir row.hrs_totales_calculadas a un número
                            var valorNumerico = parseFloat(row.hrs_totales_calculadas);

                            // Verifica si la conversión fue exitosa y el valor es un número válido
                            if (!isNaN(valorNumerico)) {
                                // Aplica toFixed() al valor numérico
                                horas_calculadas = valorNumerico.toFixed(2);
                            } else {
                                // Maneja el caso en que la conversión no fue exitosa, por ejemplo, asignar un valor predeterminado o mostrar un mensaje de error.
                                horas_calculadas = 0;
                            }



                            // Si es una visualización, agrega el ícono de información
                            return '<a style="margin-left:1rem" href="#" onClick="javascript:modal_tiempos(' + row.pro_id + ')"><i class="fa-regular fa-clock"></i> ' + row.hrs_totales_capturadas + ' - ' + horas_calculadas + ' hrs</a>';
                        },

                    },
                    {
                        render: function(data, type, row, meta) {
                            // Si es una visualización, agrega el ícono de información
                            return '<a style="margin-left:1.5rem" href="../bitacoras/formatos/bitacora_consulta.php?idx_pro=' + row.pro_id + '" target="_blank"><span class="glyphicon glyphicon-print"></span> </a>';
                        },

                    },
                    /*{
                        render: function(data, type, row, meta) {
                            // Si es una visualización, agrega el ícono de información
                            return '<a href="../bitacoras/formatos/bitacora_exportar.php?idx_pro="' + row.pro_id + '""><span class="glyphicon glyphicon-circle-arrow-down"></span> </a>';
                        }


                    },*/
                    {
                        render: function(data, type, row, meta) {
                            // Si es una visualización, agrega el ícono de información
                            return '<a style="margin-left:0.5rem" href="#" onClick="javascript:AbreModalInfo(' + row.pro_id + ',' + row.pt_id + ')"><span class="glyphicon glyphicon-alert"></span> </a>';
                        },

                    },
                    {
                        render: function(data, type, row, meta) {
                            <?php if ($_SESSION['privilegio'] == 5) { ?>
                                // Si es una visualización, agrega el ícono de editar
                                if (row.pro_estatus === '1') {
                                    return '<a style="margin-left:1rem" href="#" onClick="javascript:AbreModalEditar(' + row.pro_id + ',' + row.pt_id + ')"><span class="glyphicon glyphicon-pencil"></span></a>';
                                } else {
                                    return "";
                                }
                            <?php } else { ?>
                                return "";
                            <?php } ?>
                        },

                    },
                    /*   {
                           render: function(data, type, row, meta) {
                               // Si es una visualización, agrega el ícono de información

                               return '<a href="#" onClick="javascript:fnc_quitar(' + data + ')"><i class="fas fa-info-circle"></i> </a>';
                           },


                       }, */

                ],
                "columnDefs": [{
                    "targets": 5, // Índice de la columna "qm_cant_entrega" (puede variar según tu configuración)
                    "className": "left-align" // Asigna una clase CSS a la columna
                }],
            });
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
                    <li class="breadcrumb-item active" aria-current="page">Listado bitacoras</li>
                </ol>
            </nav>
        </div>

        <!-- mensaje de baja -->
        <div class="col-sm-4 col-md-3">
            <div class="alert alert-info hide" id="alerta-baja" style="height: 40px;width: 300px">
                <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                <strong>Titulo</strong> &nbsp;&nbsp;
                <span> Mensaje </span>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top:2rem;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
        <table class="table  table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable" style="width: 100%;">
            <thead>
                <tr>
                    <th>Proceso</th>
                    <th>Tipo preparación</th>
                    <th>Operador</th>
                    <th>Supervisor</th>
                    <th>Kilos</th>
                    <th>Fecha carga</th>
                    <th>Hora inicio</th>
                    <th>Hora fin</th>
                    <th>Estatus</th>
                    <th>Hrs totales proceso usuario/sistema</th>
                    <th>Imprimir</th>
                    <th>Info</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>

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
                </tr>
            </tfoot>
        </table>
    </div>


    <div class="modal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    </div>

    <div class="modal" id="modal_tiempos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    </div>

    <div class="modal" id="modalInfoParam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    </div>

    <div class="modal" id="modalInfoParampal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    </div>
</div>
<script>
    /*Tiempos*/
    function modal_tiempos(id, tipo) {
        $.ajax({
            type: 'post',
            url: 'modal_tiempos.php',
            data: {
                "pro_id": id,
                "hdd_tipo": tipo
            }, //Pass $id
            success: function(result) {
                $("#modal_tiempos").html(result);
                $('#modal_tiempos').modal('show')
            }
        });
        return false;
    };

    /*Abrir Modal info parametros*/
    function AbreModalInfo(id, tipo) {
        $.ajax({
            type: 'post',
            url: 'desglose_parametros.php',
            data: {
                "pro_id": id,
                "hdd_tipo": tipo
            }, //Pass $id
            success: function(result) {
                $("#modalInfoParam").html(result);
                $('#modalInfoParam').modal('show')
            }
        });
        return false;
    };

    /*Abrir Modal Editar*/
    function AbreModalEditar(id, tipo) {
        $.ajax({
            type: 'post',
            url: 'bitacora_editar.php',
            data: {
                "pro_id": id,
                "hdd_tipo": tipo
            }, //Pass $id
            success: function(result) {
                $("#modalEditar").html(result);
                $('#modalEditar').modal('show')
            }
        });
        return false;
    };

    //elimina procesos, no activa
    function fnc_quitar(id) {
        var respuesta = confirm("¿Deseas eliminar este proceso?");
        if (respuesta == true) {
            $.ajax({
                url: 'bitacora_eliminar.php',
                data: 'id=' + id,
                type: 'post',
                success: function(result) {
                    data = JSON.parse(result);
                    setTimeout("location.reload()", 2000)
                }
            });
            return false;
        }
    }

    //cierra proceso, no activo
    function fnc_cerrar(id) {
        var respuesta = confirm("¿Deseas cerrar este proceso?");
        if (respuesta == true) {
            $.ajax({
                url: 'bitacora_cerrar.php',
                data: 'id=' + id,
                type: 'post',
                success: function(result) {
                    data = JSON.parse(result);
                    setTimeout("location.reload()", 1000)
                }
            });
            return false;
        }
    }
</script>
<style>
    .left-align {
        text-align: right;
    }
</style>
<?php include "../generales/pie_pagina.php"; ?>