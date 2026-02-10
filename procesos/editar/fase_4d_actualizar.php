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
mysqli_query($cnx, "UPDATE procesos_fase_4b_g SET pfg4_horas_reales = '$txtHrsReales' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");

//Actualiza los datos del detalle de la tabla
for($i = 1; $i <= 10; $i++)
{
	$txtRen = ${"txtRen".$i};
	$cbxTipAg = ${"cbxTipAg".$i};
	$txtTemp = ${"txtTemp".$i};
	$txtHraIni = ${"txtHraIni".$i};
	$txtHraFin = ${"txtHraFin".$i};
	$txtHraIniMov = ${"txtHraIniMov".$i};
	$txtHraFinMov = ${"txtHraFinMov".$i};
	$txtPh = ${"txtPh".$i};
	$txtCe = ${"txtCe".$i};
	$txtPpm = ${"txtPpm".$i};
	$txtagua_a = ${"cbxAgua".$i};
	$txtObs = ${"txtObs".$i};
	$str_update = "pfd4_ren = '$txtRen' ";

	if($cbxTipAg != '' or $txtTemp != '' or $txtHraIni != '' or $txtHraFin != '' or $txtHraIniMov != '' or $txtHraFinMov != '' or $txtPh != '' or $txtCe != '' or $txtagua_a != '' )
	{
		if($cbxTipAg != ''){$str_update .= ",tpa_id = '$cbxTipAg'";}
		if($txtTemp != ''){$str_update .= ", pfd4_temp = '$txtTemp'";}
		if($txtHraIni != ''){$str_update .= ", pfd4_hr_ini = '$txtHraIni'";}
		if($txtHraFin != ''){$str_update .= ", pfd4_hr_fin = '$txtHraFin'";}
		if($txtHraIniMov != ''){$str_update .= ", pfd4_hr_ini_mov = '$txtHraIniMov'";}
		if($txtHraFinMov != ''){$str_update .= ", pfd4_hr_fin_mov = '$txtHraFinMov'";}
		if($txtPh != ''){$str_update .= ", pfd4_ph = '$txtPh'";}
		if($txtCe != ''){$str_update .= ", pfd4_ce = '$txtCe'";}
		if($txtPpm != ''){$str_update .= ", pfd4_ppm = '$txtPpm'";}
		if($txtagua_a != ''){$str_update .= ",taa_id = '$txtagua_a'";}
		if($txtObs != ''){$str_update .= ", pfd4_observaciones = '$txtObs' ";}

		/*mysqli_query($cnx, "UPDATE procesos_fase_4b_d SET tpa_id = '$cbxTipAg', pfd4_temp = '$txtTemp', pfd4_hr_ini = '$txtHraIni', pfd4_hr_fin = '$txtHraFin', pfd4_hr_ini_mov = '$txtHraIniMov', pfd4_hr_fin_mov = '$txtHraFinMov', pfd4_ph = '$txtPh', pfd4_ce = '$txtCe',taa_id = '$txtagua_a', pfd4_observaciones = '$txtObs', pfd4_ppm = '$txtPpm' WHERE pfg4_id = '$hdd_pfg' AND pfd4_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);*/

		mysqli_query($cnx, "UPDATE procesos_fase_4b_d SET $str_update WHERE pfg4_id = '$hdd_pfg' AND pfd4_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
	}
}

//Actualiza los datos de la liberación
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ce = '$txtCeLib' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj );
echo json_encode($respuesta);
?> 