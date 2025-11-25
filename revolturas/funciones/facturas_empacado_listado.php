<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
include "../utils/funciones.php";
$cnx =  Conectarse();
try {
    $listado_empacado = mysqli_query($cnx, "SELECT 
    rev.rev_folio AS revoltura,
    rev.rev_id,
    rr.rr_id,
    pres.pres_descrip,
    rr.rr_ext_inicial,
    rr.rr_ext_real,
    pres.pres_kg,
    pres.pres_id,
    cal.cal_id 
FROM 
    rev_revolturas_pt rr
INNER JOIN 
    rev_revolturas rev ON rev.rev_id = rr.rev_id
INNER JOIN 
    rev_calidad cal ON cal.cal_id = rev.cal_id
INNER JOIN 
    rev_presentacion pres ON pres.pres_id = rr.pres_id
LEFT JOIN
    rev_orden_embarque_detalle det ON det.rr_id = rr.rr_id
WHERE  
    rev.rev_count_etiquetado > 0
    AND rr.rr_ext_real > 0
GROUP BY
    rev.rev_folio, rev.rev_id, rr.rr_id, pres.pres_descrip, rr.rr_ext_inicial, rr.rr_ext_real, pres.pres_kg, pres.pres_id
ORDER BY 
    rev.rev_folio DESC
");

    $calidad = '';

    $datos = array();

    while ($fila = mysqli_fetch_assoc($listado_empacado)) {
        
        $fila['calidad'] = obtenerBloomPorCalidad($fila['cal_id']);
        $datos[] = $fila;
    }

    $json_empacado = json_encode($datos);

    echo $json_empacado;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
