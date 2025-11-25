<?php
/*Desarrollado por: Ca & Ce Technologies */
/*15 - Abril - 2022*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_POST);

// Validación de datos de entrada
if (empty($slc_enviar) || empty($hdd_inv)) {
    $respuesta = array('mensaje' => 'Error: Datos de entrada vacíos');
    echo json_encode($respuesta);
    exit;
}

// Validación de formato de datos
if (!is_numeric($slc_enviar) || !is_numeric($hdd_inv)) {
    $respuesta = array('mensaje' => 'Error: Formato de datos no válido');
    echo json_encode($respuesta);
    exit;
}

// Escapar caracteres especiales para evitar inyección de código
$slc_enviar_escapado = mysqli_real_escape_string($cnx, $slc_enviar);

// Consultas SQL con comillas dobles para valores de cadena
try {
    // Actualizar inventario
    $consulta_inventario = "UPDATE inventario SET ac_id = '$slc_enviar_escapado' WHERE inv_id = '$hdd_inv' ";
    mysqli_query($cnx, $consulta_inventario) or die(mysqli_error($cnx) . " Error al actualizar");

    // Registrar movimiento en bitacora_cajones
    $consulta_bitacora = "INSERT INTO bitacora_cajones (inv_id, cajon_inicial, cajon_final, usu_id, bc_fecha_movimiento)
                            VALUES($hdd_inv, '0', '$slc_enviar_escapado', '" . $_SESSION['idUsu'] . "','" . date("Y-m-d H:i:s") . "')";
    mysqli_query($cnx, $consulta_bitacora) or die(mysqli_error($cnx) . " Error en movimiento");

    // Registrar acción en bitacora_acciones
    ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_id, '3');

    $respuesta = array('mensaje' => "Movimiento realizado");
    echo json_encode($respuesta);
} catch (Exception $e) {
    $respuesta = array('mensaje' => "Error: " . $e->getMessage());
    echo json_encode($respuesta);
    exit;
}
