<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../funciones/funciones.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    $listado_recetas = mysqli_query($cnx, "SELECT 
    c.cte_nombre,
    rre.rre_descripcion,
    rre.rre_id,
    rre.rre_estatus
    FROM rev_receta rre
    INNER JOIN rev_clientes c ON rre.cte_id = c.cte_id");

    if (!$listado_recetas) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }


    $datos_recetas = array();

    while ($fila = mysqli_fetch_assoc($listado_recetas)) {
        $datos_recetas[] = $fila;
    }

    $json_recetas = json_encode($datos_recetas);
    echo $json_recetas;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    mysqli_close($cnx);
}
