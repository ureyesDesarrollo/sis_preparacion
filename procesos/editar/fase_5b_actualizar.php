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

if($cbxAgua == ''){$cbxAgua = 0;}
//Actualiza los datos del general de la tabla
mysqli_query($cnx, "UPDATE procesos_fase_5_g SET tpa_id = '$cbxTipAg', taa_id = '$cbxAgua' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");


//Actualiza los datos del detalle de la tabla
for($i = 1; $i <= 10; $i++)
{
	$txtRen = ${"txtRen".$i};
	$txtTemp = ${"txtTemp".$i};
	$txtHraIni = ${"txtHraIni".$i};
	$txtHraFin = ${"txtHraFin".$i};
	$txtHraIniMov = ${"txtHraIniMov".$i};
	$txtHraFinMov = ${"txtHraFinMov".$i};
	$txtPh = ${"txtPh".$i};
	$txtCe = ${"txtCe".$i};
	$txtagua_a = ${"cbxAgua".$i};
	$txtObs = ${"txtObs".$i};
	$cbxTipAgd = ${"cbxTipAgd".$i};
	
	$str_update = "pfd5_ren = '$txtRen' ";

	if($cbxTipAgd != '' or $txtTemp != '' or $txtTemp != '' or $txtHraIni != '' or $txtHraFin != '' or $txtHraFin != ''  or $txtHraIniMov != '' or $txtHraFinMov != '' or $txtPh != '' or $txtCe != '')
	{

		if($cbxTipAgd != ''){$str_update .= ",tpa_id = '$cbxTipAgd'";}
		if($txtTemp != ''){$str_update .= ", pfd5_temp = '$txtTemp'";}
		if($txtHraIni != ''){$str_update .= ", pfd5_hr_ini = '$txtHraIni'";}
		if($txtHraFin != ''){$str_update .= ", pfd5_hr_fin = '$txtHraFin'";}
		if($txtHraIniMov != ''){$str_update .= ", pfd5_hr_ini_mov = '$txtHraIniMov'";}
		if($txtHraFinMov != ''){$str_update .= ", pfd5_hr_fin_mov = '$txtHraFinMov'";}
		if($txtPh != ''){$str_update .= ", pfd5_ph = '$txtPh'";}
		if($txtCe != ''){$str_update .= ", pfd5_ce = '$txtCe'";}
		if($txtagua_a != ''){$str_update .= ",taa_id = '$txtagua_a'";}
		if($txtObs != ''){$str_update .= ", pfd5_observaciones = '$txtObs' ";}

		//mysqli_query($cnx, "UPDATE procesos_fase_5_d SET pfd5_temp = '$txtTemp', pfd5_hr_ini = '$txtHraIni', pfd5_hr_fin = '$txtHraFin', pfd5_hr_ini_mov = '$txtHraIniMov', pfd5_hr_fin_mov = '$txtHraFinMov', pfd5_ph = '$txtPh', pfd5_ce = '$txtCe',taa_id = '$txtagua_a' , pfd5_observaciones = '$txtObs' WHERE pfg5_id = '$hdd_pfg' AND pfd5_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
		mysqli_query($cnx, "UPDATE procesos_fase_5_d SET $str_update WHERE pfg5_id = '$hdd_pfg' AND pfd5_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
	}
}
	
//Actualiza los datos de la liberación
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ce = '$txtCeLib' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj );
echo json_encode($respuesta);
?> 