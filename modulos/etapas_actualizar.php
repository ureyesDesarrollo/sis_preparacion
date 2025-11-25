<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Etapa Actualizada");
echo json_encode($respuesta);

extract($_POST); 

//mysqli_query($cnx, "UPDATE preparacion_etapas SET pe_nombre = '$txtNombre', pe_hr_ideal = '$txtHrIdeal', pe_hr_maxima = '$txtHrMax', pe_hr_validacion = '$cbxValida' , pe_inicio = '$txtInicio', pe_fin = '$txtFin', pe_control_lib = '$slcLiberacion', pe_control_material = '$slcMaterial', pe_control_renglon = '$slcRenglon', pe_enviar_email = '$slcEmail' WHERE pe_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar");
mysqli_query($cnx, "UPDATE preparacion_etapas SET pe_nombre = '$txtNombre', pe_hr_ideal = '$txtHrIdeal', pe_hr_maxima = '$txtHrMax', pe_hr_validacion = '$cbxValida' , pe_inicio = '$txtInicio', pe_fin = '$txtFin' WHERE pe_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar");//, pe_enviar_email = '$slcEmail'

//ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_id, '12');
?> 