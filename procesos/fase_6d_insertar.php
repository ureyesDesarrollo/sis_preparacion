<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST); 

if($txtFeIni != '')
{
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 1");
	
	mysqli_query($cnx, "INSERT INTO procesos_fase_6b_g(pro_id, pe_id, usu_id) VALUES('$hdd_pro_id', '$hdd_pe_id', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
	$respuesta = array('mensaje' => "Fase 6d agregada");
}
else
{
	for($i = 1; $i <= 10; $i++)
	{
		$txtRen = ${"txtRen".$i};
		$hddRen = ${"hddRen".$i};
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
		
		/*if($cbxTipAg != '' and $txtTemp != '' and $txtHraIni != '' and $txtHraFin != '' and $txtHraFin != ''  and $txtHraIniMov != '' and $txtHraFinMov != '' and $txtPh != '' and $txtCe != '')
		{
			mysqli_query($cnx, "INSERT INTO procesos_fase_6b_d(pfg6_id, pfd6_ren, tpa_id, pfd6_temp, pfd6_hr_ini, pfd6_hr_fin, pfd6_hr_ini_mov, pfd6_hr_fin_mov, pfd6_ph, pfd6_ce,  usu_id, pfd6_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$cbxTipAg', '$txtTemp', '$txtHraIni', '$txtHraFin', '$txtHraIniMov', '$txtHraFinMov', '$txtPh', '$txtCe', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 1");
			
			$strMsj = "Se agrego el renglon ".$i;
		}*/
		if($hddRen == '' and $cbxTipAg != '')
		{
			mysqli_query($cnx, "INSERT INTO procesos_fase_6b_d(pfg6_id, pfd6_ren, tpa_id, usu_id, pfd6_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$cbxTipAg', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 1");
				$strMsj = "Se agrego el renglon ".$i;
		}
		else
		{ 
		
			if($hddRen != '' and $txtTemp != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET pfd6_temp = '$txtTemp' WHERE pfd6_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtHraIni  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET pfd6_hr_ini = '$txtHraIni' WHERE pfd6_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtHraFin  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET pfd6_hr_fin = '$txtHraFin' WHERE pfd6_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $hddRen != '' and $txtHraIniMov  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET pfd6_hr_ini_mov = '$txtHraIniMov' WHERE pfd6_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $hddRen != '' and $txtHraFinMov  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET pfd6_hr_fin_mov = '$txtHraFinMov' WHERE pfd6_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtPh  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET pfd6_ph = '$txtPh' WHERE pfd6_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtCe  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET pfd6_ce = '$txtCe' WHERE pfd6_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtagua_a  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET taa_id = '$txtagua_a' WHERE pfd6_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtObs  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET pfd6_observaciones = '$txtObs' WHERE pfd6_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
		}
	}

	if($strMsj != '')
	{
		$respuesta = array('mensaje' => $strMsj );
	}
	else
	{
		if($txtFeTerm != '')
		{
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '".$_SESSION['idUsu']."' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");
			
			mysqli_query($cnx, "UPDATE procesos_fase_6b_g SET taa_id = null, pfg6_horas_reales = '$txtHrsReales' WHERE pfg6_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6g");

			$strMsj = "Fase 6d actualizada";
		}
		else
		{
			if($txtHrTotales != '')
			{
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ce) VALUES('".$_SESSION['idUsu']."', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '$txtCeLib') ") or die(mysqli_error($cnx)." Error al insertar L");
	
				$strMsj = "Fase 6d parametros capturados";
			}
			else
			{
				$strMsj = "Esta vacio";
			}
		}
			
		$respuesta = array('mensaje' => $strMsj );
	}

}


echo json_encode($respuesta);
?> 