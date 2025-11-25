<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../seguridad/user_seguridad.php";
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();
$parametros = mysqli_query($cnx, "SELECT * FROM  parametros") or die(mysqli_error($cnx) . "Error: en consultar el material");
$reg_parametros = mysqli_fetch_assoc($parametros);

/* total de inventario */
$tot_inventario = mysqli_query($cnx, "SELECT SUM(inv_kg_totales) as total_inventario FROM inventario as i
INNER JOIN almacen_cajones as a on(i.ac_id = a.ac_id)
WHERE a.ac_ban = 'M' AND i.inv_tomado = 0") or die(mysqli_error($cnx) . "Error: en consultar inventario");
$reg_tot_inv = mysqli_fetch_assoc($tot_inventario);

/* total solicitado */
$tot_solicitado = mysqli_query($cnx, "select SUM(inv_kg_totales) as tot_inv_kg,i.inv_folio_interno, i.inv_kg_totales, m.mat_nombre
from inventario as i
inner join materiales as m on (i.mat_id = m.mat_id)
where i.inv_solicitado = 'S' and inv_tomado = 0 order by m.mat_nombre asc") or die(mysqli_error($cnx) . "Error: en consultar inventario");
$reg_tot_solicitado = mysqli_fetch_assoc($tot_solicitado);

$perfil_autorizado = mysqli_query($cnx, "SELECT * FROM usuarios_perfiles WHERE up_id = '" . $_SESSION['privilegio'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el inventario 1");
$reg_autorizado = mysqli_fetch_assoc($perfil_autorizado);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patio materia prima - <?php echo date("d-m-Y"); ?></title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/fontawesome/fontawesome.js"></script>

    <!-- Sweet alert -->
    <link href="../assets/sweetalert/sweetalert.css" rel="stylesheet" />
    <script src="../assets/sweetalert/sweetalert.js"></script>
    <script src="../assets/sweetalert/sweetalert2.js"></script>

    <link rel="stylesheet" href="../assets/css/style_mp.css">
    <script type="text/javascript" src="../js/alerta.js"></script>

    <!DOCTYPE html>
    <script>
        var elem = document.documentElement;

        function openFullscreen() {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                /* IE11 */
                elem.msRequestFullscreen();
            }
        }

        function closeFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                /* Safari */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                /* IE11 */
                document.msExitFullscreen();
            }
        }
    </script>
</head>

<body>
    <!--   <button onclick="openFullscreen();">Open Fullscreen</button>
    <button onclick="closeFullscreen();">Close Fullscreen</button> -->
    <nav class="navbar navbar-expand-lg navbar-red bg-red" id="encabezado">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../imagenes/logo_progel_v3.png" style="width:40%;margin-top:-10px;margin-bottom:-10px"></a>
            <a class="navbar-brand" href="#">PATIO MATERIA PRIMA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <!--  <a class="nav-link active" aria-current="page" href="#">PATIO MOLINOS</a> -->
                    </li>
                    <li class="nav-item">
                        <!-- <a class="nav-link" href="#">Features</a> -->
                    </li>
                    <li class="nav-item">
                        <!--  <a class="nav-link" href="#">Pricing</a> -->
                    </li>
                </ul>
                <span class="navbar-text">
                    <?php if (fnc_permiso($_SESSION['privilegio'], 32, 'upe_agregar') == 1) { ?>
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modal_alta_cajones" data-bs-whatever="@getbootstrap"> <i class="fa-solid fa-folder-plus"></i> Agregar</a>
                    <?php } ?>
                </span>
                <span class="navbar-text">
                    <a class="nav-link" href="patio_molinos.php"><i class="fa-solid fa-fan"></i> Patio Molinos</a>
                </span>
                <span class="navbar-text">
                    <a class="nav-link" href="../modulos/submenu_funciones.php"><i class="fa-solid fa-circle-left"></i> Regresar</a>
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid" id="full">
        <div class="row" id="indicadores">
            <div class="col-sm-12 col-md-12">
                <?php echo "<b>INVENTARIO TOTAL " . number_format($reg_tot_inv['total_inventario'] ?? 0.00) . "</b>"; ?>
            </div>
            <div class="col-sm-12 col-md-12">
                <a style="color: #000;" href="#" onClick="javascript:modal_material_solicitado()" data-bs-toggle="tooltip" data-bs-placement="top" title="Material solicitado">
                    <i class="fa-solid fa-file-invoice fa-2xl" style="color: #bfc1c3;"></i>
                    <?php echo  number_format($reg_tot_solicitado['tot_inv_kg'] ?? 0.00) . " Kg"  ?>
                </a>
                <i class="fa-solid fa-square" style="color: #0BD3D0;"> </i>
                Material solicitado
                <i class="fa-solid fa-square" style="color: #f52462;"> </i><?php echo $reg_parametros['rojo']; ?> días
                <i class="fa-solid fa-square" style="color: #f8fb5b;"> </i><?php echo $reg_parametros['amarillo']; ?> días
                <i class="fa-solid fa-square" style="color: #72f888;"> </i><?php echo $reg_parametros['verde']; ?> días
            </div>
        </div>
        <div class="table-responsive">
            <table>
                <?php     /* total de registros */
                $total_cajon = mysqli_query($cnx, "SELECT * FROM almacen_cajones WHERE ac_ban='M' AND ac_estatus = 'A' ORDER BY ac_descripcion DESC") or die(mysqli_error($cnx) . "Error: en consultar el material");
                $total_registros = mysqli_num_rows($total_cajon);
                // Calcular el número de renglones necesarios
                $num_renglones = ceil($total_registros / 8);
                // Definir cuántos registros quieres mostrar por renglón
                $registros_por_renglon = 8;

                for ($i = 0; $i < $num_renglones; $i++) {
                    $res = $i + 1;

                    if ($res == 1) {
                        $valor = '0,8';
                    }
                    if ($res == 2) {
                        $valor = '8,8';
                    }
                    if ($res == 3) {
                        $valor = '16,8';
                    }

                    /* se ejecuta consulta por cada renglon */
                    /* ---------------------NO. CAJONES------------------------ */
                    $num_cajon = mysqli_query($cnx, "SELECT * FROM almacen_cajones WHERE ac_ban='M' AND ac_estatus = 'A' ORDER BY ac_descripcion DESC LIMIT $valor") or die(mysqli_error($cnx) . "Error: en consultar el material");
                    $reg_num_cajon = mysqli_fetch_assoc($num_cajon);

                    echo '<tr>';
                    do {
                        if (fnc_permiso($_SESSION['privilegio'], 32, 'upe_agregar') == 1) {
                            echo '<th id="cajones"><a id="cajon" href="#" onClick="javascript:modal_cajon_editar(' . $reg_num_cajon['ac_id'] . ')">' . $reg_num_cajon['ac_descripcion'] . '</a></th>';
                        } else {
                            echo '<th id="cajones">';
                            #MC
                            if (fnc_permiso($_SESSION['privilegio'], 35, 'upe_agregar') == 1) {

                                echo ' <a href="#" id="link_modal" onClick="javascript:solicitar_cajon(' . $reg_num_cajon['ac_id'] . ')" data-bs-toggle="tooltip" data-bs-placement="top" title="Solicitar cajon"><i class="fa-solid fa-file-invoice fa-1xl" style="color: #bfc1c3;"></i></a>';
                            }
                            #
                            echo $reg_num_cajon['ac_descripcion'] . '</th>';
                        }
                    } while ($reg_num_cajon = mysqli_fetch_assoc($num_cajon));
                    echo '</tr>';


                    /* -----------------------DETALLE-------------------------- */
                    echo '<tr>';
                    $detalle_cajon = mysqli_query($cnx, "SELECT * FROM almacen_cajones WHERE ac_ban='M' AND ac_estatus = 'A' ORDER BY ac_descripcion DESC LIMIT $valor") or die(mysqli_error($cnx) . "Error: en consultar el material");
                    $reg_detalle = mysqli_fetch_assoc($detalle_cajon);

                    if (mysqli_num_rows($detalle_cajon) > 0) {
                        do {
                            $cad_inv = mysqli_query($cnx, "SELECT p.prv_nombre,p.prv_ncorto,m.mat_id,m.mat_nombre,i.inv_kg_totales,i.inv_folio_interno,i.inv_fecha,i.inv_hora_entrada,p.prv_id,i.inv_id,i.ac_id,inv_solicitado
                            FROM inventario as i
                            INNER JOIN proveedores AS p on(i.prv_id = p.prv_id)
                            INNER JOIN materiales as m on(i.mat_id = m.mat_id)
                            WHERE i.ac_id = " . $reg_detalle['ac_id'] . " AND i.inv_tomado = 0 AND inv_enviado <> 3 AND inv_enviado <> 4 ") or die(mysqli_error($cnx) . "Error: en consultar inventario");
                            $reg_inv = mysqli_fetch_assoc($cad_inv);

                            $tot_kg_cj = mysqli_query($cnx, "SELECT SUM(inv_kg_totales) as total_kilos  FROM inventario as i
                            INNER JOIN proveedores AS p on(i.prv_id = p.prv_id)
                            INNER JOIN materiales as m on(i.mat_id = m.mat_id)
                            WHERE i.ac_id = " . $reg_detalle['ac_id'] . " AND i.inv_tomado = 0 AND inv_enviado <> 3 AND inv_enviado <> 4 ") or die(mysqli_error($cnx) . "Error: en consultar inventario");
                            $reg_kg_cj = mysqli_fetch_assoc($tot_kg_cj);

                            echo '<td>';


                            if ($reg_kg_cj['total_kilos'] > 0) {
                                echo "<b>KG TOTALES " . number_format($reg_kg_cj['total_kilos']) . " </b>";
                                echo ' <a href="#" onClick="javascript:modal_promedio_inventario(' . $reg_detalle['ac_id'] . ')" data-bs-toggle="tooltip" data-bs-placement="top" title="Consulta promedio inventario"><i class="fa-solid fa-percent fa-2xl" style="color: #bfc1c3;"></i></a> <br><br>';
                            }
                            do {
                                if (mysqli_num_rows($cad_inv) > 0) {

                                    if ($reg_inv['inv_solicitado'] == 'S') {
                                        $solicitud = '<i class="fa-solid fa-square" style="color: #0BD3D0;"> </i> ';
                                    } else {
                                        $solicitud = '';
                                    }

                                    $fecha_entrada = new DateTime($reg_inv['inv_hora_entrada']);
                                    $fecha_actual = new DateTime(date('Y-m-d H:i:s'));
                                    // Calcula la diferencia entre fechas
                                    $diferencia = $fecha_actual->diff($fecha_entrada);
                                    /*0 - 10 color verde
									11 - 20 color amarillo
									21 - a mas días color rojo*/

                                    // Extrae el número de días de la diferencia
                                    $diasTranscurridos = $diferencia->days;
                                    /*if ($diasTranscurridos >= $reg_parametros['rojo'] || $reg_inv['inv_hora_entrada'] == '') {
                                        $color = '<i class="fa-solid fa-square" style="color: #f52462;"></i>';
                                    }
                                    if ($diasTranscurridos >= $reg_parametros['amarillo'] && $diasTranscurridos < $reg_parametros['rojo']) {
                                        $color = '<i class="fa-solid fa-square" style="color: #f8fb5b;"></i>';
                                    }
                                    if (($diasTranscurridos >= $reg_parametros['verde'] && $diasTranscurridos < $reg_parametros['amarillo']) && $reg_inv['inv_hora_entrada'] != '') {
                                        $color = '<i class="fa-solid fa-square" style="color: #72f888;"></i>';
                                    }
 */
                                    if ($diasTranscurridos > $reg_parametros['amarillo'] || $reg_inv['inv_hora_entrada'] == '') {
                                        $color = '<i class="fa-solid fa-square" style="color: #f52462;"></i>';
                                    }
                                    if ($diasTranscurridos > $reg_parametros['verde'] && $diasTranscurridos <= $reg_parametros['amarillo']) {
                                        $color = '<i class="fa-solid fa-square" style="color: #f8fb5b;"></i>';
                                    }
                                    if (($diasTranscurridos >= 0 && $diasTranscurridos <= $reg_parametros['verde']) && $reg_inv['inv_hora_entrada'] != '') {
                                        $color = '<i class="fa-solid fa-square" style="color: #72f888;"></i>';
                                    }
                                    $fecha_param = "'" . $reg_inv['inv_fecha'] . "'";

                                    //$fecha =  "<b>Fecha: " . $reg_inv['inv_fecha'] . "</b>";
                                    $fecha =  "<b>Fecha: " . $reg_inv['inv_hora_entrada'] . "</b>";

                                    echo $reg_inv['inv_folio_interno'] . " " . $solicitud . "<br>";
                                    echo $fecha . ' ' . $color .  "<br>";
                                    echo $reg_inv['mat_nombre'] . "<br>";
                                    echo $reg_inv['inv_kg_totales'] . " Kg<br>";
                                    if ($reg_autorizado['up_ban'] == 1) {
                                        echo $reg_inv['prv_nombre'] . "<br>";
                                    } else {
                                        echo $reg_inv['prv_ncorto'] . "<br>";
                                    }
                                    if (fnc_permiso($_SESSION['privilegio'], 32, 'upe_editar') == 1) {

                                        echo '<a href="#" id="link_modal" onClick="javascript:modal_croquis(' . $reg_inv['inv_id'] . ', ' . $reg_inv['prv_id'] . ', ' . $reg_inv['mat_id'] . ', ' . $reg_inv['inv_folio_interno'] . ', ' . $fecha_param . ', ' . $reg_inv['inv_kg_totales'] . ', ' . $reg_detalle['ac_id'] . ')"  data-bs-toggle="tooltip" data-bs-placement="top" title="Mover de cajón"><i class="fa-solid fa-arrows-rotate fa-2xl" style="color: #bfc1c3;"></i></a> ';
                                    }

                                    echo    ' <a href="#" onClick="javascript:modal_cajon_inventario(' . $reg_inv['inv_id'] . ')" data-bs-toggle="tooltip" data-bs-placement="top" title="Consulta inventario"><i class="fa-solid fa-receipt fa-2xl" style="color: #bfc1c3;"></i></a> ';
                                    if (fnc_permiso($_SESSION['privilegio'], 35, 'upe_agregar') == 1) {

                                        echo ' <a href="#" id="link_modal" onClick="javascript:solicitar_pedido(' . $reg_inv['inv_id'] . ')" data-bs-toggle="tooltip" data-bs-placement="top" title="Solicitar material"><i class="fa-solid fa-file-invoice fa-2xl" style="color: #bfc1c3;"></i></a>';
                                    }

                                    echo '<br><br>';
                                }
                            } while ($reg_inv = mysqli_fetch_assoc($cad_inv));
                            echo '</td>';
                        } while ($reg_detalle = mysqli_fetch_assoc($detalle_cajon));
                    }
                    echo '</tr>';

                    /* RENGLON DE SEPARACION*/
                    echo '<tr id="separacion">';
                    echo '<td id="separacion" colspan="8"></td>';
                    echo '</tr>';
                } ?>
            </table>
        </div>
        <!-- Modal movimiento inventario -->
        <div class="modal fade" id="modal_cajones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        </div>



        <!-- Modal consulta inventario -->
        <div class="modal fade" id="modal_cajon_inventario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        </div>

        <!-- Modal consulta inventario -->
        <div class="modal fade" id="modal_material_solicitado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        </div>

        <!-- Modal alta de cajon -->
        <div class="modal fade" id="modal_alta_cajones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="form_cajones_alta">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Registro de nuevo cajón</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="location.reload()"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <label for="recipient-name" class="col-form-label">No de cajón:</label>
                                    <input onkeypress="return isNumberKey(event, this);" type="text" class="form-control" id="txt_cajon_a" name="txt_cajon_a" required>
                                </div>
                                <div class="col">
                                    <label for="recipient-name" class="col-form-label">Patio</label>
                                    <select type="text" class="form-select" id="cbx_patio_a" name="cbx_patio_a" required>
                                        <option value="">Seleccione</option>
                                        <option value="M">Materia prima</option>
                                        <option value="P">Molinos</option>
                                    </select>
                                </div>
                            </div>
                            <!--   <div class="row">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cajón</th>
                                            <th>Estatus</th>
                                            <th>Patio</th>
                                        </tr>


                                        <?php
                                        $cajones = mysqli_query($cnx, "SELECT * FROM almacen_cajones ORDER BY ac_descripcion ASC") or die(mysqli_error($cnx) . "Error: en consultar el material");
                                        $reg_cajones = mysqli_fetch_assoc($cajones);

                                        if (mysqli_num_rows($cajones) > 0) {
                                            do {
                                                if ($reg_cajones['ac_estatus'] == 'A') {
                                                    $estatus = 'Activo';
                                                } else {
                                                    $estatus = 'Baja';
                                                }
                                                if ($reg_cajones['ac_ban'] == 'M') {
                                                    $patio = 'Materia prima';
                                                } else {
                                                    $patio = 'Molinos';
                                                }
                                        ?>
                                                <tr>
                                                    <td><?php echo $reg_cajones['ac_descripcion']; ?></td>
                                                    <td><?php echo $estatus; ?></td>
                                                    <td><?php echo $patio; ?></td>
                                                </tr>
                                        <?php  } while ($reg_cajones = mysqli_fetch_assoc($cajones));
                                        } ?>
                                    </thead>
                                </table>
                            </div> -->
                        </div>
                        <div class="modal-footer">
                            <!--mensajes-->
                            <div class="col-md-6">
                                <div id="alerta-errorAltaCajon" class="alert d-none">
                                    <strong class="alert-heading">¡Error!</strong>
                                    <span class="alert-body"></span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="location.reload()"><i class="fa-solid fa-rectangle-xmark"></i> Cerrar</button>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal edición cajon -->
        <div class="modal fade" id="modal_cajon_edicion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        </div>


        <!-- Modal promedio inventario -->
        <div class="modal fade" id="modal_promedio_inventario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        </div>
        <link rel="stylesheet" href="../css/estilos_footer.css">

        <footer style="position: fixed;  bottom: 0;  width: 100%;  z-index: 100;">
            Copyright 2018 by <b><a href="http://ccaconsultoresti.com/">CCA Consultores en TI</a> </b>. All Rights Reserved.
        </footer>
    </div>
</body>

</html>

<script>
    /*Abrir modal movimiento de material*/
    function modal_croquis(inv, prov, mat, folio, fecha, kg, cajon_inicial) {
        $.ajax({
            type: 'post',
            url: 'modal_cajones.php',
            data: {
                "hdd_inv": inv,
                "hdd_prov": prov,
                "hdd_mat": mat,
                "hdd_folio": folio,
                "hdd_fecha": fecha,
                "hdd_kg": kg,
                "hdd_cajon_ini": cajon_inicial,
            }, //Pass $id
            success: function(result) {
                $("#modal_cajones").html(result);
                $('#modal_cajones').modal('show')
            }
        });
        return false;
    };

    /*Abrir modal inventario cajones*/
    function modal_cajon_inventario(inv) {
        $.ajax({
            type: 'post',
            url: 'modal_cajones_inv.php',
            data: {
                "hdd_inv": inv,
            }, //Pass $id
            success: function(result) {
                $("#modal_cajon_inventario").html(result);
                $('#modal_cajon_inventario').modal('show')
            }
        });
        return false;
    };

    /*modal_material_solicitado*/
    function modal_material_solicitado() {
        $.ajax({
            type: 'post',
            url: 'modal_material_solicitado.php',
            data: {
                "hdd_inv": "",
            }, //Pass $id
            success: function(result) {
                $("#modal_material_solicitado").html(result);
                $('#modal_material_solicitado').modal('show')
            }
        });
        return false;
    };
    /* Alta de cajon */
    $(document).ready(function() {
        $("#form_cajones_alta").submit(function() {
            //alert('editar');
            var formData = $(this).serialize();
            $.ajax({
                url: "alta_cajones.php",
                type: 'POST',
                data: formData,
                success: function(result) {
                    data = JSON.parse(result);
                    if (data["mensaje"] == "El registro ya existe") {
                        alertas_v5("#alerta-errorAltaCajon", '', data["mensaje"], 3, true, 5000);
                    } else {
                        alertas_v5("#alerta-errorAltaCajon", '', data["mensaje"], 1, true, 5000);
                    }
                    $('#form_cajones_alta').each(function() {
                        this.reset();
                    });
                }
            });
            return false;
        });
    });

    function modal_cajon_editar(cajon) {
        $.ajax({
            type: 'post',
            url: 'modal_cajones_editar.php',
            data: {
                "hdd_cajon": cajon,
            }, //Pass $id
            success: function(result) {
                $("#modal_cajon_edicion").html(result);
                $('#modal_cajon_edicion').modal('show')
            }
        });
        return false;
    };

    /* solicitar pedido interno */
    function solicitar_pedido(id) {
        Swal.fire({
            title: "¿Realizar pedido?",
            //text: "You won't be able to revert this!",
            icon: "warning",
            //imageUrl: "URL_DE_TU_IMAGEN",
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            background: "#fff",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No" // Agregamos el texto para el botón de cancelar
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'solicitar_pedido.php',
                    data: 'id=' + id,
                    type: 'post',
                    success: function(result) {
                        Swal.fire({
                            title: "Realizado!",
                            text: "El supervisor de materia prima atendera su solicitud",
                            icon: "success"
                        }).then(() => {
                            // Recargar la página después de cerrar el segundo SweetAlert
                            location.reload();
                        });
                    }
                });
                return false;

            }
        });
    }

    /* solicitar pedido interno */
    function solicitar_cajon(id) {
        Swal.fire({
            title: "¿Realizar pedido?",
            icon: "warning",
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            background: "#fff",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'solicitar_cajon.php',
                    data: 'id=' + id,
                    type: 'post',
                    success: function(result) {
                        Swal.fire({
                            title: "Realizado!",
                            text: "El supervisor de materia prima atendera su solicitud",
                            icon: "success"
                        }).then(() => {
                            // Recargar la página después de cerrar el segundo SweetAlert
                            location.reload();
                        });
                    }
                });
                return false;

            } else {
                location.reload();
            }
        });
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });


    function modal_promedio_inventario(ac_id) {
        $.ajax({
            type: 'post',
            url: 'modal_promedio_inventario.php',
            data: {
                "ac_id": ac_id
            },
            success: function(result) {
                $("#modal_promedio_inventario").html(result);
                $('#modal_promedio_inventario').modal('show')
            }
        });
    }
</script>