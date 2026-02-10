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

mysqli_query($cnx, "INSERT INTO procesos(pt_id, pro_total_kg, pl_id, pro_supervisor) 
	VALUES('$cbxProceso', '$txtTotKilos', '0', '$hdd_user')") or die(mysqli_error($cnx)." Error al insertar");

$pro_id = mysqli_insert_id($cnx);//recupera el ultimo id de la conexion

mysqli_query($cnx, "INSERT INTO procesos_paletos (pp_id, prop_estatus, prop_directo) VALUES('$cbxPaleto', 1, 1) ") or die(mysqli_error($cnx)." Error al insertar 2");

$prop_id = mysqli_insert_id($cnx);

mysqli_query($cnx, "INSERT INTO procesos_paletos_d (prop_id, pro_id) VALUES('$prop_id', '$pro_id') ") or die(mysqli_error($cnx)." Error al insertar 3");

mysqli_query($cnx, "UPDATE preparacion_paletos SET le_id = '1' WHERE pp_id = '$cbxPaleto' ") or die(mysqli_error($cnx)." Error al actualizar"); //Ocupa el paleto

for($i = 1; $i <= 12; $i++)
{ 
	$cbxKilosID = ${"cbxKilosID".$i};
	$cbxMaterial = ${"cbxMaterial".$i};
	$txtKilos = ${"txtKilos".$i};
	$txtFecha = ${"txtFecha".$i};
	$txtFecha_ent_maquila = ${"txtFecha_ent_maquila".$i};

    //Inserta el tipo_traje_detalle
	if($txtFecha_ent_maquila != ''){
		$fe_maquila = "'$txtFecha_ent_maquila'";
	}else{
		$fe_maquila =  'null';
	}



	$cad_kg2 = mysqli_query($cnx, "SELECT inv_kg_totales FROM inventario WHERE inv_id = '$cbxKilosID' ");
	$reg_kg2 = mysqli_fetch_array($cad_kg2);


	if ($cbxMaterial != '' or $cbxKilosID != '' or $cbxKilosID != 0) {
		mysqli_query($cnx, "INSERT INTO procesos_materiales(pro_id,inv_id,mat_id,pma_kg,pma_fe_entrada,pma_fe_entrada_maquila) VALUES('$pro_id','$cbxKilosID', '$cbxMaterial',  '$txtKilos',  '$txtFecha',$fe_maquila)") or die(mysqli_error($cnx)." Error al insertar en [".$i."]");
	}


	mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1 WHERE inv_id = '$cbxKilosID' ") or die(mysqli_error($cnx)." Error al actualizar en inv [".$i."]");

	fnc_existencia($cbxMaterial, $txtKilos, $cbxKilosID);


}


$flt_total = $txtKilos1 + $txtKilos2 + $txtKilos3 + $txtKilos4 + $txtKilos5 + $txtKilos6 + $txtKilos7 + $txtKilos8 + $txtKilos9 + $txtKilos10 + $txtKilo11 + $txtKilos12;

mysqli_query($cnx, "UPDATE procesos SET pro_total_kg = '$flt_total' WHERE pro_id = '$pro_id' ") or die(mysqli_error($cnx)." Error al actualizar proceso"); //Ocupa el Lavador

ins_bit_acciones($_SESSION['idUsu'],'A', $pro_id, '16');


$respuesta = array('mensaje' => "Registro agregado");
echo json_encode($respuesta);


function fnc_existencia($intMaterial, $fltKgDescuenta, $intInv)
{
	$cnx = Conectarse();
	
	//Obtener la existencia actual del material
	$cad_mat = mysqli_query($cnx, "SELECT mat_existencia FROM materiales WHERE mat_id = '$intMaterial' ");
	$reg_mat = mysqli_fetch_array($cad_mat);
	
	$flt_existencia = $reg_mat['mat_existencia'] - $fltKgDescuenta;
	
	//Actualiza la existencia del material
	mysqli_query($cnx, "UPDATE materiales SET mat_existencia = '$flt_existencia' WHERE mat_id = '$intMaterial'") or die(mysqli_error($cnx)." Error al actualizar");

	if ($intMaterial != '') {

	//Insertar en el diario de almacen el movimiento
		mysqli_query($cnx, "INSERT INTO inventario_diario_materiales (usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new, inv_id)VALUES(".$_SESSION['idUsu'].", '".date("Y-m-d h:i:s")."', 'bitacora', '$intMaterial', '$fltKgDescuenta', '$reg_mat[mat_existencia]', '$flt_existencia', '$intInv' )") or die(mysqli_error($cnx)." Error al insertar en el diario");
		
	}
}
?> 




