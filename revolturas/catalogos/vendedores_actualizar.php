<?php
header('Content-Type: application/json');
require_once '../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$cnx = Conectarse();

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = isset($data['id']) ? (int)$data['id'] : 0;
    $nombre = trim($data['nombre'] ?? '');
    $nomina = trim($data['nomina'] ?? '');
    $comision = isset($data['comision']) ? floatval($data['comision']) : null;

    if ($id <= 0 || $nombre === '' || $comision === null || $comision < 0 || $comision > 100) {
        throw new Exception('Datos incompletos o inválidos.');
    }

    $nombre = mysqli_real_escape_string($cnx, $nombre);
    $nomina = mysqli_real_escape_string($cnx, $nomina);

    // Validar que no exista el mismo nombre y nómina en otro registro
    $sql_check = "SELECT 1 FROM rev_vendedores WHERE ven_numero_nomina = '$nomina' AND ven_id != $id LIMIT 1";
    $res_check = mysqli_query($cnx, $sql_check);
    if ($res_check && mysqli_num_rows($res_check) > 0) {
        throw new Exception('Ya existe un vendedor con ese número de nómina.');
    }

    $sql = "UPDATE rev_vendedores SET ven_nombre = '$nombre', ven_numero_nomina = '$nomina', ven_porcentaje_comision = $comision WHERE ven_id = $id";
    if (!mysqli_query($cnx, $sql)) {
        throw new Exception('Error al actualizar: ' . mysqli_error($cnx));
    }

    echo json_encode(['success' => true, 'message' => 'Vendedor actualizado correctamente.']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
