<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST);

$foto = $_FILES["txt_file"]["name"];
$ruta = $_FILES["txt_file"]["tmp_name"];
$destino = "../iconos/" . $foto;
copy($ruta, $destino);

mysqli_query($cnx, "INSERT INTO equipos_tipos(et_descripcion,et_tipo,et_orden,et_imagen,et_estatus,ban_almacena) VALUES('$txt_descripcion_tipo','$txt_sigla','$txt_orden','$destino','A','$slc_almacen')") or die(mysqli_error($cnx) . " Error al insertar");

$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(et_id) as res from equipos_tipos"));

ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '26');

$respuesta = array('mensaje' => "Registro guardado");
echo json_encode($respuesta);
?>