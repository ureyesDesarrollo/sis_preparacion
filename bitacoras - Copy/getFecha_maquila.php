<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
require_once('../conexion/conexion.php');
$cnx =  Conectarse();

$inv_id = $_POST['inv_id'];

$query =  mysqli_query($cnx, "SELECT inv_id, SUBSTRING(inv_fe_recibe, 1,10) as inv_fe_recibe  FROM inventario WHERE inv_id = '$inv_id'");
$reg = mysqli_fetch_array($query);

echo $reg['inv_fe_recibe']
?>