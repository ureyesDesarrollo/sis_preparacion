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

	mysqli_query($cnx, "INSERT INTO procesos_fase_8_g(pro_id, pe_id, usu_id) VALUES('$hdd_pro_id', '$hdd_pe_id', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
	$respuesta = array('mensaje' => "Fase 8 agregada");
}
else
{
	for($i = 1; $i <= 10; $i++)
	{
		$txtRen = ${"txtRen".$i};
		$hddRen = ${"hddRen".$i};
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
		$cbxTipAg = ${"cbxTipAg".$i};

		if($hddRen == '' and $txtMov != ''  and $cbxTipAg != '')
		{
			mysqli_query($cnx, "INSERT INTO procesos_fase_8_d (pfg8_id, pfd8_ren,tpa_id, pfd8_mov, usu_id, pfd8_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen','$cbxTipAg','$txtMov', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar 1");
			$strMsj = "Se agrego el renglon ".$i;
		}
		else
		{ 

			if($hddRen != '' and $txtIniLlen != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_ini_llenado = '$txtIniLlen' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtFinLlen  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_fin_llenado = '$txtFinLlen' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtIniDren != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_ini_dren = '$txtIniDren' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtFinDren  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_fin_dren = '$txtFinDren' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtTemp != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_temp = '$txtTemp' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtPh  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_ph = '$txtPh' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtCe  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_ce = '$txtCe' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtagua_a  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET taa_id = '$txtagua_a' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
			if($hddRen != '' and $txtObs  != '')
			{
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_observaciones = '$txtObs' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
				$strMsj = "Se modifico el renglon ".$i;
			}
		}
	}
	
	if($txtHrTotales1 != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_8_g SET pfg8_hr_totales = '$txtHrTotales1', usu_sup = '".$_SESSION['idUsu']."' WHERE pfg8_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 8");

		$strMsj = "Fase 8 actualizada - A";
	}
	
	if($txtHrasTotales != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_8_g SET pfg8_hr_totales2 = '$txtHrasTotales' WHERE pfg8_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 8");

		$strMsj = "Fase 8 actualizada - B";
	}

	if($txtFeLibProd != '' and $txtHrLibProd != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_8_g SET pfg8_fe_lib_prod = '$txtFeLibProd', pfg8_hr_lib_prod = '$txtHrLibProd' WHERE pfg8_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 6");

		$strMsj = "Fase 8 actualizada - C";
	}
	
	/*if($txtHrTotales1 != '' and $txtFeTermA != '' and $txtHrTermA != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_hr_totales = '$txtHrTotales1', pfg7_fe_fin = '$txtFeTermA', pfg7_hr_fin = '$txtHrTermA', usu_sup = '".$_SESSION['idUsu']."' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - A";
	}
	
	if($txtHrIniC != '' and $txtPhR1 != '' and $txtPhR2 != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_hr_ini_co = '$txtHrIniC', pfg7_agua_ph = '$txtPhR1', pfg7_cocido_ph = '$txtPhR2', usu_sup = '".$_SESSION['idUsu']."' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - B";
	}
	
	if($txtCeR1 != '' and $txtCeR2 != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_agua_ce = '$txtCeR1', pfg7_cocido_ce = '$txtCeR2'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - C";
	}
	
	if($txtAguaA != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_agua_a = '$txtAguaA'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - D";
	}
	
	if($txtHrsReales != '')
	{
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_horas_reales = '$txtHrsReales'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - E";
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
			
			/*mysqli_query($cnx, "UPDATE procesos_fase_5b_g SET pfg5_cocido_ph = '$txtCocido', pfg5_ph_agua = '$txtPhAgua', pfg5_ce_agua = '$txtCeAgua', pfg5_agua_a = '$txtAguaA' WHERE pfg5_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 4g");*/

			$strMsj = "Fase 8 actualizada";
		}
		else
		{
			if ($reg_usu['up_id'] != '3') {
			//si los dos primeros renglones de cocidos se captura algo inserta
				if($txtFeLib != '' and $txtHrLib != '' and $txtPhLib1 != '' and $txtCeLib1 != '' and $txtCue_sob1 != '' and $txtpor_ext1 != '' and $txtPhLib2 != '' and $txtCeLib2 != '' and $txtCue_sob2 != '' and $txtpor_ext2 != '')
				{
					if($txtSolides == '')
					{
						$txtSolides = 0;
					}
					mysqli_query($cnx, "INSERT INTO procesos_liberacion_b (usu_id, pro_id, pe_id, prol_fecha, prol_hora, prol_cocido_ph1, prol_ce1, prol_cocido_ph2, prol_ce2,prol_color_caldo, prol_color, prol_solides,prol_observaciones) VALUES('".$_SESSION['idUsu']."', '$hdd_pro_id', '$hdd_pe_id', '$txtFeLib', '$txtHrLib', '$txtPhLib1', '$txtCeLib1', '$txtPhLib2', '$txtCeLib2','$cbxColor_caldo','$cbxColor','$txtSolides','$txta_obs' ) ") or die(mysqli_error($cnx)." Error al insertar L");

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
				//mysqli_query($cnx, "UPDATE preparacion_lavadores SET le_id = '6' WHERE pl_id = '$hdd_lav' ") or die(mysqli_error($cnx)." Error al actualizar z"); //Libera el Lavador
				//mysqli_query($cnx, "UPDATE procesos SET pro_estatus = 2, pro_fe_termino = SYSDATE() WHERE pro_id = '$hdd_pro_id' ") or die(mysqli_error($cnx)." Error al actualizar y");//Marca el proceso como Terminado

					$strMsj = "Fase 8 parametros capturados";
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