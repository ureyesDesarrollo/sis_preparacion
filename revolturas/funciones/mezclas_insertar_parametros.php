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

        $mez_rechazado = 'A';

        // Verificación de variables vacías
        $mez_bloom = isset($mez_bloom) ? $mez_bloom : '';
        $mez_viscosidad = isset($mez_viscosidad) ? $mez_viscosidad : '';
        $mez_ph = isset($mez_ph) ? $mez_ph : '';
        $mez_trans = isset($mez_trans) ? $mez_trans : '';
        $mez_ntu = isset($mez_ntu) ? $mez_ntu : '';
        $mez_humedad = isset($mez_humedad) ? $mez_humedad : '';
        $mez_cenizas = isset($mez_cenizas) ? $mez_cenizas : '';
        $mez_ce = isset($mez_ce) ? $mez_ce : '';
        $mez_redox = isset($mez_redox) ? $mez_redox : '';
        $mez_color = isset($mez_color) ? $mez_color : '';
        $mez_fino = isset($mez_fino) ? $mez_fino : '';
        $mez_olor = isset($mez_olor) ? $mez_olor : '';
        $mez_pe_1kg = isset($mez_pe_1kg) ? $mez_pe_1kg : '';
        $mez_par_extr = isset($mez_par_extr) ? $mez_par_extr : '';
        $mez_par_ind = isset($mez_par_ind) ? $mez_par_ind : '';
        $mez_hidratacion = isset($mez_hidratacion) ? $mez_hidratacion : '';
        $mez_porcentaje_t = isset($mez_porcentaje_t) ? $mez_porcentaje_t : '';

        include 'mezclas_validacion.php';

        //Revisa las tarimas de la mezcla
        $sql = mysqli_query($cnx, "SELECT t.* FROM rev_mezclas_tarimas as m inner join rev_tarimas as t 
        on (m.tar_id = t.tar_id) WHERE mez_id = '$mez_id'");
        $filas = mysqli_fetch_assoc($sql);

        do {
            // Respalda los valores anteriores de la tarima
            //$sql3 = "INSERT INTO rev_tarimas (tar_id, tar_bloom, tar_viscosidad, tar_ph, tar_trans, tar_porcentaje_t) VALUES (
            $sql2 = "INSERT INTO rev_tarimas_hist  VALUES (
        '$filas[tar_id]',
        '$filas[tar_color]',
        '$filas[tar_redox]',
        '$filas[tar_ph]',
        '$filas[tar_trans]',
        '$filas[tar_porcentaje_t]',
        '$filas[tar_bloom]',
        '$filas[tar_viscosidad]',
        '$filas[cal_id]',
        '$filas[tar_rendimiento]',
        '$filas[tar_olor]',
        '$filas[tar_ntu]',
        '$filas[tar_humedad]',
        '$filas[tar_cenizas]',
        '$filas[tar_ce]',
        '$filas[tar_fino]',
        '$filas[tar_pe_1kg]',
        '$filas[tar_par_extr]',
        '$filas[tar_par_ind]',
        '$filas[tar_hidratacion]',
        '$filas[tar_malla_30]',
        '$filas[tar_malla_45]',
        '" . date("Y-m-d h:i:s") . "',
        '$filas[tar_rechazado]',
        '$mez_id')
         ";
            //echo $sql2;

            mysqli_query($cnx, $sql2);

            // Actualiza los valores de la tarima anterior

            $sql3 = "UPDATE rev_tarimas SET 
        tar_bloom = '$mez_bloom', 
        tar_viscosidad = '$mez_viscosidad', 
        tar_ph = '$mez_ph', 
        tar_trans = '$mez_trans',
        tar_porcentaje_t = '$mez_porcentaje_t', 
        tar_ntu =  '$mez_ntu',
        tar_humedad = '$mez_humedad',
        tar_cenizas = '$mez_cenizas',
        tar_ce = '$mez_ce',
        tar_redox = '$mez_redox', 
        tar_color = '$mez_color', 
        tar_fino = '$mez_fino',
        tar_olor = '$mez_olor',
        tar_pe_1kg = '$mez_pe_1kg',
        tar_par_extr = '$mez_par_extr',
        tar_par_ind = '$mez_par_ind',
        tar_hidratacion = '$mez_hidratacion',
        tar_malla_30 = '$mez_malla_30',
        tar_malla_45 = '$mez_malla_45',
        tar_fe_param = '" . date("Y-m-d H:i:s") . "',
        tar_rechazado = '$mez_rechazado',
        cal_id = '$cal_id',
        tar_estatus = '1'
        WHERE tar_id = '$filas[tar_id]'";

            mysqli_query($cnx, $sql3);
        } while ($filas = mysqli_fetch_assoc($sql));

        $sql_mez = "UPDATE rev_mezcla SET cal_id = '$cal_id' WHERE mez_id = '$mez_id'";
        mysqli_query($cnx, $sql_mez);
        $res = "Parametros registrados exitosamente";
        ins_bit_acciones($_SESSION['idUsu'], 'E', $mez_id, '46');
        echo json_encode(["success" => $res, "fallidos" => $parametros_fallidos]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
