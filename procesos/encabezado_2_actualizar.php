<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST); 

if($cheMolino1 == '')
{
	$cheMolino1 = 0;
}

if($cheMolino2 == '')
{
	$cheMolino2 = 0;
}

if($cheMolino3 == '')
{
	$cheMolino3 = 0;
}

if($cheMolino4 == '')
{
	$cheMolino4 = 0;
}

if($cheMolino5 == '')
{
	$cheMolino5 = 0;
}

if($cbxPila2 == '')
{
	$cbxPila2 = 0;
}

if($txtPh2 == '')
{
	$txtPh2 = 0;
}

if($txtCe2== '')
{
	$txtCe2 = 0;
}

if($txtTemp2 == '')
{
	$txtTemp2 = 0;
}
//echo "UPDATE procesos SET  pro_fe_carga = '$txtFechaCarga', pro_hr_inicio = '$txtHrIni', pro_hr_fin = '$txtHrFin', pro_molino1 = '$cheMolino1', pro_molino2 = '$cheMolino2', pro_molino3 = '$cheMolino3', pro_molino4 = '$cheMolino4', pro_molino5 = '$cheMolino5', pro_pila = '$cbxPila', pro_ph = '$txtPh', pro_temp = '$txtTemp', pro_ce = '$txtCe', pro_col_limp = '$radColador', pro_cuero = '$radCe', pro_operador = '$hdd_user', pro_fe_sistema = '".date("Y-m-d h:i:s")."' WHERE pro_id = '$hdd_pro_id' ";
mysqli_query($cnx, "UPDATE procesos SET  pro_fe_carga = '$txtFechaCarga', pro_hr_inicio = '$txtHrIni', pro_hr_fin = '$txtHrFin', pro_molino1 = '$cheMolino1', pro_molino2 = '$cheMolino2', pro_molino3 = '$cheMolino3', pro_molino4 = '$cheMolino4', pro_molino5 = '$cheMolino5', pro_pila = '$cbxPila', pro_ph = '$txtPh', pro_temp = '$txtTemp', pro_ce = '$txtCe', pro_pila2 = '$cbxPila2', pro_ph2 = '$txtPh2', pro_temp2 = '$txtTemp2', pro_ce2 = '$txtCe2', pro_col_limp = '$radColador', pro_cuero = '$radCe', pro_operador = '$hdd_user', pro_fe_sistema = '".date("Y-m-d h:i:s")."', pro_observaciones='$textObs' WHERE pro_id = '$hdd_pro_id' ") or die(mysqli_error($cnx)." Error al insertar");

$respuesta = array('mensaje' => "Informacion agregada");
echo json_encode($respuesta);

/*$pro_id = mysqli_insert_id($cnx);//recupera el ultimo id de la conexion

mysqli_query($cnx, "UPDATE preparacion_lavadores SET le_id = '5' WHERE pl_id = '$cbxLavador' ") or die(mysqli_error($cnx)." Error al actualizar"); //Ocupa el Lavador

for ($i = 1; $i <= 8; $i++)    
{  	

	if($i == 1 and $cbxKilosID1!='' and $cbxMaterial1!='' and $txtKilos1!='')
	{
		mysqli_query($cnx, "INSERT INTO procesos_materiales (pro_id, inv_id, mat_id, pma_kg, pma_fe_entrada) VALUES('$pro_id', '$cbxKilosID1', '$cbxMaterial1',  '$txtKilos1',  CURDATE())") or die(mysqli_error($cnx)." Error al insertar en [".$i."]");
		
		mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1 WHERE inv_id = '$cbxKilosID1' ") or die(mysqli_error($cnx)." Error al actualizar en inv [".$i."]");
		
		fnc_existencia($cbxMaterial1, $txtKilos1, $cbxKilosID1);
	}
	
	if($i == 2 and $cbxKilosID2!='' and $cbxMaterial2!='' and $txtKilos2!='')
	{
		mysqli_query($cnx, "INSERT INTO procesos_materiales(pro_id,inv_id,mat_id,pma_kg,pma_fe_entrada) VALUES('$pro_id','$cbxKilosID2', '$cbxMaterial2',  '$txtKilos2',  CURDATE())") or die(mysqli_error($cnx)." Error al insertar en [".$i."]");
		
		mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1 WHERE inv_id = '$cbxKilosID2' ") or die(mysqli_error($cnx)." Error al actualizar en inv [".$i."]");
		
		fnc_existencia($cbxMaterial2, $txtKilos2, $cbxKilosID2);
	} 
	
	if($i == 3 and $cbxKilosID3!='' and $cbxMaterial3!='' and $txtKilos3!='')
	{
		mysqli_query($cnx, "INSERT INTO procesos_materiales(pro_id,inv_id,mat_id,pma_kg,pma_fe_entrada) VALUES('$pro_id','$cbxKilosID3', '$cbxMaterial3',  '$txtKilos3',  CURDATE())") or die(mysqli_error($cnx)." Error al insertar en [".$i."]");
		
		mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1 WHERE inv_id = '$cbxKilosID3' ") or die(mysqli_error($cnx)." Error al actualizar en inv [".$i."]");
		
		fnc_existencia($cbxMaterial3, $txtKilos3, $cbxKilosID3);
	} 

	if($i == 4 and $cbxKilosID4!='' and $cbxMaterial4!='' and $txtKilos4!='')
	{
		mysqli_query($cnx, "INSERT INTO procesos_materiales(pro_id,inv_id,mat_id,pma_kg,pma_fe_entrada) VALUES('$pro_id','$cbxKilosID4', '$cbxMaterial4',  '$txtKilos4',  CURDATE())") or die(mysqli_error($cnx)." Error al insertar en [".$i."]");
		
		mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1 WHERE inv_id = '$cbxKilosID4' ") or die(mysqli_error($cnx)." Error al actualizar en inv [".$i."]");
		
		fnc_existencia($cbxMaterial4, $txtKilos4, $cbxKilosID4);
	} 
	
	if($i == 5 and $cbxKilosID5!='' and $cbxMaterial5!='' and $txtKilos5!='')
	{
		mysqli_query($cnx, "INSERT INTO procesos_materiales(pro_id,inv_id,mat_id,pma_kg,pma_fe_entrada) VALUES('$pro_id','$cbxKilosID5', '$cbxMaterial5',  '$txtKilos5',  CURDATE())") or die(mysqli_error($cnx)." Error al insertar en [".$i."]");
		
		mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1 WHERE inv_id = '$cbxKilosID5' ") or die(mysqli_error($cnx)." Error al actualizar en inv [".$i."]");
		
		fnc_existencia($cbxMaterial5, $txtKilos5, $cbxKilosID5);
	}
	
	if($i == 6 and $cbxKilosID6!='' and $cbxMaterial6!='' and $txtKilos6!='')
	{
		mysqli_query($cnx, "INSERT INTO procesos_materiales(pro_id,inv_id,mat_id,pma_kg,pma_fe_entrada) VALUES('$pro_id','$cbxKilosID6', '$cbxMaterial6',  '$txtKilos6',  CURDATE())") or die(mysqli_error($cnx)." Error al insertar en [".$i."]");
		
		mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1 WHERE inv_id = '$cbxKilosID6' ") or die(mysqli_error($cnx)." Error al actualizar en inv [".$i."]");
		
		fnc_existencia($cbxMaterial6, $txtKilos6, $cbxKilosID6);
	}
	
	if($i == 7 and $cbxKilosID7!='' and $cbxMaterial7!='' and $txtKilos7!='')
	{
		mysqli_query($cnx, "INSERT INTO procesos_materiales(pro_id,inv_id,mat_id,pma_kg,pma_fe_entrada) VALUES('$pro_id','$cbxKilosID7', '$cbxMaterial7',  '$txtKilos7',  CURDATE())") or die(mysqli_error($cnx)." Error al insertar en [".$i."]");
		
		mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1 WHERE inv_id = '$cbxKilosID7' ") or die(mysqli_error($cnx)." Error al actualizar en inv [".$i."]");
		
		fnc_existencia($cbxMaterial7, $txtKilos7, $cbxKilosID7);
	}
	
	if($i == 8 and $cbxKilosID8!='' and $cbxMaterial8!='' and $txtKilos8!='')
	{
		mysqli_query($cnx, "INSERT INTO procesos_materiales(pro_id,inv_id,mat_id,pma_kg,pma_fe_entrada) VALUES('$pro_id','$cbxKilosID8', '$cbxMaterial8',  '$txtKilos8',  CURDATE())") or die(mysqli_error($cnx)." Error al insertar en [".$i."]");
		
		mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1 WHERE inv_id = '$cbxKilosID8' ") or die(mysqli_error($cnx)." Error al actualizar en inv [".$i."]");
		
		fnc_existencia($cbxMaterial8, $txtKilos8, $cbxKilosID8);
	}
	
}



function fnc_existencia($intMaterial, $fltKgDescuenta, $intInv)
{
	$cnx = Conectarse();
	
	//Obtener la existencia actual del material
	$cad_mat = mysqli_query($cnx, "SELECT mat_existencia FROM materiales WHERE mat_id = '$intMaterial' ");
	$reg_mat = mysqli_fetch_array($cad_mat);
	
	$flt_existencia = $reg_mat['mat_existencia'] - $fltKgDescuenta;
	
	//Actualiza la existencia del material
	mysqli_query($cnx, "UPDATE materiales SET mat_existencia = '$flt_existencia' WHERE mat_id = '$intMaterial'") or die(mysqli_error($cnx)." Error al actualizar");
	
	//Insertar en el diario de almacen el movimiento
	mysqli_query($cnx, "INSERT INTO inventario_diario_materiales (usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new, inv_id) 
						VALUES(".$_SESSION['idUsu'].", '".date("Y-m-d h:i:s")."', 'bitacora', '$intMaterial', '$fltKgDescuenta', '$reg_mat[mat_existencia]', '$flt_existencia', '$intInv' )") or die(mysqli_error($cnx)." Error al insertar en el diario");
}*/
?> 