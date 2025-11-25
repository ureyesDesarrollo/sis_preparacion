<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
require_once('../conexion/conexion.php');
$cnx =  Conectarse();

$mat_id = $_POST['mat_id'];
$inv_id1 = $_POST['inv_id1'];
$inv_id2 = $_POST['inv_id2'];
$inv_id3 = $_POST['inv_id3'];
$inv_id4 = $_POST['inv_id4'];
$inv_id5 = $_POST['inv_id5'];
$inv_id6 = $_POST['inv_id6'];
$inv_id7 = $_POST['inv_id7'];


$query =  mysqli_query($cnx, "SELECT inv_id, inv_no_ticket, inv_kg_totales FROM inventario WHERE mat_id = '$mat_id' AND inv_tomado = 0 AND inv_enviado IN (0,2) and inv_kg_totales > 0 AND inv_id != '$inv_id1' AND inv_id != '$inv_id2' AND inv_id != '$inv_id3' AND inv_id != '$inv_id4' AND inv_id != '$inv_id5' AND inv_id != '$inv_id6' AND inv_id != '$inv_id7'  ORDER BY inv_kg_totales");

$html2 = "<option value='0'>Seleccionar</option>";

while($row = mysqli_fetch_array($query))
{
	$html2 .= "<option value='".$row['inv_id']."'>".$row['inv_kg_totales']."</option>";
} 

echo $html2;
?>