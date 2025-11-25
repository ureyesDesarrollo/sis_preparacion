<?php
require_once 'load_phpword.php';

// Iniciar buffer de salida para evitar corrupción
ob_start();
$oe_id = intval($_GET['oe_id']);
$empaque_id = intval($_GET['empaque_id']);

require_once 'revoltura_consulta.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use \PhpOffice\PhpWord\Shared\Converter;

$phpWord = new PhpWord();
$phpWord->setDefaultParagraphStyle([
    'lineHeight' => 1.0,
    'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0),
]);

$section = $phpWord->addSection([
    'pageSizeW'       => Converter::inchToTwip(8.27),
    'pageSizeH'       => Converter::inchToTwip(11.69),
    'marginLeft'      => Converter::cmToTwip(2),
    'marginRight'     => Converter::cmToTwip(2),
    'marginTop'       => Converter::cmToTwip(2.5),
    'marginBottom'    => Converter::cmToTwip(2)
]);
// Agregar encabezado
$header = $section->addHeader();

// CONTENIDO DEL CERTIFICADO
$section->addText('CERTIFICADO DE ANALISIS', ['name' => 'Lucida Handwriting', 'bold' => true, 'size' => 14], ['alignment' => 'center']);
$section->addTextBreak(1);
$tableStyle = [
    'width' => 100 * 50, // 100% del ancho (50 = 1%)
    'unit' => 'pct'
];

$borderBottomStyle = [
    'borderBottomSize' => 6,
    'borderBottomColor' => '000000',
    'cellMarginTop' => 100,    // 100 twips (~0.18 cm)
    'cellMarginRight' => 300,  // 300 twips (~0.53 cm)
    'cellMarginBottom' => 100, // 100 twips (~0.18 cm)
    'cellMarginLeft' => 300,   // 300 twips (~0.53 cm)
];



$phpWord->addTableStyle('TablaEncabezado', $tableStyle);
$table = $section->addTable('TablaEncabezado');
$table->addRow();
$table->addCell(5670, $borderBottomStyle)->addText("$cliente");
$table->addCell(4000, $borderBottomStyle)->addText("UBICACIÓN: $cliente_ubicacion");
$table->addRow();
$table->addCell(5670, $borderBottomStyle)->addText("LOTE: {$revoltura['rev_folio']}");
$cell = $table->addCell(4000, $borderBottomStyle);
$cell->addText("FECHA DE PRODUCCIÓN: $fecha_elaboracion_formateada");
$cell->addText("FECHA DE CADUCIDAD: $fecha_caducidad_formateada");
$table->addRow();
$cell2 = $table->addCell(5670, $borderBottomStyle);
$cell2->addText('DESCRIPCIÓN:');
$cell2->addText("GRENETINA ALIMENTICIA PROGEL DIAMANTE $calidad");
$table->addCell(4000, $borderBottomStyle)->addText("CANTIDAD:  $cantidad KG");

$section->addTextBreak(1);

$phpWord->addTableStyle('TablaCertificado', [
    'width' => 100 * 50,
    'unit' => 'pct',
]);

$table2 = $section->addTable('TablaCertificado');
// Encabezado
$table2->addRow();
$table2->addCell(5000)->addText('PRUEBA', ['name' => 'Times New Roman', 'bold' => true], ['alignment' => 'center']);
$table2->addCell(7000)->addText('RESULTADO', ['name' => 'Times New Roman', 'bold' => true], ['alignment' => 'center']);

$datos = [
    ['BLOOM (6.66%)', "{$revoltura['rev_bloom']} G/BLOOM"],
    ['VISCOSIDAD (6.66)', "{$revoltura['rev_viscosidad']} mps"],
    ['P.H (6.66)', "{$revoltura['rev_ph']}"],
    ['HUMEDAD', "{$revoltura['rev_humedad']} %"],
    ['CENIZAS', "{$revoltura['rev_cenizas']} %"],
    ['ASPECTO', 'POLVO GRANULAR'],
    ['GRANULOMETRIA', 'MALLA 30'],
    ['OLOR', 'CARACTERÍSTICO'],
    ['COLOR', 'AMARILLO CLARO'],
    ['CTA. TOTAL BACTERIANA', '<500 UFC/G'],
    ['COLIFORMES', 'AUSENCIA EN 1 g.'],
    ['E.COLI', 'AUSENCIA EN 1 g.'],
    ['SALMONELLA', 'AUSENCIA EN 25 g.'],
    ['S.AUREUS', 'AUSENCIA EN 1 g.'],
    ['LEVADURAS', '<10 UFC/G'],
    ['HONGOS', '<10 UFC/G']
];

foreach ($datos as $fila) {
    $row = $table2->addRow(500);
    $cell1 = $row->addCell(5000, ['valign' => 'center', 'borderSize' => 6, 'borderColor' => '000000']);
    $cell1->addText($fila[0], null, ['alignment' => 'left']);

    $cell2 = $row->addCell(5000, ['valign' => 'center', 'borderSize' => 6, 'borderColor' => '000000']);
    $cell2->addText(htmlspecialchars($fila[1], ENT_QUOTES | ENT_XML1), null, ['alignment' => 'center']);
}

// Firma
$section->addTextBreak(2);
$section->addText('ING. NANCY BARREDA', ['bold' => true, 'underline' => 'single']);
$section->addText('GERENTE DE CALIDAD');

// Limpiar buffer y forzar descarga
ob_clean();
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="certificado_varios.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save("php://output");
exit;
