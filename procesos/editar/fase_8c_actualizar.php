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
//mysqli_query($cnx, "UPDATE procesos_fase_8_g SET pfg1_temp_ag = '$txtTemp' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");

//Actualiza los datos del detalle de la tabla
for($i = 1; $i <= 10; $i++)
{
	$txtRen = ${"txtRen".$i};
	$txtMov = ${"txtMov".$i};
	$txtIniLlen = ${"txtIniLlen".$i};
	$txtFinLlen = ${"txtFinLlen".$i};
	$txtIniDren = ${"txtIniDren".$i};
	$txtFinDren = ${"txtFinDren".$i};
	$txtPh = ${"txtPh".$i};
	$txtCe = ${"txtCe".$i};
	$txtTemp = ${"txtTemp".$i};
	$txtagua_a = ${"cbxAgua".$i};
	$txtObs = ${"txtObs".$i};
		
	$cbxTipAgd = ${"cbxTipAgd".$i};
	$str_update = "pfd8_ren = '$txtRen' ";


	if($cbxTipAgd != '' or $txtMov != '' or $txtIniLlen != '' or $txtFinLlen != '' or $txtIniDren != '' or $txtFinDren != '' or $txtPh != '' or $txtCe != '' or $txtTemp != '' or $txtagua_a != '' )
	{ 
		if($cbxTipAgd != ''){$str_update .= ",tpa_id = '$cbxTipAgd'";}
		if($txtMov != ''){$str_update .= ", pfd8_mov = '$txtMov'";}
		if($txtIniLlen != ''){$str_update .= ", pfd8_ini_llenado = '$txtHIniDrenado'";}
		if($txtFinLlen != ''){$str_update .= ", pfd8_fin_llenado = '$txtHFinDrenado'";}
		if($txtIniDren != ''){$str_update .= ", pfd8_ini_dren = '$txtHIniDrenado'";}
		if($txtFinDren != ''){$str_update .= ", pfd8_fin_dren = '$txtHFinDrenado'";}
		if($txtPh != ''){$str_update .= ", pfd8_ph = '$txtPh'";}
		if($txtCe != ''){$str_update .= ", pfd8_ce = '$txtCe'";}
		if($txtTemp != ''){$str_update .= ", pfd8_temp = '$txtTemp'";}
		if($txtagua_a != ''){$str_update .= ",taa_id = '$txtagua_a'";}
		if($txtObs != ''){$str_update .= ", pfd8_observaciones = '$txtObs' ";}

		/*mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_mov = '$txtMov', pfd8_ini_llenado = '$txtIniLlen', pfd8_fin_llenado = '$txtFinLlen', pfd8_ini_dren = '$txtIniDren', pfd8_fin_dren = '$txtFinDren', pfd8_ph = '$txtPh', pfd8_ce = '$txtCe', pfd8_temp = '$txtTemp', taa_id = '$txtagua_a' ,pfd8_observaciones = '$txtObs' WHERE pfg8_id = '$hdd_pfg' AND pfd8_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);*/
		mysqli_query($cnx, "UPDATE procesos_fase_8_d SET $str_update WHERE pfg8_id = '$hdd_pfg' AND pfd8_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
	}
}
	
//Actualiza los datos de la liberación
mysqli_query($cnx, "UPDATE procesos_liberacion_b SET prol_fecha = '$txtFeLib', prol_hora = '$txtHrLib', prol_cocido_ph1 = '$txtPhLib1', prol_ce1 = '$txtCeLib1', prol_cocido_ph2 = '$txtPhLib2', prol_ce2 = '$txtCeLib2', prol_cocido_lib = '$txtCocidoLib', prol_color = '$cbxColor', prol_solides = '$txtSolides' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");

if($txtHrTotales1 != '')
{
	mysqli_query($cnx, "UPDATE procesos_fase_8_g SET pfg8_hr_totales = '$txtHrTotales1', usu_sup = '".$_SESSION['idUsu']."' WHERE pfg8_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 8-A");
}

if($txtHrasTotales != '')
{
	mysqli_query($cnx, "UPDATE procesos_fase_8_g SET pfg8_hr_totales2 = '$txtHrasTotales' WHERE pfg8_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 8-B");
}

if($txtFeLibProd != '' and $txtHrLibProd != '')
{
	mysqli_query($cnx, "UPDATE procesos_fase_8_g SET pfg8_fe_lib_prod = '$txtFeLibProd', pfg8_hr_lib_prod = '$txtHrLibProd' WHERE pfg8_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 8-C");
}

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj );
echo json_encode($respuesta);
?> 