<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_POST);

if (!empty($_FILES["txt_file_e"]["name"])) {

    $foto = $_FILES["txt_file_e"]["name"];
    $ruta = $_FILES["txt_file_e"]["tmp_name"];
    $destino = "../iconos/" . $foto;
    copy($ruta, $destino);

    mysqli_query($cnx, "UPDATE equipos_tipos SET et_descripcion = '$txt_descripcion_tipo_e', et_tipo = '$txt_sigla_e', et_orden = '$txt_orden_e',et_imagen = '$destino', et_estatus = '$slc_estatus_e', ban_almacena = '$slc_almacen_e'
WHERE et_id = '$hdd_id_tipo' ") or die(mysqli_error($cnx) . " Error al actualizar");
} else {
    mysqli_query($cnx, "UPDATE equipos_tipos SET et_descripcion = '$txt_descripcion_tipo_e', et_tipo = '$txt_sigla_e', et_orden = '$txt_orden_e', et_estatus = '$slc_estatus_e', ban_almacena = '$slc_almacen_e'
    WHERE et_id = '$hdd_id_tipo' ") or die(mysqli_error($cnx) . " Error al actualizar");
}

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_id_tipo, '25');

$respuesta = array('mensaje' => "Registro actualizado");
echo json_encode($respuesta);
?>