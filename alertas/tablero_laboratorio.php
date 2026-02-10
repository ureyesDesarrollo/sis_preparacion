<?php 
/*Desarrollado por: CCA Consultores TI*/
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Dic - 2021*/

require_once('conexion/conexion.php');
require_once('procesos/funciones_procesos.php');
  
//Obtiene el dato del ultimo lote
$cad = mysqli_query($cnx, "SELECT * FROM procesos_liberacion ORDER BY prol_id desc ") or die(mysql_error()."Error: en consultar los procesos asignados");
$reg = mysqli_fetch_assoc($cad);

echo "<br><br>Ultima captura ";
echo "Fecha y Hora: ".$reg['prol_fecha']."<br>";

//Obtiene el tiempo transcurrido
echo "<hr>";
echo "Horas transcurridas: <br>";

$date = strtotime ( $reg['prol_fecha']); 
echo fnc_horas(date ( 'Y-m-d' , $date ), date("Y-m-d"), date ( "H:i:s" , $date ), date("H:i:s"));
?>