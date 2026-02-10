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
	
	mysqli_query($cnx, "INSERT INTO procesos_fase_5b_g(pro_id, pe_id, pfg5_temp_ag, pfg5_temp, pfg5_acido, pfg5_termina, pfg5_temp2, usu_id) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtTemp', '$txtTemp2', '$txtAcido', '$txtTermina', '$txtTemp3', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
	$respuesta = array('mensaje' => "Fase 5 agregada");
}
else
{
	for($i = 1; $i <= 8; $i++)
	{
		$txtRen = ${"txtRen".$i};
		$txtAcidoF = ${"txtAcidoF".$i};
		$txtPhF = ${"txtPhF".$i};
		$txtPhBF = ${"txtPhBF".$i};
		$txtTempF = ${"txtTempF".$i};
		
		if($txtAcidoF != '' and $txtPhF != '' and $txtPhBF != '' and $txtTempF != '')
		{
			mysqli_query($cnx, "INSERT INTO procesos_fase_5b_d(pfg5_id, pfd5_ren, pfd5_acido, pfd5_ph, pfd5_ph_b, pfd5_temp, usu_id, pfd5_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$txtAcidoF', '$txtPhF', '$txtPhBF', '$txtTempF', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar ".$i);
			$strMsj = "Se agrego el renglon ".$i;
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
			
			mysqli_query($cnx, "UPDATE procesos_fase_5b_g SET pfg5_cocido_ph = '$txtCocido', taa_id = '$cbxAgua', pfg5_ce_agua = '$txtCeAgua', pfg5_ph_agua = '$txtPhAgua' WHERE pfg5_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 4g");

			$strMsj = "Fase 5 actualizada";
		}
		else
		{
			if($txtHrTotales != '' and $cbxAdel != '')
			{
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ph, prol_adelgasamiento) VALUES('".$_SESSION['idUsu']."', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '$txtPhLib', '$cbxAdel') ") or die(mysqli_error($cnx)." Error al insertar L");
	
				$strMsj = "Fase 5 parametros capturados";
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