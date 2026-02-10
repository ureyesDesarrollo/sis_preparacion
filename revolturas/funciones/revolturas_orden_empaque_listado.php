<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

try {
    $rev_id = mysqli_real_escape_string($cnx, $_POST['rev_id']);

$sql = "SELECT 
    roe.roe_id AS orden_id,
    roed.roed_id AS detalle_id, 
    roe.roe_fecha AS fecha_creacion,
    roe.roe_estado AS estado,
    rev.rev_id AS revoltura_id,
    rev.rev_kilos AS kilos_disponibles,
    pres.pres_id AS presentacion_id,
    pres.pres_descrip AS nombre_presentacion,
    pres.pres_kg AS kilos_por_unidad,
    roed.roed_cantidad AS cantidad_solicitada,
    (roed.roed_cantidad * pres.pres_kg) AS kilos_totales
    FROM rev_orden_empaque_detalle roed
    JOIN rev_orden_empaque roe ON roed.roe_id = roe.roe_id
    JOIN rev_revolturas rev ON roed.rev_id = rev.rev_id
    JOIN rev_presentacion pres ON roed.pres_id = pres.pres_id
    WHERE rev.rev_id = '$rev_id'";

    $listado_presenta = mysqli_query($cnx,$sql);
    
    $datos_presenta = array();

    while ($fila = mysqli_fetch_assoc($listado_presenta)) {
        $datos_presenta[] = $fila;
    }

    $json_presenta = json_encode($datos_presenta);

    echo $json_presenta;
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
