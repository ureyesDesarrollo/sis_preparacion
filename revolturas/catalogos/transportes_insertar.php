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

  if ($nombre === '') {
    throw new Exception('Datos incompletos o inválidos.');
  }

  $nombre = mysqli_real_escape_string($cnx, $nombre);

  // Validar que no exista el mismo nombre y nómina
  $sql_check = "SELECT 1 FROM rev_transportistas WHERE trans_nombre = '$nombre' LIMIT 1";
  $res_check = mysqli_query($cnx, $sql_check);
  if ($res_check && mysqli_num_rows($res_check) > 0) {
    throw new Exception('Ya existe un transporte con ese nombre.');
  }

  $sql = "INSERT INTO rev_transportistas (trans_nombre, trans_estatus) VALUES ('$nombre', 'A')";
  if (!mysqli_query($cnx, $sql)) {
    throw new Exception('Error al insertar: ' . mysqli_error($cnx));
  }

  echo json_encode(['success' => true, 'message' => 'Transporte registrado correctamente.']);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
  mysqli_close($cnx);
}
