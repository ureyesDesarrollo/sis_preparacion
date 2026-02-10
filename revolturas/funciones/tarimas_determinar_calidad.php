<?php

/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

//Comentario
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

// Función para determinar la calidad
/*function determinarCalidad($bloom, $viscosidad, $conn)
{
    // Consulta SQL
    $sql = "SELECT * FROM rev_calidad_rango
    WHERE '$bloom' BETWEEN blo_ini AND blo_fin
    AND '$viscosidad' BETWEEN vis_ini  AND vis_fin;";

    // Ejecutar la consulta
    $result = mysqli_query($conn, $sql);

    // Verificar si se encontraron resultados
    if (mysqli_num_rows($result) > 0) {
        $res = mysqli_fetch_assoc($result);
        return $res['cal_id'];
    } else {
        return null;
    }
}*/
try {



    if (isset($_POST['tar_bloom']) && $_POST['tar_viscosidad']) {
        $bloom = $_POST['tar_bloom'];
        $viscosidad = $_POST['tar_viscosidad'];
        //$calidad_id = determinarCalidad($tar_bloom, $tar_viscosidad, $cnx);

        $sql = "SELECT cal_id FROM rev_calidad_rango WHERE '$bloom' BETWEEN blo_ini AND blo_fin AND '$viscosidad' BETWEEN vis_ini  AND vis_fin;";
        /*echo $sql;*/
        $result = mysqli_fetch_assoc(mysqli_query($cnx, $sql));

        if ($result !== null) {
            $sql_calidad = "SELECT * FROM rev_calidad WHERE cal_id = '$result[cal_id]' ";
            $calidad_result = mysqli_query($cnx, $sql_calidad);
            $calidad = mysqli_fetch_assoc($calidad_result);


            $response = ['cal_id' => $calidad['cal_id'], 'calidad' => $calidad['cal_descripcion']];
        } else {
            $response = ['calidad' => 'Sin determinar'];
        }
    } else {
        echo json_encode(array("error" => "Parámetros no válidos."));
    }
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
