<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST); 

/*$cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos_etapas WHERE pro_id = '$pro_id' and pe_id = '$pe_id'");
$reg_quim = mysqli_fetch_array($cad_quim);*/

for($i = 1; $i <= 7; $i++)
{
	$cbx_quimico = ${"cbx_quimico".$i};
	$txt_lote_quim = ${"txt_lote_quim".$i};
	$txt_litro_quim = ${"txt_litro_quim".$i};
	
	if ($cbx_quimico != '' and $txt_lote_quim != '' and $txt_litro_quim != '') {

		mysqli_query($cnx, "INSERT INTO quimicos_etapas(quimico_id, quim_lote, quim_litros, pe_id, pro_id,usu_id) VALUES('$cbx_quimico', '$txt_lote_quim', '$txt_litro_quim','$pe_id' ,'$pro_id','".$_SESSION['idUsu']."') ") or die(mysqli_error($cnx)." Error al insertar".$i);
	}

}

	$respuesta = array('mensaje' => "Registros guardados");
echo json_encode($respuesta);

?> 