<?php

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
        return "'" . (new DateTime($date))->format('Y-m-d') . "'";
    } catch (Exception $e) {
        return 'NULL';
    }
}


try {
    $fecha_descarga = validar_fecha(${'txt_fecha_descargo'});
    $kilos_totales = ${'txt_kg_totales'};
    $observaciones = valor_o_null(${'txt_observaciones'});
    $ubicacion = ${'cbxUbicacion'};
    $hdd_id_pelambre = ${'hdd_id_pelambre'};
    $hdd_id_inventario = ${'hdd_id_inventario'};
    $hdd_id_equipo = ${'hdd_id_equipo'};

    $update_inventario_pelambre = "UPDATE inventario_pelambre SET ip_fe_descarga = $fecha_descarga, ip_kg_finales = $kilos_totales, ip_observaciones = $observaciones WHERE ip_id = $hdd_id_pelambre";

    //$update_inventario = "UPDATE inventario SET ac_id = $ubicacion, inv_enviado = 6 WHERE inv_id = $hdd_id_inventario";
    /* $update_inventario = "UPDATE inventario SET inv_enviado = 6 WHERE inv_id = $hdd_id_inventario";

    $update_equipos_preparacion = "UPDATE equipos_preparacion SET le_id = 14 WHERE ep_id = $hdd_id_equipo"; */

    $res1 = mysqli_query($cnx, $update_inventario_pelambre) or die(mysqli_error($cnx) . "Error: en inventario pelambre");
   /*  $res2 = mysqli_query($cnx, $update_inventario) or die(mysqli_error($cnx) . "Error: en inventario");
    $res3 = mysqli_query($cnx, $update_equipos_preparacion) or die(mysqli_error($cnx) . "Error: en equipos preparacion"); */

    $result = array("mensaje" => "Registro realizado");
    echo json_encode($result);
} catch (Exception $e) {
    $result = array("mensaje" => $e->getMessage());
    echo json_encode($result);
} finally {
    mysqli_close($cnx);
}
