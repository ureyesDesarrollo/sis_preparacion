<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Tipo proceso Actualizado");
echo json_encode($respuesta);

extract($_POST); 

mysqli_query($cnx, "UPDATE preparacion_tipo SET pt_descripcion = '$txtNombre', pt_revision = '$txtRevision', pt_para = '$cbxTipo',pt_estatus ='$cbx_estatus' WHERE pt_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_id, '9');
?>