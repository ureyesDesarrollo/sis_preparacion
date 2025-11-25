<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_parametros = mysqli_query(
        $cnx,
        "SELECT * FROM rev_parametros ORDER BY rp_parametro ASC"
    );
    if (!$listado_parametros) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_parametros = array();

    while ($fila = mysqli_fetch_assoc($listado_parametros)) {
        $datos_parametros[] = $fila;
    }

    $json_parametros = json_encode($datos_parametros);

    echo $json_parametros;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
