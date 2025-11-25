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
    $nombre = trim($data['nombre'] ?? '');
    $nomina = trim($data['nomina'] ?? '');
    $comision = isset($data['comision']) ? floatval($data['comision']) : null;

    if ($nombre === '' || $comision === null || $comision < 0 || $comision > 100) {
        throw new Exception('Datos incompletos o inválidos.');
    }

    $nombre = mysqli_real_escape_string($cnx, $nombre);
    $nomina = mysqli_real_escape_string($cnx, $nomina);

    // Validar que no exista el mismo nombre y nómina
    $sql_check = "SELECT 1 FROM rev_vendedores WHERE ven_numero_nomina = '$nomina' LIMIT 1";
    $res_check = mysqli_query($cnx, $sql_check);
    if ($res_check && mysqli_num_rows($res_check) > 0) {
        throw new Exception('Ya existe un vendedor con ese número de nómina.');
    }

    $sql = "INSERT INTO rev_vendedores (ven_nombre, ven_numero_nomina, ven_porcentaje_comision) VALUES ('$nombre', '$nomina', $comision)";
    if (!mysqli_query($cnx, $sql)) {
        throw new Exception('Error al insertar: ' . mysqli_error($cnx));
    }

    echo json_encode(['success' => true, 'message' => 'Vendedor registrado correctamente.']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
