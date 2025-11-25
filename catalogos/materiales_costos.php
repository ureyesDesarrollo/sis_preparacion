<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST);

$reg_costo = mysqli_fetch_array(mysqli_query($cnx, "select * from materiales_costos where mat_id = '$hdd_id' and prv_id = '$cbx_proveedor' and mc_year = '$cbx_year' "));

if($reg_costo == '')
{
    mysqli_query($cnx, "INSERT INTO materiales_costos(mc_costo,mat_id,prv_id,mc_fe_alta, mc_year) VALUES('$txt_costo','$hdd_id','$cbx_proveedor','" . date("Y-m-d h:i:s") . "', '".$cbx_year."')") or die(mysqli_error($cnx) . " Error al insertar");

    $reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(mc_id) as res from materiales_costos"));

    ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '29');

    $respuesta = array('mensaje' => "Exito");
    echo json_encode($respuesta);
}
else
{
    /*$respuesta = array('mensaje' => "Error");
    echo json_encode($respuesta);*/
    mysqli_query($cnx, "UPDATE materiales_costos SET mc_costo = '$txt_costo' WHERE mc_id = '$reg_costo[mc_id]' ") or die(mysqli_error($cnx) . " Error al insertar");


    ins_bit_acciones($_SESSION['idUsu'], 'E', $reg_costo['mc_id'], '29');

    $respuesta = array('mensaje' => "Exito");
    echo json_encode($respuesta);
}
?>