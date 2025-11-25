<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";


$cnx = Conectarse();
extract($_POST);
$cad_cbx =  mysqli_query($cnx, "SELECT prv_tipo, prv_ban FROM proveedores WHERE prv_id = '$prv_id'") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
$reg_cbx =  mysqli_fetch_array($cad_cbx);


if ($reg_cbx['prv_tipo'] == 'L' && $reg_cbx['prv_ban'] == 0) {
    $str_fecha = date("Y-m-") . "01";

    //fnc_folio_mensual();
    $cad = mysqli_query($cnx, "SELECT LPAD((COUNT(i.inv_folio_interno)+1),3,'0') as num FROM inventario as i
	inner join proveedores as p on(i.prv_id = p.prv_id) WHERE i.inv_fecha >= '$str_fecha' and p.prv_tipo = 'L'");
    $reg = mysqli_fetch_array($cad);

    echo $str_folio = date("ym") . $reg['num'];
}
if ($reg_cbx['prv_tipo'] == 'E') {
    //fnc_folio_anual();
    $str_fecha = date("Y-") . "01-01";
    $cad = mysqli_query($cnx, "SELECT LPAD((COUNT(i.inv_folio_interno)+1),3,'0') as num FROM inventario as i
	inner join proveedores as p on(i.prv_id = p.prv_id) WHERE i.inv_fecha >= '$str_fecha' and p.prv_tipo = 'E'");
    $reg = mysqli_fetch_array($cad);

    echo $str_folio = date("ym") . $reg['num'];
}
