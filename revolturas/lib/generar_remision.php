<?php
require_once 'load_phpword.php';
include '../../conexion/conexion.php';
include "../utils/funciones.php";

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

function obtenerDatosRemision($orden, $folio, $cnx)
{
    $sql = "SELECT 
        oe.cte_id AS cliente_id,
        cte.cte_nombre AS cliente_nombre,
        cte.cte_ubicacion AS cliente_ubicacion,
        oed.cantidad AS cantidad_solicitada, 
        cal.cal_id,
        CASE 
            WHEN rr.rev_id IS NOT NULL THEN rev.rev_folio
            WHEN rrc.rev_id IS NOT NULL THEN rrc_rev.rev_folio
            ELSE 'Producto General'
        END AS rev_folio,
        COALESCE(rr.rr_id, rrc.rrc_id) AS empaque_id,
        COALESCE(rr.rr_ext_inicial, rrc.rrc_ext_inicial, 0) AS existencia_inicial,
        COALESCE(rr.rr_ext_real, rrc.rrc_ext_real, 0) AS existencia_real,
        COALESCE(rr_pres.pres_id, rrc_pres.pres_id) AS presentacion_id,
        COALESCE(rr_pres.pres_descrip, rrc_pres.pres_descrip) AS presentacion_descripcion,
        COALESCE(rr_pres.pres_kg, rrc_pres.pres_kg) AS pres_kg,
        CASE 
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
            rev_clientes cte ON oe.cte_id = cte.cte_id
        LEFT JOIN rev_calidad cal ON cal.cal_id = COALESCE(rev.cal_id, rrc_rev.cal_id)
        WHERE 
            oe.oe_id = '$orden'";
    $result = mysqli_query($cnx, $sql);
    if (!$result) return null;
    $data = [];
    while ($fila = mysqli_fetch_assoc($result)) {
        $fila['calidad'] = obtenerBloomPorCalidad($fila['cal_id']);
        $data[] = $fila;
    }
    return $data;
}

// Utiliza esta función para limpiar los valores monetarios
function formatoMoneda($cantidad)
{
    return '$ ' . number_format($cantidad, 2, '.', ',');
}

function generarRemisionWord($remision, $archivo = 'REMISION.docx')
{
    $phpWord = new PhpWord();

    // Configuración de la sección
    $section = $phpWord->addSection([
        'marginLeft'   => 600,
        'marginRight'  => 600,
        'marginTop'    => 500,
        'marginBottom' => 500,
        'pageSizeW'    => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5),
        'pageSizeH'    => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(11),
    ]);

     // ---- AGREGAR LOGO ----
    $logoPath = '../../imagenes/logo_progel_v5.png';
    if (file_exists($logoPath)) {
        $section->addImage($logoPath, [
            'width' => 130, 
            'height' => 60,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
            'wrappingStyle' => 'inline'
        ]);
        $section->addTextBreak(1);
    }

    // Título y folio
    $section->addText('REMISIÓN', [
        'size' => 22,
        'bold' => true,
        'color' => '000000',
        'name' => 'Arial'
    ], ['align' => 'center', 'spaceAfter' => 200]);
    $section->addText('FOLIO: ' . $remision['folio'], [
        'size' => 12,
        'bold' => true
    ], ['align' => 'right']);

    // Info de cliente
    $section->addTextBreak(1);
    $section->addText("VENDIDO A: " . strtoupper($remision['cliente']), [
        'size' => 12,
        'bold' => true
    ]);
    // Tabla para domicilio y fecha (sin bordes)
    $tableInfo = $section->addTable([
        'borderSize'  => 0,
        'borderColor' => 'FFFFFF',
        'cellMargin'  => 60,
        'alignment'   => \PhpOffice\PhpWord\SimpleType\JcTable::START,
    ]);
    $tableInfo->addRow();
    $tableInfo->addCell(8000)->addText("DOMICILIO:   " . $remision['domicilio'], [
        'size' => 12
    ]);
    $tableInfo->addCell(4000)->addText($remision['fecha'], [
        'size' => 12
    ], ['align' => 'right']);
    $section->addTextBreak(1);

    // Tabla de productos (ahora con columna Observaciones)
    $table = $section->addTable([
        'borderSize' => 0,
        'borderColor' => 'FFFFFF',
        'cellMargin' => 10,
        'alignment'  => \PhpOffice\PhpWord\SimpleType\JcTable::START,
        'width' => 100 * 30,
    ]);

    // Encabezados
    $table->addRow();
    $table->addCell(1500)->addText('CANTIDAD', ['bold' => true, 'size' => 11], ['align' => 'center']);
    $table->addCell(5000)->addText('DESCRIPCIÓN DEL ARTÍCULO', ['bold' => true, 'size' => 11]);
    $table->addCell(1500)->addText('PRECIO', ['bold' => true, 'size' => 11], ['align' => 'right']);
    $table->addCell(1500)->addText('IMPORTE', ['bold' => true, 'size' => 11], ['align' => 'right']);
    $table->addCell(2500)->addText('OBSERVACIONES', ['bold' => true, 'size' => 11], ['align' => 'center']);

    // Productos
    foreach ($remision['productos'] as $prod) {
        $esPromocion = (isset($prod['precio']) && trim($prod['precio']) === '$ 0.00');

        $table->addRow();
        $table->addCell(1500)->addText($prod['cantidad'], [], ['align' => 'center']);
        #$table->addCell(5000)->addText($prod['descripcion']);
        $table->addCell(5000)->addText('GRENETINA ALIMENTICIA');
        $table->addCell(1500)->addText($prod['precio'], [], ['align' => 'right']);
        $table->addCell(1500)->addText($prod['importe'], [], ['align' => 'right']);
        $table->addCell(2500)->addText(
            $esPromocion ? 'MERCANCÍA DE PROMOCIÓN' : '',
            ['italic' => $esPromocion, 'bold' => $esPromocion, 'color' => $esPromocion ? 'B22222' : '000000'],
            ['align' => 'center']
        );

        // Bloom y detalles debajo
        if (!empty($prod['bloom'])) {
            $table->addRow();
            $table->addCell(1500);
            $table->addCell(5000)->addText("({$prod['bloom']})", ['size' => 10]);
            $table->addCell(1500);
            $table->addCell(1500);
            $table->addCell(2500);
        }
        if (!empty($prod['detalle'])) {
            $table->addRow();
            $table->addCell(1500);
            $table->addCell(5000)->addText("({$prod['descripcion']} - {$prod['detalle']})", ['size' => 10]);
            $table->addCell(1500);
            $table->addCell(1500);
            $table->addCell(2500);
        }
    }

    // Totales
    $section->addTextBreak(2);
    $tableTotals = $section->addTable([
        'borderSize' => 0,
        'borderColor' => 'FFFFFF',
        'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END,
    ]);
    $tableTotals->addRow();
    $tableTotals->addCell(4000)->addText('SUBTOTAL:', [
        'bold' => true,
        'size' => 12
    ]);
    $tableTotals->addCell(3000)->addText($remision['subtotal'], [
        'bold' => true,
        'size' => 12
    ], ['align' => 'right']);
    $tableTotals->addRow();
    $tableTotals->addCell(4000)->addText('TOTAL:', [
        'bold' => true,
        'size' => 12
    ]);
    $tableTotals->addCell(3000)->addText($remision['total'], [
        'bold' => true,
        'size' => 12
    ], ['align' => 'right']);

    // Importe con letra
    if (!empty($remision['total_letra'])) {
        $section->addTextBreak(1);
        $section->addText('IMPORTE CON LETRA: (' . strtoupper($remision['total_letra'] . ')'), [
            'size' => 12
        ]);
    }

    // Guardar y descargar
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="' . $archivo . '"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');

    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save("php://output");
    exit;
}


$orden = $_GET['orden_id'];
$folio = $_GET['folio'];
$precios = [];
if (!empty($_GET['precios'])) {
    $precios = json_decode($_GET['precios'], true);
}
$cnx = Conectarse();
$datos = obtenerDatosRemision($orden, $folio, $cnx);
$fmt = new \IntlDateFormatter('es_MX', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE);
$fmt->setPattern("d 'de' MMMM 'del' yyyy");

$preciosIndex = [];
foreach ($precios as $p) {
    $preciosIndex[$p['empaque_id']] = floatval($p['costo_unitario']);
}


$remision = [
    'folio' => $folio,
    'fecha' => strtoupper($fmt->format(time())),
    'cliente' => '',
    'domicilio' => '',
    'productos' => [],
    'subtotal' => '',
    'total' => '',
    'total_letra' => ''
];

foreach ($datos as $fila) {
    if (empty($remision['cliente'])) {
        $remision['cliente'] = $fila['cliente_nombre'];
        $remision['domicilio'] = $fila['cliente_ubicacion'];
    }

    $empaqueId = $fila['empaque_id'];
    $precio = $preciosIndex[$empaqueId] ?? 0;
    $kilos_solicitados = floatval($fila['cantidad_solicitada']) * floatval($fila['pres_kg']);
    $promocion = 0;
    if (isset($preciosIndex[$empaqueId . '_promo'])) {
        $promocion = floatval($preciosIndex[$empaqueId . '_promo']); // si usas otra estructura de índice
    } else {
        // Si precios viene como array de objetos: [{empaque_id, costo_unitario, promocion}]
        foreach ($precios as $p) {
            if ($p['empaque_id'] == $empaqueId) {
                $promocion = isset($p['promocion']) ? floatval($p['promocion']) : 0;
                break;
            }
        }
    }

    $kilos_facturables = max(0, $kilos_solicitados - $promocion);

    // Genera la descripción según presentación
    $desc = $fila['presentacion_id'];
    if ($desc == '3') {
        $descripcion = descripcionCajas($kilos_facturables, '1 KG' ,'CAJAS');
    } else if ($desc == '4') {
        $descripcion = descripcionCajas($kilos_facturables, '1/4 KG', 'CAJAS');
    } else if ($desc == '2') {
        $descripcion = descripcionCajas($kilos_facturables, '25 KG' ,'SACOS');
    } else {
        $descripcion = $fila['presentacion_descripcion']; // fallback
    }

    $importe = $precio * $kilos_facturables;

    // Producto facturado
    $remision['productos'][] = [
        'cantidad' => $kilos_facturables,
        'descripcion' => $descripcion,
        'bloom' => $fila['calidad'],
        'detalle' => "Lote: {$fila['rev_folio']}",
        'precio' => '$ ' . number_format($precio, 2, '.', ','),
        'importe' => '$ ' . number_format($importe, 2, '.', ','),
    ];

    // Si hubo promoción, agrega la línea de promoción
    if ($promocion > 0) {
        // Descripción de promoción, misma lógica
        if ($desc == '3') {
            $descripcion_promo = descripcionCajas($promocion, '1 KG' ,'CAJAS (PROMOCIÓN)');
        } else if ($desc == '4') {
            $descripcion_promo = descripcionCajas($promocion, '1/4 KG' , 'CAJAS (PROMOCIÓN)');
        } else if ($desc == '2') {
            $descripcion_promo = descripcionCajas($promocion, '25 KG' ,'SACOS (PROMOCIÓN)');
        } else {
            $descripcion_promo = $fila['presentacion_descripcion'] . ' (PROMOCIÓN)';
        }
        $remision['productos'][] = [
            'cantidad' => $promocion,
            'descripcion' => $descripcion_promo,
            'bloom' => $fila['calidad'],
            'detalle' => "LOTE: {$fila['rev_folio']}",
            'precio' => '$ 0.00',
            'importe' => '$ 0.00',
        ];
    }
}


$subtotal = 0;
foreach ($remision['productos'] as $prod) {
    // Elimina el símbolo y formatea para sumar
    $imp = str_replace(['$', ','], '', $prod['importe']);
    $subtotal += floatval($imp);
}
$remision['subtotal'] = '$ ' . number_format($subtotal, 2, '.', ',');
$remision['total'] = $remision['subtotal'];
$remision['total_letra'] = numeroALetras($subtotal);

generarRemisionWord($remision, 'REMISION_' . $remision['cliente'] . '.docx');
