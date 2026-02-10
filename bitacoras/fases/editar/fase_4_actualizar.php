<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Abril - 2019*/

include "../../../seguridad/user_seguridad.php";
require_once('../../../conexion/conexion.php');
include "../../../funciones/funciones.php";
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
if($txtCocido != '')
{
	$str_cad = ",pfg4_cocido_ph = '$txtCocido' ";
}
if($txtCeG != '')
{
	$str_cad2 = ", pfg4_ce = '$txtCeG' ";
}

mysqli_query($cnx, "UPDATE procesos_fase_4_g SET pfg4_temp_ag = '$txtTemp', pfg4_temp = '$txtTemp2', pfg4_acido = '$txtAcido', pfg4_termina = '$txtTermina', pfg4_temp2 = '$txtTemp3', pfg4_acido_fuerte = '$cbxAcidoF', pfg4_enzima = '$txtEnzima' $str_cad $str_cad2  WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");

//Actualiza los datos del detalle de la tabla
for($i = 1; $i <= 8; $i++)
{
	$txtRen = ${"txtRen".$i};
	$txtAcidoF = ${"txtAcidoF".$i};
	$txtPhF = ${"txtPhF".$i};
	$txtPhBF = ${"txtPhBF".$i};
	$txtTempF = ${"txtTempF".$i};
	$txtPpm = ${"txtPpm".$i};
	
	/*if($txtAcidoF != '' and $txtPhF != '' and $txtPhBF != '' and $txtTempF != '' and $txtPpm != '')
	{*/
		mysqli_query($cnx, "UPDATE procesos_fase_4_d SET pfd4_acido = '$txtAcidoF', pfd4_ph = '$txtPhF', pfd4_ph_b = '$txtPhBF', pfd4_temp = '$txtTempF', pfd4_ppm = '$txtPpm' WHERE pfg4_id = '$hdd_pfg' AND pfd4_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
	//}
}
	
//Actualiza los datos de la liberaci�n
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ph = '$txtPhLib', prol_adelgasamiento = '$cbxAdel' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj );
echo json_encode($respuesta);
?> 