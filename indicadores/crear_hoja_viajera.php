<?php
include "../conexion/conexion.php";
require_once __DIR__ . '/../revolturas/funciones/certificados_calidad/load_phpword.php';

use PhpOffice\PhpWord\TemplateProcessor;

$cnx = Conectarse();

$procesos = $_POST['procesos'] ?? [];

if (empty($procesos)) {
  echo json_encode([]);
  exit;
}

// Inicializar estructura
$procesosData = [];
foreach ($procesos as $pro_id) {
  $procesosData[$pro_id] = [
    'equipos' => [],
    'preparacion' => [],
    'materiales' => [],
    'parametros' => [
      'base' => [],
      'cocidos' => []
    ],
    'toneladas' => 0
  ];
}


// ===== EQUIPOS =====
$sqlEquipos = "
  SELECT equipo_anterior, equipo_nuevo, pro_id
  FROM movimiento_equipos
  WHERE pro_id IN (" . implode(',', array_map('intval', $procesos)) . ")
  ORDER BY be_fecha DESC
";

$resEquipos = mysqli_query($cnx, $sqlEquipos);

if (!$resEquipos) {
  echo json_encode(["error" => mysqli_error($cnx)]);
  exit;
}

while ($row = mysqli_fetch_assoc($resEquipos)) {
  $procesosData[$row['pro_id']]['equipos'][] = $row;
}

$sqlPreparacion = "SELECT pt.pt_descripcion, p.pro_id
FROM preparacion_tipo pt
INNER JOIN procesos p ON pt.pt_id = p.pt_id WHERE p.pro_id IN (" . implode(',', array_map('intval', $procesos)) . ")";
$resPreparacion = mysqli_query($cnx, $sqlPreparacion);
while ($row = mysqli_fetch_assoc($resPreparacion)) {
  $procesosData[$row['pro_id']]['preparacion'][] = $row;
}

// ===== MATERIALES =====
$sqlMateriales = "SELECT m.mat_nombre, pm.pro_id
  FROM materiales m
  INNER JOIN procesos_materiales pm ON pm.mat_id = m.mat_id
  WHERE pm.pro_id IN (" . implode(',', array_map('intval', $procesos)) . ")
  GROUP BY m.mat_id, pm.pro_id
  ORDER BY m.mat_nombre ASC
";

$resMateriales = mysqli_query($cnx, $sqlMateriales);

if (!$resMateriales) {
  echo json_encode(["error" => mysqli_error($cnx)]);
  exit;
}

while ($row = mysqli_fetch_assoc($resMateriales)) {
  $procesosData[$row['pro_id']]['materiales'][] = $row;
}

// ===== PARÁMETROS DE LIBERACIÓN =====

$sqlParametros = "SELECT pro_id, prol_solides
  FROM procesos_liberacion_b
  WHERE pro_id IN (" . implode(',', array_map('intval', $procesos)) . ")
";

$resParametros = mysqli_query($cnx, $sqlParametros);

if (!$resParametros) {
  echo json_encode(['error' => mysqli_error($cnx)]);
  exit;
}

while ($row = mysqli_fetch_assoc($resParametros)) {
  $procesosData[$row['pro_id']]['parametros']['base'] = $row;
}

$sqlParametrosCocidos = "
  SELECT
    extract,
    conductividad,
    ph,
    pro_id
FROM (
    SELECT
        CASE
            WHEN plc.prol_por_extrac IS NOT NULL
                 AND plc.prol_por_extrac <> 0
                THEN plc.prol_por_extrac
            ELSE plb.prol_por_extrac
        END AS extract,

        plc.prol_ce AS conductividad,
        plc.prol_cocido AS ph,
        plb.pro_id,

        ROW_NUMBER() OVER (
            PARTITION BY plb.pro_id
            ORDER BY plc.prol_id ASC
        ) AS rn,

        COUNT(*) OVER (
            PARTITION BY plb.pro_id
        ) AS total

    FROM procesos_liberacion_b_cocidos plc
    INNER JOIN procesos_liberacion_b plb
        ON plb.prol_id = plc.prol_id
    WHERE plb.pro_id IN (" . implode(',', array_map('intval', $procesos)) . ")
) t
WHERE rn = IF(total >= 2, 2, 1)
";

$resParametrosCocidos = mysqli_query($cnx, $sqlParametrosCocidos);

while ($row = mysqli_fetch_assoc($resParametrosCocidos)) {
  $procesosData[$row['pro_id']]['parametros']['cocidos'] = $row;
}

// ===== TONELADAS =====
$sqlToneladas = "SELECT pro_id,pro_total_kg FROM procesos WHERE pro_id IN (" . implode(',', array_map('intval', $procesos)) . ")";
$resToneladas = mysqli_query($cnx, $sqlToneladas);
while ($row = mysqli_fetch_assoc($resToneladas)) {
  $procesosData[$row['pro_id']]['toneladas'] = $row['pro_total_kg'];
}

$listaProcesos = implode('-', array_keys($procesosData));

$nombreArchivo = "Hoja_Viajera_Procesos_{$listaProcesos}_" . ".docx";

function fraseEquipos(array $equipos): string
{
  if (empty($equipos)) {
    return 'Sin cambios de equipo';
  }

  $frases = [];

  foreach ($equipos as $eq) {

    if (!empty($eq['equipo_anterior']) && !empty($eq['equipo_nuevo'])) {

      $equipoNuevo = $eq['equipo_nuevo'];

      // Reemplaza solo la palabra, conserva el resto (ej. "1")
      $equipoNuevo = str_ireplace('preparador', 'Receptor', $equipoNuevo);

      $frases[] = "{$eq['equipo_anterior']} → {$equipoNuevo}";
    }
  }

  return implode(', ', $frases);
}

function fraseMateriales(array $materiales): string
{
  if (empty($materiales)) {
    return 'Sin materiales asociados';
  }

  $lineas = [];

  foreach ($materiales as $mat) {
    $lineas[] = "- {$mat['mat_nombre']}";
  }

  return implode("\n", $lineas);
}


function fraseParametros(array $parametros): string
{
  $lineas = [];

  // Base
  if (!empty($parametros['base']['prol_solides'])) {
    $lineas[] = "Sólidos: {$parametros['base']['prol_solides']}%";
  }

  // Cocidos
  if (!empty($parametros['cocidos'])) {
    $c = $parametros['cocidos'];

    if ($c['extract'] !== null) {
      $lineas[] = "Extracto: {$c['extract']}";
    }
    if ($c['conductividad'] !== null) {
      $lineas[] = "Conductividad: {$c['conductividad']}";
    }
    if ($c['ph'] !== null) {
      $lineas[] = "pH: {$c['ph']}";
    }
  }

  if (empty($lineas)) {
    return 'Sin parámetros registrados';
  }

  return implode("\n", $lineas);
}


$frasesProcesos = [];
$textoMateriales = [];
$textoParametros = [];
$textoToneladas = [];
$textoToneladasTotales = 0;

foreach ($procesosData as $pro_id => $data) {
  $frase = "{$data['preparacion'][0]['pt_descripcion']} ";
  $frase .= "Proceso {$pro_id}\n";
  $frase .= fraseEquipos($data['equipos']) . "\n";
  $textoMateriales[] =
    "Proceso {$pro_id}\n" .
    fraseMateriales($data['materiales']);

  $textoParametros[] =
    "Proceso {$pro_id}\n" .
    fraseParametros($data['parametros']);

  $textoToneladas[] = "Proceso {$pro_id}: " . number_format($data['toneladas'], 2) . " kg";
  $textoToneladasTotales += $data['toneladas'];

  $frasesProcesos[] = $frase;
}

$textoFinal = implode("\n", $frasesProcesos);
$textoMaterialesFinal = implode("\n", $textoMateriales);
$textoParametrosFinal = implode("\n", $textoParametros);


// Evitar corrupción del archivo
ob_start();

// Cargar plantilla
$template = new TemplateProcessor(__DIR__ . '/hoja_viajera.docx');
$template->setValue('proceso', $textoFinal);
$template->setValue('hora_liberacion', date('d/m/Y H:i'));
$template->setValue('materiales', $textoMaterialesFinal);
$template->setValue('parametros', $textoParametrosFinal);
$template->setValue('toneladas', implode("\n", $textoToneladas));
$template->setValue('toneladas_totales', number_format($textoToneladasTotales, 2));

// Archivo temporal
$tempFile = tempnam(sys_get_temp_dir(), 'hojaviajera_') . '.docx';
$template->saveAs($tempFile);

// ===== CONVERTIR DOCX A PDF (WINDOWS SERVER) =====

$pdfFile = str_replace('.docx', '.pdf', $tempFile);

// Ruta real de LibreOffice
$soffice = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe"';

$cmd = $soffice .
  ' --headless --convert-to pdf --outdir ' .
  escapeshellarg(dirname($tempFile)) . ' ' .
  escapeshellarg($tempFile);

exec($cmd, $output, $returnCode);

if ($returnCode !== 0 || !file_exists($pdfFile)) {
  unlink($tempFile);
  die('Error al convertir DOCX a PDF');
}

// Limpiar buffers
ob_clean();

// Mostrar PDF (inline)
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="Hoja_Viajera.pdf"');
header('Content-Length: ' . filesize($pdfFile));

readfile($pdfFile);

// Limpieza
unlink($tempFile);
unlink($pdfFile);
exit;


//echo json_encode($procesosData);
exit;
