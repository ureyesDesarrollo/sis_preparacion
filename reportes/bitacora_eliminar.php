<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Proceso Eliminado");
echo json_encode($respuesta);

extract($_POST); 


//Seleccionar los materiales
$cadena = mysqli_query ($cnx, "SELECT * FROM procesos_materiales WHERE pro_id = '$id'") or die(mysql_error()."Error al seleccionar");
$resultado = mysqli_fetch_assoc($cadena);
$tot = mysqli_num_rows($cadena);

//Reingresar el material
if($tot > 0){
do
{
	$cad_mat = mysqli_query($cnx, "SELECT mat_existencia FROM materiales WHERE mat_id = '$resultado[mat_id]' ");
	$reg_mat = mysqli_fetch_array($cad_mat);
	
	$flt_existencia = $reg_mat['mat_existencia'] + $resultado['pma_kg'];
	
	//Actualiza la existencia del material
	mysqli_query($cnx, "UPDATE materiales SET mat_existencia = '$flt_existencia' WHERE mat_id = '$resultado[mat_id]'") or die(mysqli_error($cnx)." Error al actualizar");

	mysqli_query($cnx, "INSERT INTO inventario_diario_materiales (usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new, inv_id) 
						VALUES(".$_SESSION['idUsu'].", '".date("Y-m-d h:i:s")."', 'retorno', '$resultado[mat_id]', '$resultado[pma_kg]', '$reg_mat[mat_existencia]', '$flt_existencia', '$resultado[inv_id]' )") or die(mysqli_error($cnx)." Error al insertar en el diario");
}while($resultado = mysqli_fetch_assoc($cadena));

//Eliminar materiales
mysqli_query ($cnx, "DELETE FROM procesos_materiales WHERE pro_id = '$id'") or die(mysql_error()."Error al dar de baja");

}

//Eliminar el proceso
mysqli_query ($cnx, "DELETE FROM procesos WHERE pro_id = '$id'") or die(mysql_error()."Error al dar de baja");		

ins_bit_acciones($_SESSION['idUsu'],'B', $id, '17');
?> 