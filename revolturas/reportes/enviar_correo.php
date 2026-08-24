<?php
require 'generar_pdf.php';
require __DIR__ . '/../lib/EmailSender.php';
include __DIR__ . '/../../conexion/conexion.php';

$timestamp = date('Y-m-d H:i:s');
$fechaArchivo = date('Y-m-d');
$logFile = __DIR__ . "/log_envio.txt";
$cnx = Conectarse();
$modoPrueba = false; // Cambiar a true si no quieres enviar correo durante pruebas

function sumarKilos($datos, $campo_kilos = 'tar_kilos')
{
    $total = 0;
    if (is_array($datos)) {
        foreach ($datos as $row) {
            $total += (float)$row[$campo_kilos]; // Conversión explícita a float
        }
    }
    return number_format($total, 2, '.', '');
}

function obtenerKilos($accion, $campo_kilos = 'tar_kilos')
{
    return sumarKilos(realizarSolicitudPost(['action' => $accion]), $campo_kilos);
}

function escribirLog($mensaje)
{
    global $logFile, $timestamp;
    $origen = basename(__FILE__);
    file_put_contents($logFile, "[$timestamp] [$origen] $mensaje" . PHP_EOL, FILE_APPEND);
}

function insertarKardex($cnx, $entrada, $salida, $total)
{
    $sql = "INSERT INTO rev_kardex (kar_total_entrada, kar_total_salida, kar_inventario,kar_fecha) VALUES ('$entrada', '$salida','$total',CURDATE() - INTERVAL 1 DAY)";
    return mysqli_query($cnx, $sql);
}

function obtenerTotal($cnx, $sql)
{
    return mysqli_query($cnx, $sql);
}

// Consulta de entrada
$sql_entrada = "SELECT 
    SUM(t.tar_kilos) AS tar_kilos
    FROM rev_tarimas t
    LEFT JOIN rev_calidad c ON c.cal_id = t.cal_id
    WHERE t.tar_estatus = 0
    AND t.tar_count_etiquetado > 0 
    AND t.tar_fecha >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) + INTERVAL 7 HOUR
    AND t.tar_fecha < CURDATE() + INTERVAL 7 HOUR
    AND t.pro_id NOT IN (1,2,3);";

// Consulta de salida 
$sql_salida = "SELECT 
    SUM(
        CASE 
            WHEN rp.pres_kg IS NOT NULL THEN f.fe_cantidad * rp.pres_kg
            WHEN rpc.pres_kg IS NOT NULL THEN f.fe_cantidad * rpc.pres_kg
            ELSE 0
        END
    ) AS total_kilos_facturados
    FROM rev_revolturas_pt_facturas f
    LEFT JOIN rev_revolturas_pt rr ON f.rr_id = rr.rr_id
    LEFT JOIN rev_presentacion rp ON rr.pres_id = rp.pres_id
    LEFT JOIN rev_revolturas_pt_cliente rrc ON f.rrc_id = rrc.rrc_id
    LEFT JOIN rev_presentacion rpc ON rrc.pres_id = rpc.pres_id
    WHERE f.fe_fecha >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) + INTERVAL 7 HOUR
    AND f.fe_fecha < CURDATE() + INTERVAL 7 HOUR";

// Rutas de los PDFs
$pdf1 = __DIR__ . "/pdfs/producto_terminado_sin_empacar_{$fechaArchivo}.pdf";
$pdf2 = __DIR__ . "/pdfs/producto_terminado_empacado_{$fechaArchivo}.pdf";

// Generar los PDFs
$exito1 = generarPDF('http://localhost/sis_preparacion/revolturas/reportes/reporte_inventario.php', $pdf1);
$exito2 = generarPDF('http://localhost/sis_preparacion/revolturas/reportes/reporte_producto_terminado.php', $pdf2);

if ($exito1 && $exito2) {
    escribirLog("Los PDFs se generaron correctamente.");
    echo "Los PDFs se generaron correctamente.<br>";

    // Enviar correo con los PDFs adjuntos
    if (!$modoPrueba) {
        $mailSender = new MailSender();
        $body = "
            <p>Hola,</p>
            <p>Adjunto encontrarás los reportes de producto sin empacar y empacado.</p>
            <p>Saludos cordiales.</p>";

        $enviado = $mailSender->sendMail(
            "Reporte Diario {$fechaArchivo}",
            $body,
            $pdf1,
            $pdf2
        );

        if ($enviado) {
            escribirLog("Correo enviado con éxito.");
            echo "Correo enviado con éxito.<br>";
        } else {
            escribirLog("Error al enviar el correo.");
            echo "Error al enviar el correo.<br>";
        }
    } else {
        escribirLog("Modo prueba activado. Correo no enviado.");
        echo "Modo prueba activado. Correo no enviado.<br>";
    }

    // Obtener totales
    $entradaResult = obtenerTotal($cnx, $sql_entrada);
    $entradaRow = $entradaResult ? mysqli_fetch_assoc($entradaResult) : ['tar_kilos' => 0];
    $entrada = number_format($entradaRow['tar_kilos'] ?? 0, 2, '.', '');

    $salidaResult = obtenerTotal($cnx, $sql_salida);
    $salidaRow = $salidaResult ? mysqli_fetch_assoc($salidaResult) : ['total_kilos_facturados' => 0];
    $salida = number_format($salidaRow['total_kilos_facturados'] ?? 0, 2, '.', '');
    $total_tarimas_pesada_dia = obtenerKilos('tarimas_pesada_dia');
    $total_tarimas_procesos_analisis = obtenerKilos('tarimas_proceso_analisis');
    $total_tarimas_pendientes_enviar_almacen = obtenerKilos('tarimas_pendiente_enviar_almacen');
    $total_revolturas_terminadas = obtenerKilos('revolturas_terminadas', 'rev_kilos');
    $total_tarimas_revolvedora = obtenerKilos('tarimas_revolvedora');
    $total_revoluturas_dia = obtenerKilos('revolturas_dia', 'rev_kilos');
    $total_tarimas_disponibles = obtenerKilos('tarimas_disponibles');
    $total_empacado = json_decode(file_get_contents(__DIR__ . '/datos_existencias.json'), true);
    $total_inventario = $total_tarimas_pesada_dia + $total_tarimas_procesos_analisis + $total_tarimas_pendientes_enviar_almacen + $total_revolturas_terminadas + $total_tarimas_revolvedora + $total_revoluturas_dia + $total_tarimas_disponibles;
    $total = number_format($total_empacado['kg'] + $total_inventario ?? 0, 2, '.', '');

    // Insertar en kardex
    if (insertarKardex($cnx, $entrada, $salida, $total)) {
        escribirLog("Registro insertado en rev_kardex: Entrada = $entrada, Salida = $salida");
    } else {
        escribirLog("Error al insertar en rev_kardex: Entrada = $entrada, Salida = $salida");
    }
} else {
    if (!$exito1) escribirLog("Error al generar PDF 1.");
    if (!$exito2) escribirLog("Error al generar PDF 2.");
    echo "Error: No se generaron correctamente los PDFs.<br>";
}
