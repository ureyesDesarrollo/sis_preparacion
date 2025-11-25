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
	
	mysqli_query($cnx, "INSERT INTO procesos_fase_1_g(pro_id, pfg1_temp_ag, pe_id) VALUES('$hdd_pro_id', '$txtTemp', '$hdd_pe_id' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
	$respuesta = array('mensaje' => "Fase 1 agregada");
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
		
		/*if($txtPh == '')
		{
			$txtPh = 'IS NULL';
		}
		if($txtTemp == '')
		{
			$txtPh = 'IS NULL';
		}*/
		
		if($hddRen == '' and $cbxTipAg != '')
		{
			/*if($cbxTipAg != '')
			{*/
				mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$cbxTipAg', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 1");
				$strMsj = "Se agrego el renglon ".$i;
			//}
		}
		
		//if($hddRen != '' and ($txtTemp != '' or $txtHraIni != '' or $txtHraFin != '' or $txtHraFin != '' or $txtHraIniMov != '' or $txtHraFinMov != '' or $txtPh != '' or $txtCe != ''))
			else{ 
			
			if($hddRen != '' and $txtTemp != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_temp = '$txtTemp' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtHraIni  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_hr_ini = '$txtHraIni' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtHraFin  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_hr_fin = '$txtHraFin' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $hddRen != '' and $txtHraIniMov  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_hr_ini_mov = '$txtHraIniMov' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $hddRen != '' and $txtHraFinMov  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_hr_fin_mov = '$txtHraFinMov' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtPh  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_ph = '$txtPh' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtCe  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_ce = '$txtCe' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtagua_a  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET taa_id  = '$txtagua_a' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtObs  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_observaciones = '$txtObs' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
		}
		/*else
		{
			$strMsj = $hddRen;
		}*/
	}

/*	if($cbxTipAg1 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen1', '$cbxTipAg1', '$txtTemp1', '$txtHraIni1', '$txtHraFin1', '$txtHraIniMov1', '$txtHraFinMov1', '$txtPh1', '$txtCe1', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 1");
		
		$strMsj = "Se agrego el renglon 1";
	}
	if($cbxTipAg2!= '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen2', '$cbxTipAg2', '$txtTemp2', '$txtHraIni2', '$txtHraFin2', '$txtHraIniMov2', '$txtHraFinMov2', '$txtPh2', '$txtCe2', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 2");
		$strMsj = "Se agrego el renglon 2";
	}
	if($cbxTipAg3 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen3', '$cbxTipAg3', '$txtTemp3', '$txtHraIni3', '$txtHraFin3', '$txtHraIniMov3', '$txtHraFinMov3', '$txtPh3', '$txtCe3', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 3");
		$strMsj = "Se agrego el renglon 3";
	}
	if($cbxTipAg4 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen4', '$cbxTipAg4', '$txtTemp4', '$txtHraIni4', '$txtHraFin4', '$txtHraIniMov4', '$txtHraFinMov4', '$txtPh4', '$txtCe4', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 4");
		$strMsj = "Se agrego el renglon 4";
	}
	if($cbxTipAg5 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen5', '$cbxTipAg5', '$txtTemp5', '$txtHraIni5', '$txtHraFin5', '$txtHraIniMov5', '$txtHraFinMov5', '$txtPh5', '$txtCe5', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 5");
		$strMsj = "Se agrego el renglon 5";
	}
	if($cbxTipAg6 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen6', '$cbxTipAg6', '$txtTemp6', '$txtHraIni6', '$txtHraFin6', '$txtHraIniMov6', '$txtHraFinMov6', '$txtPh6', '$txtCe6', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 6");
		$strMsj = "Se agrego el renglon 6";
	}
	if($cbxTipAg7 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen7', '$cbxTipAg7', '$txtTemp7', '$txtHraIni7', '$txtHraFin7', '$txtHraIniMov7', '$txtHraFinMov7', '$txtPh7', '$txtCe7', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 7");
		$strMsj = "Se agrego el renglon 7";
	}
	if($cbxTipAg8 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen8', '$cbxTipAg8', '$txtTemp8', '$txtHraIni8', '$txtHraFin8', '$txtHraIniMov8', '$txtHraFinMov8', '$txtPh8', '$txtCe8', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 8");
		$strMsj = "Se agrego el renglon 8";
	}
	if($cbxTipAg9 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen9', '$cbxTipAg9', '$txtTemp9', '$txtHraIni9', '$txtHraFin9', '$txtHraIniMov9', '$txtHraFinMov9', '$txtPh9', '$txtCe9', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 9");
		$strMsj = "Se agrego el renglon 9";
	}
	if($cbxTipAg10 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen10', '$cbxTipAg10', '$txtTemp10', '$txtHraIni10', '$txtHraFin10', '$txtHraIniMov10', '$txtHraFinMov10', '$txtPh10', '$txtCe10', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 8");
		$strMsj = "Se agrego el renglon 10";
	}*/
	 if($strMsj == '')
	{
		if($txtFeTerm != '')
		{
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '".$_SESSION['idUsu']."' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");

			$strMsj = "Fase 1 actualizada";
		}
		else
		{
			if($txtHrTotales != '')
			{
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ce) VALUES('".$_SESSION['idUsu']."', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '$txtCeLib') ") or die(mysqli_error($cnx)." Error al insertar L");
	
				$strMsj = "Fase 1 parametros capturados";
			}
			else
			{
				$strMsj = "Esta vacio";
			}
		}
	}
	
	$respuesta = array('mensaje' => $strMsj );
}


echo json_encode($respuesta);
?> 