<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*25 - Junio - 2019*/
    
/*$db_host = 'localhost'; //Host del Servidor MySQL
$db_name = 'bd_preparacion'; //Nombre de la Base de datos
$db_user = 'root'; //Usuario de MySQL
$db_pass = ''; //Password de Usuario MySQL*/

require '../../conexion/configuracion.php';



$bd = "progel";

$fecha = date("Ymd_His"); //Obtenemos la fecha y hora para identificar el respaldo

// Construimos el nombre de archivo SQL Ejemplo: mibase_20170101-081120.sql
$salida_sql = $bd.'_'.$fecha.'.sql'; 

//Comando para genera respaldo de MySQL, enviamos las variales de conexion y el destino
//$dump = "mysqldump --h$db_host -u$db_user -p$db_pass --opt $db_name > $salida_sql";

//Modificado
$dump = "c:\wamp64\bin\mysql\mysql5.7.14\bin\mysqldump --host=".$server." --user=".$user." --password=".$pass." --opt ".$bd." > $salida_sql";
//c:\wamp64\bin\mysql\mysql5.7.19\bin>mysqldump -u root -p bd_preparacion
//$dump = "C:\wamp64\bin\mysql\mysql5.7.19\bin\mysqldump --user = ".$db_user." --password= ".$db_pass." --host=".$db_host." ".$db_name."  > $db_name";
//$dump = "C:\mysql5.6\mysql\bin\mysqldump --h$db_host -u$db_user -p$db_pass $db_name > $salida_sql";
//echo $dump;
system($dump, $output); //Ejecutamos el comando para respaldo

$zip = new ZipArchive(); //Objeto de Libreria ZipArchive

//Construimos el nombre del archivo ZIP Ejemplo: mibase_20160101-081120.zip
$salida_zip = "d:\RespaldoBD\\".$bd.'_'.$fecha.'.zip';

if($zip->open($salida_zip,ZIPARCHIVE::CREATE)===true) { //Creamos y abrimos el archivo ZIP
	$zip->addFile($salida_sql); //Agregamos el archivo SQL a ZIP
	$zip->close(); //Cerramos el ZIP
	unlink($salida_sql); //Eliminamos el archivo temporal SQL
	header ("Location: $salida_zip"); // Redireccionamos para descargar el Arcivo ZIP
	} else {
	echo 'Error'; //Enviamos el mensaje de error
}

//echo "Se genero el respaldo de la BD";

//https://codigosdeprogramacion.com/2017/02/21/crear-respaldo-de-mysql-desde-php/
?>

