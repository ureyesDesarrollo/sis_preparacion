<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();


extract($_POST); 
$sql_usu = mysqli_query($cnx, "SELECT * FROM usuarios WHERE usu_id = '".$_SESSION['idUsu']."'") or die(mysql_error()."Error: en consultar usuarios");
$reg_usu= mysqli_fetch_assoc($sql_usu);

if($txtFeIni != '')
{
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 1");
	
	mysqli_query($cnx, "INSERT INTO procesos_fase_7_g(pro_id, pfg7_hr_totales, usu_id) VALUES('$hdd_pro_id', '0', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
	$respuesta = array('mensaje' => "Fase 7 agregada");
}
else
{
	for($i = 1; $i <= 10; $i++)
	{
		$txtRen = ${"txtRen".$i};
		$hddRen = ${"hddRen".$i};
		$txtMov = ${"txtMov".$i};
		$txtHIniDrenado = ${"txtHIniDrenado".$i};
		$txtHFinDrenado = ${"txtHFinDrenado".$i};
		$txtPh = ${"txtPh".$i};
		$txtCe = ${"txtCe".$i};
		$txtTemp = ${"txtTemp".$i};
		$txtagua_a = ${"cbxAgua".$i};
		$txtObs = ${"txtObs".$i};
		$cbxTipAg = ${"cbxTipAg".$i};
		
		if($hddRen == '' and $txtMov != ''  and $cbxTipAg != '')
		{
			mysqli_query($cnx, "INSERT INTO procesos_fase_7_d(pfg7_id, pfd7_ren,tpa_id, pfd7_mov, usu_id, pfd7_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen','$cbxTipAg', '$txtMov', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 1");
			$strMsj = "Se agrego el renglon ".$i;
		}
		else
		{ 

			if($hddRen != '' and $txtHIniDrenado != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_hr_ini_dren = '$txtHIniDrenado' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtHFinDrenado  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_hr_fin_dren = '$txtHFinDrenado' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtTemp != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_temp = '$txtTemp' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtPh  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_ph = '$txtPh' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtCe  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_ce = '$txtCe' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtagua_a  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET taa_id = '$txtagua_a' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtObs  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_observaciones = '$txtObs' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
		}
	}
	
	//Agregar información de supervisor
	if($txtHrTotales1 != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_hr_totales = '$txtHrTotales1', usu_sup = '".$_SESSION['idUsu']."' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - A";
	}
	
	if($txtHrasTotales != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_hr_totales2 = '$txtHrasTotales' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - B";
	}
	
	if($txtFeLibPal != '' and $txtHrLibPal != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_fe_lib_pal = '$txtFeLibPal', pfg7_hr_lib_pal = '$txtHrLibPal' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6");

		$strMsj = "Fase 7 actualizada - C";
	}
	
	if($txtFeLibProd != '' and $txtHrLibProd != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_fe_lib_prod = '$txtFeLibProd', pfg7_hr_lib_prod = '$txtHrLibProd' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6");

		$strMsj = "Fase 7 actualizada - D";
	}

	if($strMsj != '' )
	{
		$respuesta = array('mensaje' => $strMsj );
	}else
	{ //penultima llave

		if($txtFeTerm != '' and $txtHrTerm != '')
		{
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '".$_SESSION['idUsu']."' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");
			
			$strMsj = "Fase 7 actualizada";
		}
		else
		{ //anterpenultima llave
			if ($reg_usu['up_id'] != '3') {
			//si los dos primeros renglones de cocidos se captura algo inserta
				if($txtFeLib != '' and $txtHrLib != '' and $txtPhLib1 != '' and $txtCeLib1 != '' and $txtCue_sob1 != '' and $txtpor_ext1 != '' and $txtPhLib2 != '' and $txtCeLib2 != '' and $txtCue_sob2 != '' and $txtpor_ext2 != '')
				{
					if($txtSolides == '')
					{
						$txtSolides = 0;
					}
					mysqli_query($cnx, "INSERT INTO procesos_liberacion_b (usu_id, pro_id, pe_id, prol_fecha, prol_hora, prol_cocido_ph1, prol_ce1, prol_cocido_ph2, prol_ce2,prol_color_caldo,prol_color,prol_solides, prol_observaciones) VALUES('".$_SESSION['idUsu']."', '$hdd_pro_id', '$hdd_pe_id', '$txtFeLib', '$txtHrLib', '$txtPhLib1', '$txtCeLib1', '$txtPhLib2', '$txtCeLib2', '$cbxColor_caldo','$cbxColor', '$txtSolides','$txta_obs') ") or die(mysqli_error($cnx)." Error al insertar L");

					$sql_liberacion = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b WHERE pro_id = '$hdd_pro_id' and pe_id = '$hdd_pe_id'") or die(mysql_error()."Error: en consultar procesos_liberacion_b");
					$reg_liberacion= mysqli_fetch_assoc($sql_liberacion);
					$tot = mysqli_num_rows($sql_liberacion);

				//if ($tot > 0) {

					for ($i=1; $i <= 5; $i++) { 
						$renglon = ${"R".$i};
						$cocido = ${"txtPhLib".$i};
						$ce = ${"txtCeLib".$i};
						$cuero = ${"txtCue_sob".$i};
						$ext = ${"txtpor_ext".$i};

						if( $cocido != '' AND $ce != '' AND $cuero != '' AND $ext != '')
						{
							mysqli_query($cnx, "INSERT INTO procesos_liberacion_b_cocidos (prol_id,prol_ren , prol_cocido, prol_ce, prol_cuero_sob, prol_por_extrac) VALUES('$reg_liberacion[prol_id]','$renglon', '$cocido', '$ce', '$cuero', '$ext') ") or die(mysqli_error($cnx)." Error al insertar L");	
						}else{
							$strMsj = "No pueden quedar campos vacios del renglon cocidos".$i;
						}

					}
				//}

					$strMsj = "Fase 7 parametros capturados";
				}
				else
				{
					$strMsj = "Debe capturar al menos los 2 primeros renglones de cocidos";
				}
			}
		}
		if($strMsj != '' )
		{
			$respuesta = array('mensaje' => $strMsj );
		}else{
			$strMsj = "Debe capturar al menos los 2 primeros campos del renglon";
			$respuesta = array('mensaje' => $strMsj );
		}
	}

}

echo json_encode($respuesta);
?> 