<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
	
extract($_POST); 

//Busca si esa fase no ha sido agregada a el formato
/*$reg_fase = mysqli_fetch_array(mysqli_query($cnx, "select mat_id from mezclas_materiales WHERE mez_id = '$hdd_id' AND mat_id = '$cbxMaterial' "));
//$tot_fase = mysqli_num_rows(mysqli_query($cnx, "select pte_id from preparacion_tipo_etapas WHERE pt_id = '$hdd_id'"));

if($reg_fase['pte_id'] <= 0)
{
	$respuesta = array('mensaje' => "Material Agregado");
	echo json_encode($respuesta);
	
	//$hdd_total = $tot_fase + 1;*/
	
	mysqli_query($cnx, "INSERT INTO preparacion_etapas_mezclas(pep_id, mez_id) VALUES('$hdd_id', '$cbxMezcla')") or die(mysqli_error($cnx)." Error al insertar");
	
	//$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(pte_id) as res from preparacion_tipo_etapas"));
	
	//ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '11');
/*}
else
{*/
	$respuesta = array('mensaje' => "Mezcla agregada");
	echo json_encode($respuesta);
//}
?> 