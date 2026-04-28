<?php
header('Content-Type: application/json');

require_once '../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode([
    'success' => false,
    'message' => 'Método no permitido'
  ]);
  exit;
}

$cnx = Conectarse();

try {

  /* =====================================
       LEER JSON
    ===================================== */

  $raw = file_get_contents('php://input');
  $data = json_decode($raw, true);

  if (!is_array($data)) {
    throw new Exception('JSON inválido.');
  }

  $id = isset($data['id']) ? (int)$data['id'] : 0;
  $nombre = trim($data['nombre'] ?? '');
  $parametros = $data['parametros'] ?? [];

  if ($id <= 0 || $nombre === '') {
    throw new Exception('Datos incompletos o inválidos.');
  }

  if (!is_array($parametros)) {
    $parametros = [];
  }

  $nombre = mysqli_real_escape_string($cnx, $nombre);

  mysqli_begin_transaction($cnx);

  /* =====================================
       VALIDAR NOMBRE DUPLICADO
    ===================================== */

  $sql_check = "
        SELECT 1
        FROM rev_transportistas
        WHERE trans_nombre = '$nombre'
        AND trans_id != $id
        LIMIT 1
    ";

  $res_check = mysqli_query($cnx, $sql_check);

  if (!$res_check) {
    throw new Exception('Error al validar transportista.');
  }

  if (mysqli_num_rows($res_check) > 0) {
    throw new Exception('Ya existe un transportista con ese nombre.');
  }

  /* =====================================
       ACTUALIZAR TRANSPORTE
    ===================================== */

  $sql = "
        UPDATE rev_transportistas
        SET trans_nombre = '$nombre'
        WHERE trans_id = $id
    ";

  if (!mysqli_query($cnx, $sql)) {
    throw new Exception('Error al actualizar transporte: ' . mysqli_error($cnx));
  }

  /* =====================================
       OBTENER PARAMETROS EXISTENTES
    ===================================== */

  $ids_existentes = [];

  $sql_existentes = "
        SELECT id
        FROM transportistas_parametros
        WHERE transportista_id = $id
    ";

  $res = mysqli_query($cnx, $sql_existentes);

  if (!$res) {
    throw new Exception('Error al consultar parámetros.');
  }

  while ($row = mysqli_fetch_assoc($res)) {
    $ids_existentes[] = (int)$row['id'];
  }

  $ids_recibidos = [];

  /* =====================================
       INSERTAR / ACTUALIZAR PARAMETROS
    ===================================== */

  foreach ($parametros as $p) {

    $par_id = isset($p['id']) ? (int)$p['id'] : 0;

    $campo = mysqli_real_escape_string(
      $cnx,
      trim($p['campo'] ?? '')
    );

    $etiqueta = mysqli_real_escape_string(
      $cnx,
      trim($p['etiqueta'] ?? '')
    );

    if ($campo === '' || $etiqueta === '') {
      continue;
    }

    /* -------- ACTUALIZAR -------- */

    if ($par_id > 0) {

      $ids_recibidos[] = $par_id;

      $sql = "
                UPDATE transportistas_parametros
                SET campo = '$campo',
                    etiqueta = '$etiqueta'
                WHERE id = $par_id
            ";

      if (!mysqli_query($cnx, $sql)) {
        throw new Exception('Error al actualizar parámetro.');
      }
    } else {

      /* -------- INSERTAR -------- */

      $sql = "
                INSERT INTO transportistas_parametros
                (transportista_id, campo, etiqueta)
                VALUES
                ($id, '$campo', '$etiqueta')
            ";

      if (!mysqli_query($cnx, $sql)) {
        throw new Exception('Error al insertar parámetro.');
      }
    }
  }

  /* =====================================
       ELIMINAR PARAMETROS BORRADOS
    ===================================== */

  $ids_a_eliminar = array_diff($ids_existentes, $ids_recibidos);

  if (!empty($ids_a_eliminar)) {

    $lista = implode(',', array_map('intval', $ids_a_eliminar));

    $sql_delete = "
            DELETE FROM transportistas_parametros
            WHERE id IN ($lista)
        ";

    if (!mysqli_query($cnx, $sql_delete)) {
      throw new Exception('Error al eliminar parámetros.');
    }
  }

  mysqli_commit($cnx);

  echo json_encode([
    'success' => true,
    'message' => 'Transportista actualizado correctamente.'
  ]);
} catch (Exception $e) {

  mysqli_rollback($cnx);

  http_response_code(400);

  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
} finally {

  mysqli_close($cnx);
}
