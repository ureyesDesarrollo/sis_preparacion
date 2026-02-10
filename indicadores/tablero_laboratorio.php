<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include "../funciones/funciones_procesos.php";
include "../seguridad/user_seguridad.php";
$cnx =  Conectarse();

$cad_tipo_eq = mysqli_query($cnx, "SELECT * FROM equipos_tipos WHERE et_estatus = 'A'") or die(mysqli_error($cnx) . "Error: en consultar tipo de equipos");
$reg_tipo_eq = mysqli_fetch_assoc($cad_tipo_eq);

$cad_tipo_estatus = mysqli_query($cnx, "SELECT distinct(le_tipo) as le_tipo FROM listado_estatus WHERE le_tipo != '' ") or die(mysqli_error($cnx) . "Error: en consultar tipo de equipos");
$reg_tipo_estatus = mysqli_fetch_assoc($cad_tipo_estatus);

//equipos normales
$cad_equipos = mysqli_query($cnx, "SELECT ep.*, le.le_color, le.le_id
FROM equipos_preparacion AS ep 
INNER JOIN equipos_tipos as e on(ep.ep_tipo = e.et_tipo)
LEFT JOIN listado_estatus AS le ON (ep.le_id = le.le_id) 
WHERE estatus = 'A'  AND ep.le_id IN (11,15) AND e.ban_almacena = 'N' 
ORDER BY 
  CASE 
    WHEN ep.le_id IN (10, 11) THEN 1
    WHEN ep.le_id = 15 THEN 2
    WHEN ep.le_id = 14 THEN 3
    ELSE 4
  END,
  ep.le_id") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
$reg_equipos = mysqli_fetch_assoc($cad_equipos);
$total_registros = mysqli_num_rows($cad_equipos);

$total_registros = mysqli_num_rows($cad_equipos);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indicadores</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="../assets/fontawesome/fontawesome.js"></script>
    <link rel="stylesheet" href="../assets/css/indicadores.css">

</head>

<body>
    <div class="container-fluid">
        <!-- ESTATUS -->
        <div class="row" style="box-shadow: 1px 2px 3px #e6e6e6">
            <?php do {
                if ($reg_tipo_estatus['le_tipo'] == 'E') {
                    $tipo = "Estatus de equipo";
                    $clase = 'class="col-xs-12 col-sm-12 col-md-3 ps-1 pe-1"';
                    $clase_estatus = 'class="col-4 ps-1 pe-1"';
                } else if ($reg_tipo_estatus['le_tipo'] == 'P') {
                    $tipo = "Estatus de proceso";
                    $clase = 'class="col-xs-12 col-sm-12 col-md-5 ps-1 pe-1"';
                    $clase_estatus = 'class="col-3 ps-1 pe-1"';
                } else {
                    $tipo = "Sin identificar";
                }

                $cad_estatus = mysqli_query($cnx, "SELECT le_estatus,le_color FROM listado_estatus WHERE le_color <> '' and le_tipo = '" . $reg_tipo_estatus['le_tipo'] . "'
                ORDER BY 
                CASE 
                    WHEN le_id IN (10, 11) THEN 1
                    WHEN le_id = 15 THEN 2
                    WHEN le_id = 14 THEN 3
                    ELSE 4
                END,
                le_id;") or die(mysqli_error($cnx) . "Error: en consultar estatus");
                $reg_estatus = mysqli_fetch_assoc($cad_estatus);
            ?>
                <!-- <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"> -->
                <div <?php echo $clase; ?> style="margin-bottom: 0.3rem;">
                    <div class="card">
                        <div class="card-header pt-0 pb-0">
                            <h6 class="text-center"><?php echo $tipo; ?></h6>
                        </div>
                        <div class="card-body pt-1 pb-1">
                            <div class="row ">
                                <?php do { ?>
                                    <div <?php echo $clase_estatus; ?>>
                                        <div class="alert p-2 m-0" style="<?php echo "background:" . $reg_estatus['le_color'] ?>;">
                                            <h6 class="text-center" style="font-weight:bold;font-size: .7rem;margin:0px"><?php echo $reg_estatus['le_estatus']; ?></h6>
                                        </div>
                                    </div>
                                <?php } while ($reg_estatus = mysqli_fetch_assoc($cad_estatus)) ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } while ($reg_tipo_estatus = mysqli_fetch_assoc($cad_tipo_estatus)); ?>



            <div class="col-xs-12 col-sm-12 col-md-4" style="color: #000;text-align:right;margin-top:1.5rem">
                <span style="font-weight: bold;margin-right:0.5rem">TABLERO DE LABORATORIO</span>
                <!-- <img src="../imagenes/logo_progel_v5.png" alt=""> -->
                <?php if ($_SESSION['privilegio'] != 3) { ?>
                    <a href="../index_inicio.php">
                        <i class="fa-solid fa-circle-left"></i> Regresar
                    </a>
                <?php } ?>

                <a href="../seguridad/salir.php">
                    <i class="fa-solid fa-user"></i> Cerrar sesión
                </a>
            </div>
        </div>

        <!-- EQUIPOS NORMALES -->
        <div class="row">
            <h3>EQUIPOS</h3>

            <?php
            do {
                if ($total_registros > 0) {
            ?>
                    <?php

                    if ($reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 11) {
                        //1.selecciona el ultimo proceso activo en el equipo
                        $cad_proceso = mysqli_query($cnx, "SELECT MAX(p.pro_id) as pro_id FROM procesos as p 
                                                    inner join procesos_equipos as pe on (p.pro_id = pe.pro_id)
                                                    WHERE pe.ep_id = '" . $reg_equipos['ep_id'] . "' and p.pro_estatus = 1") or die(mysqli_error($cnx) . "Error: en consultar los procesos");
                        $reg_procesos = mysqli_fetch_assoc($cad_proceso);
                        $tot_procesos = mysqli_num_rows($cad_proceso);

                        //3.selecciona datos del proceso
                        $cad_pro_res = mysqli_query($cnx, "SELECT pro_id, pro_fe_carga, pro_hr_inicio, pro_hr_fin, pro_supervisor,pro_operador, pro_total_kg, pt_id FROM procesos                
                            where pro_id = '" . $reg_procesos['pro_id'] . "' order by pro_fe_carga") or die(mysqli_error($cnx) . "Error: en consultar procesos");
                        $reg_pro_res = mysqli_fetch_assoc($cad_pro_res);

                        //5. Selecciona los datos de tipo preparacion y material
                        $cad_tp = mysqli_query($cnx, "SELECT * FROM preparacion_tipo where pt_id = '" . $reg_pro_res['pt_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar tipos de preparacion");
                        $reg_tp = mysqli_fetch_assoc($cad_tp);

                        //selecciona las fases del tipo de proceso
                        $cad_fases = mysqli_query($cnx, "SELECT e.pe_id, e.pe_descripcion FROM preparacion_tipo_etapas as p
                            inner join preparacion_etapas as e on(p.pe_id =e.pe_id)
                          WHERE p.pt_id = '" . $reg_tp['pt_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos materiales");
                        $reg_fases = mysqli_fetch_assoc($cad_fases);
                    }
                    ?>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-4 mb-3 ps-1 pe-1 indicador">
                        <!-- color de estatus del equipo -->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-body p-2 border fw-bold" style="background: <?php echo $reg_equipos['le_color'] ?>">
                                    <!-- Si esta en estatus en cualquier excepto descompuesto y reparación, abre para captura -->
                                    <?php if ($reg_equipos['le_id'] == 9 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 11) {
                                        //No se que que hace esta validación aqui 
                                    ?>
                                        <?php
                                        if (($_SESSION['privilegio'] == 3 or $_SESSION['privilegio'] == 4 or $_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28) and $reg_tipo_eq['ban_almacena'] == 'N') { ?>

                                            <div class="text-center">
                                                <a href="../bitacoras/bitacora.php?id_e=<?php echo $reg_equipos['ep_id'] ?>" target="_blank" class="numero"><?php echo $reg_equipos['ep_descripcion'] . " / " . $reg_procesos['pro_id']; ?></a>
                                            </div>
                                        <?php } else { ?>
                                            <a href="../bitacoras/formatos/bitacora_consulta.php?id_e=<?php echo $reg_equipos['ep_id'] ?>" target="_blank" class="numero"><?php echo $reg_equipos['ep_descripcion'] ?></a>
                                        <?php } ?>
                                    <?php }
                                    /* Si esta descompuesto o en reparación */ else { ?>
                                        <a href="#" style="cursor: default;text-decoration: none;color: inherit;" class="numero"><?php echo $reg_equipos['ep_descripcion'] ?></a>
                                    <?php } ?>
                                </div>
                                <?php if ($reg_tipo_eq['ban_almacena'] != 'S') { ?>
                                    <!-- información del proceso -->
                                    <div class="card-body p-1 border" style="font-size: .7rem;">
                                        <span style="font-weight: bold;"> Tipo preparación:</span>
                                        <?php if ($reg_equipos['le_id'] == 11) {
                                            echo $reg_tp['pt_descripcion'];
                                        } ?><br>
                                        <span style="font-weight: bold;">Fecha carga molino:</span>
                                        <?php
                                        echo $reg_pro_res['pro_fe_carga'];
                                        ?><br><br>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Fases</th>
                                                    <th>Parametros</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                do {
                                                    /*    echo "SELECT * FROM procesos_auxiliar as a
                                                        INNER JOIN preparacion_etapas as e on (a.pe_id = e.pe_id)
                                                        WHERE a.pro_id = '" . $reg_pro_res['pro_id'] . "' and a.pe_id = '" . $reg_fases['pe_id'] . "'<br><br>"; */


                                                    $cad_fases_d = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar as a
                                                        INNER JOIN preparacion_etapas as e on (a.pe_id = e.pe_id)
                                                        WHERE a.pro_id = '" . $reg_pro_res['pro_id'] . "' and a.pe_id = '" . $reg_fases['pe_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar detalle fases");
                                                    $reg_fases_d = mysqli_fetch_assoc($cad_fases_d);
                                                    $tot_auxiliar = mysqli_num_rows($cad_fases_d);

                                                    //selecciona parametros liberación
                                                    $cad_param = mysqli_query($cnx, "SELECT * FROM procesos_liberacion as p
                                                        WHERE pe_id = '" . $reg_fases['pe_id'] . "' and  pro_id= '" . $reg_pro_res['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar parametros liberacion");
                                                    $reg_param = mysqli_fetch_assoc($cad_param);

                                                    //selecciona parametros liberación b(fase 7,8,8c)
                                                    $cad_param_b = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b as p
                                                           WHERE pe_id = '" . $reg_fases['pe_id'] . "' and  pro_id= '" . $reg_pro_res['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar parametros liberacion");
                                                    $reg_param_b = mysqli_fetch_assoc($cad_param_b);


                                                    $lib_coc_b = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b_cocidos WHERE prol_id = '$reg_param_b[prol_id]'") or die(mysqli_error($cnx) . "Error: en consultar procesos de liberacion");
                                                    $reg_lib_coc = mysqli_fetch_assoc($lib_coc_b);

                                                    if ($reg_param['prol_fecha'] == '') {
                                                        $fe_fin = date("Y-m-d");
                                                        $hora_fin = date("H:i:s");
                                                    } else {
                                                        $fechaHora = $reg_param['prol_fecha'];
                                                        list($fe_fin, $hora_fin) = explode(' ', $fechaHora);
                                                        $fe_fin = $fe_fin;
                                                        $hora_fin = $hora_fin;
                                                    }

                                                ?>
                                                    <tr>
                                                        <td><?php echo $reg_fases['pe_descripcion'] ?></td>
                                                        <td><?php
                                                            if (isset($reg_param['prol_id'])) { ?>
                                                                <table class="table table-bordered">
                                                                    <tr>
                                                                        <th>Fecha inicia fase</th>
                                                                        <td><?php echo fnc_formato_val($reg_fases_d['proa_fe_ini']) . ' ' . $reg_fases_d['proa_hr_ini']; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Fecha termina fase</th>
                                                                        <td><?php echo $reg_fases_d['proa_fe_fin'] . ' ' . $reg_fases_d['proa_hr_fin']; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Tiempo transcurrido</th>
                                                                        <td><?php
                                                                            $tiempo_transcurrido = fnc_horas($reg_fases_d['proa_fe_ini'], $fe_fin, $reg_fases_d['proa_hr_ini'], $hora_fin);
                                                                            if ($tiempo_transcurrido == '00:00') {
                                                                                echo "-";
                                                                            } else {
                                                                                echo $tiempo_transcurrido;
                                                                            } ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Fecha libera control proceso</th>
                                                                        <td><?php echo fnc_formato_val($reg_param['prol_fecha']); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Horas transcurridas inicio - liberación</th>
                                                                        <td>
                                                                            <?php
                                                                            $tiempo_transcurrido = fnc_horas($reg_fases_d['proa_fe_ini'], $fe_fin, $reg_fases_d['proa_hr_ini'], $hora_fin);
                                                                            if ($tiempo_transcurrido == '00:00') {
                                                                                echo "-";
                                                                            } else {
                                                                                echo $tiempo_transcurrido;
                                                                            } ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php if ($reg_param['prol_ce'] != NULL) { ?>
                                                                        <tr>
                                                                            <th>CE de liberación
                                                        </td>
                                                        <td><?php echo fnc_formato_val($reg_param['prol_ce']); ?></td>
                                                    </tr>
                                                <?php }
                                                                    if ($reg_param['prol_hr_totales'] != NULL) { ?>
                                                    <tr>
                                                        <th>Horas totales</th>
                                                        <td><?php echo fnc_formato_val($reg_param['prol_hr_totales']); ?></td>
                                                    </tr>
                                                <?php }
                                                                    if ($reg_param['prol_ph'] != NULL) { ?>
                                                    <tr>
                                                        <th>PH de liberación</th>
                                                        <td><?php echo fnc_formato_val($reg_param['prol_ph']); ?></td>
                                                    </tr>
                                                <?php }
                                                                    if ($reg_param['prol_color'] != NULL) { ?>
                                                    <tr>
                                                        <th>Color</th>
                                                        <td><?php echo fnc_formato_val($reg_param['prol_color']); ?></td>
                                                    </tr>
                                                <?php }
                                                                    if ($reg_param['prol_adelgasamiento'] != NULL) { ?>
                                                    <tr>
                                                        <th>Adelgazamiento</th>
                                                        <td><?php echo fnc_formato_val($reg_param['prol_adelgasamiento']); ?></td>
                                                    </tr>
                                                <?php }
                                                                    if ($reg_param['prol_peroxido'] != NULL) { ?>
                                                    <tr>
                                                        <th>Peroxido</th>
                                                        <td><?php echo fnc_formato_val($reg_param['prol_peroxido']); ?></td>
                                                    </tr>
                                                <?php } ?>
                                        </table>
                                    <?php } else if (isset($reg_param_b['prol_id'])) { ?>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>Fecha</td>
                                                <td colspan="3"><?php echo $reg_param_b['prol_fecha'] . ' ' . $reg_param_b['prol_hora']; ?></td>
                                            </tr>


                                            <tr>
                                                <td>Cocido ph</td>
                                                <td>CE</td>
                                                <td>Cuero sob</td>
                                                <td>% Ext</td>
                                            </tr>
                                            <?php do { ?>
                                                <tr>
                                                    <td><?php echo "L" . $reg_lib_coc['prol_ren'] . ' ' . $reg_lib_coc['prol_cocido']; ?></td>
                                                    <td><?php echo $reg_lib_coc['prol_ce']; ?></td>
                                                    <td><?php echo $reg_lib_coc['prol_cuero_sob']; ?></td>
                                                    <td><?php echo $reg_lib_coc['prol_por_extrac']; ?></td>
                                                </tr>
                                            <?php } while ($reg_lib_coc = mysqli_fetch_assoc($lib_coc_b)); ?>
                                            <tr>
                                                <td>Color</td>
                                                <td colspan="3"><?php echo $reg_param_b['prol_color']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Color caldo</td>
                                                <td colspan="3"><?php echo $reg_param_b['prol_color_caldo']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>% de solidos</td>
                                                <td colspan="3"><?php echo $reg_param_b['prol_solides']; ?></td>
                                            </tr>
                                        </table>
                                    <?php } else if ($tot_auxiliar > 0 && $reg_fases_d['proa_fe_fin'] != '') { //si hay registros por operador en la fase y fecha de termino

                                                                //calcula el tiempo transcurrido entre fecha de termino  a hora actual
                                                                $tiempo_transcurrido = fnc_horas($reg_fases_d['proa_fe_fin'], date("Y-m-d"), $reg_fases_d['proa_hr_fin'], date("H:i:s"));
                                                                if ($tiempo_transcurrido > '00:20') {
                                                                    echo "<span style='color:red'>Pendiente por liberar <i class='fa-solid fa-triangle-exclamation'></i> Urgente</span>";
                                                                } else {
                                                                    echo "<span style='color:red'>Pendiente por liberar</span>";
                                                                }
                                                            } else {
                                                                echo "<span style='color:red'>Pendiente por liberar</span>";
                                                            }  ?>
                                    </td>
                                    </tr>
                                <?php
                                                } while ($reg_fases = mysqli_fetch_assoc($cad_fases));
                                ?>
                                </tbody>
                                </table>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php
                } ?>

            <?php } while ($reg_equipos = mysqli_fetch_assoc($cad_equipos)); ?>
        </div>
    </div>

</body>
<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<style>
    .table>:not(caption)>*>* {
        padding: 0.1rem 0.1rem;

    }
</style>

</html>

<?php
//5 ocupado
//6 libre
//7 descompuesto
//8 reparación

//nuevos estatus
//9 libre
//10 orden de trabajo
//11 ocupado
//12 descompuesto
//13 reparación
?>