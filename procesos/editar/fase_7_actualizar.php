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

/*if($txtHrTotales1 == ''){$txtHrTotales1 = 0;}
if($txtHrasTotales == ''){$txtHrasTotales = 0;}
if($txtFeLibPal == ''){$txtFeLibPal = '0000-00-00';}

mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_hr_totales = '$txtHrTotales1', pfg7_hr_totales2 = '$txtHrasTotales', pfg7_fe_lib_pal = '$txtFeLibPal', pfg7_hr_lib_pal = '$txtHrLibPal', pfg7_fe_lib_prod = '$txtFeLibProd', pfg7_hr_lib_prod = '$txtHrLibProd' WHERE pro_id = '$hdd_pro_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");
*/
if($txtHrTotales1 != '')
{
	mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_hr_totales = '$txtHrTotales1', usu_sup = '".$_SESSION['idUsu']."' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");
}

if($txtHrasTotales != '')
{
	mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_hr_totales2 = '$txtHrasTotales' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");
}

if($txtFeLibPal != '' and $txtHrLibPal != '')
{
	mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_fe_lib_pal = '$txtFeLibPal', pfg7_hr_lib_pal = '$txtHrLibPal' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6");
}

if($txtFeLibProd != '' and $txtHrLibProd != '')
{
	mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_fe_lib_prod = '$txtFeLibProd', pfg7_hr_lib_prod = '$txtHrLibProd' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6");
}

//Actualiza los datos del detalle de la tabla
/*for($i = 1; $i <= 10; $i++)
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
	$txtObs = ${"txtObs".$i};
	
	if($cbxTipAg != '' and $txtTemp != '' and $txtHraIni != '' and $txtHraFin != '' and $txtHraFin != ''  and $txtHraIniMov != '' and $txtHraFinMov != '' and $txtPh != '' and $txtCe != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_1_d SET tpa_id = '$cbxTipAg', pfd1_temp = '$txtTemp', pfd1_hr_ini = '$txtHraIni', pfd1_hr_fin = '$txtHraFin', pfd1_hr_ini_mov = '$txtHraIniMov', pfd1_hr_fin_mov = '$txtHraFinMov', pfd1_ph = '$txtPh', pfd1_ce = '$txtCe' WHERE pfg1_id = '$hdd_pfg' AND pfd1_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);

	}
}*/

for($i = 1; $i <= 10; $i++)
{
	$txtRen = ${"txtRen".$i};
	$txtMov = ${"txtMov".$i};
	$txtHIniDrenado = ${"txtHIniDrenado".$i};
	$txtHFinDrenado = ${"txtHFinDrenado".$i};
	$txtPh = ${"txtPh".$i};
	$txtCe = ${"txtCe".$i};
	$txtTemp = ${"txtTemp".$i};
	$txtagua_a = ${"cbxAgua".$i};
	$txtObs = ${"txtObs".$i};
	$cbxTipAgd = ${"cbxTipAgd".$i};

	$str_update = "pfd7_ren = '$txtRen' ";

	if($cbxTipAgd != '' or $txtMov != '' or $txtHIniDrenado != '' or $txtHFinDrenado != '' or $txtPh != ''  or $txtCe != '' or $txtTemp != '' or $txtagua_a != '' or $txtObs != ''){
	
	    if($cbxTipAgd != ''){$str_update .= ",tpa_id = '$cbxTipAgd'";}
		if($txtMov != ''){$str_update .= ", pfd7_mov = '$txtMov'";}
		if($txtHIniDrenado != ''){$str_update .= ", pfd7_hr_ini_dren = '$txtHIniDrenado'";}
		if($txtHFinDrenado != ''){$str_update .= ", pfd7_hr_fin_dren = '$txtHFinDrenado'";}
		if($txtPh != ''){$str_update .= ", pfd7_ph = '$txtPh'";}
		if($txtCe != ''){$str_update .= ", pfd7_ce = '$txtCe'";}
		if($txtTemp != ''){$str_update .= ", pfd7_temp = '$txtTemp'";}
		if($txtagua_a != ''){$str_update .= ",taa_id = '$txtagua_a'";}
		if($txtObs != ''){$str_update .= ", pfd7_observaciones = '$txtObs' ";}
		
		/*mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_mov = '$txtMov', pfd7_hr_ini_dren = '$txtHIniDrenado', pfd7_hr_fin_dren = '$txtHFinDrenado', pfd7_ph = '$txtPh', pfd7_ce = '$txtCe', pfd7_temp = '$txtTemp', taa_id = '$txtagua_a',pfd7_observaciones = '$txtObs' WHERE pfg7_id = '$hdd_pfg' AND pfd7_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);*/

		mysqli_query($cnx, "UPDATE procesos_fase_7_d SET $str_update WHERE pfg7_id = '$hdd_pfg' AND pfd7_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
	}
}
	
//Actualiza los datos de la liberación
if($txtFeLib != '' and $txtHrLib != '' and $txtPhLib1 != '' and $txtCeLib1 != '' and $txtPhLib2 != '' and $txtCeLib2 != '' and $txtCocidoLib != '')
{
	mysqli_query($cnx, "UPDATE procesos_liberacion_b SET prol_fecha = '$txtFeLib', prol_hora = '$txtHrLib', prol_cocido_ph1 = '$txtPhLib1', prol_ce1 = '$txtCeLib1', prol_cocido_ph2 = '$txtPhLib2', prol_ce2 = '$txtCeLib2', prol_cocido_lib = '$txtCocidoLib, prol_color = '$cbxColor', prol_solides = '$txtSolides' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");
}

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj );
echo json_encode($respuesta);
?> 