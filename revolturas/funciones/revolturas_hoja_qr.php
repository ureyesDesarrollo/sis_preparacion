<?php
require_once 'certificados_calidad/load_phpword.php';
require_once '../../conexion/conexion.php';
include "../../assets/barcode/barcode.php";
ob_start();
$cnx = Conectarse();
$generator = new barcode_generator();
$rev_id = intval($_GET['rev_id']);

$rev_folio = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT rev_folio FROM rev_revolturas WHERE rev_id = '$rev_id'"))['rev_folio'];
var_dump($rev_folio);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;

$phpWord = new PhpWord();
$phpWord->setDefaultParagraphStyle([
    'lineHeight' => 1.0,
    'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0),
]);

$section = $phpWord->addSection([
    'orientation'     => 'landscape',
    'pageSizeW'       => Converter::inchToTwip(11.69), // ancho para A4 horizontal
    'pageSizeH'       => Converter::inchToTwip(8.27),  // alto para A4 horizontal
    'marginLeft'      => Converter::cmToTwip(2),
    'marginRight'     => Converter::cmToTwip(2),
    'marginTop'       => Converter::cmToTwip(2.5),
    'marginBottom'    => Converter::cmToTwip(2)
]);

$section->addText("$rev_folio", ['bold' => true, 'size' => 140]);
$currentDir = dirname($_SERVER['REQUEST_URI']);
$url = 'http://' . $_SERVER['HTTP_HOST'] . $currentDir . '/revolturas_detalle.php?rev_id=' . $rev_id;
#$url = 'http://192.168.1.188' . $currentDir . '/revolturas_detalle.php?rev_id=' . $rev_id;
$image = $generator->render_image('qr', "$url",'');

// Ruta temporal donde guardarás el QR
$temp_qr_path = sys_get_temp_dir() . "/qr_rev_$rev_id.png";

// Guardar imagen PNG en archivo temporal
imagepng($image, $temp_qr_path);
imagedestroy($image); // Liberar memoria

$section->addImage($temp_qr_path, [
    'width' => 300,
    'height' => 300,
    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
]);

ob_clean();
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="revoltura_' . $rev_folio . '.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save("php://output");
unlink($temp_qr_path);
exit;