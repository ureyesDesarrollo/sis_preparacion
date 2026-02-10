<?php 
/*Desarrollado por: CCA Consultores TI*/
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Dic - 2021*/

require_once('conexion/conexion.php');
require_once('procesos/funciones_procesos.php');
  
//Obtiene los procesos de los paletos
$cad = mysqli_query($cnx, "SELECT x.prop_id, x.pp_id   
						   FROM procesos_paletos as x 
						   WHERE  x.prop_estatus = 1 and x.pp_id = 1
						   ORDER BY x.pp_id ") or die(mysql_error()."Error: en consultar los procesos asignados");
$reg = mysqli_fetch_assoc($cad);

$cad_b = mysqli_query($cnx, "SELECT x.prop_id, x.pp_id   
						   FROM procesos_paletos as x 
						   WHERE  x.prop_estatus = 1 and x.pp_id = 2
						   ORDER BY x.pp_id ") or die(mysql_error()."Error: en consultar los procesos asignados");
$reg_b = mysqli_fetch_assoc($cad_b);

echo "<br><br>1A - Proceso: ";
echo $reg['prop_id']."<br>";

//Obtiene el tiempo transcurrido
echo "<hr>";
echo "1B - Proceso: ";
echo $reg_b['prop_id']."<br>";

?>