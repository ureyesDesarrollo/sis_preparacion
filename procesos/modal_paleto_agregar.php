<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST); 
//echo "SELECT * FROM procesos_paletos_d WHERE pro_id = '$txtPro'";
//Revisar si el proceso ya esta en un paleto
$cad_prop_d = mysqli_query($cnx, "SELECT * FROM procesos_paletos_d WHERE pro_id = '$txtPro'");
$reg_prop_d = mysqli_fetch_array($cad_prop_d);

if($reg_prop_d['prod_id'] != '')
{
	$cad_prop = mysqli_query($cnx, "SELECT * FROM procesos_paletos WHERE prop_id = '$reg_prop_d[prop_id]'");
	$reg_prop = mysqli_fetch_array($cad_prop);

	//Cambie el paleto del sproceso
	mysqli_query($cnx, "UPDATE procesos_paletos SET pp_id = '$cbxPaleto' WHERE prop_id = '$reg_prop_d[prop_id]' ") or die(mysqli_error($cnx)." Error al actualizar paleto");

	//Marca el nuevo paleto como ocupado
	mysqli_query($cnx, "UPDATE preparacion_paletos SET le_id = '1' WHERE pp_id = '$cbxPaleto' ") or die(mysqli_error($cnx)." Error al actualizar paleto A");
	
	//Marca el anterior paleto como libre
	mysqli_query($cnx, "UPDATE preparacion_paletos SET le_id = '2' WHERE pp_id = '$reg_prop[pp_id]' ") or die(mysqli_error($cnx)." Error al actualizar paleto B");
		
	//Guardamos el paleto anterior en el historial
	mysqli_query($cnx, "INSERT INTO procesos_paletos_hist (prop_id, pp_id) VALUES('$reg_prop_d[prop_id]', '$reg_prop[pp_id]') ") or die(mysqli_error($cnx)." Error al insertar h");
	
	$respuesta = array('mensaje' => "Paleto enviado a Paleto");
}
else
{
	
	//Marcar el proceso con estatus 3
	mysqli_query($cnx, "UPDATE procesos SET pro_estatus = 3, pro_fe_termino = SYSDATE() WHERE pro_id = '$txtPro'") or die(mysqli_error($cnx)." Error al insertar ");
	
	if($txtLavador != 0)
	{
		//Liberar el lavador
		mysqli_query($cnx, "UPDATE preparacion_lavadores SET le_id = '6' WHERE pl_id = '$txtLavador' ") or die(mysqli_error($cnx)." Error al actualizar lavador");
		
		mysqli_query($cnx, "UPDATE procesos SET pro_estatus = 2, pro_fe_termino = SYSDATE() WHERE pro_id = '$txtPro' ") or die(mysqli_error($cnx)." Error al actualizar proceso");
	}
	
	if($txtPaleto != 0)
	{
		//Liberar el paleto
		mysqli_query($cnx, "UPDATE preparacion_paletos SET le_id = '2' WHERE pp_id = '$txtPaleto' ") or die(mysqli_error($cnx)." Error al actualizar lavador");
		
		mysqli_query($cnx, "UPDATE procesos_paletos SET prop_estatus = 2 WHERE prop_id = '$txtProp' ") or die(mysqli_error($cnx)." Error al actualizar proceso");
	}
	
	//Revisa si el paleto tiene estatus libre
	$cad_pa = mysqli_query($cnx, "SELECT * FROM preparacion_paletos WHERE pp_id = '$cbxPaleto'");
	$reg_pa = mysqli_fetch_array($cad_pa);
	
	if($reg_pa['le_id'] == 2)
	{
		//Marcar el paleto como ocupado
		mysqli_query($cnx, "UPDATE preparacion_paletos SET le_id = '1' WHERE pp_id = '$cbxPaleto' ") or die(mysqli_error($cnx)." Error al actualizar paleto");
		
		if($cbxProceso == ''){$cbxProceso = 0;}
		
		//Asociar el proceso a paleto
		mysqli_query($cnx, "INSERT INTO procesos_paletos (pp_id, prop_estatus, pt_id) VALUES('$cbxPaleto', 1, '$cbxProceso') ") or die(mysqli_error($cnx)." Error al insertar proceso");
		
		$prop_id = mysqli_insert_id($cnx);
		
		mysqli_query($cnx, "INSERT INTO procesos_paletos_d (prop_id, pro_id) VALUES('$prop_id', '$txtPro') ") or die(mysqli_error($cnx)." Error al insertar d x");
	}
	else
	{
		//selecciona donde el estatus del equipo se oucpado y el paleto sea igual 10
		$cad_pro = mysqli_query($cnx, "SELECT * FROM procesos_paletos WHERE pp_id = '$cbxPaleto' and prop_estatus = 1");
		$reg_pro = mysqli_fetch_array($cad_pro);
	
		mysqli_query($cnx, "INSERT INTO procesos_paletos_d (prop_id, pro_id) VALUES('$reg_pro[prop_id]', '$txtPro') ") or die(mysqli_error($cnx)." Error al insertar d");
	}
	
	//mysqli_query($cnx, "UPDATE procesos SET pro_estatus = 2, pro_fe_termino = SYSDATE() WHERE pro_id = '$hdd_pro_id' ") or die(mysqli_error($cnx)." Error al actualizar y");//Marca el proceso como Terminado , en que momento lo debe terminar? Quitar consulta
	
	$respuesta = array('mensaje' => "Lavador enviado a Paleto");

}

	echo json_encode($respuesta);
?>