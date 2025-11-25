<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
require "../../alertas/PHPMailer/Exception.php";
require "../../alertas/PHPMailer/PHPMailer.php";
require "../../alertas/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$cnx = Conectarse();
extract($_POST);
$sql_usu = mysqli_query($cnx, "SELECT * FROM usuarios WHERE usu_id = '" . $_SESSION['idUsu'] . "'") or die(mysqli_error($cnx) . "Error: en consultar usuarios");
$reg_usu = mysqli_fetch_assoc($sql_usu);

if ($txtFeIni != '' and $txtHrIni != '' and $txtTemp != '') {
    mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 1");

    mysqli_query($cnx, "INSERT INTO procesos_fase_7b_g(pro_id, pe_id, pfg7_temp_ag, pfg7_acido_diluido,pfg7_temp,  pfg7_acido, pfg7_norm, pfg7_ph, pfg7_ce, usu_id) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtTemp', '$cbxDiluido','$txtTemp2', '$txtAcido', '$txtNorm',  '$txtPh', '$txtCe', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 2");

    $respuesta = array('mensaje' => "Fase 7 agregada");
} else {

    for ($i = 1; $i <= 35; $i++) {
        $txtRen = ${"txtRen" . $i};
        $txtAcidoF = ${"txtAcidoF" . $i};
        $txtPhF = ${"txtPhF" . $i};
        $txtCeF = ${"txtCeF" . $i};
        $txtTempF = ${"txtTempF" . $i};
        $txtNormF = ${"txtNormF" . $i};

        if ($txtTempF == '' || !isset($txtTempF)) {
            $temp_ren = 0;
        } else {
            $temp_ren = $txtTempF;
        }
        if ($txtAcidoF == '' || !isset($txtAcidoF)) {
            $acido_ren = 0;
        } else {
            $acido_ren = $txtAcidoF;
        }


        if ($txtPhF == '' || !isset($txtPhF)) {
            $ph_ren = 0;
        } else {
            $ph_ren = $txtPhF;
        }


        if (isset($acido_ren) && isset($ph_ren) && isset($txtCeF) && isset($temp_ren) && isset($txtNormF)) {

            /* echo "TEMPERATURA". */
            $temp = $temp_ren;/* ."<br>"; */
            $acido = $acido_ren;
            $ph = $ph_ren;
            if ($txtCeF != '' and $txtNormF != '') {
                mysqli_query($cnx, "INSERT INTO procesos_fase_7b_d(pfg7_id, pfd7_ren, pfd7_acido, pfd7_ph, pfd7_ce, pfd7_temp, pfd7_norm, usu_id, pfd7_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$acido', '$ph', '$txtCeF', '$temp', '$txtNormF','" . $_SESSION['idUsu'] . "', SYSDATE() ) ") or die(mysqli_error($cnx) . " Error al insertar " . $i);
                $strMsj = "Se agrego el renglon " . $i;

                //fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $ph, $_SESSION['idUsu'], $lavador, $paleto, 'R', 'N', $txtNormF);
            }
        }
    }



    if ($txtHrTotales1 != '' and $txtFeTermA != '' and $txtHrTermA != '') {
        mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_hr_totales = '$txtHrTotales1', pfg7_fe_fin = '$txtFeTermA', pfg7_hr_fin = '$txtHrTermA', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

        $strMsj = "Fase 7 actualizada - A";
    }

    /*   if ($txtHrIniC != '' and $txtPhR1 != '' and $txtPhR2 != '') {
        mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_hr_ini_co = '$txtHrIniC', pfg7_agua_ph = '$txtPhR1', pfg7_cocido_ph = '$txtPhR2', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

        //$strMsj = "Fase 7 actualizada - B";
    }
 */
    if ($txtPhR1 != '' and $txtCeR1 != '' and $txtTemR1 != '') {
        mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_agua_ph = '$txtPhR1',pfg7_agua_ce = '$txtCeR1',pfg7_tem_final = '$txtTemR1' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

        $strMsj = "Fase 7 actualizada - C";
    }

    if ($cbxAgua != '') {
        mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET taa_id = '$cbxAgua'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

        //$strMsj = "Fase 7 actualizada - D";
    }

    if ($txtHrsReales != '') {
        mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_horas_reales = '$txtHrsReales'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

        $strMsj = "Fase 7 actualizada - E";
    }

    if ($txaObservaciones != '') {
        mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_observaciones = '$txaObservaciones', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");
    }

    if ($strMsj != '') {
        $respuesta = array('mensaje' => $strMsj);
    } else {
        if ($txtFeTerm != '') {
            mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

            /*mysqli_query($cnx, "UPDATE procesos_fase_5b_g SET pfg5_cocido_ph = '$txtCocido', pfg5_ph_agua = '$txtPhAgua', pfg5_ce_agua = '$txtCeAgua', pfg5_agua_a = '$txtAguaA' WHERE pfg5_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 4g");*/

            $strMsj = "Fase 7 actualizada";
        } else {
            if ($txtHrTotales != '' and $reg_usu['up_id'] == '6') {
                mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ph) VALUES('" . $_SESSION['idUsu'] . "', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '0') ") or die(mysqli_error($cnx) . " Error al insertar L");

                //fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhLib, $_SESSION['idUsu'], $lavador, $paleto, 'L', 'Hr', $txtHrTotales);
                /*fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhLib, $_SESSION['idUsu'], $lavador, $paleto, 'L');
				fnc_alertas_v2($hdd_pe_id, 'Hr', $hdd_pro_id, $txtHrTotales, $_SESSION['idUsu'], $lavador, $paleto, 'L');*/

                $strMsj = "Fase 7 parametros capturados";
            } else {
                $strMsj = "Esta vacio";
            }
        }

        $respuesta = array('mensaje' => $strMsj);
    }
}

echo json_encode($respuesta);
