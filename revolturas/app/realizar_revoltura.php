<?php
header('Content-Type: application/json');
include '../../conexion/conexion.php';
include "../../funciones/funciones.php";
date_default_timezone_set('America/Mexico_City');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cnx = Conectarse();
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar que los datos existen y no están vacíos
    if (
        empty($data['rev_id']) ||
        empty($data['rev_hora_ini']) ||
        empty($data['rev_imanes_limpios']) ||
        empty($data['rev_sacos_limpios']) ||
        empty($data['rev_libre_sobrantes']) ||
        empty($data['rev_mezcladora'] ||
            empty($data['usu_id']))
    ) {
        echo json_encode(["error" => "Todos los campos son obligatorios"]);
        http_response_code(400); // Código de error 400 (Bad Request)
        exit;
    }

    $rev_id = mysqli_real_escape_string($cnx, $data['rev_id']);
    $rev_fecha_procesamiento = date("Y-m-d H:i:s");
    $rev_hora_ini = mysqli_real_escape_string($cnx, $data['rev_hora_ini']);
    $rev_imanes_limpios = mysqli_real_escape_string($cnx, $data['rev_imanes_limpios']);
    $rev_sacos_limpios = mysqli_real_escape_string($cnx, $data['rev_sacos_limpios']);
    $rev_libre_sobrantes = mysqli_real_escape_string($cnx, $data['rev_libre_sobrantes']);
    $rev_mezcladora = mysqli_real_escape_string($cnx, $data['rev_mezcladora']);
    $usu_id = mysqli_real_escape_string($cnx, $data['usu_id']);

    // Estado de la revoltura y equipo
    $rev_estatus = '1'; // En proceso
    $e_estatus = '2'; // Mezcladora ocupada

    $sql = "UPDATE rev_revolturas SET
            rev_fecha_procesamiento = '$rev_fecha_procesamiento', 
            rev_hora_ini = '$rev_hora_ini', 
            rev_imanes_limpios = '$rev_imanes_limpios',
            rev_sacos_limpios = '$rev_sacos_limpios', 
            rev_libre_sobrantes = '$rev_libre_sobrantes', 
            rev_mezcladora = '$rev_mezcladora',
            rev_estatus = '$rev_estatus' 
            WHERE rev_id = '$rev_id'";

    ins_bit_acciones($usu_id, 'E', $rev_id, '46');

    // Actualizar el estado de la mezcladora
    $ocupar_equipo = "UPDATE rev_equipos SET e_estatus = '$e_estatus' WHERE e_id = '$rev_mezcladora'";

    try {
        if (mysqli_query($cnx, $sql) && mysqli_query($cnx, $ocupar_equipo)) {
            echo json_encode(["success" => "Revoltura actualizada correctamente"]);
        } else {
            throw new Exception("Error al actualizar la revoltura: " . mysqli_error($cnx));
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
        http_response_code(500); // Código de error 500 (Internal Server Error)
    } finally {
        mysqli_close($cnx);
    }
} else {
    http_response_code(405); // Código de error 405 (Método no permitido)
    echo json_encode(['error' => 'Método no permitido']);
}