<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_tarimas = mysqli_query(
        $cnx,
        "SELECT 
    tr.tar_id,
    tr.tar_folio,
    tr.tar_fecha,
    tr.tar_kilos,
    tr.pro_id,
    tr.tar_fino,
    tr.tar_estatus,
    tr.cal_id,
    tr.tar_rendimiento,
    tr.tar_rechazado,
    u.usu_nombre,
    c.cal_descripcion,
    tr.tar_count_etiquetado,
    tr.pro_id_2,
    np.niv_codigo,
    np.niv_id,
    r.rac_descripcion,
    (SELECT lote_estatus FROM lotes_anio WHERE lote_id = (SELECT lote_id FROM procesos_agrupados WHERE pro_id = tr.pro_id)) AS lote_estatus
FROM 
    rev_tarimas tr
INNER JOIN 
    usuarios u ON tr.usu_id = u.usu_id
LEFT JOIN 
    rev_nivel_posicion np ON tr.niv_id = np.niv_id
LEFT JOIN rev_racks r ON np.rac_id = r.rac_id
LEFT JOIN 
    rev_calidad c ON c.cal_id = tr.cal_id"
    );
    if (!$listado_tarimas) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_tarimas = array();

    while ($fila = mysqli_fetch_assoc($listado_tarimas)) {
        $datos_tarimas[] = $fila;
    }

    $json_tarimas = json_encode($datos_tarimas);

    echo $json_tarimas;
} catch (Exception $e) {
    echo json_decode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
