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
	
	
	mysqli_query($cnx, "INSERT INTO procesos_fase_6_g(pro_id, pfg6_temp_ag, pfg6_temp, pfg6_acido, pfg6_temp2, pfg6_norm,pfg6_acido_diluido, pfg6_ph, usu_id) VALUES('$hdd_pro_id',  '$txtTemp', '$txtTemp2', '$txtAcido', '$txtTemp3', '$txtNorm', '$cbxDiluido', '$txtPh', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
	$respuesta = array('mensaje' => "Fase 6 agregada");
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
			mysqli_query($cnx, "INSERT INTO procesos_fase_6_d(pfg6_id, pfd6_ren, pfd6_acido, pfd6_ph, pfd6_ce, pfd6_temp, pfd6_norm, usu_id, pfd6_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$txtAcidoF', '$txtPhF', '$txtCeF', '$txtTempF', '$txtNormF','".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar ".$i);
			$strMsj = "Se agrego el renglon ".$i;
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
			mysqli_query($cnx, "INSERT INTO procesos_fase_6_d2(pfg6_id, pfd6_ren, pfd6_ini_mov, pfd6_ini_reposo, pfd6_ph, pfd6_ce, pfd6_norm, pfd6_temp, usu_id, pfd6_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen2', '$txtIniMovD', '$txtIniRepD', '$txtPhD', '$txtCeD', '$txtNormD', '$txtTempD', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar B".$i);
			$strMsj = "Se agrego el renglon ".$i;
		}
	}

	//Agregar informaci�n de supervisor
	if($txtFeTermS != '' and $txtHrTermS != '' and $txtHrsReales != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_6_g SET pfg6_fe_fin = '$txtFeTermS', pfg6_hr_fin = '$txtHrTermS', pfg6_hr_totales = '$txtHrsReales' WHERE pfg6_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6");

		$strMsj = "Fase 6 actualizada - A";
	}
	
	if($txtHrIniCo != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_6_g SET pfg6_hr_cocido = '$txtHrIniCo' WHERE pfg6_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6");

		$strMsj = "Fase 6 actualizada - B";
	}
	
	if($txtPh2F != '' and $txtCe2F != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_6_g SET pfg6_ph2 = '$txtPh2F', pfg6_ce2 = '$txtCe2F' WHERE pfg6_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6");

		$strMsj = "Fase 6 actualizada - C";
	}
	
	if($txtPh3F != '' and $txtCe3F != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_6_g SET pfg6_ph3 = '$txtPh3F', pfg6_ce3 = '$txtCe3F' WHERE pfg6_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6");

		$strMsj = "Fase 6 actualizada - D";
	}

	if($cbxAgua != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_6_g SET taa_id = '$cbxAgua'WHERE pfg6_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6");

		$strMsj = "Fase 6 actualizada - D";
	}

	if($strMsj != '' )
	{
		$respuesta = array('mensaje' => $strMsj );
	}
	else
	{
	
		if($txtFeTerm != '')
		{
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '".$_SESSION['idUsu']."' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");
			
			$strMsj = "Fase 6 actualizada";
		}
		else
		{
			if($txtHrTotales != '')
			{
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ph) VALUES('".$_SESSION['idUsu']."', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '$txtPhLib' ) ") or die(mysqli_error($cnx)." Error al insertar L");
	
				$strMsj = "Fase 6 parametros capturados";
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