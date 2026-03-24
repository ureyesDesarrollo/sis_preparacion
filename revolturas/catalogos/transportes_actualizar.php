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

  // 🔹 Validaciones
  if ($id <= 0 || $nombre === '') {
    throw new Exception('Datos incompletos o inválidos.');
  }

  $nombre = mysqli_real_escape_string($cnx, $nombre);

  // 🔹 Validar duplicado de nombre (opcional pero recomendado)
  $sql_check = "
        SELECT 1
        FROM rev_transportistas
        WHERE trans_nombre = '$nombre'
        AND trans_id != $id
        LIMIT 1
    ";

  $res_check = mysqli_query($cnx, $sql_check);

  if ($res_check && mysqli_num_rows($res_check) > 0) {
    throw new Exception('Ya existe un transportista con ese nombre.');
  }

  // 🔹 UPDATE
  $sql = "
        UPDATE rev_transportistas
        SET
            trans_nombre = '$nombre'
        WHERE trans_id = $id
    ";

  if (!mysqli_query($cnx, $sql)) {
    throw new Exception('Error al actualizar: ' . mysqli_error($cnx));
  }

  echo json_encode([
    'success' => true,
    'message' => 'Transportista actualizado correctamente.'
  ]);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
} finally {
  mysqli_close($cnx);
}
