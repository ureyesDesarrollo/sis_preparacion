<?php 
/*Desarrollado por: CCA Consultores TI*/
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Dic - 2021*/

require_once('conexion/conexion.php');
require_once('procesos/funciones_procesos.php');
  
//Obtiene el dato del ultimo lote
$cad = mysqli_query($cnx, "SELECT * FROM lotes ORDER BY lote_id desc ") or die(mysql_error()."Error: en consultar los procesos asignados");
$reg = mysqli_fetch_assoc($cad);

echo "Lote: ".$reg['lote_id']."<br>";
echo "Fecha: ".$reg['lote_fecha']."<br>";
echo "Hora: ".$reg['lote_hora']."<br>";

//Obtiene el tiempo transcurrido
echo "<hr>";
echo "Horas transcurridas: <br>";
echo fnc_horas($reg['lote_fecha'], date("Y-m-d"), $reg['lote_hora'], date("H:i:s"));
?>