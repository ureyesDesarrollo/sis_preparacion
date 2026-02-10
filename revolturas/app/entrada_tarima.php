<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

// Función para registrar logs (se mantiene igual)
function logMensaje($mensaje)
{
    $logFile = __DIR__ . "/logs.log";
    $fecha = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$fecha] $mensaje" . PHP_EOL, FILE_APPEND);
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logMensaje("Error: Método no permitido.");
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Conexión a la base de datos
$cnx = Conectarse();

// Obtener datos del cuerpo de la petición (JSON)
$data = json_decode(file_get_contents('php://input'), true);

// Validar parámetros requeridos
if (!isset($data['tarima_id'], $data['nueva_posicion'], $data['rac_id'], $data['tar_estatus'], $data['usu_id'])) {
    logMensaje("Error: Faltan parámetros en la petición.");
    echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros necesarios']);
    exit;
}

// Asignar variables
$tarima_id = intval($data['tarima_id']);
$nueva_posicion = mysqli_real_escape_string($cnx, $data['nueva_posicion']);
$rac_id = intval($data['rac_id']);
$tar_estatus = intval($data['tar_estatus']);
$usu_id = intval($data['usu_id']);

// Validar datos
if ($tarima_id <= 0 || empty($nueva_posicion)) {
    logMensaje("Error: Datos inválidos. Tarima ID: $tarima_id, Nueva posición: $nueva_posicion");
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
    exit;
}

logMensaje("Inicio: Moviendo tarima $tarima_id a $nueva_posicion (Rack $rac_id).");

// 1. Obtener y desocupar la posición actual (si existe)
$sql_actual = "SELECT niv_id FROM rev_nivel_posicion_detalle WHERE tar_id = $tarima_id";
$result_actual = mysqli_query($cnx, $sql_actual);

if ($result_actual && mysqli_num_rows($result_actual) > 0) {
    $pos_actual = mysqli_fetch_assoc($result_actual)['niv_id'];
    mysqli_query($cnx, "UPDATE rev_nivel_posicion SET niv_ocupado = 0 WHERE niv_id = $pos_actual");
    ins_bit_acciones($usu_id, 'E', $pos_actual, '40');
    logMensaje("Posición $pos_actual desocupada.");
}

// 2. Obtener ID de la nueva posición
$sql_nueva = "SELECT niv_id FROM rev_nivel_posicion WHERE niv_codigo = '$nueva_posicion' AND rac_id = $rac_id";
$result_nueva = mysqli_query($cnx, $sql_nueva);

if (!$result_nueva || mysqli_num_rows($result_nueva) === 0) {
    logMensaje("Error: Posición $nueva_posicion no existe en rack $rac_id.");
    echo json_encode(['status' => 'error', 'message' => 'Posición no válida']);
    exit;
}

$nueva_niv_id = mysqli_fetch_assoc($result_nueva)['niv_id'];

// 3. Eliminar registros anteriores de la tarima
mysqli_query($cnx, "DELETE FROM rev_nivel_posicion_detalle WHERE tar_id = $tarima_id");

// 4. Insertar en nueva posición
$sql_insert = "INSERT INTO rev_nivel_posicion_detalle (niv_id, tar_id, cantidad) VALUES ($nueva_niv_id, $tarima_id, 1000.00)";
if (!mysqli_query($cnx, $sql_insert)) {
    logMensaje("Error al insertar en rev_nivel_posicion_detalle: " . mysqli_error($cnx));
    echo json_encode(['status' => 'error', 'message' => 'Error al mover tarima']);
    exit;
}

// 5. Actualizar ocupación de la nueva posición
mysqli_query($cnx, "UPDATE rev_nivel_posicion SET niv_ocupado = 1 WHERE niv_id = $nueva_niv_id");
ins_bit_acciones($usu_id, 'E', $nueva_niv_id, '40');

// 6. Actualizar niv_id en rev_tarimas (si no es rack 2)
if ($rac_id != 2) {
    mysqli_query($cnx, "UPDATE rev_tarimas SET niv_id = $nueva_niv_id WHERE tar_id = $tarima_id");
    ins_bit_acciones($usu_id, 'E', $tarima_id, '41');
}

// 7. Actualizar estatus de la tarima
mysqli_query($cnx, "UPDATE rev_tarimas SET tar_estatus = $tar_estatus WHERE tar_id = $tarima_id");

logMensaje("Éxito: Tarima $tarima_id movida a posición $nueva_niv_id.");
echo json_encode(['status' => 'success', 'message' => 'Tarima movida correctamente']);
