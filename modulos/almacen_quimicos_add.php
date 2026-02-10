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
                            title: 'Equipos',
                            filename: 'Listado_equipos_excel',

                            //Aquí es donde generas el botón personalizado
                            text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            //Botón para PDF
                            extend: 'pdf',
                            footer: true,
                            title: 'Equipos ',
                            filename: 'Listado_equipos_pdf',
                            text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },
                        //Botón para print
                        {
                            extend: 'print',
                            footer: true,
                            title: 'equipos ',
                            filename: 'Listado_equipos_print',
                            text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        }
                    ]
                },
                ajax: {
                    url: "almacen_quimicos_listado.php",
                    dataSrc: "",
                },
                columns: [{
                        data: "qa_id"
                    },
                    {
                        data: function(row, type, set, meta) {
                            return row.usu_nombre + ' (' + row.usu_usuario + ')';
                        }
                    },
                    {
                        data: "qa_fe_entrega"
                    },
                    {
                        data: "quimico_descripcion"
                    },
                    {
                        data: "qa_lote"
                    },
                    {
                        data: "qm_cant_entrega"
                    },
                    {
                        data: "um_descripcion"
                    },
                ],
                "columnDefs": [{
                    "targets": 5, // Índice de la columna "qm_cant_entrega" (puede variar según tu configuración)
                    "className": "left-align" // Asigna una clase CSS a la columna
                }],


               /*  "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();
                    var sum = api.column(5, {
                        page: 'current'
                    }).data().reduce(function(a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);

                    // Formatear la suma como número con separadores de miles y decimales
                    var formattedSum = sum.toLocaleString();

                    // Mostrar la suma formateada en el footer y alinear a la izquierda
                    var footerCell = $(api.column(5).footer());
                    footerCell.html("Total: " + sum.toFixed(2));
                    footerCell.css('text-align', 'right'); // Establecer alineación a la izquierda
                }, */



            });
        });
    });

    function refresh() {
        location.reload();
    }
</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container">
    <div class="row">
        <div class="col-sm-4 col-md-7 ">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="submenu_funciones.php" style="font-size: 14px;color: #000">Funciones</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Almacen de químicos</li>
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

        <?php if (fnc_permiso($_SESSION['privilegio'], 27, 'upe_agregar') == 1) { ?>
            <div class="col-sm-2 col-md-2 col-lg-2" style="text-align:right">
                <a class="iconos" href="#" data-toggle="modal" data-target="#modal_quimicos_control" data-whatever="@getbootstrap"> <i class="fa-solid fa-square-plus fa-2xl"></i>&nbsp;Entrega de químicos</a>
            </div>
        <?php } ?>
    </div>




    <div class="container" style="margin-top:2rem;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
        <table class="table  table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable" style="width: 100%;">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Entregado a:</th>
                    <th>Fecha entrega</th>
                    <th>Químico</th>
                    <th>Lote</th>
                    <th style="text-align: right;">Cantidad</th>
                    <th>Unidad</th>
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
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- alta de equipos -->
    <div class="modal" tabindex="-1" role="dialog" id="modal_quimicos_control">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Control de químicos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="form_quimicos_almacen">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Entregue a operador :</label>
                                <select name="cbx_operador_entrega" type="email" class="form-control" id="cbx_operador_entrega" required>
                                    <option value="">Seleccionar</option>
                                    <?php
                                    $query =  mysqli_query($cnx, "SELECT * FROM usuarios WHERE usu_est = 'A' and up_id = 3 ORDER BY usu_nombre");
                                    while ($row = mysqli_fetch_array($query)) { ?>
                                        <option value="<?php echo mb_convert_encoding($row['usu_id'], "UTF-8");  ?>"><?php echo mb_convert_encoding($row['usu_nombre'] . ' (' . $row['usu_usuario'] . ')', "UTF-8");  ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Fecha y hora entrega:</label>
                                <input name="txt_fecha" type="datetime-local" class="form-control" id="txt_fecha" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Químico :</label>
                                <select name="cbx_quimico" type="email" class="form-control" id="cbx_quimico" required>
                                    <option value="">Seleccionar</option>
                                    <?php
                                    $query =  mysqli_query($cnx, "SELECT * FROM quimicos  WHERE quimico_est = 'A' ORDER BY quimico_descripcion");
                                    while ($row = mysqli_fetch_array($query)) { ?>
                                        <option value="<?php echo mb_convert_encoding($row['quimico_id'], "UTF-8");  ?>"><?php echo mb_convert_encoding($row['quimico_descripcion'], "UTF-8");  ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Lote</label>
                                <input name="txt_lote" type="text" class="form-control" id="txt_lote" maxlength="15" required placeholder="Lote">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Cantidad entregada</label>
                                <input onkeypress="return isNumberKey(event, this);" name="txt_cantidad" type="text" class="form-control" id="txt_cantidad" maxlength="15" required placeholder="Cantidad entregada">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Unidad de medida :</label>
                                <select name="cbx_unidad" type="email" class="form-control" id="cbx_unidad" required>
                                    <option value="">Seleccionar</option>
                                    <?php
                                    $query =  mysqli_query($cnx, "SELECT * FROM unidades_medida ORDER BY um_descripcion");
                                    while ($row = mysqli_fetch_array($query)) { ?>
                                        <option value="<?php echo mb_convert_encoding($row['um_id'], "UTF-8");  ?>"><?php echo mb_convert_encoding($row['um_descripcion'] . ' - ' . $row['um_abreviacion'], "UTF-8");  ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <!--mensajes-->
                            <div class="col-sm-6 col-lg-7">
                                <div class="alert alert-info" id="alerta-equipos" style="height: 40px;display:none;position:fixed">
                                    <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                                    <strong>Titulo</strong> &nbsp;&nbsp;
                                    <span> Mensaje </span>
                                </div>
                            </div>

                            <!-- 
                            <div class="col-sm-6 col-lg-7">
                                <div class="alert alert-danger" id="alerta-equipo_nombre_valida" style=" height: 40px;display:none;text-align:left">
                                    <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                                    <strong>Titulo</strong> &nbsp;&nbsp;
                                    <span> Mensaje </span>
                                </div>
                            </div> -->
                            <div class="col-sm-3 col-lg-2">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><i class="fa-solid fa-xmark"></i> Cerrar</button>
                            </div>
                            <div class="col-sm-3 col-lg-2">
                                <button class="btn btn-primary" type="submit"><i class="fa-regular fa-floppy-disk"></i> Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal modificar -->
    <div class="modal" id="modal_editar_equipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    </div>
</div>
<style>
    .left-align {
        text-align: right;
    }
</style>
<?php include "../generales/pie_pagina.php"; ?>