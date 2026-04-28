<?php

declare(strict_types=1);

// Captura cualquier salida accidental de includes/warnings para que no corrompa el DOCX.
ob_start();
ini_set('display_errors', '0');
error_reporting(E_ALL);

require_once 'load_phpword.php';
include '../../conexion/conexion.php';
include '../utils/funciones.php';

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;

function limpiarTextoWord($texto): string
{
    $texto = (string)($texto ?? '');

    if ($texto === '') {
        return '';
    }

    // Asegura UTF-8 y elimina caracteres de control inválidos para XML.
    if (function_exists('mb_convert_encoding')) {
        $texto = mb_convert_encoding($texto, 'UTF-8', 'UTF-8');
    }

    $texto = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $texto) ?? '';
    $texto = preg_replace('/\s+/u', ' ', $texto) ?? '';

    return trim($texto);
}

function mayusculasSeguro(string $texto): string
{
    return function_exists('mb_strtoupper') ? mb_strtoupper($texto, 'UTF-8') : strtoupper($texto);
}

function limpiarNombreArchivo(string $texto): string
{
    $texto = limpiarTextoWord($texto);
    $texto = preg_replace('/[^A-Za-z0-9_\- ]/', '', $texto) ?? '';
    $texto = trim($texto);

    return $texto !== '' ? $texto : 'CLIENTE';
}

function limpiarBuffersSalida(): void
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
}

function enviarArchivoDocx(string $rutaArchivo, string $nombreDescarga): void
{
    if (!is_file($rutaArchivo) || !file_exists($rutaArchivo)) {
        throw new RuntimeException('No se pudo generar el archivo DOCX.');
    }

    limpiarBuffersSalida();

    if (headers_sent($file, $line)) {
        throw new RuntimeException('Los headers ya fueron enviados en ' . $file . ':' . $line);
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="' . $nombreDescarga . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($rutaArchivo));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');

    readfile($rutaArchivo);

    @unlink($rutaArchivo);
    exit;
}

function obtenerDatosRemision($orden, $folio, $cnx)
{
    $orden = mysqli_real_escape_string($cnx, (string)$orden);

    $sql = "SELECT
        oe.cte_id AS cliente_id,
        cte.cte_nombre AS cliente_nombre,
        cte.cte_ubicacion AS cliente_ubicacion,
        oed.oed_tipo_producto AS tipo_producto,
        oed.cantidad AS cantidad_solicitada,
        cal.cal_id,

        rr.rr_id,
        rrc.rrc_id,
        pe.pe_id,

        CASE
            WHEN oed.oed_tipo_producto = 'EXTERNO' THEN pe.pe_lote
            WHEN rr.rev_id IS NOT NULL THEN rev.rev_folio
            WHEN rrc.rev_id IS NOT NULL THEN rrc_rev.rev_folio
            ELSE 'Producto General'
        END AS rev_folio,

        CASE
            WHEN oed.oed_tipo_producto = 'EXTERNO' THEN pe.pe_id
            ELSE COALESCE(rr.rr_id, rrc.rrc_id)
        END AS empaque_id,

        CASE
            WHEN oed.oed_tipo_producto = 'EXTERNO' THEN COALESCE(pe.pe_existencia_inicial, 0)
            ELSE COALESCE(rr.rr_ext_inicial, rrc.rrc_ext_inicial, 0)
        END AS existencia_inicial,

        CASE
            WHEN oed.oed_tipo_producto = 'EXTERNO' THEN COALESCE(pe.pe_existencia_real, 0)
            ELSE COALESCE(rr.rr_ext_real, rrc.rrc_ext_real, 0)
        END AS existencia_real,

        COALESCE(rr_pres.pres_id, rrc_pres.pres_id, pe_pres.pres_id) AS presentacion_id,
        COALESCE(rr_pres.pres_descrip, rrc_pres.pres_descrip, pe_pres.pres_descrip) AS presentacion_descripcion,
        COALESCE(rr_pres.pres_kg, rrc_pres.pres_kg, pe_pres.pres_kg) AS pres_kg,

        CASE
            WHEN oed.oed_tipo_producto = 'EXTERNO' THEN 'EXTERNO'
            WHEN rr.rr_id IS NOT NULL THEN 'GENERAL'
            WHEN rrc.rrc_id IS NOT NULL THEN 'CLIENTE'
            ELSE 'GENERAL'
        END AS tipo_revoltura

    FROM
        rev_orden_embarque oe
    INNER JOIN
        rev_orden_embarque_detalle oed ON oe.oe_id = oed.oe_id

    LEFT JOIN
        rev_revolturas_pt rr ON rr.rr_id = oed.rr_id
    LEFT JOIN
        rev_revolturas rev ON rev.rev_id = rr.rev_id
    LEFT JOIN
        rev_presentacion rr_pres ON rr_pres.pres_id = rr.pres_id

    LEFT JOIN
        rev_revolturas_pt_cliente rrc ON rrc.rrc_id = oed.rrc_id
    LEFT JOIN
        rev_revolturas rrc_rev ON rrc_rev.rev_id = rrc.rev_id
    LEFT JOIN
        rev_presentacion rrc_pres ON rrc_pres.pres_id = rrc.pres_id

    LEFT JOIN
        producto_externo pe ON pe.pe_id = oed.pe_id
    LEFT JOIN
        rev_presentacion pe_pres ON pe_pres.pres_id = pe.pres_id

    LEFT JOIN
        rev_clientes cte ON oe.cte_id = cte.cte_id
    LEFT JOIN
        rev_calidad cal ON cal.cal_id = COALESCE(rev.cal_id, rrc_rev.cal_id)

    WHERE
        oe.oe_id = '$orden'";

    $result = mysqli_query($cnx, $sql);
    if (!$result) {
        return null;
    }

    $data = [];
    while ($fila = mysqli_fetch_assoc($result)) {
        $fila['calidad'] = ($fila['tipo_producto'] === 'EXTERNO')
            ? ''
            : limpiarTextoWord((string)obtenerBloomPorCalidad($fila['cal_id']));

        $fila['cliente_nombre'] = limpiarTextoWord($fila['cliente_nombre'] ?? '');
        $fila['cliente_ubicacion'] = limpiarTextoWord($fila['cliente_ubicacion'] ?? '');
        $fila['rev_folio'] = limpiarTextoWord($fila['rev_folio'] ?? '');
        $fila['presentacion_descripcion'] = limpiarTextoWord($fila['presentacion_descripcion'] ?? '');

        $data[] = $fila;
    }

    return $data;
}

function formatoMoneda($cantidad): string
{
    return '$ ' . number_format((float)$cantidad, 2, '.', ',');
}

function obtenerDescripcionProducto($fila, $kilos_facturables, $esPromocion = false): string
{
    $presentacionId = (string)($fila['presentacion_id'] ?? '');

    if ($presentacionId === '3') {
        return limpiarTextoWord(descripcionCajas(
            $kilos_facturables,
            '1 KG',
            $esPromocion ? 'CAJAS (PROMOCIÓN)' : 'CAJAS'
        ));
    }

    if ($presentacionId === '4') {
        return limpiarTextoWord(descripcionCajas(
            $kilos_facturables,
            '1/4 KG',
            $esPromocion ? 'CAJAS (PROMOCIÓN)' : 'CAJAS'
        ));
    }

    if ($presentacionId === '2') {
        return limpiarTextoWord(descripcionCajas(
            $kilos_facturables,
            '25 KG',
            $esPromocion ? 'SACOS (PROMOCIÓN)' : 'SACOS'
        ));
    }

    if ($presentacionId === '6') {
        return limpiarTextoWord(descripcionCajas(
            $kilos_facturables,
            '500 GRAMOS',
            $esPromocion ? 'CAJAS (PROMOCIÓN)' : 'CAJAS',
            10
        ));
    }

    return limpiarTextoWord(
        $esPromocion
            ? (($fila['presentacion_descripcion'] ?? '') . ' (PROMOCIÓN)')
            : ($fila['presentacion_descripcion'] ?? '')
    );
}

function generarRemisionWord(array $remision, string $archivo = 'REMISION.docx'): void
{
    $phpWord = new PhpWord();

    $section = $phpWord->addSection([
        'marginLeft'   => 600,
        'marginRight'  => 600,
        'marginTop'    => 500,
        'marginBottom' => 500,
        'pageSizeW'    => Converter::inchToTwip(8.5),
        'pageSizeH'    => Converter::inchToTwip(11),
    ]);

    $logoPath = '../../imagenes/logo_empresa.png';
    if (file_exists($logoPath)) {
        $section->addImage($logoPath, [
            'width' => 100,
            'height' => 120,
            'alignment' => Jc::START,
            'wrappingStyle' => 'inline',
        ]);
        $section->addTextBreak(1);
    }

    $section->addText('REMISIÓN', [
        'size' => 22,
        'bold' => true,
        'color' => '000000',
        'name' => 'Arial',
    ], ['align' => 'center', 'spaceAfter' => 200]);

    $section->addText('FOLIO: ' . limpiarTextoWord($remision['folio'] ?? ''), [
        'size' => 12,
        'bold' => true,
    ], ['align' => 'right']);

    $section->addTextBreak(1);
    $section->addText('VENDIDO A: ' . mayusculasSeguro(limpiarTextoWord($remision['cliente'] ?? '')), [
        'size' => 12,
        'bold' => true,
    ]);

    $tableInfo = $section->addTable([
        'borderSize'  => 0,
        'borderColor' => 'FFFFFF',
        'cellMargin'  => 60,
        'alignment'   => JcTable::START,
    ]);
    $tableInfo->addRow();
    $tableInfo->addCell(8000)->addText('DOMICILIO:   ' . limpiarTextoWord($remision['domicilio'] ?? ''), [
        'size' => 12,
    ]);
    $tableInfo->addCell(4000)->addText(limpiarTextoWord($remision['fecha'] ?? ''), [
        'size' => 12,
    ], ['align' => 'right']);
    $section->addTextBreak(1);

    $table = $section->addTable([
        'borderSize' => 0,
        'borderColor' => 'FFFFFF',
        'cellMargin' => 10,
        'alignment'  => JcTable::START,
        'width' => 100 * 30,
    ]);

    $table->addRow();
    $table->addCell(1500)->addText('CANTIDAD', ['bold' => true, 'size' => 11], ['align' => 'center']);
    $table->addCell(5000)->addText('DESCRIPCIÓN DEL ARTÍCULO', ['bold' => true, 'size' => 11]);
    $table->addCell(1500)->addText('PRECIO', ['bold' => true, 'size' => 11], ['align' => 'right']);
    $table->addCell(1500)->addText('IMPORTE', ['bold' => true, 'size' => 11], ['align' => 'right']);
    $table->addCell(2500)->addText('OBSERVACIONES', ['bold' => true, 'size' => 11], ['align' => 'center']);

    foreach (($remision['productos'] ?? []) as $prod) {
        $esPromocion = !empty($prod['es_promocion']);
        $presentacionId = (string)($prod['presentacion_id'] ?? '');

        $table->addRow();
        $table->addCell(1500)->addText(limpiarTextoWord((string)($prod['cantidad'] ?? '')), [], ['align' => 'center']);
        $table->addCell(5000)->addText(
            $presentacionId === '6' ? 'GRENETINA HIDROLIZADA' : 'GRENETINA ALIMIENTICIA'
        );
        $table->addCell(1500)->addText(limpiarTextoWord($prod['precio'] ?? ''), [], ['align' => 'right']);
        $table->addCell(1500)->addText(limpiarTextoWord($prod['importe'] ?? ''), [], ['align' => 'right']);
        $table->addCell(2500)->addText(
            $esPromocion ? 'MERCANCÍA DE PROMOCIÓN' : '',
            ['italic' => $esPromocion, 'bold' => $esPromocion, 'color' => $esPromocion ? 'B22222' : '000000'],
            ['align' => 'center']
        );

        if (!empty($prod['bloom'])) {
            $table->addRow();
            $table->addCell(1500);
            $table->addCell(5000)->addText('(' . limpiarTextoWord($prod['bloom']) . ')', ['size' => 10]);
            $table->addCell(1500);
            $table->addCell(1500);
            $table->addCell(2500);
        }

        if (!empty($prod['detalle'])) {
            $detalle = limpiarTextoWord(($prod['descripcion'] ?? '') . ' - ' . ($prod['detalle'] ?? ''));
            $table->addRow();
            $table->addCell(1500);
            $table->addCell(5000)->addText('(' . $detalle . ')', ['size' => 10]);
            $table->addCell(1500);
            $table->addCell(1500);
            $table->addCell(2500);
        }
    }

    $section->addTextBreak(2);
    $tableTotals = $section->addTable([
        'borderSize' => 0,
        'borderColor' => 'FFFFFF',
        'alignment' => JcTable::END,
    ]);
    $tableTotals->addRow();
    $tableTotals->addCell(4000)->addText('SUBTOTAL:', [
        'bold' => true,
        'size' => 12,
    ]);
    $tableTotals->addCell(3000)->addText(limpiarTextoWord($remision['subtotal'] ?? ''), [
        'bold' => true,
        'size' => 12,
    ], ['align' => 'right']);
    $tableTotals->addRow();
    $tableTotals->addCell(4000)->addText('TOTAL:', [
        'bold' => true,
        'size' => 12,
    ]);
    $tableTotals->addCell(3000)->addText(limpiarTextoWord($remision['total'] ?? ''), [
        'bold' => true,
        'size' => 12,
    ], ['align' => 'right']);

    if (!empty($remision['total_letra'])) {
        $section->addTextBreak(1);
        $section->addText('IMPORTE CON LETRA: (' . mayusculasSeguro(limpiarTextoWord($remision['total_letra'])) . ')', [
            'size' => 12,
        ]);
    }

    $tempFile = tempnam(sys_get_temp_dir(), 'remision_');
    if ($tempFile === false) {
        throw new RuntimeException('No se pudo crear el archivo temporal.');
    }

    $rutaDocx = $tempFile . '.docx';
    @rename($tempFile, $rutaDocx);

    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($rutaDocx);

    enviarArchivoDocx($rutaDocx, $archivo);
}

$orden = $_GET['orden_id'] ?? '';
$folio = $_GET['folio'] ?? '';

$precios = [];
if (!empty($_GET['precios'])) {
    $precios = json_decode((string)$_GET['precios'], true);
    if (!is_array($precios)) {
        $precios = [];
    }
}

$cnx = Conectarse();
if (function_exists('mysqli_set_charset')) {
    @mysqli_set_charset($cnx, 'utf8mb4');
}

$datos = obtenerDatosRemision($orden, $folio, $cnx);

if ($datos === null) {
    limpiarBuffersSalida();
    http_response_code(500);
    exit('No se pudieron obtener los datos de la remisión.');
}

$fmt = new \IntlDateFormatter('es_MX', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE);
$fmt->setPattern("d 'de' MMMM 'del' yyyy");

$preciosIndex = [];
foreach ($precios as $p) {
    $empaqueId = $p['empaque_id'] ?? null;
    if ($empaqueId !== null && $empaqueId !== '') {
        $preciosIndex[$empaqueId] = [
            'costo_unitario' => (float)($p['costo_unitario'] ?? 0),
            'promocion' => (float)($p['promocion'] ?? 0),
        ];
    }
}

$remision = [
    'folio' => limpiarTextoWord((string)$folio),
    'fecha' => mayusculasSeguro((string)$fmt->format(time())),
    'cliente' => '',
    'domicilio' => '',
    'productos' => [],
    'subtotal' => '',
    'total' => '',
    'total_letra' => '',
];

foreach ($datos as $fila) {
    if (empty($remision['cliente'])) {
        $remision['cliente'] = limpiarTextoWord($fila['cliente_nombre'] ?? '');
        $remision['domicilio'] = limpiarTextoWord($fila['cliente_ubicacion'] ?? '');
    }

    $empaqueId = $fila['empaque_id'];
    $precioData = $preciosIndex[$empaqueId] ?? [
        'costo_unitario' => 0,
        'promocion' => 0,
    ];
    $presentacionId = (string)($fila['presentacion_id'] ?? '');

    $precio = (float)$precioData['costo_unitario'];
    $promocion = (float)$precioData['promocion'];
    $kilosSolicitados = (float)($fila['cantidad_solicitada'] ?? 0) * (float)($fila['pres_kg'] ?? 0);
    $kilosFacturables = max(0, $kilosSolicitados - $promocion);

    $descripcion = obtenerDescripcionProducto($fila, $kilosFacturables, false);
    $importe = $precio * $kilosFacturables;

    $remision['productos'][] = [
        'presentacion_id' => $presentacionId,
        'cantidad' => $kilosFacturables,
        'descripcion' => $descripcion,
        'bloom' => limpiarTextoWord($fila['calidad'] ?? ''),
        'detalle' => limpiarTextoWord('Lote: ' . ($fila['rev_folio'] ?? '')),
        'precio' => formatoMoneda($precio),
        'importe' => formatoMoneda($importe),
        'es_promocion' => false,
    ];

    if ($promocion > 0) {
        $descripcionPromo = obtenerDescripcionProducto($fila, $promocion, true);

        $remision['productos'][] = [
            'presentacion_id' => $presentacionId,
            'cantidad' => $promocion,
            'descripcion' => $descripcionPromo,
            'bloom' => limpiarTextoWord($fila['calidad'] ?? ''),
            'detalle' => limpiarTextoWord('LOTE: ' . ($fila['rev_folio'] ?? '')),
            'precio' => '$ 0.00',
            'importe' => '$ 0.00',
            'es_promocion' => true,
        ];
    }
}

$subtotal = 0.0;
foreach ($remision['productos'] as $prod) {
    $imp = str_replace(['$', ','], '', (string)($prod['importe'] ?? '0'));
    $subtotal += (float)$imp;
}

$remision['subtotal'] = formatoMoneda($subtotal);
$remision['total'] = $remision['subtotal'];
$remision['total_letra'] = limpiarTextoWord((string)numeroALetras($subtotal));

$nombreCliente = limpiarNombreArchivo($remision['cliente']);
$nombreArchivo = 'REMISION_' . ($remision['folio'] !== '' ? $remision['folio'] : 'SIN_FOLIO') . '_' . $nombreCliente . '.docx';

generarRemisionWord($remision, $nombreArchivo);
