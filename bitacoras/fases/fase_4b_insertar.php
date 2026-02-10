<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST); 

if($txtFeIni != '')
{
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 1");
	
	mysqli_query($cnx, "INSERT INTO procesos_fase_4_g(pro_id, pe_id, pfg4_temp_ag, pfg4_temp, pfg4_acido, pfg4_termina, pfg4_temp2, pfg4_acido_fuerte, pfg4_enzima,usu_id) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtTemp', '$txtTemp2', '$txtAcido', '$txtTermina', '$txtTemp3', '$cbxAcidoF', '0','".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
	$respuesta = array('mensaje' => "Fase 4b agregada");
}
else
{
	for($i = 1; $i <= 8; $i++)
	{
		$txtRen = ${"txtRen".$i};
		$txtAcidoF = ${"txtAcidoF".$i};
		$txtPhF = ${"txtPhF".$i};
		$txtTempF = ${"txtTempF".$i};
		
		if($txtAcidoF != '' and $txtPhF != '' and $txtTempF != '')
		{
			mysqli_query($cnx, "INSERT INTO procesos_fase_4_d(pfg4_id, pfd4_ren, pfd4_acido, pfd4_ph, pfd4_temp,pfd4_ppm, usu_id, pfd4_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$txtAcidoF', '$txtPhF', '$txtTempF','0','".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar ".$i);
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
			
			mysqli_query($cnx, "UPDATE procesos_fase_4_g SET pfg4_cocido_ph = '$txtCocido', pfg4_ce = '$txtCeG' WHERE pfg4_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 4g");

			$strMsj = "Fase 4b actualizada";
		}
		else
		{
			if($txtHrTotales != '' and $cbxAdel != '')
			{
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ph, prol_adelgasamiento) VALUES('".$_SESSION['idUsu']."', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '$txtPhLib', '$cbxAdel') ") or die(mysqli_error($cnx)." Error al insertar L");
	
				$strMsj = "Fase 4b parametros capturados";
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