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

  $id = isset($data['trans_id']) ? (int)$data['trans_id'] : 0;
  $accion = isset($data['accion']) ? strtoupper(trim($data['accion'])) : '';

  // 🔹 Validación
  if ($id <= 0 || !in_array($accion, ['A', 'B'])) {
    throw new Exception('Datos inválidos para eliminación lógica.');
  }

  // 🔹 UPDATE estatus
  $sql = "
        UPDATE rev_transportistas
        SET trans_estatus = '$accion'
        WHERE trans_id = $id";

  if (!mysqli_query($cnx, $sql)) {
    throw new Exception('Error al actualizar estado: ' . mysqli_error($cnx));
  }

  // 🔹 Mensaje dinámico
  $msg = $accion === 'B'
    ? 'Transportista dado de baja correctamente.'
    : 'Transportista reactivado correctamente.';

  echo json_encode([
    'success' => true,
    'message' => $msg
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
