<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

try {
    $listado_bloom = mysqli_query($cnx, "SELECT blo_id,blo_ini,blo_fin,blo_etiqueta, blo_estatus FROM rev_bloom");

    if (!$listado_bloom) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    while ($fila = mysqli_fetch_assoc($listado_bloom)) {
        $datos_bloom[] = $fila;
    }

    $json_bloom = json_encode($datos_bloom);

    echo $json_bloom;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
