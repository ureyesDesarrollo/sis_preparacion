<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

include "../../conexion/conexion.php";
$cnx = Conectarse();

/* =====================================================
   1️⃣ Validar POST truncado (post_max_size)
===================================================== */
$contentLength = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
if ($contentLength > 0 && empty($_POST) && empty($_FILES)) {
  echo json_encode([
    'success' => false,
    'message' => 'Carga excede límites del servidor'
  ]);
  exit;
}

/* =====================================================
   2️⃣ Validar datos base
===================================================== */
$embarqueId = (int)($_POST['embarque_id'] ?? 0);
$usuarioId  = 1;

if ($embarqueId <= 0) {
  echo json_encode(['success' => false, 'message' => 'Embarque inválido']);
  exit;
}

if ($usuarioId <= 0) {
  echo json_encode(['success' => false, 'message' => 'Usuario inválido']);
  exit;
}

/* =====================================================
   3️⃣ Validar archivos múltiples
===================================================== */
if (!isset($_FILES['imagenes'])) {
  echo json_encode(['success' => false, 'message' => 'No se recibieron imágenes']);
  exit;
}

if (!is_array($_FILES['imagenes']['name'])) {
  // Solo un archivo
  $_FILES['imagenes'] = [
    'name' => [$_FILES['imagenes']['name']],
    'type' => [$_FILES['imagenes']['type']],
    'tmp_name' => [$_FILES['imagenes']['tmp_name']],
    'error' => [$_FILES['imagenes']['error']],
    'size' => [$_FILES['imagenes']['size']],
  ];
}

$totalArchivos = count($_FILES['imagenes']['name']);

if ($totalArchivos <= 0) {
  echo json_encode(['success' => false, 'message' => 'No hay archivos válidos']);
  exit;
}

/* =====================================================
   4️⃣ Validar que embarque exista
===================================================== */
$stmt = $cnx->prepare("SELECT oe_id, tarimas_liberadas FROM rev_orden_embarque WHERE oe_id = ? LIMIT 1");
$stmt->bind_param("i", $embarqueId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if (!$row) {
  echo json_encode(['success' => false, 'message' => 'Embarque no encontrado']);
  exit;
}

$tarimasLiberadas = (int)$row['tarimas_liberadas'];
$stmt->close();
if ($totalArchivos < $tarimasLiberadas) {
  echo json_encode([
    'success' => false,
    'message' => "Se requieren al menos {$tarimasLiberadas} evidencias"
  ]);
  exit;
}

/* =====================================================
   5️⃣ Crear carpeta en NAS
===================================================== */
define('NAS_BASE', '\\\\NAS01\\ARCHIVOS_SISTEMAS\\SIST_REVOLTURAS\\');

$fecha = date('Y-m-d');
$carpetaRelativa = "embarques/embarque_{$embarqueId}/{$fecha}/";
$rutaFisica = NAS_BASE . $carpetaRelativa;

if (!is_dir($rutaFisica)) {
  if (!mkdir($rutaFisica, 0775, true)) {
    echo json_encode(['success' => false, 'message' => 'No se pudo crear carpeta en NAS']);
    exit;
  }
}

/* =====================================================
   6️⃣ Validaciones
===================================================== */
$allowed = ['jpg', 'jpeg', 'png'];
$maxBytes = 10 * 1024 * 1024; // 10MB
$finfo = new finfo(FILEINFO_MIME_TYPE);

$rutasGuardadas = [];

try {

  $cnx->begin_transaction();

  for ($i = 0; $i < $totalArchivos; $i++) {

    if ($_FILES['imagenes']['error'][$i] !== UPLOAD_ERR_OK) {
      throw new Exception("Error en archivo índice {$i}");
    }

    $size = (int)$_FILES['imagenes']['size'][$i];
    if ($size <= 0 || $size > $maxBytes) {
      throw new Exception("Archivo demasiado grande índice {$i}");
    }

    $tmp = $_FILES['imagenes']['tmp_name'][$i];
    $nombreOriginal = $_FILES['imagenes']['name'][$i];
    $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed, true)) {
      throw new Exception("Extensión no permitida índice {$i}");
    }

    $mime = $finfo->file($tmp);

    $nombreGuardado = time() . "_{$usuarioId}_{$i}." . $ext;
    $rutaArchivo = $rutaFisica . $nombreGuardado;

    if (!move_uploaded_file($tmp, $rutaArchivo)) {
      throw new Exception("No se pudo guardar archivo índice {$i}");
    }

    $rutasGuardadas[] = $rutaArchivo;

    $orden = $i + 1;

    $stmt = $cnx->prepare("
            INSERT INTO embarque_evidencias
            (embarque_id, carpeta_relativa, nombre_guardado, nombre_original, mime_type, tamano_bytes, orden, creado_por)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

    $stmt->bind_param(
      "issssiii",
      $embarqueId,
      $carpetaRelativa,
      $nombreGuardado,
      $nombreOriginal,
      $mime,
      $size,
      $orden,
      $usuarioId
    );

    if (!$stmt->execute()) {
      throw new Exception("Error insertando BD índice {$i}");
    }

    $stmt->close();
  }

  $cnx->commit();

  echo json_encode([
    'success' => true,
    'message' => 'Evidencias subidas correctamente',
    'total' => $totalArchivos
  ]);
  exit;
} catch (Throwable $e) {

  $cnx->rollback();

  // 🔥 Limpieza física si falla BD
  foreach ($rutasGuardadas as $ruta) {
    if (is_file($ruta)) {
      @unlink($ruta);
    }
  }

  echo json_encode([
    'success' => false,
    'message' => 'Error subiendo evidencias',
    'error' => $e->getMessage()
  ]);
  exit;
}
