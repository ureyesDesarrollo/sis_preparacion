<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
require_once('../conexion/conexion.php');
include('../seguridad/user_seguridad.php');

$cnx =  Conectarse();
extract($_POST);

$perfil_autorizado = mysqli_query($cnx, "SELECT * FROM usuarios_perfiles WHERE up_id = '" . $_SESSION['privilegio'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el inventario 1");
$reg_autorizado = mysqli_fetch_assoc($perfil_autorizado);

if (!isset($inv_id6)) {
	$inv_id6 = 0;
}
if (!isset($inv_id7)) {
	$inv_id7 = 0;
}
if (!isset($inv_id8)) {
	$inv_id8 = 0;
}
if (!isset($inv_id9)) {
	$inv_id9 = 0;
}
if (!isset($inv_id10)) {
	$inv_id10 = 0;
}
if (!isset($inv_id11)) {
	$inv_id11 = 0;
}
if (!isset($inv_id12)) {
	$inv_id12 = 0;
}

/*$query =  mysqli_query($cnx, "SELECT inv_id, inv_no_ticket, inv_kg_totales,prv_id,inv_fecha, inv_fe_recibe FROM inventario WHERE mat_id = '$mat_id' AND inv_tomado = 0 AND inv_enviado IN (0,2) and inv_kg_totales > 0 AND inv_id != '$inv_id1' AND inv_id != '$inv_id2' AND inv_id != '$inv_id3' AND inv_id != '$inv_id4' AND inv_id != '$inv_id5' AND inv_id != '$inv_id6' AND inv_id != '$inv_id7'  AND inv_id != '$inv_id8' AND inv_id != '$inv_id9' AND inv_id != '$inv_id10' AND inv_id != '$inv_id11' AND inv_id != '$inv_id12' ORDER BY inv_kg_totales");*/
$query =  mysqli_query($cnx, "SELECT i.inv_id, i.inv_no_ticket, i.inv_kg_totales, i.prv_id, i.inv_fecha, i.inv_fe_recibe, i.inv_enviado, p.prv_tipo, i.inv_folio_interno
	from inventario as i 
	inner join proveedores as p on (i.prv_id = p.prv_id)
	inner join almacen_cajones as a on(i.ac_id = a.ac_id) 
	where mat_id = '$mat_id' and 
	i.inv_tomado = 0 and i.inv_kg_totales > 0 
	and ( (p.prv_tipo = 'L' and  i.inv_enviado = 0) or (p.prv_tipo = 'L' and i.inv_enviado = 2) or (p.prv_tipo != 'L' and i.inv_enviado = 2))  
	and i.inv_id != '$inv_id1' and i.inv_id != '$inv_id2' and i.inv_id != '$inv_id3' and i.inv_id != '$inv_id4' and i.inv_id != '$inv_id5' and i.inv_id != '$inv_id6' 
	and i.inv_id != '$inv_id7'  and i.inv_id != '$inv_id8' and i.inv_id != '$inv_id9' and i.inv_id != '$inv_id10' and i.inv_id != '$inv_id11' and i.inv_id != '$inv_id12'
	and ac_ban = 'P'
	order by  inv_fecha");
$row = mysqli_fetch_array($query);

$html2 = "<option value=''>Selecciona</option>";

$background = "";
do {
	$cad_prov = mysqli_query($cnx, "SELECT prv_nombre, prv_tipo, prv_ban,prv_ncorto FROM proveedores WHERE prv_id = " . $row['prv_id'] . "");
	$prov = mysqli_fetch_array($cad_prov);

	if ($prov['prv_tipo'] == 'L' && $prov['prv_ban'] == '1') {
		$background = "#E6E6";
	}
	if ($prov['prv_tipo'] == 'E') {
		$background = "#F7FEA0";
	}

	if ($reg_autorizado['up_ban'] == 1) {
		$prov = $prov['prv_nombre'];
	} else {
		$prov = $prov['prv_ncorto'];
	}

	$html2 .= "<option style='text-align: right;width: 100px;'" . $background . "'' value='" . $row['inv_id'] . "'>" . $row['inv_kg_totales'] . " / " . $prov . " / " . $row['inv_fecha'] . " / " . $row['inv_fe_recibe'] . " (" . $row['inv_folio_interno'] . ")" . "</option>";
} while ($row = mysqli_fetch_array($query));

echo $html2;
