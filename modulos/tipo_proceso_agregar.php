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
$reg_fase = mysqli_fetch_array(mysqli_query($cnx, "select pte_id from preparacion_tipo_etapas WHERE pt_id = '$hdd_id' AND pe_id = '$cbxFase' "));
$tot_fase = mysqli_num_rows(mysqli_query($cnx, "select pte_id from preparacion_tipo_etapas WHERE pt_id = '$hdd_id'"));

if (empty($reg_fase['pte_id'])) {
	$respuesta = array('mensaje' => "Fase Agregada");
	echo json_encode($respuesta);

	$hdd_total = $tot_fase + 1;

	mysqli_query($cnx, "INSERT INTO preparacion_tipo_etapas(pt_id, pe_id, pte_orden) VALUES('$hdd_id', '$cbxFase', '$hdd_total')") or die(mysqli_error($cnx) . " Error al insertar");

	$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(pte_id) as res from preparacion_tipo_etapas"));

	ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '11');
} else {
	$respuesta = array('mensaje' => "La fase ya existe");
	echo json_encode($respuesta);
}
?>