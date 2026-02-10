<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";


$cnx = Conectarse();


extract($_POST);

$cad_cbx =  mysqli_query($cnx, "SELECT prv_tipo, prv_ban FROM proveedores WHERE prv_id = '$cbxProveedor'") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
$reg_cbx =  mysqli_fetch_array($cad_cbx);


if ($reg_cbx['prv_tipo'] == 'L' && $reg_cbx['prv_ban'] == 1) {
	//echo "Local y maquila"."<br>";
	mysqli_query($cnx, "INSERT INTO inventario(inv_fecha, inv_hora, inv_dia, inv_no_ticket, inv_placas, inv_camioneta, prv_id, mat_id, inv_kilos, inv_prueba, inv_desc_ag, inv_desc_d, inv_desc_ren, inv_kg_totales, inv_calidad, int_cve_compra, inv_total_cueros, inv_enviado) VALUES('" . date("Y-m-d") . "', '" . date("H:i:s") . "', " . date("w") . ",'$txtNoTicket', '$txtPlacas', '$txtCamioneta', '$cbxProveedor', '$cbxMaterial', '$txtKg', '$txtSecador', '$txtDAgua', '$txtDRendimiento', '$txtDRendimiento', '$txtKgTotales', '$cbxCalidad', '$txtClave_comp', '$txtTotCueros', 1)") or die(mysqli_error($cnx) . " Error al insertar");
} else if ($reg_cbx['prv_tipo'] == 'L' && $reg_cbx['prv_ban'] == 0) {
	//echo "Local"."<br>";

	//folio consecutivo mensual
	/* $str_fecha = date("Y-m-") . "01";
	$cad = mysqli_query($cnx, "SELECT LPAD((COUNT(i.inv_folio_interno)+1),3,'0') as num FROM inventario as i
	inner join proveedores as p on(i.prv_id = p.prv_id) WHERE i.inv_fecha >= '$str_fecha' and p.prv_tipo = 'L'");
	$reg = mysqli_fetch_array($cad);

	//si el folio es diferente al calculado por el de sistema
	if ($txt_folio_interno != $reg['num']) {
		$str_folio = date("ym") . $txt_folio_interno;
	} else {
		$str_folio = date("ym") . $reg['num'];
	} */

	mysqli_query($cnx, "INSERT INTO inventario(inv_fecha, inv_hora, inv_dia, inv_no_ticket, inv_placas, inv_camioneta, prv_id, mat_id, inv_kilos, inv_prueba, inv_desc_ag, inv_desc_d, inv_desc_ren, inv_kg_totales, inv_calidad,
	inv_hora_entrada,
	inv_hora_salida,
	inv_folio_interno,
	inv_estado,
	inv_prueba_rendimiento,
	usu_id, ac_id) VALUES('" . date("Y-m-d") . "', '" . date("H:i:s") . "', " . date("w") . ",'$txtNoTicket', '$txtPlacas', '$txtCamioneta', '$cbxProveedor', '$cbxMaterial', '$txtKg', '$txtSecador', '$txtDAgua', '$txtDRendimiento', '$txtDRendimiento', '$txtKgTotales', '$cbxCalidad',
	'$txt_hora_entrada',
	'$txt_hora_salida',
	'$txt_folio_interno',
	'$cbx_estado',
	'$txt_prueba_redimiento',
	'" . $_SESSION['idUsu'] . "', '$cbxUbicacion'
	)") or die(mysqli_error($cnx) . " Error al insertar loc");

	$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(inv_id) as res from inventario"));

	ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '3');

	//Obtener la existencia actual del material
	$cad_mat = mysqli_query($cnx, "SELECT mat_existencia FROM materiales WHERE mat_id = '$cbxMaterial' ");
	$reg_mat = mysqli_fetch_array($cad_mat);

	$flt_existencia = $reg_mat['mat_existencia'] + $txtKgTotales;

	//Actualiza la existencia del material
	mysqli_query($cnx, "UPDATE materiales SET mat_existencia = '$flt_existencia' WHERE mat_id = '$cbxMaterial'") or die(mysqli_error($cnx) . " Error al actualizar");

	//Insertar en el diario de almacen el movimiento
	mysqli_query($cnx, "INSERT INTO inventario_diario_materiales (usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new, inv_id) VALUES(" . $_SESSION['idUsu'] . ", '" . date("Y-m-d H:i:s") . "', 'inventario', '$cbxMaterial', '$txtKgTotales', '$reg_mat[mat_existencia]', '$flt_existencia', '$reg_ultimo_id[res]' )") or die(mysqli_error($cnx) . " Error al insertar en el diario");

} else {
	/* echo "Extranjero"; */
	//folio consecutivo anual
	/* 	$str_fecha = date("Y-") . "01-01";
	$cad = mysqli_query($cnx, "SELECT LPAD((COUNT(i.inv_folio_interno)+1),3,'0') as num FROM inventario as i
	inner join proveedores as p on(i.prv_id = p.prv_id) WHERE i.inv_fecha >= '$str_fecha' and p.prv_tipo = 'E'");
	$reg = mysqli_fetch_array($cad);

	//si el folio es diferente al calculado por el de sistema
	if ($txt_folio_interno != $reg['num']) {
		$str_folio = date("ym") . $txt_folio_interno;
	} else {
		$str_folio = date("ym") . $reg['num'];
	}
 */
	mysqli_query($cnx, "INSERT INTO inventario(inv_fecha, inv_hora, inv_dia, inv_no_ticket, inv_placas, inv_camioneta, prv_id, mat_id, inv_kilos, inv_prueba, inv_kg_totales, inv_calidad, inv_no_factura, inv_peso_factura, inv_por_merma, inv_no_tarimas, inv_no_sacos,
	inv_hora_entrada,
	inv_hora_salida,
	inv_folio_interno,
	inv_prueba_rendimiento,
	usu_id, ac_id) VALUES('" . date("Y-m-d") . "', '" . date("H:i:s") . "', " . date("w") . ",'$txtNoTicket', '$txtPlacas', '$txtCamioneta', '$cbxProveedor', '$cbxMaterial', '$txtKg', '$txtSecador', '$txtKgTotales', '$cbxCalidad', '$txtFactura', '$txtPeso', '$txtMerma', '$txtTarimas', '$txtSacos',
	'$txt_hora_entrada',
	'$txt_hora_salida',
	'$txt_folio_interno',
	'$txt_prueba_redimiento',
	'" . $_SESSION['idUsu'] . "', '0')") or die(mysqli_error($cnx) . " Error al insertar ext");

	$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(inv_id) as res from inventario"));

	ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '3');
}

$respuesta = array('mensaje' => "Inventario Agregado");
echo json_encode($respuesta);
