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

/*$query =  mysqli_query($cnx, "SELECT inv_id, inv_no_ticket, inv_kg_totales,prv_id,inv_fecha, inv_fe_recibe FROM inventario WHERE mat_id = '$mat_id' AND inv_tomado = 0 AND inv_enviado IN (0,2) and inv_kg_totales > 0 AND inv_id != '$inv_id1' AND inv_id != '$inv_id2' AND inv_id != '$inv_id3' AND inv_id != '$inv_id4' AND inv_id != '$inv_id5' AND inv_id != '$inv_id6' AND inv_id != '$inv_id7'  AND inv_id != '$inv_id8' AND inv_id != '$inv_id9' AND inv_id != '$inv_id10' AND inv_id != '$inv_id11' AND inv_id != '$inv_id12' ORDER BY inv_kg_totales");*/
$query =  mysqli_query($cnx, "SELECT i.inv_id, i.inv_no_ticket, i.inv_kg_totales, i.prv_id, i.inv_fecha, i.inv_fe_recibe, i.inv_enviado, p.prv_tipo
	from inventario as i 
	inner join proveedores as p on (i.prv_id = p.prv_id)
	where mat_id = '$mat_id' and 
	i.inv_tomado = 0 and i.inv_kg_totales > 0 
	and ( (p.prv_tipo = 'L' and  i.inv_enviado = 0) or (p.prv_tipo = 'L' and i.inv_enviado = 2) or (p.prv_tipo != 'L' and i.inv_enviado = 2))  
	and i.inv_id != '$inv_id1' and i.inv_id != '$inv_id2' and i.inv_id != '$inv_id3' and i.inv_id != '$inv_id4' and i.inv_id != '$inv_id5' and i.inv_id != '$inv_id6' and i.inv_id != '$inv_id7'  and i.inv_id != '$inv_id8' and i.inv_id != '$inv_id9' and i.inv_id != '$inv_id10' and i.inv_id != '$inv_id11' and i.inv_id != '$inv_id12'
	order by inv_kg_totales");
$row = mysqli_fetch_array($query);

$html2 = "<option value='0'>Selecciona</option>";

do
{
	$cad_prov = mysqli_query($cnx, "SELECT prv_nombre FROM proveedores WHERE prv_id = ".$row['prv_id']."");
	$prov = mysqli_fetch_array($cad_prov);

	if ( $reg_cbx['prv_tipo'] == 'L' && $reg_cbx['prv_ban'] == '1'){
		$background = "#E6E6";
	}
	if ($reg_cbx['prv_tipo'] == 'E') { $background = "#F7FEA0"; }


	$html2 .= "<option style='text-align: right;width: 100px;'".$background."'' value='".$row['inv_id']."'>".$row['inv_kg_totales']." / ".$prov['prv_nombre']. " / ".$row['inv_fecha']. " / ".$row['inv_fe_recibe']." (".$row['inv_id'].")". "</option>";
} while($row = mysqli_fetch_array($query));

echo $html2;
?>