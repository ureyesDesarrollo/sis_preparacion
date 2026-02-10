<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

try {
    $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
    $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;


    $sql = "SELECT t.*, c.cal_descripcion, c.cal_color 
            FROM rev_tarimas t 
            LEFT JOIN rev_calidad c ON c.cal_id = t.cal_id";


    if ($fecha_inicio && $fecha_fin) {
        $sql .= " WHERE DATE(t.tar_fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    } else {
        // Si no hay fechas, retornar un array vacío
        echo json_encode([]);
        exit;
    }

    $listado_calidad = mysqli_query($cnx, $sql);

    if (!$listado_calidad) {
        echo json_encode(array('error' => 'Error en la consulta: ' . mysqli_error($cnx)));
        exit;
    }

    $datos_calidad = array();
    while ($fila = mysqli_fetch_assoc($listado_calidad)) {
        $datos_calidad[] = $fila;
    }

    echo json_encode($datos_calidad);
} catch (Exception $e) {
    echo json_encode(array('error' => $e->getMessage()));
} finally {
    mysqli_close($cnx);
}
