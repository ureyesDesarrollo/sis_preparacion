<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_mezclas = mysqli_query(
        $cnx,
        "SELECT m.mez_id, m.mez_folio, DATE(m.mez_fecha) as mez_fecha, m.mez_estatus,c.cal_descripcion,m.cal_id,m.mez_kilos
        FROM rev_mezcla m LEFT JOIN rev_calidad c ON m.cal_id = c.cal_id"
    );
    if (!$listado_mezclas) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_mezclas = array();

    while ($fila = mysqli_fetch_assoc($listado_mezclas)) {
        $datos_mezclas[] = $fila;
    }

    $json_mezclas = json_encode($datos_mezclas);

    echo $json_mezclas;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
