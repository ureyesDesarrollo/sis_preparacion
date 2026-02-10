<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_tarimas_almacen = mysqli_query(
        $cnx,
        "SELECT tar_id,
        DATE(tr.tar_fecha) as tar_fecha,
        tr.pro_id,
        tr.pro_id_2,
        tr.tar_fino,
        tr.tar_folio,
        tr.tar_kilos,
        tr.tar_bloom,
        tr.tar_viscosidad,
        tr.tar_ph,
        tr.tar_trans,
        tr.tar_color,
        tr.tar_par_extr,
        tr.tar_par_ind,
        tr.tar_redox,
        tr.tar_malla_30,
        tr.tar_malla_45,
        tr.tar_humedad,
        tr.tar_estatus,
        tr.cal_id,
        c.cal_descripcion,
        tr.tar_rechazado
        FROM rev_tarimas tr JOIN rev_calidad c ON tr.cal_id = c.cal_id WHERE tr.tar_estatus = 1 AND tr.tar_count_etiquetado > 0"
    );
    if (!$listado_tarimas_almacen) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_tarimas_almacen = array();

    while ($fila = mysqli_fetch_assoc($listado_tarimas_almacen)) {
        $datos_tarimas_almacen[] = $fila;
    }

    $json_tarimas_almacen = json_encode($datos_tarimas_almacen);

    echo $json_tarimas_almacen;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
