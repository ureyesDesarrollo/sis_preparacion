<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx = Conectarse();

$cad_equipos = mysqli_query($cnx, "SELECT SQL_CALC_FOUND_ROWS p.pro_id, u.usu_usuario as ope, x.usu_usuario as sup, t.pt_descripcion, p.pro_total_kg, p.pro_fe_carga, p.pro_hr_inicio, p.pro_hr_fin, p.pro_estatus, p.pt_id,IFNULL(hrs_totales_capturadas,0) AS hrs_totales_capturadas, IFNULL(p.hrs_totales_calculadas,0) AS hrs_totales_calculadas
FROM  procesos as p  
inner join procesos_equipos as e on(p.pro_id = e.pro_id)
inner join usuarios as u on(p.pro_operador = u.usu_id)
inner join usuarios as x on(p.pro_supervisor =  x.usu_id)
inner join preparacion_tipo as t on(p.pt_id  = t.pt_id)
where pro_fe_carga > '2023-10-10'
GROUP BY e.pro_id ");
$tot = mysqli_num_rows($cad_equipos);

$equipos = array();

while ($reg_equipos = mysqli_fetch_assoc($cad_equipos)) {
    $cad_material = mysqli_query($cnx, "SELECT * FROM procesos_materiales as pm 
    inner join materiales as m on(pm.mat_id = m.mt_id)
    WHERE pro_id = '" . $reg_equipos['pro_id'] . "'");

    // Elimina las líneas relacionadas con la consulta cad_tiempos

    // Agregar el registro de equipos al arreglo final
    $equipos[] = $reg_equipos;
}

// Convertir el arreglo $equipos a formato JSON
$json_resultado = json_encode($equipos);

// Imprimir o devolver el resultado JSON
echo $json_resultado;

// Cerrar la conexión a la base de datos
mysqli_close($cnx);
