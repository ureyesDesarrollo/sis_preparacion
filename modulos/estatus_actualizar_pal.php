<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST); 
//echo "1 UPDATE preparacion_paletos SET le_id = '$cbxEstatus' WHERE pp_id = '$hdd_id' ";
mysqli_query($cnx, "UPDATE preparacion_paletos SET le_id = '$cbxEstatus' WHERE pp_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar");

//Se ejecuta si va de estatus ocupado a libre	
if ($hdd_est == 1 and $cbxEstatus == 2)
{
	/*$txt_fecha = date("Y-m-d");
	$txt_mes = date("m");				
	mysqli_query($cnx, "INSERT INTO lotes(lote_fecha, lote_hora, lote_mes, lote_folio, lote_turno, usu_id) 
						VALUES ('$txt_fecha', '$txt_hora', '$txt_mes', '$txt_lote', '$slc_turno', '".$_SESSION['idUsu']."') ") or die(mysqli_error($cnx)." Error al insertar el lote");
						
	$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(lote_id) as res from lotes"));*/ 
						
	//Consulta los procesos de paletos que se asocian a un lote
	$cad_proc = mysqli_query($cnx,"select * from procesos_paletos WHERE pp_id = '$hdd_id' and prop_estatus = 1 ");
	$reg_proc =  mysqli_fetch_assoc($cad_proc);
	
	do
	{
		mysqli_query($cnx, "INSERT INTO lotes_procesos(lote_id, prop_id) 
						VALUES ('$cbxLote', '$reg_proc[prop_id]') ") or die(mysqli_error($cnx)." Error al insertar el lote procesos");
	}while($reg_proc =  mysqli_fetch_assoc($cad_proc));
	
	$str_mensaje = "y Lote asignado";
}	
	
//Si cambia el estatus de libre del paleto 1A y 1B, termina los procesos que tenga guardados.
if($cbxEstatus == '2' and ($hdd_id == '1' or $hdd_id == '2'))
{ 
	mysqli_query($cnx, "UPDATE procesos_paletos SET prop_estatus = 2 WHERE pp_id = '$hdd_id' and prop_estatus = 1 ") or die(mysqli_error($cnx)." Error al actualizar proceso");
}

mysqli_query($cnx, "INSERT INTO bitacora_cambio_estatus(bce_fecha, usu_id, bce_est_actual, bce_est_nuevo, bce_descripcion, pp_id, pl_id) 
					VALUES ('".date("Y-m-d H:i:s")."', '".$_SESSION['idUsu']."', '$hdd_est', '$cbxEstatus', '$txaComentarios', 0, '$hdd_id') ") or die(mysqli_error($cnx)." Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_id, '13');

$respuesta = array('mensaje' => "Estatus actualizado ".$str_mensaje);
echo json_encode($respuesta);
?> 