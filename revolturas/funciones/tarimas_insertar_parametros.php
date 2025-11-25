<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        extract($_POST);

        $tar_rechazado = 'A';

        // Verificación de variables vacías
        $tar_bloom = isset($tar_bloom) ? $tar_bloom : '';
        $tar_viscosidad = isset($tar_viscosidad) ? $tar_viscosidad : '';
        $tar_ph = isset($tar_ph) ? $tar_ph : '';
        $tar_trans = isset($tar_trans) ? $tar_trans : '';
        $tar_ntu = isset($tar_ntu) ? $tar_ntu : '';
        $tar_humedad = isset($tar_humedad) ? $tar_humedad : '';
        $tar_cenizas = isset($tar_cenizas) ? $tar_cenizas : '';
        $tar_ce = isset($tar_ce) ? $tar_ce : '';
        $tar_redox = isset($tar_redox) ? $tar_redox : '';
        $tar_color = isset($tar_color) ? $tar_color : '';
        //$tar_fino = isset($tar_fino) ? $tar_fino : '';
        $tar_olor = isset($tar_olor) ? $tar_olor : '';
        $tar_pe_1kg = isset($tar_pe_1kg) ? $tar_pe_1kg : '';
        $tar_par_extr = isset($tar_par_extr) ? $tar_par_extr : '';
        $tar_par_ind = isset($tar_par_ind) ? $tar_par_ind : '';
        $tar_hidratacion = isset($tar_hidratacion) ? $tar_hidratacion : '';
        $tar_porcentaje_t = isset($tar_porcentaje_t) ? $tar_porcentaje_t : '';
        $tar_coliformes = isset($tar_coliformes) ? $tar_coliformes : '';
        $tar_ecoli = isset($tar_ecoli) ? $tar_ecoli : '';
        $tar_salmonella = isset($tar_salmonella) ? $tar_salmonella : '';
        $tar_saereus = isset($tar_saereus) ? $tar_saereus : '';

        include 'tarimas_validacion.php';


        $isFino = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT tar_fino FROM rev_tarimas WHERE tar_id = '$tar_id'"))['tar_fino'];

        if ($isFino == 'F') {
            $valores_a_eliminar = ['malla_30', 'malla_45'];
            $parametros_fallidos = array_diff($parametros_fallidos, $valores_a_eliminar);
        }

        // Verificar si quedan otros parámetros fallidos después de eliminar las mallas
        if (!empty($parametros_fallidos)) {
            $tar_rechazado = $tar_rechazado; // Mantener el estado como rechazado si hay otros fallidos
        } else {
            $tar_rechazado = 'A'; // Aceptar si no hay otros parámetros fallidos
        }


        $sql = "UPDATE rev_tarimas SET 
        tar_bloom = '$tar_bloom', 
        tar_viscosidad = '$tar_viscosidad', 
        tar_ph = '$tar_ph', 
        tar_trans = '$tar_trans',
        tar_porcentaje_t = '$tar_porcentaje_t', 
        tar_ntu =  '$tar_ntu',
        tar_humedad = '$tar_humedad',
        tar_cenizas = '$tar_cenizas',
        tar_ce = '$tar_ce',
        tar_redox = '$tar_redox', 
        tar_color = '$tar_color', 
        tar_olor = '$tar_olor',
        tar_pe_1kg = '$tar_pe_1kg',
        tar_par_extr = '$tar_par_extr',
        tar_par_ind = '$tar_par_ind',
        tar_hidratacion = '$tar_hidratacion',
        tar_malla_30 = '$tar_malla_30',
        tar_malla_45 = '$tar_malla_45',
        tar_coliformes = '$tar_coliformes',
        tar_ecoli = '$tar_ecoli',
        tar_salmonella = '$tar_salmonella',
        tar_saereus = '$tar_saereus',
        tar_fe_param = '" . date("Y-m-d H:i:s") . "',
        tar_rechazado = '$tar_rechazado',
        cal_id = '$cal_id'
        WHERE tar_id = '$tar_id'";
        if (mysqli_query($cnx, $sql)) {
            if ($tar_rechazado === 'C') {
                $res = 'Parametros registrados exitosamente, algunos datos no cumplen con el mínimo establecido. Se marcará como en cuarentena.';
            } else if($tar_rechazado === 'A'){
                $res = "Parametros registrados exitosamente";
            } else {
                $res = 'Tarima con problemas de microbiologia sera rechazada';
            }
            ins_bit_acciones($_SESSION['idUsu'], 'E', $tar_id, '41');
            echo json_encode(["success" => $res, "fallidos" => $parametros_fallidos, "rechazado" => $tar_rechazado]);
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
