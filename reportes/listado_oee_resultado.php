<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Creado: Enero-2024*/
include "../conexion/conexion.php";
$cnx =  Conectarse();
$cadena = mysqli_query($cnx, "select pro_id, pro_total_kg from procesos where pro_fe_carga = '" . $_POST['fechaIni'] . "'") or die(mysqli_error($cnx) . "Error: en consultar 1");
$registros = mysqli_fetch_assoc($cadena);
$tot_registros = mysqli_num_rows($cadena);

$fe_filtro_ini = $_POST['fechaIni']." 00:00:00";
$fe_filtro_fin = $_POST['fechaIni']." 23:59:59";

$cadena2 = mysqli_query($cnx, "select p.pro_id, p.pt_id, TIMESTAMPDIFF(HOUR,pro_fe_sistema, pro_fe_termino) as hras, hrs_totales_capturadas as hrs_tot, pro_hrs_tot_muerto as t_muerto 
                                from procesos AS p 
                                where p.pro_fe_termino >= '" . $fe_filtro_ini . "' AND p.pro_fe_termino <= '" . $fe_filtro_fin. "' ") or die(mysqli_error($cnx) . "Error: en consultar 2");
$registros2 = mysqli_fetch_assoc($cadena2);
$tot_registros2 = mysqli_num_rows($cadena2);

$cadena3 = mysqli_query($cnx, "select p.pro_id from procesos AS p 
                                where p.pro_fe_termino >= '" . $fe_filtro_ini . "' AND p.pro_fe_termino <= '" . $fe_filtro_fin. "'") or die(mysqli_error($cnx) . "Error: en consultar 3");
$registros3 = mysqli_fetch_assoc($cadena3);
$tot_registros3 = mysqli_num_rows($cadena3);

?>

<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script src=../assets/datatable/jquery.dataTables.min.js></script>
<script src=../assets/datatable/dataTables.bootstrap5.min.js></script>

<script src="../assets/fontawesome/fontawesome.js">
</script>

<div class="col-md-10">
    <span style="font-weight:bold;font-size:16px">Eficiencia</span><br>
    <span style="font-weight:bold;font-size:12px; color:#FFCCCC;">Procesos cargados en el día</span>
    <br>
</div>
<div class="col-md-12">
    <!--  <div class="col-md-1" id="equipo">
        Kilos<br>Proceso
    </div> -->
    <?php
    if ($tot_registros > 0) {

        $flt_kg = 0;
        do { ?>
            <div class="col-md-2" id="equipo" style="width:160px"><?php
                                                $flt_kg += $registros['pro_total_kg'];
                                                echo '<a data-bs-toggle="tooltip" title="Kilos">' . $registros['pro_total_kg'] . '</a><br>' . '<span><a data-bs-toggle="tooltip" title="Proceso">' . $registros['pro_id'] . "</a></span><br>"; ?>
            </div>
    <?php } while ($registros = mysqli_fetch_assoc($cadena));

        $flt_ton = $flt_kg / 1000;
        echo "Total TON = " . number_format($flt_ton, 2);
        $flt_p_efi = number_format(($flt_ton / 90)*100, 2);
        echo "<br> <span style='font-weight: bold;font-size: 16px;'>= " . $flt_p_efi . " %</span>";
    } else {
        echo "No hay registros del día";
    }
    ?>
</div>

<br>
<div class="col-md-12" style="margin-top: 2rem;">
    <span style="font-weight:bold;font-size:16px">Disponibilidad</span>
    <br>
    <span style="font-weight:bold;font-size:12px; color:#FFCCCC;">Terminados en el día</span>
    <br>
</div>

<div class="col-md-12">
    <?php
    if ($tot_registros2 > 0) {
        $flt_dispo = $flt_t_disp = 0;
        $cont_2 = 0;
        do {

            $cad_pt = mysqli_query($cnx, "SELECT sum(e.pe_hr_maxima) as hrs_x
 	                                    FROM preparacion_tipo_etapas as p
                                        INNER JOIN preparacion_etapas as e on(p.pe_id = e.pe_id)
 	                                    WHERE p.pt_id = '" . $registros2['pt_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar las etapas");
            $reg_pt = mysqli_fetch_assoc($cad_pt);
			echo "**".$registros2['hrs_tot']."**";
		if ($registros2['hrs_tot'] != '' and $registros2['hrs_tot'] != 0){//$registros2['hrs_tot'] = 1;}

            //$res_disp = number_format((($registros2['hrs_tot'] - $registros2['t_muerto']) / $reg_pt['hrs_x'])*100, 2);
            $res_disp = number_format((($registros2['hrs_tot'] - $registros2['t_muerto']) / ($registros2['hrs_tot']))*100, 2);
		}
		else
		{$res_disp =0;}

    ?>
            <div class="col-md-2" id="equipo3" style="width:160px">
                <?php
                echo '<a data-bs-toggle="tooltip" title="HR totales sis- HR ideal">' . $registros2['hras'] . " / " . $reg_pt['hrs_x'] . " = " .  number_format($registros2['hras'] / $reg_pt['hrs_x'], 2)  . '</a><br>';
                echo '<a data-bs-toggle="tooltip" title="HR totales usu- HR ideal">(' . $registros2['hrs_tot'] . "-" . $registros2['t_muerto'] . ")/" . $registros2['hrs_tot'] . " = " . $res_disp . '</a><br>';
                echo '<span><a data-bs-toggle="tooltip" title="Proceso">' . $registros2['pro_id'] . '</a></span><br>'; ?>
            </div>
    <?php
            $flt_p_disp += $res_disp;
            $cont_2 += 1;
        } while ($registros2 = mysqli_fetch_assoc($cadena2));

        $flt_t_disp = $flt_p_disp / $cont_2;

        echo ' <div class="col-md-2">
        <span style="font-weight: bold;font-size: 16px;">= ' . ($flt_t_disp) . "%</span></div>";
    } else {
        echo "No hay registros del día";
    } ?>
</div>

<br>
<div class="col-md-12" style="margin-top: 2rem;">
    <span style="font-weight:bold;font-size:16px">Calidad</span>
    <br>
    <span style="font-weight:bold;font-size:12px; color:#FFCCCC;">Terminados en el día</span>
</div>

<div class="col-md-12" style="margin-bottom: 10rem;">
    <div class="col-md-2" id="equipo3" style="width:160px">
        PH (2.2-2.4)<br>
        CE (0-6)<br>
        Extr(85-97)<br>
        Proceso <br>
        %
    </div>
    <?php
    if ($tot_registros2 > 0) {

        $flt_p_cal = $flt_t_cal = 0;
        $cont = 0;
        do {

            $cad_lib = mysqli_query($cnx, "SELECT *
 	                                    FROM procesos_liberacion_b  
 	                                    WHERE pro_id = '" . $registros3['pro_id'] . "' ") or die(mysqli_error($cnx) . "Error: las liberaciones");
            $reg_lib = mysqli_fetch_assoc($cad_lib);

            $cad_lib_c = mysqli_query($cnx, "SELECT *
            FROM procesos_liberacion_b_cocidos  
            WHERE prol_id = '" . $reg_lib['prol_id'] . "' ") or die(mysqli_error($cnx) . "Error: las liberaciones cocidos");
            $reg_lib_c = mysqli_fetch_assoc($cad_lib_c);

    ?>
            <div class="col-md-1" id="equipo3">
                <?php
                echo '<a data-bs-toggle="tooltip" title="PH">' . $reg_lib_c['prol_cocido'] . ' </a>';
                if ($reg_lib_c['prol_cocido'] >= 2.2 and $reg_lib_c['prol_cocido'] <= 2.4) {
                    echo "(+)";
                    $str_ph = 1;
                } else {
                    echo "(-)";
                    $str_ph = 0;
                }

                echo '<br><a data-bs-toggle="tooltip" title="CE">' . $reg_lib_c['prol_ce'] . ' </a>';
                if ($reg_lib_c['prol_ce'] >= 0 and $reg_lib_c['prol_ce'] <= 6) {
                    echo "(+)";
                    $str_ce = 1;
                } else {
                    echo "(-)";
                    $str_ce = 0;
                }

                echo '<br><a data-bs-toggle="tooltip" title="EXT">' .  $reg_lib_c['prol_por_extrac'] .  ' </a>';
                if ($reg_lib_c['prol_por_extrac'] >= 85 and $reg_lib_c['prol_por_extrac'] <= 97) {
                    echo "(+)";
                    $str_ext = 1;
                } else {
                    echo "(-)";
                    $str_ext = 0;
                }

                echo '<br><span><a data-bs-toggle="tooltip" title="Proceso"> ' . $registros3['pro_id'] . '</a></span><br>';

                if ($str_ph == 1 and $str_ce == 1 and $str_ext == 1) {
                    $res_cal = '100%';
                }

                if ($str_ph == 1 and $str_ce == 1 and $str_ext == 0) {
                    $res_cal = '66.66%';
                }
                if ($str_ph == 1 and $str_ce == 0 and $str_ext == 1) {
                    $res_cal = '66.66%';
                }
                if ($str_ph == 0 and $str_ce == 1 and $str_ext == 1) {
                    $res_cal = '66.66%';
                }

                if ($str_ph == 1 and $str_ce == 0 and $str_ext == 0) {
                    $res_cal = '33.33%';
                }
                if ($str_ph == 0 and $str_ce == 0 and $str_ext == 1) {
                    $res_cal = '33.33%';
                }
                if ($str_ph == 0 and $str_ce == 1 and $str_ext == 0) {
                    $res_cal = '33.33%';
                }
				
				if ($str_ph == 0 and $str_ce == 0 and $str_ext == 0) {
                    $res_cal = '0%';
                }

                echo $res_cal;
                $flt_p_cal += $res_cal;
                $cont += 1;
                ?>
            </div>
    <?php } while ($registros3 = mysqli_fetch_assoc($cadena3));

    $flt_t_cal = $flt_p_cal / $cont;

        echo "<div class='col-md-2'><span style='font-weight: bold;font-size: 16px;'>= " . ($flt_t_cal) . "%</span></div>";
    } else {
        echo "No hay registros del día";
    }   ?>
</div>

<div class="col-md-12" style="margin-bottom: 10rem;">
    <span style='font-weight: bold;font-size: 20px;'> OEE = <?php echo number_format(($flt_t_disp * $flt_p_efi * $flt_t_cal) / 10000, 2); ?>
        <br>
        OEE = ( Eficiencia * disponibilidad * calidad ) / 10000</span>
        <br>
        <?php echo $flt_p_efi ." * ".$flt_t_disp." * ". $flt_t_cal." / 10000"; ?>
</div>

<style>
    body {
        font-size: 12.5px;
    }

    #equipo {
        border: 1px solid#e6e6e6;
        box-shadow: -3px 0px 15px #e6e6e6;
        border-radius: 3px;
        height: 4rem;
        text-align: center;
        font-weight: bold;
        margin-right: 0.5rem;
    }

    #equipo2 {
        border: 1px solid#e6e6e6;
        box-shadow: -3px 0px 15px #e6e6e6;
        border-radius: 3px;
        height: 6rem;
        text-align: center;
        font-weight: bold;
        /* margin-right: 0.5rem; */
    }

    #equipo3 {
        border: 1px solid#e6e6e6;
        box-shadow: -3px 0px 15px #e6e6e6;
        border-radius: 3px;
        height: 10rem;
        text-align: center;
        font-weight: bold;
        margin-right: 0.5rem;
    }

    span {
        font-weight: lighter;
    }

    a {
        color: #000;
    }
</style>