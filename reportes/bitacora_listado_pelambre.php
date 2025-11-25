<?php
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx = Conectarse();
try {
    $listado_pelambre = mysqli_query($cnx, "SELECT 
    ip.inv_id,
    ip.ip_id,
    fnc_nombre_material(ip.inv_id) as material,
    ip.ep_id,
    e.ep_descripcion,
    ip.ip_fecha_envio,
    ip.ip_fecha_remojo,
    ip.ip_hora_ini_remojo,
    ip.ip_hora_ini_carga,
    ip.ip_hora_fin_carga,
    ip.usu_id,
    u.usu_nombre
    FROM 
    inventario_pelambre ip
    INNER JOIN 
    equipos_preparacion e ON ip.ep_id = e.ep_id
    INNER JOIN 
    usuarios u ON ip.usu_id = u.usu_id;");

    $total = mysqli_num_rows($listado_pelambre);

    if (!$listado_pelambre) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $datos_pelambre = array();

    while ($fila = mysqli_fetch_assoc($listado_pelambre)) {
        $datos_pelambre[] = $fila;
    }

    $json_pelambre = json_encode($datos_pelambre);

    echo $json_pelambre;
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    mysqli_close($cnx);
}
