<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

try {
    $listado_viscosidad = mysqli_query($cnx, "SELECT vis_id,vis_descrip,vis_min_val,vis_max_val,vis_color FROM rev_viscosidades");

    if (!$listado_viscosidad) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    while ($fila = mysqli_fetch_assoc($listado_viscosidad)) {
        $datos_viscosidad[] = $fila;
    }

    $json_viscosidad = json_encode($datos_viscosidad);

    echo $json_viscosidad;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
