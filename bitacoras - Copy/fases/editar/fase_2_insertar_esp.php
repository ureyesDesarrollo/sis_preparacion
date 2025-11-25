<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

include "../../../seguridad/user_seguridad.php";
require_once('../../../conexion/conexion.php');
include "../../../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST); 
	//$respuesta = "SELECT * FROM procesos_fase_2_g WHERE pro_id = '$hdd_pro_id' and pe_id = 2";
	
	$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_g WHERE pro_id = '$hdd_pro_id' and pe_id = 2");
	$reg_fa = mysqli_fetch_array($cad_fa);
	$tot_fa = mysqli_num_rows($cad_fa);
	
	if($reg_fa['pfg2_id'] == ''){

		mysqli_query($cnx, "INSERT INTO procesos_fase_2_g(pro_id, pe_id, pfg2_temp_ag, pfg2_ph_ant, pfg2_ce, pfg2_sosa, pfg2_ph_aju, pfg2_peroxido) VALUES('$hdd_pro_id', '2', '$txtTemp', '$txtPhAnt', '$txtCe', '$txtAjSosa', '$txtPhAj', '$txtPeroxido' ) ") or die(mysqli_error($cnx)." Error al insertar 2");
	
		$respuesta = array('mensaje' => "Fase 2 agregada");
	}
	else
	{
		mysqli_query($cnx, "UPDATE procesos_fase_2_g SET pfg2_temp_ag = '$txtTemp', pfg2_ph_ant = '$txtPhAnt', pfg2_ce = '$txtCe', pfg2_sosa = '$txtAjSosa', pfg2_ph_aju = '$txtPhAj', pfg2_peroxido = '$txtPeroxido' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx)." Error al actualizar 2");
		$respuesta = array('mensaje' => "Fase 2 editada");

	}
echo json_encode($respuesta);
?> 