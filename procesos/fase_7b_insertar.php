<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
require("../alertas/class.phpmailer.php");
$cnx = Conectarse();



extract($_POST); 
$sql_usu = mysqli_query($cnx, "SELECT * FROM usuarios WHERE usu_id = '".$_SESSION['idUsu']."'") or die(mysql_error()."Error: en consultar usuarios");
$reg_usu= mysqli_fetch_assoc($sql_usu);

if ($txt_lavador == '') { $lavador = '0'; }else{ $lavador = "$txt_lavador"; }

if ($txt_paleto == '') { $paleto = '0'; }else{ $paleto = "$txt_paleto"; }

if($txtFeIni != '' and $txtHrIni != '' and $txtTemp != '')
{
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 1");
	
	mysqli_query($cnx, "INSERT INTO procesos_fase_7b_g(pro_id, pe_id, pfg7_temp_ag, pfg7_acido_diluido,pfg7_temp,  pfg7_acido, pfg7_norm, pfg7_ph, pfg7_ce, usu_id) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtTemp', '$cbxDiluido','$txtTemp2', '$txtAcido', '$txtNorm',  '$txtPh', '$txtCe', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
	$respuesta = array('mensaje' => "Fase 7 agregada");
}
else
{
	for($i = 1; $i <= 8; $i++)
	{
		$txtRen = ${"txtRen".$i};
		$txtAcidoF = ${"txtAcidoF".$i};
		$txtPhF = ${"txtPhF".$i};
		$txtCeF = ${"txtCeF".$i};
		$txtTempF = ${"txtTempF".$i};
		$txtNormF = ${"txtNormF".$i};
		
		if($txtAcidoF != '' and $txtPhF != '' and $txtCeF != '' and $txtTempF != ''  and $txtNormF != '')
		{
			mysqli_query($cnx, "INSERT INTO procesos_fase_7b_d(pfg7_id, pfd7_ren, pfd7_acido, pfd7_ph, pfd7_ce, pfd7_temp, pfd7_norm, usu_id, pfd7_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$txtAcidoF', '$txtPhF', '$txtCeF', '$txtTempF', '$txtNormF','".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar ".$i);
			$strMsj = "Se agrego el renglon ".$i;

			 //fnc_alertas($etapa, $parametro, $proceso, $valor, $usuario, $lavador, $paleto, $tipo);
			fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhF, $_SESSION['idUsu'], $lavador, $paleto, 'R', 'N', $txtNormF );
		    /*fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhF, $_SESSION['idUsu'], $lavador, $paleto, 'R');
			fnc_alertas_v2($hdd_pe_id, 'N', $hdd_pro_id, $txtNormF, $_SESSION['idUsu'], $lavador, $paleto, 'R');*/
		}
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

		if($txtIniMovD != '' and $txtIniRepD != '' )//  or ( and $i <= 3)
		{ 

			if($txtPhD == '' and $txtCeD == '' and $txtNormD == '' and $txtTempD == '')
			{
				$txtPhD = 0;
				$txtCeD = 0;
				$txtNormD = 0; 
				$txtTempD = 0;
			}
			mysqli_query($cnx, "INSERT INTO procesos_fase_7b_d2(pfg7_id, pfd7_ren, pfd7_ini_mov, pfd7_ini_reposo, pfd7_ph, pfd7_norm, pfd7_temp, usu_id, pfd7_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen2', '$txtIniMovD', '$txtIniRepD', '$txtPhD', '$txtNormD', '$txtTempD', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar B".$i);
			$strMsj = "Se agrego el renglon ".$i;

			 //fnc_alertas($etapa, $parametro, $proceso, $valor, $usuario, $lavador, $paleto, $tipo);
			 fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhD, $_SESSION['idUsu'], $lavador, $paleto, 'L', 'N', $txtNormD );
		    /*fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhD, $_SESSION['idUsu'], $lavador, $paleto, 'R');
			fnc_alertas_v2($hdd_pe_id, 'N', $hdd_pro_id, $txtNormD, $_SESSION['idUsu'], $lavador, $paleto, 'R');*/
		}
	}

	if($txtHrTotales1 != '' and $txtFeTermA != '' and $txtHrTermA != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_hr_totales = '$txtHrTotales1', pfg7_fe_fin = '$txtFeTermA', pfg7_hr_fin = '$txtHrTermA', usu_sup = '".$_SESSION['idUsu']."' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - A";
	}
	
	if($txtHrIniC != '' and $txtPhR1 != '' and $txtPhR2 != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_hr_ini_co = '$txtHrIniC', pfg7_agua_ph = '$txtPhR1', pfg7_cocido_ph = '$txtPhR2', usu_sup = '".$_SESSION['idUsu']."' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		//$strMsj = "Fase 7 actualizada - B";
	}
	
	if($txtCeR1 != '' and $txtCeR2 != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_agua_ce = '$txtCeR1', pfg7_cocido_ce = '$txtCeR2'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - C";
	}
	
	if($cbxAgua != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET taa_id = '$cbxAgua'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		//$strMsj = "Fase 7 actualizada - D";
	}
	
	if($txtHrsReales != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_horas_reales = '$txtHrsReales'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - E";
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
			
			/*mysqli_query($cnx, "UPDATE procesos_fase_5b_g SET pfg5_cocido_ph = '$txtCocido', pfg5_ph_agua = '$txtPhAgua', pfg5_ce_agua = '$txtCeAgua', pfg5_agua_a = '$txtAguaA' WHERE pfg5_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 4g");*/

			$strMsj = "Fase 7 actualizada";
		}
		else
		{
			if($txtHrTotales != '' and $reg_usu['up_id'] == '6' )
			{
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ph) VALUES('".$_SESSION['idUsu']."', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '$txtPhLib') ") or die(mysqli_error($cnx)." Error al insertar L");

				fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhLib, $_SESSION['idUsu'], $lavador, $paleto, 'L', 'Hr', $txtHrTotales );
				/*fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhLib, $_SESSION['idUsu'], $lavador, $paleto, 'L');
				fnc_alertas_v2($hdd_pe_id, 'Hr', $hdd_pro_id, $txtHrTotales, $_SESSION['idUsu'], $lavador, $paleto, 'L');*/

				$strMsj = "Fase 7 parametros capturados";
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