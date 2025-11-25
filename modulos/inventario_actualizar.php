<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";


$cnx = Conectarse();

$respuesta = array('mensaje' => "Inventario Actualizado");
echo json_encode($respuesta);

extract($_POST);

//folio consecutivo mensual
/* $str_fecha = date("Y-m-") . "01";
$cad = mysqli_query($cnx, "SELECT LPAD((COUNT(i.inv_folio_interno)+1),3,'0') as num FROM inventario as i
inner join proveedores as p on(i.prv_id = p.prv_id) WHERE i.inv_fecha >= '$str_fecha' and p.prv_tipo = 'L'");
$reg = mysqli_fetch_array($cad);

//si el folio es diferente al calculado por el de sistema
if ($txt_folio_interno != $reg['num']) {
    $str_folio = date("ym") . $txt_folio_interno;
} else {
    $str_folio = date("ym") . $reg['num'];
} */


mysqli_query($cnx, "UPDATE inventario SET inv_fe_recibe = '$txt_hora_entrada', prv_recibe = '$cbxProveedor', inv_kg_totales = '$txtKgTotales', inv_enviado = 2, inv_kg_lavador =  '$txtKg' , inv_prueba = '$txtPrbSecador' , inv_desc_ag = '$txtDAgua', inv_desc_d = '$txtDescarne', inv_desc_ren = '$txtDRendimiento', inv_kg_entrada_maq = '$txtKgEntradaMaq',ac_id = '$cbxUbicacion',
inv_hora_salida2 = '$txt_hora_salida',
inv_folio_interno2 =  '$txt_folio_interno'
WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx) . " Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_id, '3');

//Obtener la existencia actual del material
$cad_mat = mysqli_query($cnx, "SELECT mat_existencia FROM materiales WHERE mat_id = '$hddMaterial' ");
$reg_mat = mysqli_fetch_array($cad_mat);

$flt_existencia = $reg_mat['mat_existencia'] + $txtKgTotales;

mysqli_query($cnx, "UPDATE materiales SET mat_existencia = '$flt_existencia' WHERE mat_id = '$hddMaterial'") or die(mysqli_error($cnx) . " Error al actualizar");

//Insertar en el diario de almacen el movimiento
mysqli_query($cnx, "INSERT INTO inventario_diario_materiales (usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new, inv_id) VALUES(" . $_SESSION['idUsu'] . ", '" . date("Y-m-d H:i:s") . "', 'inventario', '$hddMaterial', '$txtKgTotales', '$reg_mat[mat_existencia]', '$flt_existencia', '$hdd_id' )") or die(mysqli_error($cnx) . " Error al insertar en el diario");
