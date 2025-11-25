<?php
/*Desarrollado por CCA Consultores*/
/*31 - Octubre - 2021*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);

//selecciona dato agrupador del proceso
$cad = mysqli_query($cnx, "SELECT DISTINCT(e.pro_id),g.pa_id FROM procesos_equipos as e 
inner join procesos as p on(e.pro_id = p.pro_id)
inner join procesos_agrupados as g on(e.pro_id = g.pro_id)
WHERE  ep_id = '" . $eq_nuevo . "' and p.pro_estatus = 1 and pe_ban_activo = 1");
$reg =  mysqli_fetch_array($cad);

//suma los kislos de los procesos con el mismo dato agrupador
$cad_tot = mysqli_query($cnx, "SELECT p.pro_id,SUM(p.pro_total_kg) as kg_totales FROM procesos_agrupados as a 
INNER JOIN procesos as p on(a.pro_id = p.pro_id) WHERE  pa_id = '" . $reg['pa_id'] . "'
");
$reg_tot =  mysqli_fetch_array($cad_tot);

if(isset($reg_tot['kg_totales'])){
    echo $reg_tot['kg_totales'];

}else{
    echo '0';
}
