<?php
/*Desarrollado por: Ulises Reys */
/*Actualizado: Enero-2025*/
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
ini_set('display_errors', 0);
include "../../conexion/conexion.php";
$rev_id = $_GET['rev_id'];

$cnx =  Conectarse();
try {
    $listado_revolturas = mysqli_query(
        $cnx,
        "SELECT rev_folio, rev_fecha,rev_count_etiquetado AS contador FROM rev_revolturas WHERE rev_id = '$rev_id'"
    );

    $datos_revolturas = mysqli_fetch_assoc($listado_revolturas);
    // Actualización del contador de etiquetas
    $update_contador = "UPDATE rev_revolturas SET rev_count_etiquetado = {$datos_revolturas['contador']} + 1 WHERE rev_id = '$rev_id'";

    $currentDir = dirname($_SERVER['REQUEST_URI']);

    $url = 'http://' . $_SERVER['HTTP_HOST'] . $currentDir . '/revolturas_detalle.php?rev_id=' . $rev_id;

    //$url = 'http://192.168.100.8:80' . $currentDir . '/revolturas_detalle.php?rev_id=' . $rev_id;

    $tar_fecha = $datos_revolturas['rev_fecha']; // "2025-03-23 22:03:33"
    $fecha_formateada = (new DateTime($tar_fecha))->format('Y/m/d H:i');


    $zpl = <<<ZPL
    ^XA
    ^CI28
    ^PW815  // Ancho: 102 mm (815 puntos)
    ^LL1622  // Alto: 203 mm (1622 puntos)

    /* Primer QR (ahora en la parte superior) */
    ^FO250,60^BQN,2,7^FDQA,{$url}^FS  

    /* Primer bloque de información (ahora debajo del QR) */
    ^FO100,405^A0N,70,70^FDRevoltura: {$datos_revolturas['rev_folio']}^FS
    ^FO100,475^A0N,70,70^FD{$fecha_formateada}^FS

    /* Línea horizontal de separación */
    ^FO50,811^GB700,3,3^FS  

    /* Segundo bloque de información */
    ^FO100,881^A0N,70,70^FDRevoltura: {$datos_revolturas['rev_folio']}^FS
    ^FO100,951^A0N,70,70^FD{$fecha_formateada}^FS

    /* Segundo QR (tamaño fijo y con espacio definido) */
    ^FO250,1161^BQN,2,7^FDQA,{$url}^FS  

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

    $printer_ip = "192.168.1.9";
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
    echo $e->getMessage();
} finally {
    mysqli_close($cnx);
    restore_error_handler();
    exit();
}
