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
    $usu_id = $_SESSION['idUsu'];
    $ip_id = ${'hdd_id_pelambre'};
    /* $ip_fe_hr_ter_remojo = ${'txt_hora_termina_remojo4'}; */

    /*  if ($ip_fe_hr_ter_remojo == '') {
        $respuesta = array('mensaje' => "Campos vacíos");
        echo json_encode($respuesta);
        exit;
    } */

    $ip_fe_hr_ter_remojo = validar_fecha($ip_fe_hr_ter_remojo);

    for ($i = 1; $i <= 3; $i++) {
        $hdd_id = ${"hdd_id" . $i};
        $renglon = ${"txt_renglon" . $i};
        $ipe_porcentaje = valor_o_null(${"txt_porcentaje" . $i});
        $ipe_cantidad = valor_o_null(${"txt_cantidad" . $i});
        $quim_id = valor_o_null(${"hdd_id_mat" . $i});
        $ipe_horas = valor_o_null(${"txt_horas" . $i});
        $ipe_minutos = valor_o_null(${"txt_minutos" . $i});
        $ipe_fe_hr_inicio = validar_fecha(${"txt_fe_inicio" . $i});
        $ipe_fe_hr_fin = validar_fecha(${"txt_fe_final" . $i});
        $ipe_observaciones = valor_o_null(${"txt_obs" . $i});


        if ($hdd_id == '') {
            $query = "INSERT INTO inventario_pelambre_etapas_1 (
                ipe_ren, ip_id, ipe_etapa, ipe_porcentaje,ipe_cantidad, quim_id, 
                ipe_horas, ipe_minutos, ipe_fe_hr_inicio, ipe_fe_hr_fin, 
                ipe_observaciones, usu_id
            ) VALUES (
                $renglon, $ip_id, 1,$ipe_porcentaje, $ipe_cantidad, $quim_id, 
                $ipe_horas, $ipe_minutos, $ipe_fe_hr_inicio, $ipe_fe_hr_fin, 
                $ipe_observaciones, $usu_id
            )";
        }
        if ($hdd_id != '') {
            $query = "UPDATE inventario_pelambre_etapas_1 SET ipe_fe_hr_inicio = $ipe_fe_hr_inicio,ipe_fe_hr_fin = $ipe_fe_hr_fin, ipe_observaciones = $ipe_observaciones  WHERE ipe_id = '$hdd_id'";
        }



        $res = mysqli_query($cnx, $query);

        if (!$res) {
            throw new Exception("Error en la inserción: " . mysqli_error($cnx));
        } else {
            $uptade_equipo = "UPDATE equipos_preparacion SET le_id = 11 WHERE ep_id = $hdd_id_equipo";
            $resp_equipo = mysqli_query($cnx, $uptade_equipo);
			$respuesta = array('mensaje' => "Registro realizado");

        }
    }

    //echo "UPDATE inventario_pelambre SET ip_fe_hr_ter_remojo = '$txt_hora_termina_remojo' WHERE ip_id = $ip_id";
if($txt_hora_termina_remojo != '')  {
	    $query2 = "UPDATE inventario_pelambre SET ip_fe_hr_ter_remojo = '$txt_hora_termina_remojo' WHERE ip_id = $ip_id";
	  $update = mysqli_query($cnx, $query2);
    if ($update) {
        $respuesta = array('mensaje' => "Registro realizado");
    } else {
        throw new Exception("Error al actualizar: " . mysqli_error($cnx));
    }
	}
	

  

    echo json_encode($respuesta);
} catch (Exception $e) {
    echo json_encode(array('mensaje' => $e->getMessage()));
}
