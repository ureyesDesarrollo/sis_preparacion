<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();
try {
    /* $query = "SELECT pro_id, 
    ROUND(tar_rendimiento * 100, 2) AS rendimiento, 
    @tarimas := COUNT(tar_id) AS tarimas,
    ROUND(SUM(tar_viscosidad) / @tarimas, 2) AS vis,
    ROUND(SUM(tar_bloom) / @tarimas, 2) AS bloom,
    ROUND(SUM(tar_ph) / @tarimas, 2) AS ph,
    ROUND(SUM(tar_color) / @tarimas, 2) AS color,
    ROUND(SUM(tar_olor) / @tarimas, 2) AS olor,
    ROUND(SUM(tar_redox) / @tarimas, 2) AS redox,
    ROUND(SUM(tar_humedad) / @tarimas, 2) AS humedad,
    ROUND(SUM(tar_malla_30) / @tarimas, 2) AS malla_30,
    ROUND(SUM(tar_malla_45) / @tarimas, 2) AS malla_45
    FROM rev_tarimas GROUP BY pro_id ORDER BY pro_id ASC;"; */

    $query = "SELECT t.pro_id,
    ROUND(tar_rendimiento * 100, 2) AS rendimiento,
    COUNT(tar_id) AS tarimas,
    ROUND(AVG(tar_viscosidad), 2) AS vis,
    ROUND(AVG(tar_bloom), 2) AS bloom,
    ROUND(AVG(tar_ph), 2) AS ph,
    ROUND(AVG(tar_color), 2) AS color,
    ROUND(AVG(tar_olor), 2) AS olor,
    ROUND(AVG(tar_redox), 2) AS redox,
    ROUND(AVG(tar_humedad), 2) AS humedad,
    ROUND(AVG(tar_malla_30), 2) AS malla_30,
    ROUND(AVG(tar_malla_45), 2) AS malla_45, 
    fnc_bd_material(t.pro_id) as nom
    FROM rev_tarimas as t
    GROUP BY t.pro_id ORDER BY t.pro_id ASC";
    $consulta = mysqli_query($cnx, $query);
    if (!$consulta) {
        die("Error al obtener procesos: " . mysqli_error($cnx));
    }

    $datos = array();
    while ($fila = mysqli_fetch_assoc($consulta)) {
        $datos[] = $fila;
    }
    echo json_encode($datos);
} catch (Exception $e) {
    echo json_encode($e->getMessage());
}
