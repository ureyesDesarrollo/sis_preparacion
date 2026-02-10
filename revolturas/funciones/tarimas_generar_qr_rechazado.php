<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Enero-2025 */
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
include "../../conexion/conexion.php";
ini_set('display_errors', 0);
$tar_id = $_GET['tar_id'];
$opcion = intval($_GET['opcion']);
$cnx = Conectarse();

try {
    // Consulta de datos de la tarima
    $listado_tarimas = mysqli_query(
        $cnx,
        "SELECT tar_folio, pro_id,pro_id_2,tar_fecha, tar_fino, tar_count_etiquetado AS contador 
         FROM rev_tarimas 
         WHERE tar_id = '$tar_id'"
    );

    $datos_tarimas = mysqli_fetch_assoc($listado_tarimas);

    // Actualización del contador de etiquetas
    $update_contador = "UPDATE rev_tarimas SET tar_count_etiquetado = {$datos_tarimas['contador']} + 1 WHERE tar_id = '$tar_id'";

    // URL para el código QR
    $currentDir = dirname($_SERVER['REQUEST_URI']);
    $url = 'http://' . $_SERVER['HTTP_HOST'] . $currentDir . '/tarimas_detalle.php?tar_id=' . $tar_id;
    //$url = 'http://172.20.10.2/sis_preparacion/revolturas/funciones/tarimas_detalle.php?tar_id=' . $tar_id;
    $fino = ($datos_tarimas['tar_fino'] == 'F') ? 'FINO' : '';
    $pro_id_2 = !empty($datos_tarimas['pro_id_2']) ? '/' . $datos_tarimas['pro_id_2'] : '';

    $tar_fecha = $datos_tarimas['tar_fecha']; // "2025-03-23 22:03:33"
    $fecha_formateada = (new DateTime($tar_fecha))->format('Y/m/d H:i');

    // Diseño del código ZPL
    $zpl = <<<ZPL
    CT~~CD,~CC^~CT~
    ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
    ^XA
    ^MMT
    ^PW815
    ^LL1622
    ^LS0
    ^FT424,1584^A0B,362,304^FH\^FDRECHAZADO^FS
    ^FT587,1494^A0B,135,134^FH\^FDPROCESO: {$datos_tarimas['pro_id']}{$pro_id_2}^FS
    ^FT762,1494^A0B,135,134^FH\^FDTARIMA: {$datos_tarimas['tar_folio']}^FS
    ^PQ1,0,1,Y^XZ
    ^XZ
    ZPL;


    function sendToPrinter($printer_ip, $printer_port, $zpl)
    {
        $fp = fsockopen($printer_ip, $printer_port, $errno, $errstr, 10);
        if (!$fp) {
            logError("No se pudo conectar con la impresora ($errno): $errstr");
            return false;
        }

        fwrite($fp, $zpl); // Envía el ZPL
        fclose($fp);
        return true;
    }

    function logError($message)
    {
        file_put_contents('printer_error.log', date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
    }

    function updateCounter($cnx, $update_contador)
    {
        if (!mysqli_query($cnx, $update_contador)) {
            logError("Error al actualizar el contador de etiquetas: " . mysqli_error($cnx));
            return false;
        }
        return true;
    }

    $printer_ip = "192.168.1.97";

    $printer_port = 9100;
    if (sendToPrinter($printer_ip, $printer_port, $zpl)) {
        // Actualizar el contador en la base de datos
        if (updateCounter($cnx, $update_contador)) {
            echo json_encode(['success' => "Etiqueta enviada correctamente a la impresora."]);
        } else {
            echo json_encode(['error' => 'Error al actualizar el contador de etiquetas']);
        }
    } else {
        echo json_encode(['error' => "Error al conectar con la impresora."]);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    mysqli_close($cnx);
    restore_error_handler();
    exit();
}
