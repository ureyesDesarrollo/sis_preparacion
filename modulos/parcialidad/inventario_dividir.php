<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Octubre-2023*/
include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();


extract($_POST);

//Actualiza los kilos sobrantes
/*if ($txtSobra == '0') {
	mysqli_query($cnx, "UPDATE inventario SET inv_kilos = '$txtSobra' , inv_enviado = '1' WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar el inventario");
}else{
	mysqli_query($cnx, "UPDATE inventario SET inv_kilos = '$txtSobra' WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar el inventario");
}*/

//Selecciona los datos de todo el inventario
$cad_inv = mysqli_query($cnx, "SELECT * FROM inventario WHERE inv_id = '$hdd_id' ");
$reg_inv = mysqli_fetch_array($cad_inv);

if ($reg_inv['inv_desc_ag'] == '') {
    $reg_inv['inv_desc_ag'] = 0;
}
if ($reg_inv['inv_desc_d'] == '') {
    $reg_inv['inv_desc_d'] = 0;
}
if ($reg_inv['inv_desc_ren'] == '') {
    $reg_inv['inv_desc_ren'] = 0;
}

if ($reg_inv['inv_hora_entrada'] != '') {
    $hora_entrada = "'$reg_inv[inv_hora_entrada]'";
} else {
    $hora_entrada = 'NULL';
}

if ($reg_inv['inv_hora_salida'] != '') {
    $hora_salida = "'$reg_inv[inv_hora_salida]'";
} else {
    $hora_salida = 'NULL';
}
//si hay kilos sobrantes actualiza e inserta
if ($txtSobra != '0') {
    //actualiza kilos pendientes por recibir
    mysqli_query($cnx, "UPDATE inventario SET inv_kilos = '$txtSobra' WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx) . " Error al actualizar el inventario");

    //Insera el nuevo inventario que se dividio
    mysqli_query($cnx, "INSERT INTO inventario(inv_fecha, inv_hora, inv_dia, inv_no_ticket, inv_placas, inv_camioneta, prv_id, mat_id, inv_kilos, inv_prueba, inv_desc_ag, inv_desc_d, inv_desc_ren, inv_kg_totales, inv_calidad, inv_no_factura, inv_peso_factura, inv_por_merma, inv_no_tarimas, inv_no_sacos,inv_enviado,inv_fe_enviado,inv_id_key,prv_recibe,inv_folio_interno,inv_hora_entrada,inv_hora_salida,ac_id,inv_solicitado,inv_humedad_origen) 
					VALUES('$reg_inv[inv_fecha]', '$reg_inv[inv_hora]', '$reg_inv[inv_dia]','$reg_inv[inv_no_ticket]', '$reg_inv[inv_placas]',
					'$reg_inv[inv_camioneta]', '$reg_inv[prv_id]', '$reg_inv[mat_id]', '$txtEnvia', '$reg_inv[inv_prueba]', '$reg_inv[inv_desc_ag]',
					'$reg_inv[inv_desc_d]', '$reg_inv[inv_desc_ren]', '$reg_inv[inv_kg_totales]', '$reg_inv[inv_calidad]', '$reg_inv[inv_no_factura]', 
					'$reg_inv[inv_peso_factura]', '$reg_inv[inv_por_merma]', 
					'$reg_inv[inv_no_tarimas]', '$reg_inv[inv_no_sacos]','1',
					'" . date("Y-m-d h:i:s") . "','$hdd_id','$cbx_maquila',
					'$reg_inv[inv_folio_interno]',$hora_entrada,$hora_salida,'$reg_inv[ac_id]','','$reg_inv[inv_humedad_origen]')") or die(mysqli_error($cnx) . " Error al insertar");

    $reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(inv_id) as res from inventario"));

    ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '3');
} else {
    //actualiza a enviado
    mysqli_query($cnx, "UPDATE inventario SET inv_enviado = '1',prv_recibe = '$cbx_maquila', inv_fe_enviado = '" . date("Y-m-d h:i:s") . "' WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx) . " Error al actualizar el inventario");
}

$respuesta = array('mensaje' => "Inventario enviado");
echo json_encode($respuesta);
