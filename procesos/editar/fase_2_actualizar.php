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
	echo "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni', proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' <br>";
	mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni', proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx)." Error al actualizar 1");
}

//Actualiza los datos del general de la tabla
echo "UPDATE procesos_fase_2_g SET pfg2_temp_ag = '$txtTemp', pfg2_ph_ant = '$txtPhAnt', pfg2_ce = '$txtCe', pfg2_sosa = '$txtAjSosa', pfg2_ph_aju = '$txtPhAj', pfg2_peroxido = '$txtPeroxido' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id'<br>";
mysqli_query($cnx, "UPDATE procesos_fase_2_g SET pfg2_temp_ag = '$txtTemp', pfg2_ph_ant = '$txtPhAnt', pfg2_ce = '$txtCe', pfg2_sosa = '$txtAjSosa', pfg2_ph_aju = '$txtPhAj', pfg2_peroxido = '$txtPeroxido' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");


//Actualiza los datos del detalle de la tabla
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
	
	if($txtHr != '' and $txtPh != '' and $txtSosa != '' and $txtTemp != '' and $txtRedox != '')
	{ //echo "UPDATE procesos_fase_2_d SET pfd2_hr = '$txtHr', pfd2_ph = '$txtPh', pfd2_sosa = '$txtSosa', pfd2_temp = '$txtTemp', pfd2_redox = '$txtRedox', pfd2_acido = '$txtAcidoX' WHERE pfg2_id = '$hdd_pfg' AND pfd2_ren = '$txtRen' ";
		mysqli_query($cnx, "UPDATE procesos_fase_2_d SET pfd2_hr = '$txtHr', pfd2_ph = '$txtPh', pfd2_sosa = '$txtSosa', pfd2_peroxido='$txtPeroxido', pfd2_temp = '$txtTemp', pfd2_redox = '$txtRedox', pfd2_acido = '0' WHERE pfg2_id = '$hdd_pfg' AND pfd2_ren = '$txtRen' ") or die(mysqli_error($cnx)." Error al actualizar el renglon ".$i);
	}
}
	
//Actualiza los datos de la liberación
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ph = '$txtPhLib', prol_color = '$cbxColor', prol_peroxido = '$txtPeroxidoL' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj );
echo json_encode($respuesta);

?> 