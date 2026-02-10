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

if ($txt_lavador == '') { $lavador = '0'; }else{ $lavador = "$txt_lavador"; }

if ($txt_paleto == '') { $paleto = '0'; }else{ $paleto = "$txt_paleto"; }

if($txtFeIni != ''  and $txtHrIni != '' and $txtTemp != '' and $txtEnzima != '' and $txtTemp != ' ' and $txtEnzima != ' ')
{
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 1");
	
	mysqli_query($cnx, "INSERT INTO procesos_fase_2b_g(pro_id,  pfg2_temp_ag, pfg2_enzima, usu_id) VALUES('$hdd_pro_id', '$txtTemp', '$txtEnzima', '".$_SESSION['idUsu']."' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
	$respuesta = array('mensaje' => "Fase 2 agregada");
}
else
{
	for($i = 1; $i <=6; $i++)
	{ 
		//if($i > 2){$i+=1;}
		
		$txtRen = ${"txtRen".$i};
		$txtHoraD = ${"txtHoraD".$i};
		$txtPhD = ${"txtPhD".$i};
		$txtSosaD = ${"txtSosaD".$i};
		$txtAcidoD = ${"txtAcidoD".$i};
		//echo $i.$txtHora." -".$txtPhD." -".$txtSosaD."\n";	
		if($txtHoraD != '' and $txtPhD != '' and $txtSosaD != '' and $txtAcidoD != '')//  
		{
			mysqli_query($cnx, "INSERT INTO procesos_fase_2b_d(pfg2_id, pfd2_ren, pfd2_hr, pfd2_ph, pfd2_sosa, pfd2_acido, usu_id, pfd2_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$txtHoraD', '$txtPhD', '$txtSosaD', '$txtAcidoD', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar ".$i);
			$strMsj = "Se agrego el renglon ".$i;
		 	fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhD, $_SESSION['idUsu'], $lavador, $paleto, 'R', '0', '0');
		}
	}
	
	for($i = 7; $i <= 44; $i++)
	{
		$txtRen2 = ${"txtRen2".$i};
		$txtHora2 = ${"txtHora2".$i};
		$txtPh2 = ${"txtPh2".$i};
		$txtTemp2 = ${"txtTemp2".$i};
		$txtMinMov = ${"txtMinMov".$i};
		$txtReposo = ${"txtReposo".$i};
		$txtSosaT = ${"txtSosaT".$i};
		$txtAcidoT = ${"txtAcidoT".$i};

		if($txtHora2 != '' and $txtPh2 != '' and $txtTemp2 != '' and $txtMinMov != '' and $txtReposo != ''and $txtSosaT != '' and $txtAcidoT != '')
		{
			mysqli_query($cnx, "INSERT INTO procesos_fase_2b_d2(pfg2_id, pfd22_ren, pfd22_hr, pfd22_ph, pfd22_temp, pfd22_reposo, pfd22_min, pfd22_sosa, pfd22_acido, usu_id, pfd22_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen2', '$txtHora2', '$txtPh2', '$txtTemp2', '$txtReposo', '$txtMinMov', '$txtSosaT', '$txtAcidoT', '".$_SESSION['idUsu']."', SYSDATE() ) ") or die(mysqli_error($cnx)." Error al insertar B".$i);
			$strMsj = "Se agrego el renglon ".$i;



		    //fnc_alertas($etapa, $parametro, $proceso, $valor, $usuario, $lavador, $paleto, $tipo);
			fnc_alertas($hdd_pe_id, 'ph', $hdd_pro_id, $txtPh2, $_SESSION['idUsu'], $lavador, $paleto, 'R', 'Temp', $txtTemp2 );
			//fnc_alertas($hdd_pe_id, 'Temp', $hdd_pro_id, $txtTemp2, $_SESSION['idUsu'], $lavador, $paleto, 'R');
		}
	}
	
	for($i = 1; $i <= 2; $i++)
	{
		$txtPhS = ${"txtPhS".$i};
		$txtHoraS = ${"txtHoraS".$i};
		
		if($txtPhS != '' and $txtHoraS != '' and $i == 1)
		{
			mysqli_query($cnx, "UPDATE procesos_fase_2b_g SET pfg2_ph1 = '$txtPhS', pfg2_hr1 = '$txtHoraS', pfg2_usu1 = '".$_SESSION['idUsu']."' WHERE pfg2_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 2b -".$i);

			fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhS, $_SESSION['idUsu'], $lavador, $paleto, 'L', 'Hr', $txtHoraS );
			//fnc_alertas($hdd_pe_id, 'Hr', $hdd_pro_id, $txtHoraS, $_SESSION['idUsu'], $lavador, $paleto, 'L');

			$strMsj = "Se agrego el renglon ".$i." de chequeo";

		}
		
		else if($txtPhS != '' and $txtHoraS != '' and $i == 2)
		{
			mysqli_query($cnx, "UPDATE procesos_fase_2b_g SET pfg2_ph2 = '$txtPhS', pfg2_hr2 = '$txtHoraS', pfg2_usu2 = '".$_SESSION['idUsu']."' WHERE pfg2_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 2b -".$i);

			fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhS, $_SESSION['idUsu'], $lavador, $paleto, 'L','Hr', $txtHoraS);
			//fnc_alertas_v2($hdd_pe_id, 'Hr', $hdd_pro_id, $txtHoraS, $_SESSION['idUsu'], $lavador, $paleto, 'L');

			$strMsj = "Se agrego el renglon ".$i." de chequeo";
		}
		/*else
		{
			$strMsj = "tiene ".$txtPhS." -".$txtHora." -".$i;
		}*/
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
			
			mysqli_query($cnx, "UPDATE procesos_fase_2b_g SET pfg2_hr_totales = '$txtHrasTot' WHERE pfg2_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 2b");

			$strMsj = "Fase 2 actualizada";
		}
		else
		{
			if($txtHrTotales != '')
			{
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales) VALUES('".$_SESSION['idUsu']."', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales' ) ") or die(mysqli_error($cnx)." Error al insertar L");

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