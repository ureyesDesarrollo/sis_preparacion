<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Abril - 2019*/

include "../../../seguridad/user_seguridad.php";
require_once('../../../conexion/conexion.php');
include "../../../funciones/funciones.php";
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

//if($cbxAgua == ''){$cbxAgua = 0;}
if($cbxAgua != ''){
//Actualiza los datos del general de la tabla
mysqli_query($cnx, "UPDATE procesos_fase_6b_g SET taa_id = '$cbxAgua' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");
}

if($txtHrsReales != '')
{
	mysqli_query($cnx, "UPDATE procesos_fase_6b_g SET pfg6_horas_reales = '$txtHrsReales' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");
}

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
	$txtagua_a = ${"cbxAgua".$i};
	$txtObs = ${"txtObs".$i};
	
	/*if($cbxTipAg != '' and $txtTemp != '' and $txtHraIni != '' and $txtHraFin != ''  and $txtHraIniMov != '' and $txtHraFinMov != '' and $txtPh != '' and $txtCe != ''  and $txtCe != '')
	{*/
		
		mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET tpa_id = '$cbxTipAg', pfd6_temp = '$txtTemp', pfd6_hr_ini = '$txtHraIni', pfd6_hr_fin = '$txtHraFin', pfd6_hr_ini_mov = '$txtHraIniMov', pfd6_hr_fin_mov = '$txtHraFinMov', pfd6_ph = '$txtPh', pfd6_ce = '$txtCe',taa_id = '$txtagua_a', pfd6_observaciones = '$txtObs' WHERE pfg6_id = '$hdd_pfg' AND pfd6_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
	//}
}
	
//Actualiza los datos de la liberaci�n
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ce = '$txtCeLib' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj );
echo json_encode($respuesta);
?> 