<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Inventario Actualizado");
echo json_encode($respuesta);

extract($_POST); 

/*mysqli_query($cnx, "UPDATE inventario SET inv_fe_recibe = '".date("Y-m-d H:i:s")."', prv_recibe = '$cbxProveedor', inv_kg_totales = '$txtKgTotales', inv_enviado = 2, inv_kg_lavador =  '$txtKgLavador' , inv_prueba2 = '$txtPrbSecador' , inv_desc_ag2 = '$txtDAgua', inv_desc_d2 = '$txtDescarne', inv_desc_ren2 = '$txtDRendimiento', inv_kg_entrada_maq = '$txtKgEntradaMaq' WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar");*/

//Actualiza los kilos pendientes por recibir, nueva
mysqli_query($cnx, "UPDATE inventario SET inv_kg_totales = '$txt_pendientes' WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar el inventario");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_id, '3');

//Selecciona los datos de todo el inventario, nueva
$cad_inv = mysqli_query($cnx, "SELECT * FROM inventario WHERE inv_id = '$hdd_id' ");
$reg_inv = mysqli_fetch_array($cad_inv);

if($reg_inv['inv_desc_ag'] == ''){$reg_inv['inv_desc_ag'] = 0;}
if($reg_inv['inv_desc_d'] == ''){$reg_inv['inv_desc_d'] = 0;}
if($reg_inv['inv_desc_ren'] == ''){$reg_inv['inv_desc_ren'] = 0;}	

if($reg_inv['inv_fe_enviado'] == null){$reg_inv['inv_fe_enviado'] =  'null'; }else{
	$reg_inv['inv_fe_enviado'] = "'$reg_inv[inv_fe_enviado]'";
}	
if($reg_inv['inv_fe_recibe'] == null){$reg_inv['inv_fe_recibe'] = 'null'; }else{
	$reg_inv['inv_fe_recibe'] = "'$reg_inv[inv_fe_recibe]'";
}	

if($reg_inv['inv_no_factura'] == null){$reg_inv['inv_no_factura'] = 'null'; }else{
	$reg_inv['inv_no_factura'] = "'$reg_inv[inv_no_factura]'";
}	

//Insera el nuevo inventario que se dividio, nueva
mysqli_query($cnx, "INSERT INTO inventario(inv_fecha, inv_hora, inv_dia, inv_no_ticket, inv_placas, inv_camioneta, prv_id, mat_id, inv_kilos, inv_prueba, inv_desc_ag, inv_desc_d, inv_desc_ren, inv_kg_totales, inv_calidad, inv_no_factura, inv_peso_factura, inv_por_merma, inv_no_tarimas, inv_no_sacos,inv_enviado,inv_fe_enviado,inv_fe_recibe,prv_recibe,inv_tomado,inv_kg_entrada_maq,inv_kg_lavador,int_cve_compra ,inv_total_cueros,inv_prueba2,inv_desc_ag2,inv_desc_d2,inv_desc_ren2, inv_id_key) VALUES('$reg_inv[inv_fecha]', '$reg_inv[inv_hora]', '$reg_inv[inv_dia]','$reg_inv[inv_no_ticket]', '$reg_inv[inv_placas]', '$reg_inv[inv_camioneta]', '$reg_inv[prv_id]', '$reg_inv[mat_id]', '$reg_inv[inv_kilos]', '$txtPrbSecador', '$txtDAgua', '$txtDescarne', '$txtDRendimiento', '$txtKgTotales', '$reg_inv[inv_calidad]', $reg_inv[inv_no_factura], '$reg_inv[inv_peso_factura]', '$reg_inv[inv_por_merma]', '$reg_inv[inv_no_tarimas]', '$reg_inv[inv_no_sacos]','2',$reg_inv[inv_fe_enviado], '".date("Y-m-d H:i:s")."' ,'$cbxProveedor','1','$txtKgEntradaMaq','$txtKgLavador','$reg_inv[int_cve_compra]','$reg_inv[inv_total_cueros]','$txtPrbSecador','$txtDAgua','$txtDescarne','$txtDRendimiento','$hdd_id')") or die(mysqli_error($cnx)." Error al insertar nuevo inventario");

//Obtener la existencia actual del material
$cad_mat = mysqli_query($cnx, "SELECT mat_existencia FROM materiales WHERE mat_id = '$hddMaterial' ");
$reg_mat = mysqli_fetch_array($cad_mat);

$flt_existencia = $reg_mat['mat_existencia'] + $txtKgTotales;

//echo "UPDATE materiales SET mat_existencia = '$flt_existencia' WHERE mat_id = '$hddMaterial'<br>";
mysqli_query($cnx, "UPDATE materiales SET mat_existencia = '$flt_existencia' WHERE mat_id = '$hddMaterial'") or die(mysqli_error($cnx)." Error al actualizar");

//Insertar en el diario de almacen el movimiento
/*echo "INSERT INTO inventario_diario_materiales (usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new, inv_id) VALUES(".$_SESSION['idUsu'].", '".date("Y-m-d H:i:s")."', 'inventario', '$hddMaterial', '$txtKgTotales', '$reg_mat[mat_existencia]', '$flt_existencia', '$hdd_id'";*/
mysqli_query($cnx, "INSERT INTO inventario_diario_materiales (usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new, inv_id) VALUES(".$_SESSION['idUsu'].", '".date("Y-m-d H:i:s")."', 'inventario', '$hddMaterial', '$txtKgTotales', '$reg_mat[mat_existencia]', '$flt_existencia', '$hdd_id' )") or die(mysqli_error($cnx)." Error al insertar en el diario");
?> 