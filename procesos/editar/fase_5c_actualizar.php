<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Abril - 2019*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST); 

//Actualiza los datos del auxiliar
if($txtFeTerm == '' and $txtHrTerm == '')
{
	mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");
}
else
{
	mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni', proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");
}

//Actualiza los datos del general de la tabla
mysqli_query($cnx, "UPDATE procesos_fase_5b_g SET pfg5_temp_ag = '$txtTemp', pfg5_temp = '$txtTemp2', pfg5_acido = '$txtAcido', pfg5_termina = '$txtTermina', pfg5_temp2 = '$txtTemp3', pfg5_cocido_ph = '$txtCocido', taa_id = '$cbxAgua', pfg5_ce_agua = '$txtCeAgua', pfg5_ph_agua = '$txtPhAgua' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");

//Actualiza los datos del detalle de la tabla
for($i = 1; $i <= 8; $i++)
{
	$txtRen = ${"txtRen".$i};
	$txtAcidoF = ${"txtAcidoF".$i};
	$txtPhF = ${"txtPhF".$i};
	$txtPhBF = ${"txtPhBF".$i};
	$txtTempF = ${"txtTempF".$i};
	
	/*if($txtAcidoF != '' and $txtPhF != '' and $txtPhBF != '' and $txtTempF != '')
	{*/
		mysqli_query($cnx, "UPDATE procesos_fase_5b_d SET pfd5_acido = '$txtAcidoF', pfd5_ph = '$txtPhF', pfd5_ph_b = '$txtPhBF', pfd5_temp = '$txtTempF' WHERE pfg5_id = '$hdd_pfg' AND pfd5_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
	//}
}
	
//Actualiza los datos de la liberación
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ph = '$txtPhLib', prol_adelgasamiento = '$cbxAdel' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj );
echo json_encode($respuesta); 
?> 