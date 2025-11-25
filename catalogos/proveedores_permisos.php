<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../generales/menu.php');
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx =  Conectarse();

$cad_perfil = mysqli_query($cnx, "SELECT * FROM usuarios_perfiles ORDER BY up_nombre") or die(mysqli_error($cnx) . "Error: en consultar");
$reg_perfil = mysqli_fetch_assoc($cad_perfil);
?>

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
                            title: 'Permisos proveedor',
                            filename: 'Listado_visualizacion_proveedores_excel',

                            //Aquí es donde generas el botón personalizado
                            text: '<button title="Exportar excel" class="btn btn-outline-success"><i class="fas fa-file-excel"></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            //Botón para PDF
                            extend: 'pdf',
                            footer: true,
                            title: 'Permisos proveedor ',
                            filename: 'Listado_visualizacion_proveedores_pdf',
                            text: '<button title="Exportar pdf" class="btn btn-outline-danger"><i class="far fa-file-pdf"></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        //Botón para print
                        {
                            extend: 'print',
                            footer: true,
                            title: 'Permisos proveedor ',
                            filename: 'Listado_visualizacion_proveedores_print',
                            text: '<button title="Imprimir" class="btn btn-outline-info"><i class="fa-solid fa-print"></i></i></button>',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        }
                    ]
                },
            });
        });
    });

    /*Para cambiar el estatus a B*/
    function fnc_autoriza(id, autorizado) {
        var respuesta = confirm("¿Permitir visualización del nombre proveedor?");
        if (respuesta) {
            $.ajax({
                url: 'proveedor_permisos_actualizar.php',
                data: {
                    id: id,
                    autorizado: autorizado
                },
                type: 'post',
                success: function(result) {
                    data = JSON.parse(result);
                    //alertas("#alerta-baja", 'Listo!', data["mensaje"], 1, true, 5000);
                    //setTimeout(location.reload(), 1000);//Revisa esta Ceci
                    /* setTimeout("location.reload()", 1000) */
                    location.reload()
                }
            });
            return false;
        }
    }

    function fnc_mezclar() {
        $.ajax({
            url: 'proveedores_mezclar.php',
            data: {
                id: "",
            },
            type: 'post',
            success: function(result) {
                data = JSON.parse(result);
                alertas("#alerta-baja", 'Listo!', data["mensaje"], 1, true, 5000);
                //setTimeout(location.reload(), 1000);//Revisa esta Ceci
                setTimeout("location.reload()", 2000)
                /*  location.reload() */
            }
        });
        return false;
    }

    function refresh() {
        location.reload();
    }
</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4 col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="submenu_catalogos.php" style="font-size: 14px;color: #000">Catálogos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Visibilidad proveedores</li>
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

        <div class="col-sm-4 col-md-1">
            <button class="btn btn-primary" onclick="javascript:fnc_mezclar();"><img src="../iconos/guardar.png" alt=""> Mezclar</button>
        </div>
    </div>

    <div class="tab-content container-fluid">

        <div class="tab-pane fade in active" id="permisos_proveedor" style="margin-top:2rem;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
            <table class="table  table-hover" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Perfil</th>
                        <th>Autorizado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ren = 1;
                    do {
                        if (isset($reg_perfil['up_id'])) {
                            if ($reg_perfil['up_ban'] == '1') {
                                $autorizado = "Si";
                                $on_off = "0"; /*  apaga */
                            } else {
                                $autorizado = "No";
                                $on_off = "1";  /* enciende */
                            }

                            if ($reg_perfil['up_id'] != 1) {
                    ?>
                                <tr>
                                    <td><?php echo $reg_perfil['up_id'] ?></td>
                                    <td><?php echo $reg_perfil['up_nombre'] ?></td>
                                    <td><?php echo $autorizado ?></td>
                                    <td style="padding-left: 0px" align="center">
                                        <?php if (fnc_permiso($_SESSION['privilegio'], 34, 'upe_editar') == 1) { ?><a href="javascript:fnc_autoriza(<?= $reg_perfil['up_id'] ?>, <?= $on_off ?>);" alt="Autorizar">
                                                <?php if ($reg_perfil['up_ban'] == '1') { ?>
                                                    <i class="fa-solid fa-lock-open" style="color: #b4d0a4;"></i>
                                                <?php } else { ?>
                                                    <i class="fa-solid fa-lock"></i>
                                                <?php } ?>
                                            <?php } ?>
                                    </td>
                                </tr>
                    <?php
                                $ren += 1;
                            
                        }
                    } } while ($reg_perfil = mysqli_fetch_assoc($cad_perfil)); ?>

                </tbody>
            </table>
        </div>

    </div>
 <?php include "../generales/pie_pagina.php"; ?>