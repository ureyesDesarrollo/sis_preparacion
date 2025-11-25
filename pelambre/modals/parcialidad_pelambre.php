<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Octubre-2023*/
include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST);

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
//if ($txtSobra != '0') {
if ($txtSobra > 0) {
    //actualiza kilos pendientes por recibir

    mysqli_query($cnx, "UPDATE inventario SET inv_kg_totales = '$txtSobra' WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx) . " Error al actualizar el inventario x");

    //Insera el nuevo inventario que se dividio
    //cajon, estatus
    mysqli_query($cnx, "INSERT INTO inventario(inv_fecha, inv_hora, inv_dia, 
                    inv_no_ticket, inv_placas, inv_camioneta, prv_id, mat_id, 
                    inv_prueba, inv_desc_ag, inv_desc_d, inv_desc_ren, 
                    inv_calidad, inv_no_factura, inv_peso_factura, inv_por_merma, 
                    inv_no_tarimas, inv_no_sacos,inv_enviado,inv_fe_enviado,
                    inv_id_key,prv_recibe,inv_folio_interno,inv_folio_interno2,
                    inv_hora_entrada,inv_hora_salida, ac_id, inv_solicitado, 
                    inv_kilos, inv_kg_totales, inv_fe_recibe) 
					VALUES('$reg_inv[inv_fecha]', '$reg_inv[inv_hora]', '$reg_inv[inv_dia]',
                    '$reg_inv[inv_no_ticket]', '$reg_inv[inv_placas]',
					'$reg_inv[inv_camioneta]', '$reg_inv[prv_id]', '$reg_inv[mat_id]', 
                    '$reg_inv[inv_prueba]', '$reg_inv[inv_desc_ag]',
					'$reg_inv[inv_desc_d]', '$reg_inv[inv_desc_ren]', 
                    '$reg_inv[inv_calidad]', '$reg_inv[inv_no_factura]', 
					'$reg_inv[inv_peso_factura]', '$reg_inv[inv_por_merma]', 
					'$reg_inv[inv_no_tarimas]', '$reg_inv[inv_no_sacos]','2',
					'" . date("Y-m-d h:i:s") . "','$hdd_id','0',
					'$reg_inv[inv_folio_interno]','$txt_folio_interno',
                    $hora_entrada,$hora_salida,'$cbx_cajon','' , '$reg_inv[inv_kilos]', 
                    '$txtEnvia','" . date("Y-m-d h:i:s") . "')") or die(mysqli_error($cnx) . " Error al insertar");

    $reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(inv_id) as res from inventario"));

    ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '3');
    $respuesta = array('mensaje' => "Registro realizado 1");
} else {
    #actualiza recibido de pelambre y envia a cajon
    mysqli_query($cnx, "UPDATE inventario SET inv_enviado = '2', prv_recibe = '0', ac_id ='$cbx_cajon', inv_folio_interno2 = '$txt_folio_interno', inv_fe_recibe = '" . date("Y-m-d h:i:s") . "', inv_kg_totales = '$txtEnvia' WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx) . " Error al actualizar el inventario 1");

    //cierra el proceso activo
    mysqli_query($cnx, "UPDATE inventario_pelambre SET ip_ban = '0' WHERE inv_id = '$hdd_id' ") or die(mysqli_error($cnx) . " Error al actualizar el inventario 2");

    //Selecciona el equipo pelambre
    $cad_pelambre = mysqli_query($cnx, "SELECT ep_id FROM inventario_pelambre WHERE inv_id = '$hdd_id' ");
    $reg_pelambre = mysqli_fetch_array($cad_pelambre);

    #actualiza estatus equipo a drenado
    mysqli_query($cnx, "UPDATE equipos_preparacion SET le_id = 14 WHERE ep_id = " . $reg_pelambre['ep_id'] . " ") or die(mysqli_error($cnx) . " Error al actualizar el inventario 3");

    $respuesta = array('mensaje' => "Registro realizado 2");
}

echo json_encode($respuesta);
