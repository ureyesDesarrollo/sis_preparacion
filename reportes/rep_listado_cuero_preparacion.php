<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx =  Conectarse();
extract($_POST);
// Ejecutar la consulta SQL

$str_filtro = '';

if($fecha_inicio != '') {$str_filtro .= " and  p.pro_fe_carga >= '$fecha_inicio'";}
if($fecha_fin != '') {$str_filtro .= " and p.pro_fe_carga <= '$fecha_fin'";}
if($hora_inicio != '') {$hora_inicio = $hora_inicio.':00'; $str_filtro .= " and p.pro_hr_inicio >= '$hora_inicio'";}
if($hora_fin != '') {$hora_fin = $hora_fin.':00';$str_filtro .= " and p.pro_hr_inicio < '$hora_fin'";}

/* if ($hora_inicio != '') {
    $filtro_inicio = "(p.pro_fe_carga >= '$fecha_inicio' and pro_hr_inicio >= '$hora_inicio')";
} else {
    $filtro_inicio = "p.pro_fe_carga >= '$fecha_inicio'";
}

if ($hora_fin != '') {
    $filtro_fin = " and (p.pro_fe_carga <= '$fecha_fin' and pro_hr_inicio <= '$hora_fin')";
} else {
    $filtro_fin = " and p.pro_fe_carga <= '$fecha_fin'";
}
 */
/* echo "SELECT p.pro_id, e.ep_descripcion, SUM(pm.pma_kg) as cantidad, m.mat_nombre,m.mat_id,p.pro_fe_carga,p.pro_hr_inicio 
FROM procesos as p 
inner join procesos_equipos as d on(p.pro_id = d.pro_id)
inner join equipos_preparacion as e on(d.ep_id = e.ep_id) 
inner join procesos_materiales as pm on(p.pro_id = pm.pro_id) 
inner join materiales as m on(pm.mat_id = m.mat_id) 
WHERE (p.pro_fe_carga >= '$fecha_inicio' and p.pro_hr_inicio >= '$hora_inicio:00') 
and (p.pro_fe_carga <= '$fecha_fin' and p.pro_hr_inicio >= '$hora_fin:00')
GROUP BY p.pro_id
<br><br>"; */
/* echo "SELECT p.pro_id, e.ep_descripcion, SUM(pm.pma_kg) as cantidad, m.mat_nombre, m.mat_id, p.pro_fe_carga, p.pro_hr_inicio 
FROM procesos as p 
INNER JOIN procesos_equipos as d ON p.pro_id = d.pro_id 
INNER JOIN equipos_preparacion as e ON d.ep_id = e.ep_id 
INNER JOIN procesos_materiales as pm ON p.pro_id = pm.pro_id 
INNER JOIN materiales as m ON pm.mat_id = m.mat_id 
WHERE 
(p.pro_fe_carga = '$fecha_inicio' AND p.pro_hr_inicio >= '$hora_inicio') OR
(p.pro_fe_carga > '$fecha_inicio' AND p.pro_fe_carga < '$fecha_fin') OR
(p.pro_fe_carga = '$fecha_fin' AND p.pro_hr_inicio < '$hora_fin')
GROUP BY 
p.pro_id"; */

$cadena = "SELECT p.pro_id, e.ep_descripcion, SUM(pm.pma_kg) as cantidad, m.mat_nombre, m.mat_id, p.pro_fe_carga, p.pro_hr_inicio 
            FROM procesos as p 
            INNER JOIN procesos_equipos as d ON p.pro_id = d.pro_id 
            INNER JOIN equipos_preparacion as e ON d.ep_id = e.ep_id 
            INNER JOIN procesos_materiales as pm ON p.pro_id = pm.pro_id 
            INNER JOIN materiales as m ON pm.mat_id = m.mat_id 
            WHERE $str_filtro
            GROUP BY p.pro_id";
/* echo $cadena; */

$cad = mysqli_query($cnx, "SELECT p.pro_id, e.ep_descripcion, p.pro_total_kg as cantidad, m.mat_nombre, m.mat_id, p.pro_fe_carga, p.pro_hr_inicio 
                            FROM procesos as p 
                            INNER JOIN procesos_equipos as d ON p.pro_id = d.pro_id 
                            INNER JOIN equipos_preparacion as e ON d.ep_id = e.ep_id 
                            INNER JOIN procesos_materiales as pm ON p.pro_id = pm.pro_id 
                            INNER JOIN materiales as m ON pm.mat_id = m.mat_id 
                            WHERE 
                            (p.pro_fe_carga = '$fecha_inicio' AND p.pro_hr_inicio >= '$hora_inicio') OR
                            (p.pro_fe_carga > '$fecha_inicio' AND p.pro_fe_carga < '$fecha_fin') OR
                            (p.pro_fe_carga = '$fecha_fin' AND p.pro_hr_inicio < '$hora_fin')
                            GROUP BY                             p.pro_id");

/* $cad = mysqli_query($cnx, "SELECT p.pro_id, e.ep_descripcion, SUM(pm.pma_kg) as cantidad, m.mat_nombre,m.mat_id,p.pro_fe_carga,p.pro_hr_inicio 
                            FROM procesos as p 
                            inner join procesos_equipos as d on(p.pro_id = d.pro_id)
                            inner join equipos_preparacion as e on(d.ep_id = e.ep_id) 
                            inner join procesos_materiales as pm on(p.pro_id = pm.pro_id) 
                            inner join materiales as m on(pm.mat_id = m.mat_id) 
                            WHERE (p.pro_fe_carga >= '$fecha_inicio' and p.pro_hr_inicio >= '$hora_inicio:00') 
                            and (p.pro_fe_carga <= '$fecha_fin' and p.pro_hr_inicio >= '$hora_fin:00')
                            GROUP BY p.pro_id");
 */
$tot = mysqli_num_rows($cad);
$tot = mysqli_num_rows($cad);

// Convertir el resultado de la consulta a un objeto JSON
$material = array();
while ($reg = mysqli_fetch_assoc($cad)) {
    $material[] = $reg;
}


// Cerrar la conexión a la base de datos
mysqli_close($cnx);

// Devolver el objeto JSON
echo json_encode($material);
