<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include '../conexion/configuracion.php';

$db_host = $server;
$db_user = "prueba_root";
$db_pass = "Prueba2020";
/*$db_user = "root";
$db_pass = "";*/
$db_name = $bd;
$fecha = date("Y_m_d-h_i_s");	

//Nombre del respaldo
$ruta = "D:\RespaldoBD\\";
$backup_file = $ruta.$db_name.'_'.$fecha.'.sql';

//$dump = "mysqldump --column-statistics=0 -h$db_host -u$db_user -p$db_pass $db_name --opt > $backup_file";
$dump = "mysqldump -h$db_host -u$db_user -p$db_pass $db_name --opt > $backup_file"; 
//echo $dump;
//Normalmente el respaldo se genera en la misma carpeta donde se realizo la ejecucion.

//	O tu puedes asignarle una direccion donde se almacenara el respaldo. Ejemplo:
//	C:\Respaldos/$backup_file
system($dump);

echo '<script language="javascript">alert("Respaldo realizado con exito");window.location.href="submenu_funciones.php"</script>';
?>