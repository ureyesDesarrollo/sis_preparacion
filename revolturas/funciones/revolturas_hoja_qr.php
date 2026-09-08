<?php

require_once 'certificados_calidad/load_phpword.php';
require_once '../../conexion/conexion.php';
include "../../assets/barcode/barcode.php";

use PhpOffice\PhpWord\TemplateProcessor;

ob_start();

$cnx = Conectarse();

$generator = new barcode_generator();

$rev_id = intval($_GET['rev_id']);

/*
|--------------------------------------------------------------------------
| DATOS DE LA REVOLTURA
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT r.rev_folio, c.cte_id, c.cte_nombre AS cliente, cal.cal_descripcion AS calidad
    FROM rev_revolturas r
    INNER JOIN rev_clientes c ON r.rev_teo_cliente = c.cte_id
    LEFT JOIN rev_calidad cal ON r.rev_teo_calidad = cal.cal_id
    WHERE rev_id = '$rev_id'
";

$result = mysqli_query($cnx, $sql);
$revoltura = mysqli_fetch_assoc($result);

$rev_folio = $revoltura['rev_folio'];
$cliente = $revoltura['cliente'];
$id_cliente = $revoltura['cte_id'];
$calidad = $revoltura['calidad'] ?? 'SIN CALIDAD TEORICA';


/*
|--------------------------------------------------------------------------
| GENERAR QR
|--------------------------------------------------------------------------
*/

$currentDir = dirname($_SERVER['REQUEST_URI']);

$url = 'http://' .
    $_SERVER['HTTP_HOST'] .
    $currentDir .
    '/revolturas_detalle.php?rev_id=' .
    $rev_id;

$image = $generator->render_image(
    'qr',
    $url,
    ''
);

$temp_qr_path =
    sys_get_temp_dir() .
    "/qr_rev_$rev_id.png";

imagepng(
    $image,
    $temp_qr_path
);

imagedestroy($image);


/*
|--------------------------------------------------------------------------
| CARGAR TEMPLATE
|--------------------------------------------------------------------------
*/

$paths = [
    'template_azul' => __DIR__ . '/template_azul.docx',
    'template_dorada' => __DIR__ . '/template_dorada.docx',
    'template_morado' => __DIR__ . '/template_morado.docx',
    'template_verde' => __DIR__ . '/template_verde.docx',
    'template_default' => __DIR__ . '/template_revoltura_10_paginas.docx',
];

$calidadNormalizada = strtoupper(trim($calidad));

$templatePath = $paths['template_default'];

// Selección por calidad para TODOS los clientes
switch ($calidadNormalizada) {
    case 'AZUL':
        $templatePath = $paths['template_azul'];
        break;

    case 'DORADA':
    case '280':
        $templatePath = $paths['template_dorada'];
        break;

    case 'MORADO':
        $templatePath = $paths['template_morado'];
        break;

    case 'VERDE':
    case '250':
        $templatePath = $paths['template_verde'];
        break;
}

// Excepciones específicas por cliente
if ($id_cliente == 251) {
    $templatePath = $paths['template_dorada'];
}

if (!file_exists($templatePath)) {
    die('La plantilla no existe: ' . $templatePath);
}

$template = new TemplateProcessor($templatePath);


/*
|--------------------------------------------------------------------------
| REEMPLAZAR DATOS
|--------------------------------------------------------------------------
*/

$template->setValue(
    'folio',
    $rev_folio
);

$clienteEtiqueta = $cliente;

if ($id_cliente == 74) {
    $clienteEtiqueta = strtoupper($calidad) . ' - ' . $cliente;
} elseif ($id_cliente == 251) {
    $clienteEtiqueta = 'DORADA - VENTA PUBLICO EN GENERAL';
}

$template->setValue('cliente', $clienteEtiqueta);

/*
|--------------------------------------------------------------------------
| QR
|--------------------------------------------------------------------------
*/

$template->setImageValue(
    'qr',
    [
        'path'   => $temp_qr_path,
        'width'  => 300,
        'height' => 300,
        'ratio'  => true
    ]
);

/*
|--------------------------------------------------------------------------
| NUMERACIÓN
|--------------------------------------------------------------------------
*/

$totalPaginas = 10;

for ($i = 1; $i <= $totalPaginas; $i++) {

    $template->setValue(
        "numeracion_$i",
        "$i/$totalPaginas"
    );
}


/*
|--------------------------------------------------------------------------
| GUARDAR TEMPORALMENTE
|--------------------------------------------------------------------------
*/

$temp_docx =
    sys_get_temp_dir() .
    "/revoltura_$rev_folio.docx";

$template->saveAs($temp_docx);


/*
|--------------------------------------------------------------------------
| DESCARGAR
|--------------------------------------------------------------------------
*/

ob_clean();

header(
    "Content-Description: File Transfer"
);

header(
    'Content-Disposition: attachment; filename="revoltura_' .
        $rev_folio .
        '.docx"'
);

header(
    'Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'
);

header(
    'Content-Length: ' .
        filesize($temp_docx)
);

header(
    'Cache-Control: must-revalidate'
);

readfile($temp_docx);


/*
|--------------------------------------------------------------------------
| LIMPIAR ARCHIVOS TEMPORALES
|--------------------------------------------------------------------------
*/

unlink($temp_qr_path);
unlink($temp_docx);

exit;
