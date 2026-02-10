<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Fase Subida");
echo json_encode($respuesta);

extract($_POST); 

//$result = mysqli_query ($cnx, "DELETE FROM preparacion_tipo_etapas WHERE pte_id = '$id'") or die(mysql_error()."Error al dar de baja");	

//Selecciona el orden de la operacion actual
$reg1 = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT pte_id, pte_orden, pt_id from preparacion_tipo_etapas where pte_id = $id"));
//echo $id."-".$reg1[pte_orden];
//echo "<br>";
$ord_arr = $reg1['pte_orden'] - 1;
//Selecciona el id del orden actual
$reg2 = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT pte_id, pte_orden from preparacion_tipo_etapas where pte_orden = $ord_arr and pt_id = '$reg1[pt_id]'"));
//echo $reg2[pte_id]."-".$reg2[pte_orden];
$ord_aba = $reg2['pte_orden'] + 1;
//echo "UPDATE preparacion_tipo_etapas SET pto_orden = '$ord_aba' where pte_id = '$reg2[pte_id]' ";
//Modifica el orden del id seleccionado
mysqli_query($cnx, "UPDATE preparacion_tipo_etapas SET pte_orden = '$ord_aba' where pte_id = '$reg2[pte_id]' ") or die(mysqli_error()."Error 1");
//echo "UPDATE preparacion_tipo_etapas SET pte_orden = '$ord_arr' where pte_id = '$reg1[pte_id]'";
//Modifica el orden del orden seleccioado
mysqli_query($cnx, "UPDATE preparacion_tipo_etapas SET pte_orden = '$ord_arr' where pte_id = '$reg1[pte_id]'") or die(mysqli_error()."Error 2");	

//ins_bit_acciones($_SESSION['idUsu'],'B', $id, 'Fase - Proceso');
?> 