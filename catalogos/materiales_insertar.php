<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST);

$chk_ingreso = isset($chk_ingreso) ? 'S' : 'N';
$resultado = mysqli_query($cnx, "INSERT INTO materiales(mt_id, mat_nombre, um_id,mat_costo, 
    mat_stock_min,mat_stock_max,mat_existencia,mat_est,mat_comentarios,mat_ingreso) 
    VALUES('$cbxTipo','$txtMaterial','$cbxMedida',0,'$txtSMin','$txtSMax','$txtExistencia','$txtEstatus','$txaNotas','$chk_ingreso')");

//atrapa errores
if (!$resultado) {
    $mensajeError =  mysqli_error($cnx);
    if ($mensajeError = 'Duplicate entry') {
        $mensajeError = 'El registro ya existe';
    } else {
        $mensajeError = 'Error';
    }

    $respuesta = array('mensaje' => $mensajeError);
    die(json_encode($respuesta));
}


$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(mat_id) as res from materiales"));

ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '5');

$respuesta = array('mensaje' => "Exito");
echo json_encode($respuesta);
