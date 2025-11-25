<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Abril - 2019*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST); 

//Actualiza los datos del auxiliar
if($txtFeTerm == '' and $txtHrTerm == '')
{
	mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");
}
else
{
	mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni', proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");
}

//Actualiza los datos del general de la tabla
mysqli_query($cnx, "UPDATE procesos_fase_2b_g SET pfg2_temp_ag = '$txtTemp', pfg2_enzima = '$txtEnzima' WHERE pro_id = '$hdd_pro_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");

//Actualiza los datos del detalle de la tabla
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
		mysqli_query($cnx, "UPDATE procesos_fase_2b_d SET pfd2_hr = '$txtHoraD', pfd2_ph = '$txtPhD', pfd2_sosa = '$txtSosaD', pfd2_acido = '$txtAcidoD' WHERE pfg2_id = '$hdd_pfg' AND pfd2_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
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
		mysqli_query($cnx, "UPDATE procesos_fase_2b_d2 SET pfd22_hr = '$txtHora2', pfd22_ph = '$txtPh2', pfd22_temp = '$txtTemp2', pfd22_reposo = '$txtReposo', pfd22_min = '$txtMinMov', pfd22_sosa = '$txtSosaT', pfd22_acido = '$txtAcidoT' WHERE pfg2_id = '$hdd_pfg' AND pfd22_ren = '$txtRen2' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
	}
}

for($i = 1; $i <= 2; $i++)
{
	$txtPhS = ${"txtPhS".$i};
	$txtHoraS = ${"txtHoraS".$i};
	
	if($txtPhS != '' and $txtHoraS != '' and $i == 1)
	{
		mysqli_query($cnx, "UPDATE procesos_fase_2b_g SET pfg2_ph1 = '$txtPhS', pfg2_hr1 = '$txtHoraS' WHERE pfg2_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 2b -".$i);
	}
	
	else if($txtPhS != '' and $txtHoraS != '' and $i == 2)
	{
		mysqli_query($cnx, "UPDATE procesos_fase_2b_g SET pfg2_ph2 = '$txtPhS', pfg2_hr2 = '$txtHoraS' WHERE pfg2_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 2b -".$i);
	}
}
	
//Actualiza los datos de la liberación
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj );
echo json_encode($respuesta);
?> 