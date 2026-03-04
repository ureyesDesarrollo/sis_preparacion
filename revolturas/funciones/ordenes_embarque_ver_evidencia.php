<?php
include '../../conexion/conexion.php';
$conexion = Conectarse();

define('NAS_BASE', '\\\\NAS01\\ARCHIVOS_SISTEMAS\\SIST_REVOLTURAS\\');

$evidenciaId = (int)($_GET['evidencia_id'] ?? 0);

if ($evidenciaId <= 0) {
  http_response_code(400);
  exit("Evidencia inválida");
}

$stmt = $conexion->prepare("
    SELECT carpeta_relativa, nombre_guardado, nombre_original, mime_type
    FROM embarque_evidencias
    WHERE evidencia_id = ?
    LIMIT 1
");

$stmt->bind_param("i", $evidenciaId);
$stmt->execute();
$res = $stmt->get_result();
$ev = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$ev) {
  http_response_code(404);
  exit("No existe");
}

$path = NAS_BASE . $ev['carpeta_relativa'] . $ev['nombre_guardado'];

if (!is_file($path)) {
  http_response_code(404);
  exit("Archivo no encontrado en NAS");
}

if (ob_get_length()) ob_clean();

$mime = $ev['mime_type'] ?: 'application/octet-stream';
$original = basename($ev['nombre_original']);

header('Content-Type: ' . $mime);
header('Content-Disposition: inline; filename="' . $original . '"');
header('Content-Length: ' . filesize($path));

readfile($path);
exit;
