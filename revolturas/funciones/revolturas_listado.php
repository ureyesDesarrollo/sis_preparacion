<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_revolturas = mysqli_query(
        $cnx,
        "SELECT r.rev_id, r.rev_folio, DATE(r.rev_fecha) as rev_fecha, 
        r.rev_estatus,c.cal_descripcion,r.cal_id,
        u.usu_nombre,r.rev_factura, r.rev_kilos, cl.cte_nombre, 
        r.rev_fecha_procesamiento, r.rev_fe_param, r.rev_prioritario
         FROM rev_revolturas r
         INNER JOIN usuarios u ON r.usu_id = u.usu_id
         LEFT JOIN rev_clientes cl ON r.rev_teo_cliente = cl.cte_id
         LEFT JOIN rev_calidad c ON r.cal_id = c.cal_id WHERE rev_count_etiquetado > 0"
    );
    if (!$listado_revolturas) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_revolturas = array();

    while ($fila = mysqli_fetch_assoc($listado_revolturas)) {
        $datos_revolturas[] = $fila;
    }

    $json_revolturas = json_encode($datos_revolturas);

    echo $json_revolturas;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
