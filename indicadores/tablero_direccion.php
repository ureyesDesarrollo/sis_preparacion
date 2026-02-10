<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include "../funciones/funciones_procesos.php";
include "../seguridad/user_seguridad.php";
$cnx =  Conectarse();

/* $cad_tipo_eq = mysqli_query($cnx, "SELECT * FROM equipos_tipos WHERE et_estatus = 'A'") or die(mysqli_error($cnx) . "Error: en consultar tipo de equipos");
$reg_tipo_eq = mysqli_fetch_assoc($cad_tipo_eq); */



//equipos normales
$cad_equipos = mysqli_query($cnx, "SELECT ep.*, le.le_color, le.le_id
FROM equipos_preparacion AS ep 
INNER JOIN equipos_tipos as e on(ep.ep_tipo = e.et_tipo)
LEFT JOIN listado_estatus AS le ON (ep.le_id = le.le_id) 
WHERE estatus = 'A'  AND ep.le_id IN (10, 11, 14, 15) AND e.ban_almacena = 'N' 
ORDER BY 
  CASE 
    WHEN ep.le_id IN (10, 11) THEN 1
    WHEN ep.le_id = 15 THEN 2
    WHEN ep.le_id = 14 THEN 3
    ELSE 4
  END,
  ep.le_id;") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
$reg_equipos = mysqli_fetch_assoc($cad_equipos);
$total_registros = mysqli_num_rows($cad_equipos);

//equipos almacen
$cad_eq_almacen = mysqli_query($cnx, "SELECT ep.*, le.le_color, le.le_id
FROM equipos_preparacion AS ep 
INNER JOIN equipos_tipos as e on(ep.ep_tipo = e.et_tipo)
LEFT JOIN listado_estatus AS le ON (ep.le_id = le.le_id) 
WHERE estatus = 'A'  AND ep.le_id IN (10, 11, 14, 15) AND e.ban_almacena = 'S'") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
$reg_eq_almacen = mysqli_fetch_assoc($cad_eq_almacen);
$total_reg_almacen = mysqli_num_rows($cad_eq_almacen);

$cad_tipo_estatus = mysqli_query($cnx, "SELECT distinct(le_tipo) as le_tipo FROM listado_estatus WHERE le_tipo != '' ") or die(mysqli_error($cnx) . "Error: en consultar tipo de equipos");
$reg_tipo_estatus = mysqli_fetch_assoc($cad_tipo_estatus);
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

                $cad_estatus = mysqli_query($cnx, "SELECT le_estatus,le_color,le_id FROM listado_estatus WHERE le_color <> '' and le_tipo = '" . $reg_tipo_estatus['le_tipo'] . "'
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
                                <?php do {
                                    if ($reg_estatus['le_id'] == 10 || $reg_estatus['le_id'] == 12 || $reg_estatus['le_id'] == 14) {
                                        $color_text_est = '#fff';
                                    } else {
                                        $color_text_est = '#000';
                                    }

                                ?>
                                    <div <?php echo $clase_estatus; ?>>
                                        <div class="alert p-2 m-0" style="<?php echo 'background:' . $reg_estatus['le_color'] . ';color:' . $color_text_est ?>;">
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
                <span style="font-weight: bold;margin-right:1rem">TABLERO DE DIRECCIÓN</span>
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
                //equipos receptores/paletos/ preparadores
                /*  $cad_equipos = mysqli_query($cnx, "SELECT ep.*, le.le_color, le.le_id FROM equipos_preparacion AS ep
            LEFT JOIN listado_estatus AS le ON (ep.le_id = le.le_id) 
            WHERE ep_tipo = '{$reg_tipo_eq['et_tipo']}' AND estatus = 'A' and ep.le_id in (10,11,14,15) ORDER BY ep.le_id") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
            $reg_equipos = mysqli_fetch_assoc($cad_equipos);
 
            $total_registros = mysqli_num_rows($cad_equipos);*/
                if ($total_registros > 0) {

                    /*  echo "<h3>" . $reg_tipo_eq['et_descripcion'] . "</h3>"; */
            ?>
                    <!-- <div class="row"> -->
                    <?php /* do { */

                    if ($reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 15) {
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

                        //4.selecciona datos de liberación
                        $cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion                
                             where pro_id = '" . $reg_procesos['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos");
                        $reg_lib = mysqli_fetch_assoc($cad_lib);

                        //obtener supervisor
                        $cad_usu_sup = mysqli_query($cnx, "SELECT * FROM usuarios             
                            where usu_id = '" . $reg_pro_res['pro_supervisor'] . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos");
                        $reg_usu_sup = mysqli_fetch_assoc($cad_usu_sup);

                        //obtener operador
                        $cad_usu_op = mysqli_query($cnx, "SELECT * FROM usuarios             
                            where usu_id = '" . $reg_pro_res['pro_operador'] . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos");
                        $reg_usu_op = mysqli_fetch_assoc($cad_usu_op);

                        $cad_material = mysqli_query($cnx, "SELECT mat_nombre 
                            FROM procesos_materiales as m
                            inner join materiales as x on (m.mat_id = x.mat_id)
                            where m.pro_id = '" . $reg_procesos['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos materiales");
                        $reg_material = mysqli_fetch_assoc($cad_material);

                        //5. Selecciona los datos de tipo preparacion y material
                        $cad_tp = mysqli_query($cnx, "SELECT * FROM preparacion_tipo where pt_id = '" . $reg_pro_res['pt_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar tipos de preparacion");
                        $reg_tp = mysqli_fetch_assoc($cad_tp);

                        //selecciona las fases del tipo de proceso
                        $cad_fases = mysqli_query($cnx, "SELECT e.pe_id, e.pe_descripcion FROM preparacion_tipo_etapas as p
                             inner join preparacion_etapas as e on(p.pe_id =e.pe_id)
                           WHERE p.pt_id = '" . $reg_tp['pt_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos materiales");
                        $reg_fases = mysqli_fetch_assoc($cad_fases);
                    }

                    if ($reg_equipos['le_id'] == 10) {
                        $clase = 'class="col-xs-3 col-sm-3 col-md-3 col-lg-4 mb-3 ps-1 pe-1 indicador"';
                    } else {
                        $clase = 'class="col-xs-3 col-sm-3 col-md-3 col-lg-4 mb-3 ps-1 pe-1 indicador"';
                    }
                    ?>
                    <div <?php echo $clase; ?>>
                        <!-- color de estatus del equipo -->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-body p-2 border fw-bold" style="background: <?php echo $reg_equipos['le_color'] ?>">
                                    <!-- Si esta en estatus en cualquier excepto descompuesto y reparación, abre para captura -->
                                    <?php if ($reg_equipos['le_id'] == 9 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 15 or $reg_equipos['le_id'] == 15) {
                                        //No se que que hace esta validación aqui 
                                    ?>
                                        <?php
                                        if (($_SESSION['privilegio'] == 3 or $_SESSION['privilegio'] == 4 or $_SESSION['privilegio'] == 6) and $reg_tipo_eq['ban_almacena'] == 'N') { ?>

                                            <div class="text-center">
                                                <a href="../bitacoras/bitacora.php?id_e=<?php echo $reg_equipos['ep_id'] ?>" target="_blank" class="numero"><?php echo $reg_equipos['ep_descripcion'] . " / " . $reg_auxiliar['pro_id']; ?></a>
                                            </div>
                                        <?php } else { ?>
                                            <a href="../bitacoras/formatos/bitacora_consulta.php?id_e=<?php echo $reg_equipos['ep_id'] ?>" target="_blank" class="numero"><?php echo $reg_equipos['ep_descripcion'] ?></a>
                                        <?php } ?>
                                    <?php }
                                    /* Si esta descompuesto o en reparación */ else { ?>
                                        <a href="#" style="cursor: default;text-decoration: none;color: inherit;" class="numero"><?php echo $reg_equipos['ep_descripcion'] ?></a>
                                    <?php } ?>
                                </div>

                                <div class="card-body p-1 border" id="contenido" style="font-size: .7rem;">
                                    <span style="font-weight: bold;"> Tipo preparación:</span>
                                    <?php if ($reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {
                                        echo $reg_tp['pt_descripcion'];
                                    } ?><br><br>

                                    <span style="font-weight: bold;">Material: </span>
                                    <?php if ($reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {
                                        echo $reg_material['mat_nombre'];
                                    } ?><br>

                                    <span style="font-weight: bold;">
                                        Carga de material (<?php echo $reg_usu_sup['usu_usuario'] ?>):</span>
                                    <?php if ($reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {
                                        echo number_format($reg_pro_res['pro_total_kg'], 2) . " Kgs";
                                    } ?><br>
                                    <span style="font-weight: bold;"> Comienza carga molino (<?php echo $reg_usu_op['usu_usuario'] ?>):</span> <?php if ($reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {

                                                                                                                                                    if ($reg_pro_res['pro_fe_carga'] == '') {
                                                                                                                                                        echo "Cargandose";
                                                                                                                                                    } else {
                                                                                                                                                        echo $reg_pro_res['pro_fe_carga'] . ' ' . $reg_pro_res['pro_hr_inicio'];
                                                                                                                                                    }
                                                                                                                                                } ?><br>
                                    <span style="font-weight: bold;"> Termina carga molino:</span> <?php if ($reg_equipos['le_id'] == 11 or $reg_equipos['le_id'] == 10 or $reg_equipos['le_id'] == 15) {
                                                                                                        if ($reg_pro_res['pro_hr_fin'] == '') {
                                                                                                            echo "-";
                                                                                                        } else {
                                                                                                            echo $reg_pro_res['pro_hr_fin'];
                                                                                                        }
                                                                                                    } ?>
                                    <br><br>
                                    <!--  mostrar tabla con fases para estatus ocupado y liberado lab. -->
                                    <?php if ($reg_equipos['le_id'] == 11 || $reg_equipos['le_id'] == 15) { ?>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Fases</th>
                                                    <th>Fecha inicia</th>
                                                    <th>Hora ideal</th>
                                                    <th>Hora max</th>
                                                    <th>Fecha termina</th>
                                                    <th>Horas transcurridas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                do {

                                                    $cad_fases_d = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar as a
                                                        INNER JOIN preparacion_etapas as e on (a.pe_id = e.pe_id)
                                                        WHERE a.pro_id = '" . $reg_pro_res['pro_id'] . "' and a.pe_id = '" . $reg_fases['pe_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar detalle fases");
                                                    $reg_fases_d = mysqli_fetch_assoc($cad_fases_d);


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


                                                    if ($reg_fases_d['proa_fe_fin'] == '') {
                                                        $fe_fin = date("Y-m-d");
                                                        $hora_fin = date("H:i:s");
                                                    } else {
                                                        $fe_fin = $reg_fases_d['proa_fe_fin'];
                                                        $hora_fin = $reg_fases_d['proa_hr_fin'];
                                                    }


                                                    $tiempo_transcurrido2 = fnc_horas_insertar($reg_fases_d['proa_fe_ini'], $fe_fin, $reg_fases_d['proa_hr_ini'], $hora_fin);

                                                    if ($tiempo_transcurrido2 == '0.00') {
                                                        $color = 'style="background:#fff"';
                                                    } else if ($tiempo_transcurrido2 >= $reg_fases_d['pe_hr_ideal']) {
                                                        $color = 'style="background:#FC678D"';
                                                        echo '<span style="background:#FC678D"></span>';
                                                    } else if ($tiempo_transcurrido2 <= $reg_fases_d['pe_hr_ideal']) {
                                                        $color = 'style="background:#fff"';
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?php echo $reg_fases['pe_descripcion'] ?></td>
                                                        <td><?php if ($reg_fases_d['proa_fe_ini'] == '') {
                                                                echo "-";
                                                            } else {
                                                                echo $reg_fases_d['proa_fe_ini'] . ' ' . $reg_fases_d['proa_hr_ini'];
                                                            } ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($reg_fases_d['pe_hr_ideal'] == '') {
                                                                echo "-";
                                                            } else {
                                                                echo $reg_fases_d['pe_hr_ideal'];
                                                            } ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($reg_fases_d['pe_hr_maxima'] == '') {
                                                                echo "-";
                                                            } else {
                                                                echo $reg_fases_d['pe_hr_maxima'];
                                                            } ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($reg_fases_d['proa_fe_fin'] == '' && $reg_fases_d['proa_fe_fin'] != '') {
                                                                echo "-";
                                                            }
                                                            if ($reg_fases_d['proa_fe_ini'] == '' && $reg_fases_d['proa_fe_fin'] == '') {
                                                                //echo "<span style='color:red'>Pendiente por liberar Laboratorio</span>";
                                                                echo "-";
                                                            } else {
                                                                echo $reg_fases_d['proa_fe_fin'] . ' ' . $reg_fases_d['proa_hr_fin'];
                                                            } ?>
                                                        </td>
                                                        <td <?php echo $color ?>>
                                                            <?php
                                                            $tiempo_transcurrido = fnc_horas($reg_fases_d['proa_fe_ini'], $fe_fin, $reg_fases_d['proa_hr_ini'], $hora_fin);
                                                            if ($tiempo_transcurrido == '00:00') {
                                                                echo "-";
                                                            } else {
                                                                echo $tiempo_transcurrido;
                                                            } ?>
                                                        </td>

                                                        <!--   <td>
                                                                <?php
                                                                /* Eficiencia del Tiempo o cumplimiento del Tiempo Ideal = (hora idea/hora real proceso) * 100% */

                                                                //cumplimiento del tiempo ideal
                                                                /* $cumplimiento_tiempo_ideal = ($reg_fases_d['pe_hr_ideal'] / $tiempo_transcurrido) * 100;
                                                                    echo number_format($cumplimiento_tiempo_ideal, 2) . "%"; */
                                                                ?>
                                                            </td> -->

                                                    </tr>
                                                <?php
                                                } while ($reg_fases = mysqli_fetch_assoc($cad_fases));
                                                ?>
                                            </tbody>
                                        </table>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php /* } while ($reg_equipos = mysqli_fetch_assoc($cad_equipos)); */
                } ?>
                <!--  </div> -->
            <?php } while ($reg_equipos = mysqli_fetch_assoc($cad_equipos)); ?>
        </div>
    </div>

    <!-- EQUIPOS ALMACEN -->
    <div class="row">
        <h3>RECEPTORES</h3>
        <?php
        do {

            if ($total_reg_almacen > 0) {
        ?>
                <?php
                ?>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 mb-3 ps-1 pe-1 indicador">
                    <!-- color de estatus del equipo -->
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body p-2 border fw-bold" style="background: <?php echo $reg_eq_almacen['le_color'] ?>">
                                <!-- Si esta en estatus en cualquier excepto descompuesto y reparación, abre para captura -->
                                <?php if ($reg_eq_almacen['le_id'] == 9 or $reg_eq_almacen['le_id'] == 10 or $reg_eq_almacen['le_id'] == 11 or $reg_eq_almacen['le_id'] == 15 or $reg_eq_almacen['le_id'] == 15) {
                                    //No se que que hace esta validación aqui 
                                ?>
                                    <?php
                                    if (($_SESSION['privilegio'] == 3 or $_SESSION['privilegio'] == 4 or $_SESSION['privilegio'] == 6) and $reg_tipo_eq['ban_almacena'] == 'N') { ?>

                                        <div class="text-center">
                                            <a href="../bitacoras/bitacora.php?id_e=<?php echo $reg_eq_almacen['ep_id'] ?>" target="_blank" class="numero"><?php echo $reg_eq_almacen['ep_descripcion'] . " / " . $reg_auxiliar['pro_id']; ?></a>
                                        </div>
                                    <?php } else { ?>
                                        <a href="../bitacoras/formatos/bitacora_consulta.php?id_e=<?php echo $reg_eq_almacen['ep_id'] ?>" target="_blank" class="numero"><?php echo $reg_eq_almacen['ep_descripcion'] ?></a>
                                    <?php } ?>
                                <?php }
                                /* Si esta descompuesto o en reparación */ else { ?>
                                    <a href="#" style="cursor: default;text-decoration: none;color: inherit;" class="numero"><?php echo $reg_eq_almacen['ep_descripcion'] ?></a>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php
            } ?>
        <?php } while ($reg_eq_almacen = mysqli_fetch_assoc($cad_eq_almacen)); ?>
    </div>

</body>
<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<style>
    .table>:not(caption)>*>* {
        padding: 0.1rem 0.1rem;
        color: var(--bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)));
        background-color: var(--bs-table-bg);
        border-bottom-width: var(--bs-border-width);
        box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)));
    }
</style>

</html>
<script>
    // Obtener el elemento div por su ID
    var miDiv = document.getElementById('contenido');

    // Obtener el tamaño predeterminado
    var anchoPredeterminado = window.getComputedStyle(miDiv, null).getPropertyValue('width');
    var altoPredeterminado = window.getComputedStyle(miDiv, null).getPropertyValue('height');

    // Imprimir el tamaño predeterminado en la consola
    console.log('Ancho predeterminado:', anchoPredeterminado);
    console.log('Alto predeterminado:', altoPredeterminado);

    // Ahora puedes aplicar estos valores a otros divs según sea necesario
</script>
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