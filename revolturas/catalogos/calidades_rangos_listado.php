<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_calidad_rango = mysqli_query(
        $cnx,
        "SELECT cr.*, c.cal_descripcion, c.cal_color FROM rev_calidad_rango cr 
    JOIN rev_calidad c ON c.cal_id = cr.cal_id"
    );

    if (!$listado_calidad_rango) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_calidad_rango = array();

    while ($fila = mysqli_fetch_assoc($listado_calidad_rango)) {
        $datos_calidad_rango[] = $fila;
    }

    $json_calidad_rango = json_encode($datos_calidad_rango);

    echo $json_calidad_rango;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
