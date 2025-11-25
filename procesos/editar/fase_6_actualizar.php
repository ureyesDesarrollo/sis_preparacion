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


if($txtPh2F != '' or $txtCe2F != '' or $txtPh3F != '' or $txtCe3F != '' ){
	$txtPh2F = '$txtPh2F';
	$txtCe2F = '$txtCe2F';
	$txtPh3F = '$txtPh3F';
	$txtCe3F = '$txtCe3F';
}else{
	$txtPh2F =  'null';
	$txtCe2F =  'null';
	$txtPh3F =  'null';
	$txtCe3F =  'null';

}

//Actualiza los datos del general de la tabla
mysqli_query($cnx, "UPDATE procesos_fase_6_g SET pfg6_temp_ag = '$txtTemp',pfg6_acido_diluido = '$cbxDiluido', pfg6_temp = '$txtTemp2', pfg6_acido = '$txtAcido', pfg6_temp2 = '$txtTemp3', pfg6_norm = '$txtNorm', pfg6_ph = '$txtPh', pfg6_fe_fin = '$txtFeTermS', pfg6_hr_fin = '$txtHrTermS', pfg6_hr_totales = '$txtHrsReales', pfg6_hr_cocido = '$txtHrIniCo', pfg6_ph2 = $txtPh2F, pfg6_ce2 = $txtCe2F, pfg6_ph3 = $txtPh3F, pfg6_ce3 = $txtCe3F, taa_id = '$cbxAgua' WHERE pro_id = '$hdd_pro_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");

//Actualiza los datos del detalle de la tabla
for($i = 1; $i <= 8; $i++)
{
	$txtRen = ${"txtRen".$i};
	$txtAcidoF = ${"txtAcidoF".$i};
	$txtPhF = ${"txtPhF".$i};
	$txtCeF = ${"txtCeF".$i};
	$txtTempF = ${"txtTempF".$i};
	$txtNormF = ${"txtNormF".$i};
	
	/*if($txtAcidoF != '' and $txtPhF != '' and $txtCeF != '' and $txtTempF != ''  and $txtNormF != '')
	{*/
		mysqli_query($cnx, "UPDATE procesos_fase_6_d SET pfd6_acido = '$txtAcidoF', pfd6_ph = '$txtPhF', pfd6_ce = '$txtCeF', pfd6_temp = '$txtTempF', pfd6_norm = '$txtNormF' WHERE pfg6_id = '$hdd_pfg' AND pfd6_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
	//}
	}
	
	for($i = 1; $i <= 6; $i++)
	{
		$txtRen2 = ${"txtRen2".$i};
		$txtIniMovD = ${"txtIniMovD".$i};
		$txtIniRepD = ${"txtIniRepD".$i};
		$txtPhD = ${"txtPhD".$i};
		$txtCeD = ${"txtCeD".$i};
		$txtNormD = ${"txtNormD".$i};
		$txtTempD = ${"txtTempD".$i};
		
	/*if($txtIniMovD != '' and $txtIniRepD != '' )//  or ( and $i <= 3)
	{ */

		if($txtPhD == '' and $txtCeD == '' and $txtNormD == '' and $txtTempD == '')
		{
			$txtPhD = 0;
			$txtCeD = 0;
			$txtNormD = 0; 
			$txtTempD = 0;
		}
		mysqli_query($cnx, "UPDATE procesos_fase_6_d2 SET pfd6_ini_mov = '$txtIniMovD', pfd6_ini_reposo = '$txtIniRepD', pfd6_ph = '$txtPhD', pfd6_ce = '$txtCeD', pfd6_norm = '$txtNormD', pfd6_temp = '$txtTempD' WHERE pfg6_id = '$hdd_pfg' AND pfd6_ren = '$txtRen2' ") or die(mysqli_error($cnx)." Error al actualizar el renglon B ".$i);
	//}
	}
	
//Actualiza los datos de la liberación
	mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ph = '$txtPhLib' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");

	ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

	$strMsj = "Informacion Actualizada";
	$respuesta = array('mensaje' => $strMsj );
	echo json_encode($respuesta);
	?> 