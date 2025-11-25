<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

//Obtener proceso
try {
    $listado_procesos = mysqli_query(
        $cnx,
        "SELECT l.lote_id, a.pro_id, l.lote_folio,a.pro_id_pa
        FROM lotes_anio as l
        inner join procesos_agrupados as a on(l.lote_id = a.lote_id)
        where l.lote_estatus = 2
        ORDER BY lote_fecha, lote_hora asc"
    );

    if (!$listado_procesos) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_procesos = array();

    while ($fila = mysqli_fetch_assoc($listado_procesos)) {
        $datos_procesos[] = $fila;
    }

    $json_procesos = json_encode($datos_procesos);

    echo $json_procesos;
} catch (Exception $e) {
    echo json_decode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
