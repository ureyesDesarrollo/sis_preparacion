<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Enero-2025 */
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
include "../../conexion/conexion.php";
ini_set('display_errors', 0);
$tar_id = $_GET['tar_id']; // ID de la tarima
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


    // Diseño del código ZPL
    $zpl = <<<ZPL
    ^XA
    ^CI28
    ^PW2101  // Ancho: 2101 puntos (10.40 cm)
    ^LL1544  // Alto: 1544 puntos (7.62 cm)

    // Primer bloque de elementos
    ^FO100,76^A0N,50,50^FDProceso: {$datos_tarimas['pro_id']}{$pro_id_2}^FS
    ^FO100,126^A0N,50,50^FDTarima: {$datos_tarimas['tar_folio']}^FS
    ^FO100,176^A0N,50,50^FDFecha: {$datos_tarimas['tar_fecha']}^FS
    ^FO100,224^A0N,50,50^FD{$fino}^FS

    ^FO298,300^BQN,2,5^FDQA,{$url}^FS  // Primer QR más grande

    // Recuadro izquierdo para "Aceptado" (QR superior)
    ^FO40,300^GB190,150,5^FS  // Recuadro izquierdo
    ^FO60,330^A0N,40,40^FDAceptado^FS

    // Recuadro derecho para "Rechazado" (QR superior)
    ^FO550,300^GB210,150,5^FS  // Recuadro derecho
    ^FO570,330^A0N,40,40^FDRechazado^FS

    // Línea horizontal de separación
    ^FO50,650^GB1951,4,4^FS  // Línea de separación

    // Segundo bloque de elementos
    ^FO100,700^A0N,50,50^FDProceso: {$datos_tarimas['pro_id']}{$pro_id_2}^FS
    ^FO100,760^A0N,50,50^FDTarima: {$datos_tarimas['tar_folio']}^FS
    ^FO100,820^A0N,50,50^FDFecha: {$datos_tarimas['tar_fecha']}^FS
    ^FO100,880^A0N,50,50^FD{$fino}^FS

    ^FO298,950^BQN,2,5^FDQA,{$url}^FS  // Segundo QR más grande

    // Recuadro izquierdo para "Aceptado" (QR inferior)
    ^FO40,950^GB190,150,5^FS  // Recuadro izquierdo
    ^FO60,980^A0N,40,40^FDAceptado^FS

    // Recuadro derecho para "Rechazado" (QR inferior)
    ^FO550,950^GB210,150,5^FS  // Recuadro derecho
    ^FO570,980^A0N,40,40^FDRechazado^FS

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
    echo "Error: " . $e->getMessage();
} finally {
    mysqli_close($cnx);
    restore_error_handler();
    exit();
}
