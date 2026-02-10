<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        extract($_POST);

        $rev_rechazado = 'A';

        // Verificación de variables vacías
        $rev_bloom = isset($rev_bloom) ? $rev_bloom : '';
        $rev_viscosidad = isset($rev_viscosidad) ? $rev_viscosidad : '';
        $rev_ph = isset($rev_ph) ? $rev_ph : '';
        $rev_trans = isset($rev_trans) ? $rev_trans : '';
        $rev_ntu = isset($rev_ntu) ? $rev_ntu : '';
        $rev_humedad = isset($rev_humedad) ? $rev_humedad : '';
        $rev_cenizas = isset($rev_cenizas) ? $rev_cenizas : '';
        $rev_ce = isset($rev_ce) ? $rev_ce : '';
        $rev_redox = isset($rev_redox) ? $rev_redox : '';
        $rev_color = isset($rev_color) ? $rev_color : '';
        $rev_fino = isset($rev_fino) ? $rev_fino : '';
        $rev_olor = isset($rev_olor) ? $rev_olor : '';
        $rev_pe_1kg = isset($rev_pe_1kg) ? $rev_pe_1kg : '';
        $rev_par_extr = isset($rev_par_extr) ? $rev_par_extr : '';
        $rev_par_ind = isset($rev_par_ind) ? $rev_par_ind : '';
        $rev_hidratacion = isset($rev_hidratacion) ? $rev_hidratacion : '';
        $rev_porcentaje_t = isset($rev_porcentaje_t) ? $rev_porcentaje_t : '';

        include 'revolturas_validacion.php';

        $sql = "UPDATE rev_revolturas SET 
        rev_bloom = '$rev_bloom', 
        rev_viscosidad = '$rev_viscosidad', 
        rev_ph = '$rev_ph', 
        rev_trans = '$rev_trans',
        rev_porcentaje_t = '$rev_porcentaje_t', 
        rev_ntu =  '$rev_ntu',
        rev_humedad = '$rev_humedad',
        rev_cenizas = '$rev_cenizas',
        rev_ce = '$rev_ce',
        rev_redox = '$rev_redox', 
        rev_color = '$rev_color', 
        rev_olor = '$rev_olor',
        rev_pe_1kg = '$rev_pe_1kg',
        rev_par_extr = '$rev_par_extr',
        rev_par_ind = '$rev_par_ind',
        rev_hidratacion = '$rev_hidratacion',
        rev_malla_30 = '$rev_malla_30',
        rev_malla_45 = '$rev_malla_45',
        rev_malla_60 = '$rev_malla_60',
        rev_malla_100 = '$rev_malla_100',
        rev_malla_200 = '$rev_malla_200',
        rev_malla_base = '$rev_malla_base',
        rev_fe_param = '" . date("Y-m-d H:i:s") . "',
        rev_rechazado = '$rev_rechazado',
        cal_id = '$cal_id'
        WHERE rev_id = '$rev_id'";
        if (mysqli_query($cnx, $sql)) {
            if ($rev_rechazado === 'R') {
                $res = 'Parametros registrados exitosamente, algunos datos no cumplen con el mínimo establecido. Se marcará como rechazado.';
            } else {
                $res = "Parametros registrados exitosamente";
            }
            ins_bit_acciones($_SESSION['idUsu'], 'E', $rev_id, '46');
            echo json_encode(["success" => $res, "fallidos" => $parametros_fallidos]);
        } else {
            $res = "Error en la actualización: " . mysqli_error($cnx);
            echo json_encode(["error" => $res]);
        }
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
