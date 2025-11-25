<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx =  Conectarse();

// Ejecutar la consulta SQL
$cad_equipos = mysqli_query($cnx, "SELECT qa.*,
                                    u.usu_nombre,u.usu_usuario,q.quimico_descripcion,m.um_descripcion 
                                    FROM quimicos_almacen as qa
                                    inner join usuarios as u on(qa.usu_id = u.usu_id)
                                    inner join quimicos as q on(qa.quim_id = q.quimico_id)
                                    inner join unidades_medida as m on(qa.um_id = m.um_id)");
$tot = mysqli_num_rows($cad_equipos);

// Convertir el resultado de la consulta a un objeto JSON
$equipos = array();
while ($reg_equipos = mysqli_fetch_assoc($cad_equipos)) {
    $equipos[] = $reg_equipos;
}


// Cerrar la conexión a la base de datos
mysqli_close($cnx);

// Devolver el objeto JSON
echo json_encode($equipos);
?>