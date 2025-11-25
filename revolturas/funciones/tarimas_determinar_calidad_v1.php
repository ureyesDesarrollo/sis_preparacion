<?php

/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {

    $tar_bloom = isset($_POST['tar_bloom']) ? $_POST['tar_bloom'] : 0;
    $tar_viscosidad = isset($_POST['tar_viscosidad']) ? $_POST['tar_viscosidad'] : 0;

    $calidad_id = '0';

    // Consulta para determinar la calidad basada en el rango de bloom
    if ($tar_bloom !== 0) {
        $sql_bloom = "SELECT cal_id FROM rev_calidad_rango 
                      WHERE $tar_bloom BETWEEN blo_ini AND blo_fin";

        $resultado_bloom = mysqli_query($cnx, $sql_bloom);

        if (mysqli_num_rows($resultado_bloom) > 0) {
            $row_bloom = mysqli_fetch_assoc($resultado_bloom);
            $calidad_id = $row_bloom['cal_id'];
        } else {
            // Verificar si está fuera de los rangos por ser menor o mayor
            $sql_bloom_min = "SELECT MIN(blo_ini) as min_bloom FROM rev_calidad_rango";
            $resultado_bloom_min = mysqli_query($cnx, $sql_bloom_min);
            $min_bloom = mysqli_fetch_assoc($resultado_bloom_min)['min_bloom'];

            $sql_bloom_max = "SELECT MAX(blo_fin) as max_bloom FROM rev_calidad_rango";
            $resultado_bloom_max = mysqli_query($cnx, $sql_bloom_max);
            $max_bloom = mysqli_fetch_assoc($resultado_bloom_max)['max_bloom'];

            if ($tar_bloom < $min_bloom) {
                $calidad_id = '6'; //verde
            } elseif ($tar_bloom > $max_bloom) {
                $calidad_id = '3'; // Azul
            }
        }
    }

    // Consulta para determinar la calidad basada en el rango de viscosidad
    if ($tar_viscosidad !== 0 && $tar_viscosidad !== '') {
        // Cambiar la lógica para que se adapte a vis_ini mayor que vis_fin
        $sql_viscosidad = "SELECT cal_id FROM rev_calidad_rango 
                           WHERE ($tar_viscosidad >= vis_fin AND $tar_viscosidad <= vis_ini)";

        $resultado_viscosidad = mysqli_query($cnx, $sql_viscosidad);

        if (mysqli_num_rows($resultado_viscosidad) > 0) {
            $row_viscosidad = mysqli_fetch_assoc($resultado_viscosidad);
            $calidad_id = $row_viscosidad['cal_id'];
        } else {
            // Verificar si está fuera de los rangos por ser menor o mayor
            $sql_viscosidad_min = "SELECT MIN(vis_fin) as min_vis FROM rev_calidad_rango";
            $resultado_viscosidad_min = mysqli_query($cnx, $sql_viscosidad_min);
            $min_vis = mysqli_fetch_assoc($resultado_viscosidad_min)['min_vis'];

            $sql_viscosidad_max = "SELECT MAX(vis_ini) as max_vis FROM rev_calidad_rango";
            $resultado_viscosidad_max = mysqli_query($cnx, $sql_viscosidad_max);
            $max_vis = mysqli_fetch_assoc($resultado_viscosidad_max)['max_vis'];

            if ($tar_viscosidad < $min_vis) {
                $calidad_id = '6'; //Verde
            } elseif ($tar_viscosidad > $max_vis) {
                $calidad_id = '3'; //Azul
            }
        }
    }

    if ($calidad_id !== '0') {
        $sql_calidad = "SELECT * FROM rev_calidad WHERE cal_id = '$calidad_id'";
        $calidad_result = mysqli_query($cnx, $sql_calidad);
        $calidad = mysqli_fetch_assoc($calidad_result);

        $response = ['cal_id' => $calidad['cal_id'],'calidad' => $calidad['cal_descripcion']];
    } else {
        $response = ['calidad' => 'Sin determinar'];
    }

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
?>
