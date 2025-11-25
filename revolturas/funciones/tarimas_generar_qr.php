<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Enero-2025 */
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
date_default_timezone_set("America/Mazatlan");
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
    ^XA
    ^CI28
    ^PW815  // Ancho: 102 mm (815 puntos)
    ^LL1622  // Alto: 203 mm (1622 puntos)

    /* Primer QR (ahora en la parte superior) */
    ^FO250,60^BQN,2,7^FDQA,{$url}^FS  

    /* Primer bloque de información (ahora debajo del QR) */
    ^FO100,405^A0N,70,70^FDProceso: {$datos_tarimas['pro_id']}{$pro_id_2}^FS
    ^FO100,475^A0N,70,70^FDTarima: {$datos_tarimas['tar_folio']}^FS
    ^FO100,545^A0N,70,70^FD{$fecha_formateada}^FS

    /* Solo imprime si $fino tiene un valor */
    ^IF{$fino}^
        ^FO100,615^A0N,70,70^FD{$fino}^FS
    ^ENDIF

    /* Línea horizontal de separación */
    ^FO50,811^GB700,3,3^FS  

    /* Segundo bloque de información */
    ^FO100,881^A0N,70,70^FDProceso: {$datos_tarimas['pro_id']}{$pro_id_2}^FS
    ^FO100,951^A0N,70,70^FDTarima: {$datos_tarimas['tar_folio']}^FS
    ^FO100,1021^A0N,70,70^FD{$fecha_formateada}^FS

    /* Solo imprime si $fino tiene un valor */
    ^IF{$fino}^
        ^FO100,1091^A0N,70,70^FD{$fino}^FS
    ^ENDIF

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

    $printer_ip = "";
    switch($opcion){
        case 1: //Impresion proceso
            $printer_ip = "192.168.1.99";
            break;
        case 2: //Impresion cuarentena
            $printer_ip = "192.168.1.97";
            break;
        case 3: //Impresion Aceptada
            $printer_ip = "192.168.1.98";
            break;
    }

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
