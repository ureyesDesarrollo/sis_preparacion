<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_presentacion = mysqli_query($cnx, "SELECT * FROM rev_presentacion");
    if (!$listado_presentacion) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_presentacion = array();

    while ($fila = mysqli_fetch_assoc($listado_presentacion)) {
        $datos_presentacion[] = $fila;
    }

    $json_presentacion = json_encode($datos_presentacion);

    echo $json_presentacion;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
