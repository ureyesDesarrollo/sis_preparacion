<?php
header('Content-Type: application/json');
include '../../conexion/conexion.php';
include "../../funciones/funciones.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$cnx = Conectarse();
$data = json_decode(file_get_contents('php://input'), true);

// **🔹 Validaciones: Verificar que los datos requeridos existen y no están vacíos**
if (empty($data['rev_id']) || empty($data['rev_hora_fin']) || empty($data['usu_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Todos los campos son obligatorios"]);
    exit;
}

// **🔹 Escapar los valores para evitar SQL Injection**
$rev_id = mysqli_real_escape_string($cnx, $data['rev_id']);
$rev_hora_fin = mysqli_real_escape_string($cnx, $data['rev_hora_fin']);
$usu_id = mysqli_real_escape_string($cnx, $data['usu_id']);

// **🔹 Definir nuevos estados**
$rev_estatus = '2'; // Terminada
$e_estatus = '1'; // Mezcladora libre

// **🔹 Iniciar una transacción**
mysqli_begin_transaction($cnx);

try {
    // **🔹 Actualizar la revoltura con la hora de finalización**
    $sql = "UPDATE rev_revolturas SET rev_hora_fin = '$rev_hora_fin', rev_estatus = '$rev_estatus' WHERE rev_id = '$rev_id'";
    if (!mysqli_query($cnx, $sql)) {
        throw new Exception("Error al actualizar la revoltura: " . mysqli_error($cnx));
    }

    // **🔹 Registrar la acción en la bitácora**
    ins_bit_acciones($usu_id, 'E', $rev_id, '46');

    // **🔹 Obtener la mezcladora asociada a la revoltura**
    $sql_mez = "SELECT rev_mezcladora FROM rev_revolturas WHERE rev_id = '$rev_id'";
    $result_mez = mysqli_query($cnx, $sql_mez);
    $rev_mezcladora = ($result_mez && mysqli_num_rows($result_mez) > 0) ? mysqli_fetch_assoc($result_mez)['rev_mezcladora'] : null;

    if (!$rev_mezcladora) {
        throw new Exception("No se encontro la mezcladora asociada a la revoltura");
    }

    // **🔹 Actualizar el estado de la mezcladora**
    $desocupar_equipo = "UPDATE rev_equipos SET e_estatus = '$e_estatus' WHERE e_id = '$rev_mezcladora'";
    if (!mysqli_query($cnx, $desocupar_equipo)) {
        throw new Exception("Error al desocupar la mezcladora: " . mysqli_error($cnx));
    }

    // **🔹 Obtener las tarimas asociadas a la revoltura**
    $tarimas_rev = "SELECT t.tar_id, np.niv_id FROM rev_revolturas_tarimas rt 
                    INNER JOIN rev_tarimas t ON rt.tar_id = t.tar_id
                    LEFT JOIN rev_nivel_posicion_detalle npd ON npd.tar_id = t.tar_id 
                    LEFT JOIN rev_nivel_posicion np ON np.niv_id = npd.niv_id
                    WHERE rt.rev_id = '$rev_id'";
    $res_tar = mysqli_query($cnx, $tarimas_rev);
    if (!$res_tar) {
        throw new Exception("Error al obtener las tarimas asociadas a la revoltura: " . mysqli_error($cnx));
    }

    // **🔹 Procesar cada tarima**
    while ($tarima = mysqli_fetch_assoc($res_tar)) {
        $niv_id = $tarima['niv_id'];

        // **Desocupar la posición**
        $desocupar_pos = "UPDATE rev_nivel_posicion SET niv_ocupado = 0 WHERE niv_id = '$niv_id'";
        if (!mysqli_query($cnx, $desocupar_pos)) {
            throw new Exception("Error al desocupar la posición con niv_id: $niv_id");
        }

        // **Eliminar detalle de la posición**
        $eliminar_detalle = "DELETE FROM rev_nivel_posicion_detalle WHERE niv_id = '$niv_id'";
        if (!mysqli_query($cnx, $eliminar_detalle)) {
            throw new Exception("Error al eliminar el detalle de la posición con niv_id: $niv_id");
        }
    }

    // **🔹 Confirmar la transacción**
    mysqli_commit($cnx);
    echo json_encode(["success" => "Revoltura terminada correctamente"]);
} catch (Exception $e) {
    // **🔹 Rollback en caso de error**
    mysqli_rollback($cnx);
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
