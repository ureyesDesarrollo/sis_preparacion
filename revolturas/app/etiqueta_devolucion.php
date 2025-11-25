<?php
try {
    header('Content-Type: application/json');
    include "../../conexion/conexion.php";

    $cnx = Conectarse();
    $rev_folio = isset($_GET['lote']) ? $_GET['lote'] : null;

    $rev_id = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT rev_id FROM rev_revolturas WHERE rev_folio = '$rev_folio'"))['rev_id'];
    if (!$rev_id) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Revoltura no encontrada']);
        exit;
    }
    $currentDir = dirname($_SERVER['REQUEST_URI']);
    $currentDir = str_replace('/app', '/funciones', $currentDir);
    $url = 'http://' . $_SERVER['HTTP_HOST'] . $currentDir . '/revolturas_detalle.php?rev_id=' . $rev_id;

    $zpl = <<<ZPL
    CT~~CD,~CC^~CT~
    ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
    ^XA
    ^MMT
    ^PW815
    ^LL1622
    ^LS0
    ^FT319,1584^A0B,259,290^FH\^FDDEVOLUCION^FS
    ^FT475,1577^A0B,135,134^FH\^FDLOTE:{$rev_folio}^FS
    ^FO544,667^BQN,2,5^FDQA,{$url}^FS
    ^PQ1,0,1,Y^XZ
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


    $printer_ip = "192.168.1.97";

    $printer_port = 9100;
    if (sendToPrinter($printer_ip, $printer_port, $zpl)) {
        // Actualizar el contador en la base de datos
        echo json_encode(['success' => "Etiqueta de devolucion enviada correctamente a la impresora."]);
    } else {
        echo json_encode(['error' => "Error al conectar con la impresora."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al generar etiqueta: ' . $e->getMessage()]);
    exit;
}
