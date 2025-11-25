<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();

$respuesta = array('mensaje' => "Material agregado el al proceso");
echo json_encode($respuesta);

extract($_POST); 

$cad_kg2 = mysqli_query($cnx, "SELECT inv_kg_totales FROM inventario WHERE inv_id = '$cbxKilosID' ");
$reg_kg2 = mysqli_fetch_array($cad_kg2);

$txtKilos = $reg_kg2['inv_kg_totales'];

mysqli_query($cnx, "INSERT INTO procesos_materiales (pro_id, inv_id, mat_id, pma_kg, pma_fe_entrada) VALUES('$hdd_id', '$cbxKilosID', '$cbxMaterial',  '$txtKilos',  '$txtFecha')") or die(mysqli_error($cnx)." Error al insertar ");
		
mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1 WHERE inv_id = '$cbxKilosID' ") or die(mysqli_error($cnx)." Error al actualizar en inventario");

$cad_kg = mysqli_query($cnx, "SELECT pro_total_kg FROM procesos WHERE pro_id = '$hdd_id' ");
$reg_kg = mysqli_fetch_array($cad_kg);

$flt_total = $reg_kg['pro_total_kg'] + $txtKilos;

mysqli_query($cnx, "UPDATE procesos SET pro_total_kg = '$flt_total' WHERE pro_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar en procesos");

fnc_existencia($cbxMaterial, $txtKilos, $cbxKilosID);

/*//Actualiza los kilos sobrantes
mysqli_query($cnx, "UPDATE inventario SET inv_kg_totales = '$txtSobra' WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar el inventario");

//Selecciona los datos de todo el inventario
$cad_inv = mysqli_query($cnx, "SELECT * FROM inventario WHERE inv_id = '$hdd_id' ");
$reg_inv = mysqli_fetch_array($cad_inv);

//Insera el nuevo inventario que se dividio
mysqli_query($cnx, "INSERT INTO inventario(inv_fecha, inv_hora, inv_dia, inv_no_ticket, inv_placas, inv_camioneta, prv_id, mat_id, inv_kilos, inv_prueba, inv_desc_ag, inv_desc_d, inv_desc_ren, inv_kg_totales, inv_calidad, inv_no_factura, inv_peso_factura, inv_por_merma, inv_no_tarimas, inv_no_sacos, inv_id_key) 
					VALUES('$reg_inv[inv_fecha]', '$reg_inv[inv_hora]', '$reg_inv[inv_dia]','$reg_inv[inv_no_ticket]', '$reg_inv[inv_placas]', '$reg_inv[inv_camioneta]', '$reg_inv[prv_id]', '$reg_inv[mat_id]', '$reg_inv[inv_kilos]', '$reg_inv[inv_prueba]', '$reg_inv[inv_desc_ag]', '$reg_inv[inv_desc_d]', '$reg_inv[inv_desc_ren]', '$txtToma', '$reg_inv[inv_calidad]', '$reg_inv[inv_no_factura]', '$reg_inv[inv_peso_factura]', '$reg_inv[inv_por_merma]', '$reg_inv[inv_no_tarimas]', '$reg_inv[inv_no_sacos]', '$hdd_id')") or die(mysqli_error($cnx)." Error al insertar");*/
					
function fnc_existencia($intMaterial, $fltKgDescuenta, $intInv)
{
	$cnx = Conectarse();
	
	//Obtener la existencia actual del material
	$cad_mat = mysqli_query($cnx, "SELECT mat_existencia FROM materiales WHERE mat_id = '$intMaterial' ");
	$reg_mat = mysqli_fetch_array($cad_mat);
	
	$flt_existencia = $reg_mat['mat_existencia'] - $fltKgDescuenta;
	
	//Actualiza la existencia del material
	mysqli_query($cnx, "UPDATE materiales SET mat_existencia = '$flt_existencia' WHERE mat_id = '$intMaterial'") or die(mysqli_error($cnx)." Error al actualizar");
	
	//Insertar en el diario de almacen el movimiento
	mysqli_query($cnx, "INSERT INTO inventario_diario_materiales (usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new, inv_id) 
						VALUES(".$_SESSION['idUsu'].", '".date("Y-m-d h:i:s")."', 'bitacora', '$intMaterial', '$fltKgDescuenta', '$reg_mat[mat_existencia]', '$flt_existencia', '$intInv' )") or die(mysqli_error($cnx)." Error al insertar en el diario");
}
?>