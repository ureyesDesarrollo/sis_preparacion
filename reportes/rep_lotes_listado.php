<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx =  Conectarse();

// Ejecutar la consulta SQL
/* $cad = mysqli_query($cnx, "SELECT l.*, u.usu_usuario, pe.pro_id   
        FROM lotes_anio as l 
        INNER JOIN usuarios as u on(l.usu_id = u.usu_id)
        inner join procesos_agrupados as a on (l.lote_id = a.lote_id)
        inner join procesos_equipos as pe on(a.pro_id = pe.pro_id)
        WHERE pe.pe_ban_activo = 1
        ORDER BY lote_fecha, lote_hora DESC
"); */
$cad = mysqli_query($cnx, "SELECT l.*, u.usu_usuario, a.pro_id, l.lote_rendimiento
        FROM lotes_anio as l 
        INNER JOIN usuarios as u on(l.usu_id = u.usu_id)
        inner join procesos_agrupados as a on(l.lote_id = a.lote_id)
        ORDER BY lote_fecha, lote_hora DESC
");
$tot = mysqli_num_rows($cad);

// Convertir el resultado de la consulta a un objeto JSON
$bitacora = array();
while ($reg_bitacora = mysqli_fetch_assoc($cad)) {
    $bitacora[] = $reg_bitacora;
}


// Cerrar la conexión a la base de datos
mysqli_close($cnx);

// Devolver el objeto JSON
echo json_encode($bitacora);
