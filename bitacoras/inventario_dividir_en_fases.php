<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();

$respuesta = array('mensaje' => "Inventario Dividido");
echo json_encode($respuesta);

extract($_POST); 

//Actualiza los kilos sobrantes
mysqli_query($cnx, "UPDATE inventario SET inv_kg_totales = '$txtSobra' WHERE inv_id = '$hdd_id_inv_en_fases' ") or die(mysqli_error($cnx)." Error al actualizar el inventario");

//Selecciona los datos de todo el inventario
$cad_inv = mysqli_query($cnx, "SELECT * FROM inventario WHERE inv_id = '$hdd_id_inv_en_fases' ");
$reg_inv = mysqli_fetch_array($cad_inv);

if($reg_inv['inv_desc_ag'] == ''){$reg_inv['inv_desc_ag'] = 0;}
if($reg_inv['inv_desc_d'] == ''){$reg_inv['inv_desc_d'] = 0;}
if($reg_inv['inv_desc_ren'] == ''){$reg_inv['inv_desc_ren'] = 0;}	
if($reg_inv['inv_fe_enviado'] == NULL){$reg_inv['inv_fe_enviado'] =  'NULL'; }else{
	$reg_inv['inv_fe_enviado'] = "'$reg_inv[inv_fe_enviado]'";
}	
if($reg_inv['inv_fe_recibe'] == NULL){$reg_inv['inv_fe_recibe'] = 'NULL'; }else{
	$reg_inv['inv_fe_recibe'] = "'$reg_inv[inv_fe_recibe]'";
}								

//Insera el nuevo inventario que se dividio
mysqli_query($cnx, "INSERT INTO inventario(inv_fecha,inv_fe_recibe, inv_hora, inv_dia, inv_no_ticket, inv_placas, inv_camioneta, prv_id, mat_id, inv_kilos, inv_prueba, inv_desc_ag, inv_desc_d, inv_desc_ren, inv_kg_totales, inv_calidad, inv_no_factura, inv_peso_factura, inv_por_merma, inv_no_tarimas, inv_no_sacos, inv_id_key,inv_enviado, inv_folio_interno) 
					VALUES('$reg_inv[inv_fecha]',$reg_inv[inv_fe_recibe], '$reg_inv[inv_hora]', '$reg_inv[inv_dia]','$reg_inv[inv_no_ticket]', '$reg_inv[inv_placas]', '$reg_inv[inv_camioneta]',
					 '$reg_inv[prv_id]', '$reg_inv[mat_id]', '$reg_inv[inv_kilos]', '$reg_inv[inv_prueba]', '$reg_inv[inv_desc_ag]', '$reg_inv[inv_desc_d]', '$reg_inv[inv_desc_ren]',
					  '$txtToma', '$reg_inv[inv_calidad]', '$reg_inv[inv_no_factura]', '$reg_inv[inv_peso_factura]', '$reg_inv[inv_por_merma]', '$reg_inv[inv_no_tarimas]',
					   '$reg_inv[inv_no_sacos]', '$hdd_id_inv_en_fases','$reg_inv[inv_enviado]', '$reg_inv[inv_folio_interno]')") or die(mysqli_error($cnx)." Error al insertar");
?>