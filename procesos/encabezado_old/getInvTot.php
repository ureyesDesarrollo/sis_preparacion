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
$inv_id8 = $_POST['inv_id8'];
$inv_id9 = $_POST['inv_id9'];
$inv_id10 = $_POST['inv_id10'];
$inv_id11 = $_POST['inv_id11'];
$inv_id12 = $_POST['inv_id12'];


$query =  mysqli_query($cnx, "SELECT i.inv_id, i.inv_no_ticket, i.inv_kg_totales, i.prv_id,p.prv_nombre FROM inventario as i
INNER JOIN proveedores as p ON(i.prv_id = p.prv_id) WHERE mat_id = '$mat_id' AND inv_tomado = 0 AND inv_enviado IN (0,2) and inv_kg_totales > 0 AND inv_id != '$inv_id1' AND inv_id != '$inv_id2' AND inv_id != '$inv_id3' AND inv_id != '$inv_id4' AND inv_id != '$inv_id5' AND inv_id != '$inv_id6' AND inv_id != '$inv_id7'  AND inv_id != '$inv_id8' AND inv_id != '$inv_id9' AND inv_id != '$inv_id10' AND inv_id != '$inv_id11' AND inv_id != '$inv_id12' ORDER BY inv_kg_totales");

$html2 = "<option value='0'>Seleccionar</option>";

while($row = mysqli_fetch_array($query))
{
	$html2 .= "<option value='".$row['inv_id']."'>".$row['inv_kg_totales']." / ".$row['prv_nombre']."</option>";
} 

echo $html2;
?>