<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_calidad = mysqli_query($cnx, "SELECT * FROM rev_calidad");
    if (!$listado_calidad) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_calidad = array();

    while ($fila = mysqli_fetch_assoc($listado_calidad)) {
        $datos_calidad[] = $fila;
    }

    $json_calidad = json_encode($datos_calidad);

    echo $json_calidad;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
