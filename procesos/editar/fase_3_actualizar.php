<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST); 

//Actualiza los datos del auxiliar
/*if($txtEnzima == '')
{
		mysqli_query($cnx, "UPDATE procesos_fase_3_g SET  pfg3_enzima = '0'") or die(mysqli_error($cnx)." Error al actualizar enzima");
}*/

if($txtFeTerm == '' and $txtHrTerm == '')
{
	mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");

	mysqli_query($cnx, "UPDATE procesos_fase_3_g SET pro_id = '$hdd_pro_id', pfg3_enzima = '$txtEnzima', usu_id = '".$_SESSION['idUsu']."' ") or die(mysqli_error($cnx)." Error al insertar 2");
}
else
{
	mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni', proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");
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
		$txtPpm = ${"txtPpm".$i};
		$txtagua_a = ${"cbxAgua".$i};
		$txtObs = ${"txtObs".$i};
		
		$str_update = "pfd3_ren = '$txtRen' ";

		if($cbxTipAg != '' or $txtTemp != '' or $txtHraIni != '' or $txtHraFin != '' or $txtHraIniMov != '' or $txtHraFinMov != '' or $txtPh != '' or $txtCe != '' or $txtPpm != '' or $txtagua_a != '')
		{

			if($cbxTipAg != ''){$str_update .= ",tpa_id = '$cbxTipAg'";}
		if($txtTemp != ''){$str_update .= ", pfd3_temp = '$txtTemp'";}
		if($txtHraIni != ''){$str_update .= ", pfd3_hr_ini = '$txtHraIni'";}
		if($txtHraFin != ''){$str_update .= ", pfd3_hr_fin = '$txtHraFin'";}
		if($txtHraIniMov != ''){$str_update .= ", pfd3_hr_ini_mov = '$txtHraIniMov'";}
		if($txtHraFinMov != ''){$str_update .= ", pfd3_hr_fin_mov = '$txtHraFinMov'";}
		if($txtPh != ''){$str_update .= ", pfd3_ph = '$txtPh'";}
		if($txtCe != ''){$str_update .= ", pfd3_ce = '$txtCe'";}
		if($txtPpm != ''){$str_update .= ", pfd3_ppm = '$txtPpm'";}
		if($txtagua_a != ''){$str_update .= ",taa_id = '$txtagua_a'";}
		if($txtObs != ''){$str_update .= ", pfd3_observaciones = '$txtObs' ";}

			/*mysqli_query($cnx, "UPDATE procesos_fase_3_d SET tpa_id = '$cbxTipAg', pfd3_temp = '$txtTemp', pfd3_hr_ini = '$txtHraIni', pfd3_hr_fin = '$txtHraFin', pfd3_hr_ini_mov = '$txtHraIniMov', pfd3_hr_fin_mov = '$txtHraFinMov', pfd3_ph = '$txtPh', pfd3_ce = '$txtCe', pfd3_ppm = '$txtPpm',taa_id = '$txtagua_a', pfd3_observaciones = '$txtObs' WHERE pro_id = '$hdd_pro_id' AND pfd3_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);*/

		mysqli_query($cnx, "UPDATE procesos_fase_3_d SET $str_update WHERE pro_id = '$hdd_pro_id' AND pfd3_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
		}
	}

	
//Actualiza los datos de la liberación
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ce = '$txtCeLib' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj );
echo json_encode($respuesta);
?> 