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

if($txtFeIni != '' and $txtHrIni != '' and $txtTempB != '' and $txtPhAnt != '' and $txtCe != '' and $txtAjSosa != '' and $txtPhAj != '' and $txtPeroxido != ''  and $txtTempB != ' ' and $txtPhAnt != ' ' and $txtCe != ' ' and $txtAjSosa != ' ' and $txtPhAj != ' ' and $txtPeroxido != ' ')
{
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 1");
	
	mysqli_query($cnx, "INSERT INTO procesos_fase_2_g(pro_id, pe_id, pfg2_temp_ag, pfg2_ph_ant, pfg2_ce, pfg2_sosa, pfg2_ph_aju, pfg2_peroxido) VALUES('$hdd_pro_id', '2', '$txtTempB', '$txtPhAnt', '$txtCe', '$txtAjSosa', '$txtPhAj', '$txtPeroxido' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
	$respuesta = array('mensaje' => "Fase 2 agregada");
}
else
{
	for($i = 1; $i <= 10; $i++)
	{
		$txtRen = ${"txtRen".$i};
		$txtHr = ${"txtHr".$i};
		$txtPh = ${"txtPh".$i};
		$txtSosa = ${"txtSosa".$i};
		$txtTemp = ${"txtTemp".$i};
		$txtRedox = ${"txtRedox".$i};
		//$txtAcidoX = ${"txtAcidoX".$i};
		$txtPeroxido = ${"txtPeroxido".$i};
		
		if($txtHr != '' and $txtPh != '' and $txtSosa != '' and $txtTemp != '' and $txtRedox != '' and $txtPeroxido != '')
		{			
			mysqli_query($cnx, "INSERT INTO procesos_fase_2_d(pfg2_id, pfd2_ren, pfd2_hr, pfd2_ph, pfd2_sosa, pfd2_peroxido, pfd2_temp, pfd2_redox, pfd2_acido, usu_id, pfd2_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$txtHr', '$txtPh', '$txtSosa', '$txtPeroxido','$txtTemp', '$txtRedox', '0', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar ".$i);
			$strMsj = "Se agrego el renglon ".$i;
			
			if ($txt_lavador == '') { $lavador = '0'; }else{ $lavador = "$txt_lavador"; }

			if ($txt_paleto == '') { $paleto = '0'; }else{ $paleto = "$txt_paleto"; }

		    //fnc_alertas($etapa, $parametro, $proceso, $valor, $usuario, $lavador, $paleto, $tipo);
		    fnc_alertas($hdd_pe_id, 'ph', $hdd_pro_id, $txtPh, $_SESSION['idUsu'], $lavador, $paleto, 'R', 'ppm', $txtRedox );
		    /*fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPh, $_SESSION['idUsu'], $lavador, $paleto, 'R');
			fnc_alertas_v2($hdd_pe_id, 'ppm', $hdd_pro_id, $txtRedox, $_SESSION['idUsu'], $lavador, $paleto, 'R');*/
		}
	}
/*	if($txtLavTipAgua2!= '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, pfd1_tipo_ag, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id) VALUES('1', '$txtRen2', '$txtLavTipAgua2', '$txtTemp2', '$txtHraIni2', '$txtHraFin2', '$txtHraIniMov2', '$txtHraFinMov2', '$txtPh2', '$txtCe2', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
		$strMsj = "Se agrego el renglon 2";
	}
	if($txtLavTipAgua3 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, pfd1_tipo_ag, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id) VALUES('1', '$txtRen3', '$txtLavTipAgua3', '$txtTemp3', '$txtHraIni3', '$txtHraFin3', '$txtHraIniMov3', '$txtHraFinMov3', '$txtPh3', '$txtCe3', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 3");
		$strMsj = "Se agrego el renglon 3";
	}
	if($txtLavTipAgua4 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, pfd1_tipo_ag, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id) VALUES('1', '$txtRen4', '$txtLavTipAgua4', '$txtTemp4', '$txtHraIni4', '$txtHraFin4', '$txtHraIniMov4', '$txtHraFinMov4', '$txtPh4', '$txtCe4', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 4");
		$strMsj = "Se agrego el renglon 4";
	}
	if($txtLavTipAgua5 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, pfd1_tipo_ag, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id) VALUES('1', '$txtRen5', '$txtLavTipAgua5', '$txtTemp5', '$txtHraIni5', '$txtHraFin5', '$txtHraIniMov5', '$txtHraFinMov5', '$txtPh5', '$txtCe5', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 5");
		$strMsj = "Se agrego el renglon 5";
	}
	if($txtLavTipAgua6 != '')
	{
		mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, pfd1_tipo_ag, pfd1_temp, pfd1_hr_ini, pfd1_hr_fin, pfd1_hr_ini_mov, pfd1_hr_fin_mov, pfd1_ph, pfd1_ce, usu_id) VALUES('1', '$txtRen6', '$txtLavTipAgua6', '$txtTemp6', '$txtHraIni6', '$txtHraFin6', '$txtHraIniMov6', '$txtHraFinMov6', '$txtPh6', '$txtCe6', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 6");
		$strMsj = "Se agrego el renglon 6";
	}*/
	if($strMsj != '')
	{
		$respuesta = array('mensaje' => $strMsj );
	}
	else
	{
		if($txtFeTerm != '')
		{
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '".$_SESSION['idUsu']."' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");

			$strMsj = "Fase 2 actualizada";
		}
		else
		{
			if($txtHrTotales != '')
			{

				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_peroxido, prol_ph, prol_color) VALUES('".$_SESSION['idUsu']."', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '$txtPeroxidoL','$txtPhLib', '$cbxColor') ") or die(mysqli_error($cnx)." Error al insertar L");
	
				$strMsj = "Fase 2 parametros capturados";
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