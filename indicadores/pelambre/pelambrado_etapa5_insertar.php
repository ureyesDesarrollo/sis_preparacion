<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: info@ccaconsultoresti.com */
/* Actualizado: Diciembre-2023 */

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();
extract($_POST);

function valor_o_null($variable)
{
    return ($variable == '') ? 'NULL' : "'$variable'";
}

function validar_fecha($date)
{
    if ($date == '') {
        return 'NULL';
    }
    try {
        return "'" . (new DateTime($date))->format('Y-m-d H:i:s') . "'";
    } catch (Exception $e) {
        return 'NULL';
    }
}

try {
    $ban = '';
    $usu_id = $_SESSION['idUsu'];
    $ip_id = ${'hdd_id_pelambre'};

    for ($i = 1; $i <= 1; $i++) {
        $hdd_id = ${"hdd_id" . $i};
        $renglon = ${"txt_renglon" . $i};
        $ipe_fe_inicio = valor_o_null(${"txt_fe_ini_bla" . $i});
        $ipe_hr_inicio  = valor_o_null(${"txt_hora_ini_bla" . $i});
        $ipe_hr_fin = valor_o_null(${"txt_hora_fin_bla" . $i});
        $ipe_ph = valor_o_null(${"txt_ph_bla" . $i});
        $ipe_ce = valor_o_null(${"txt_ce_bla" . $i});
        $ipe_redox = valor_o_null(${"txt_redox_bla" . $i});

        if ($hdd_id == '') {
            $query = "INSERT INTO inventario_pelambre_etapas_2 (
                ipe_ren, ip_id, ipe_etapa, ipe_fe_inicio,ipe_hr_inicio, ipe_hr_fin, 
                ipe_ph, ipe_ce,ipe_redox,usu_id
            ) VALUES (
                $renglon, $ip_id, 5,$ipe_fe_inicio, $ipe_hr_inicio, $ipe_hr_fin, 
                $ipe_ph, $ipe_ce,$ipe_redox, $usu_id
            )";
        }
        if ($hdd_id != '') {
            $query = "UPDATE inventario_pelambre_etapas_2 SET ipe_fe_inicio = $ipe_fe_inicio,ipe_hr_inicio = $ipe_hr_inicio, ipe_hr_fin = $ipe_hr_fin, ipe_ph = $ipe_ph,ipe_ce = $ipe_ce,ipe_redox = $ipe_redox WHERE ipe_id = '$hdd_id'";
        }

        $res = mysqli_query($cnx, $query);

        if (!$res) {
            throw new Exception("Error en la inserción: " . mysqli_error($cnx));
        }
    }

    $horas_totales = valor_o_null($txt_horas_tot_bla1);
    $query2 = "UPDATE inventario_pelambre SET ip_hrs_totales = $horas_totales WHERE ip_id = $ip_id";

    $update = mysqli_query($cnx, $query2);
    if ($update) {
        $respuesta = array('mensaje' => "Registro realizado");
    } else {
        throw new Exception("Error al actualizar: " . mysqli_error($cnx));
    }

    echo json_encode($respuesta);
} catch (Exception $e) {
    echo json_encode(array('mensaje' => $e->getMessage()));
}
