<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";


$cnx = Conectarse();

try {
    $listado_clientes = mysqli_query($cnx, "SELECT * FROM rev_clientes ORDER BY cte_nombre ASC");
    if (!$listado_clientes) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_clientes = array();

    while ($fila = mysqli_fetch_assoc($listado_clientes)) {
        $datos_clientes[] = $fila;
    }

    $json_clientes = json_encode($datos_clientes);

    echo $json_clientes;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}