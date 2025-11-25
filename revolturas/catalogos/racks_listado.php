<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_racks = mysqli_query(
        $cnx,
        "SELECT * FROM rev_racks"
    );
    if (!$listado_racks) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_racks = array();

    while ($fila = mysqli_fetch_assoc($listado_racks)) {
        $datos_racks[] = $fila;
    }

    $json_racks = json_encode($datos_racks);

    echo $json_racks;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
