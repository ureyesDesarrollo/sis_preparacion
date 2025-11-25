<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../generales/menu.php');
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx =  Conectarse();

$cad_equipos = mysqli_query($cnx, "SELECT *
                             FROM equipos_preparacion ORDER BY ep_descripcion") or die(mysqli_error($cnx) . "Error: en consultar el material");
$reg_equipos = mysqli_fetch_assoc($cad_equipos);

$cad_tipo_eq_g = mysqli_query($cnx, "SELECT * from equipos_tipos order by et_descripcion");
$reg_tipo_eq_g =  mysqli_fetch_assoc($cad_tipo_eq_g);

?>

<!--formato de tablas -->
<!-- <link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../assets/datatable/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script> -->
<link rel="stylesheet" href="../css/estilos_catalogos.css">


<!-- <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
 -->
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
        $("#form_equipos").submit(function() {
            var formData = $(this).serialize();
            $.ajax({
                url: "equipos_agregar.php",
                type: 'POST',
                data: formData,
                success: function(result) {
                    data = JSON.parse(result);
                    alertas("#alerta-equipos", 'Listo!', data["mensaje"], 1, true, 5000);
                    document.getElementById('alerta-equipos').style.display = 'block';
                    $('#form_equipos').each(function() {
                        this.reset();
                    });
                }
            });
            return false;
        });

        $(document).ready(function() {
            $("#form_tipo_equipos").submit(function() {
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: "equipos_tipo_agregar.php",
                    type: 'POST',
                    data: formData,
                    contentType: "application/json",
                    success: function(result) {
                        data = JSON.parse(result);
                        alertas("#alerta-tipo_equipos", 'Listo!', data["mensaje"], 1, true, 5000);
                        document.getElementById('alerta-tipo_equipos').style.display = 'block';
                        document.getElementById('imagen-seleccionada').style.display = 'none';

                        $('#form_tipo_equipos').each(function() {
                            this.reset();
                        });

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
                return false;
            });
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
            });
        });
    });

    /*Para cambiar el estatus a B*/
    function fnc_baja(id) {
        var respuesta = confirm("¿Deseas dar de baja este registro?");
        if (respuesta) {
            $.ajax({
                url: 'equipos_baja.php',
                data: 'id=' + id,
                type: 'post',
                success: function(result) {
                    data = JSON.parse(result);
                    alertas("#alerta-baja", 'Listo!', data["mensaje"], 1, true, 5000);
                    //setTimeout(location.reload(), 1000);//Revisa esta Ceci
                    setTimeout("location.reload()", 2000)
                }
            });
            return false;
        }
    }

    /*Abrir Modal Editar*/
    function fnc_editar(id) {
        $.ajax({
            type: 'post',
            url: 'equipos_edit.php',
            data: {
                "hdd_id": id
            }, //Pass $id
            success: function(result) {
                $("#modal_editar_equipo").html(result);
                $('#modal_editar_equipo').modal('show')
            }
        });
        return false;
    };

    //editar tipos de equipos
    function fnc_editar_tipos(id) {
        $.ajax({
            type: 'post',
            url: 'equipos_tipo_edit.php',
            data: {
                "hdd_id": id
            }, //Pass $id
            success: function(result) {
                $("#modal_edicion_equipos").html(result);
                $('#modal_edicion_equipos').modal('show')
            }
        });
        return false;
    };

    function refresh() {
        location.reload();
    }
</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container">
    <div class="row">
        <div class="col-sm-4 col-md-5 ">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="submenu_catalogos.php" style="font-size: 14px;color: #000">Catálogos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Equipos</li>
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

        <!--    <div class="col-sm-1 col-md-1">
            <a class="iconos" href="formatos/listado_mat_tipo.php" target="_blank"><i class="fa-solid fa-print"></i>
                Imprimir</a>
        </div>
        <div class="col-sm-1 col-md-1">
            <a class="iconos" href="exportar/mat_tipo.php" target="_blank"><i class="fa-solid fa-file-excel"></i>
                Exp.excel</a>
        </div> -->

        <?php if (fnc_permiso($_SESSION['privilegio'], 25, 'upe_agregar') == 1) { ?>
            <div class="col-sm-2 col-md-2 col-lg-2" style="text-align:right">
                <a class="iconos" href="#" data-toggle="modal" data-target="#modal_alta_equipos" data-whatever="@getbootstrap"> <i class="fa-solid fa-square-plus fa-2xl"></i>&nbsp;Equipo</a>
            </div>
        <?php }
        if (fnc_permiso($_SESSION['privilegio'], 25, 'upe_agregar') == 1) { ?>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <a class="iconos" href="#" data-toggle="modal" data-target="#modal_alta_tipo_equipos" data-whatever="@getbootstrap">
                    <i class="fa-solid fa-square-plus fa-2xl"></i>&nbsp;Tipo equipo</a>
            </div>
        <?php } ?>
    </div>


    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#equipos">Equipos</a></li>
        <li><a data-toggle="tab" href="#tipos_equipos">Tipos equipos </a></li>
    </ul>

    <div class="tab-content container">


        <div class="tab-pane fade in active" id="equipos" style="margin-top:2rem;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
            <table class="table  table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Descripción equipo</th>
                        <th>Tipo</th>
                        <th>Kg minimo</th>
                        <th>Kg máximo</th>
                        <th>Estatus</th>
                        <th>Habilitado</th>
                        <th>Editar</th>
                        <th>Deshabilitar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ren = 1;
                    do {
                        if (isset($reg_equipos['ep_tipo'])) {
                            $cad_tipo_eq = mysqli_query($cnx, "SELECT et_descripcion from equipos_tipos where et_tipo = '$reg_equipos[ep_tipo]'");
                            $reg_tipo_eq =  mysqli_fetch_assoc($cad_tipo_eq);

                            $cad_estatus = mysqli_query($cnx, "SELECT le_estatus from  listado_estatus where le_id = '$reg_equipos[le_id]'");
                            $reg_estatus =  mysqli_fetch_assoc($cad_estatus);
                    ?>
                            <tr>
                                <td><?php echo $reg_equipos['ep_id'] ?></td>
                                <td><?php echo $reg_equipos['ep_descripcion'] ?></td>
                                <td><?php echo $reg_tipo_eq['et_descripcion'] ?></td>
                                <td style="text-align: right;"><?php echo number_format($reg_equipos['ep_carga_min']) ?></td>
                                <td style="text-align: right;"><?php echo  number_format($reg_equipos['ep_carga_max']) ?></td>
                                <td><?php echo $reg_estatus['le_estatus'] ?></td>
                                <td><?php if ($reg_equipos['estatus'] == 'A') {
                                        echo "Si";
                                    } else {
                                        echo "No";
                                    } ?></td>
                                <td style="padding-left: 0px" align="center"><?php if (fnc_permiso($_SESSION['privilegio'], 25, 'upe_editar') == 1) { ?><a href="#" onClick="javascript:fnc_editar(<?= $reg_equipos['ep_id']; ?>)" alt="Editar"><i class="fa-regular fa-pen-to-square"></i><?php } ?></td>
                                <td style="padding-left: 0px" align="center"><?php if (fnc_permiso($_SESSION['privilegio'], 25, 'upe_borrar') == 1) { ?><a href="javascript:fnc_baja(<?= $reg_equipos['ep_id'] ?>);" alt="Deshabilitar"><i class="fa-regular fa-trash-can"></i><?php } ?></td>
                            </tr>
                    <?php
                            $ren += 1;
                        }
                    } while ($reg_equipos = mysqli_fetch_assoc($cad_equipos)); ?>

                </tbody>

                <tfoot>
                    <!--   <?php for ($i = $ren; $i <= 12; $i++) { ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr> -->
                </tfoot>
            </table>
        </div>

        <div class="tab-pane fade" id="tipos_equipos" style="margin-top:2rem;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
            <table class="table  table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Sigla</th>
                        <th>Orden</th>
                        <th>Estatus</th>
                        <th>Almacén</th>
                        <th>Imagen</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ren = 1;
                    do {
                        if (isset($reg_tipo_eq_g['et_id'])) {
                    ?>
                            <tr>
                                <td><?php echo $reg_tipo_eq_g['et_id'] ?></td>
                                <td><?php echo $reg_tipo_eq_g['et_descripcion'] ?></td>
                                <td><?php echo $reg_tipo_eq_g['et_tipo'] ?></td>
                                <td><?php echo $reg_tipo_eq_g['et_orden'] ?></td>
                                <td><?php if ($reg_tipo_eq_g['et_estatus'] == 'A') {
                                        echo "Activo";
                                    } else {
                                        echo "Baja";
                                    } ?>
                                </td>
                                <td><?php if ($reg_tipo_eq_g['ban_almacena'] == 'S') {
                                        echo "Sí";
                                    } else {
                                        echo "No";
                                    } ?>
                                </td>
                                <td><img src="<?php echo $reg_tipo_eq_g['et_imagen'] ?>" alt="" style="width:60px"></td>

                                <td style="padding-left: 0px" align="center"><?php if (fnc_permiso($_SESSION['privilegio'], 25, 'upe_editar') == 1) { ?><a href="#" onClick="javascript:fnc_editar_tipos(<?= $reg_tipo_eq_g['et_id']; ?>)" alt="Editar"><i class="fa-regular fa-pen-to-square"></i><?php } ?></td>
                            </tr>
                    <?php
                            $ren += 1;
                        }
                    } while ($reg_tipo_eq_g =  mysqli_fetch_assoc($cad_tipo_eq_g)); ?>

                </tbody>

                <tfoot>

                </tfoot>
            </table>
        </div>

    </div>
    <!-- alta de equipos -->
    <div class="modal" tabindex="-1" role="dialog" id="modal_alta_equipos">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alta de equipos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="form_equipos">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descripción equipo:</label>
                                <input onchange="valida_nombre_equipo()" name="txt_descripcion" type="text" class="form-control" id="txt_descripcion" maxlength="60" required placeholder=" Descripción equipo">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Tipo equipo:</label>
                                <select name="cbx_tipo" type="email" class="form-control" id="cbx_tipo" required>
                                    <option value="">Seleccionar</option>
                                    <?php
                                    $query =  mysqli_query($cnx, "SELECT * FROM equipos_tipos WHERE et_estatus = 'A' ORDER BY et_descripcion ");
                                    while ($row = mysqli_fetch_array($query)) { ?>
                                        <option value="<?php echo mb_convert_encoding($row['et_tipo'], "UTF-8");  ?>"><?php echo mb_convert_encoding($row['et_descripcion'], "UTF-8");  ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Capacidad minima(Kg):</label>
                                <input onkeypress="return isNumberKey(event, this);" name="txt_capacidad_min" type="text" class="form-control" id="txt_capacidad_min" maxlength="8" required placeholder=" Capacidad minima(Kg)">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Capacidad maxima(Kg):</label>
                                <input onkeypress="return isNumberKey(event, this);" name="txt_capacidad_max" type="text" class="form-control" id="txt_capacidad_max" maxlength="8" required placeholder="Capacidad maxima(Kg)">
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


                            <div class="col-sm-6 col-lg-7">
                                <div class="alert alert-danger" id="alerta-equipo_nombre_valida" style=" height: 40px;display:none;text-align:left">
                                    <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                                    <strong>Titulo</strong> &nbsp;&nbsp;
                                    <span> Mensaje </span>
                                </div>
                            </div>
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
    <!-- alta de equipos -->
    <div class="modal" tabindex="-1" role="dialog" id="modal_edicion_equipos">
    </div>

    <!-- modal modificar -->
    <div class="modal" id="modal_editar_equipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    </div>


    <!-- alta tipo de equipos -->
    <div class="modal" tabindex="-1" role="dialog" id="modal_alta_tipo_equipos">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alta tipo de equipos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="form_tipo_equipos">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descripción tipo de equipo:</label>
                                <input onchange="valida_tipo_equipo()" name="txt_descripcion_tipo" type="text" class="form-control" id="txt_descripcion_tipo" maxlength="25" required placeholder=" Descripción tipo de equipo">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Sigla:</label>
                                <input onchange="valida_sigla()" name="txt_sigla" type="text" class="form-control" id="txt_sigla" maxlength="1" required placeholder=" Sigla">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Orden en tablero:</label>
                                <input onkeypress="return isNumberKey(event, this);" onchange="valida_orden()" name="txt_orden" type="text" class="form-control" id="txt_orden" maxlength="2" required placeholder="Orden en tablero">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Almacena</label>
                                <select class="form-control" name="slc_almacen" id="slc_almacen" required>
                                    <option value="">Selecciona</option>
                                    <option value="S">Sí</option>
                                    <option value="N">No</option>
                                </select>
                            </div>
                            <div class="form-group col-md-8">
                                <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Imagen de equipo</label>
                                <input name="txt_file" type="file" class="form-control" id="txt_file" maxlength="2" required placeholder="Orden en tablero">
                            </div>
                            <div class="form-group col-md-4">
                                <img id="imagen-seleccionada" style="width: 40%;margin-top:2rem;display:none" src="" alt="Imagen seleccionada">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-1 col-lg-1"></div>
                            <!--mensajes-->
                            <div class="col-sm-6 col-lg-7">
                                <div class="alert alert-info" id="alerta-tipo_equipos" style="height: 40px;display:none;position:fixed">
                                    <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                                    <strong>Titulo</strong> &nbsp;&nbsp;
                                    <span> Mensaje </span>
                                </div>
                            </div>

                            <div class="col-sm-6 col-lg-7">
                                <div class="alert alert-danger" id="alerta-tipo_equipos_valida" style=" height: 40px;display:none;">
                                    <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                                    <strong>Titulo</strong> &nbsp;&nbsp;
                                    <span> Mensaje </span>
                                </div>
                            </div>

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



    <script>
        // Función para mostrar la imagen seleccionada en el elemento <img>
        function mostrarImagenSeleccionada(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById("imagen-seleccionada").src = e.target.result;
                    document.getElementById('imagen-seleccionada').style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Agrega un evento onChange al input para ejecutar la función cuando se selecciona una imagen
        document.getElementById("txt_file").addEventListener("change", function() {
            mostrarImagenSeleccionada(this);
        });

        function valida_orden() {
            var orden = document.getElementById("txt_orden").value;
            $.ajax({
                url: "get_orden_quipo_tipo.php",
                type: 'POST',
                data: 'orden=' + orden,
                success: function(result) {
                    //data = JSON.parse(result);
                    // Mostrar el resultado
                    if (result != '') {
                        data = JSON.parse(result);
                        document.getElementById('txt_orden').value = '';
                        alertas("#alerta-tipo_equipos_valida", '', data["mensaje"], 4, true, 5000);

                        document.getElementById('alerta-tipo_equipos_valida').style.display = 'block';
                        //document.getElementById('imagen-seleccionada').style.display = 'none';
                    }
                }
            });
            return false;
        }

        function valida_sigla() {
            var orden = document.getElementById("txt_sigla").value;
            $.ajax({
                url: "get_sigla_equipo_tipo.php",
                type: 'POST',
                data: 'orden=' + orden,
                success: function(result) {
                    //data = JSON.parse(result);
                    // Mostrar el resultado
                    if (result != '') {
                        data = JSON.parse(result);
                        alertas("#alerta-tipo_equipos_valida", '', data["mensaje"], 4, true, 5000);
                        document.getElementById('txt_sigla').value = '';
                        document.getElementById('alerta-tipo_equipos_valida').style.display = 'block';
                    }
                }
            });
            return false;
        }

        function valida_tipo_equipo() {
            var tipo = document.getElementById("txt_descripcion_tipo").value;
            $.ajax({
                url: "get_equipo_tipo.php",
                type: 'POST',
                data: 'tipo=' + tipo,
                success: function(result) {
                    //data = JSON.parse(result);
                    // Mostrar el resultado
                    if (result != '') {
                        data = JSON.parse(result);
                        alertas("#alerta-tipo_equipos_valida", '', data["mensaje"], 4, true, 5000);
                        document.getElementById('txt_descripcion_tipo').value = '';
                        document.getElementById('alerta-tipo_equipos_valida').style.display = 'block';

                    }
                }
            });
            return false;
        }

        function valida_nombre_equipo() {
            var dato = document.getElementById("txt_descripcion").value;
            $.ajax({
                url: "get_equipo_nombre.php",
                type: 'POST',
                data: 'dato=' + dato,
                success: function(result) {
                    if (result != '') {
                        data = JSON.parse(result);
                        alertas("#alerta-equipo_nombre_valida", '', data["mensaje"], 4, true, 5000);
                        document.getElementById('txt_descripcion').value = '';
                        document.getElementById('alerta-equipo_nombre_valida').style.display = 'block';

                    }
                }
            });
            return false;
        }
    </script>
    <?php include "../generales/pie_pagina.php"; ?>