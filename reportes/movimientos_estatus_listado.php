<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx =  Conectarse();

$cad = mysqli_query($cnx, "SELECT b.*,fnc_estatus_listado(b.bce_est_actual) as estatus_actual, fnc_estatus_listado(b.bce_est_nuevo) as estatus_nuevo,  u.usu_usuario,e.ep_descripcion
FROM bitacora_cambio_estatus as b 
INNER JOIN usuarios as u on(b.usu_id = u.usu_id)
INNER JOIN equipos_preparacion as e on(b.ep_id = e.ep_id)
where bce_fecha > '2023-01-01' ");
$tot = mysqli_num_rows($cad);

$bitacora = array();
while ($reg_bitacora = mysqli_fetch_assoc($cad)) {
    $bitacora[] = [
        'bce_id' => $reg_bitacora['bce_id'],
        'bce_fecha' => $reg_bitacora['bce_fecha'],
        'estatus_actual' => $reg_bitacora['estatus_actual'],
        'estatus_nuevo' => $reg_bitacora['estatus_nuevo'],
        'usu_id' => $reg_bitacora['usu_id'],
        'usu_usuario' => $reg_bitacora['usu_usuario'],
        'ep_id' => $reg_bitacora['ep_id'],
        'ep_descripcion' => $reg_bitacora['ep_descripcion'],
        'bce_ot' => $reg_bitacora['bce_ot'],
        'bce_descripcion' => $reg_bitacora['bce_descripcion'],
    ];
}


// Cerrar la conexi贸n a la base de datos
mysqli_close($cnx);

// Devolver el objeto JSON
echo json_encode($bitacora);
