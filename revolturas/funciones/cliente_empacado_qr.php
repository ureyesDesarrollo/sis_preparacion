<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Enero-2025 */
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
include "../../conexion/conexion.php";
ini_set('display_errors', 0);
$rrc_id = $_GET['rrc_id']; // ID de la tarima
$cnx = Conectarse();

try {
    // Consulta de datos de la tarima
    $listado_empaque_cliente = mysqli_query(
        $cnx,
        "SELECT rrc.rrc_ext_real, c.cte_nombre, c.cte_id,p.pres_id, p.pres_descrip
         FROM rev_revolturas_pt_cliente rrc
         INNER JOIN rev_clientes c ON rrc.cte_id = c.cte_id
         INNER JOIN rev_presentacion p ON rrc.pres_id = p.pres_id
         WHERE rrc.rrc_id = '$rrc_id'"
    );

    $datos_empaque_cliente = mysqli_fetch_assoc($listado_empaque_cliente);

    // URL para el código QR
    $currentDir = dirname($_SERVER['REQUEST_URI']);
    //$url = 'http://' . $_SERVER['HTTP_HOST'] . $currentDir . '/tarimas_detalle.php?tar_id=' . $tar_id;
    //$url = 'http://172.20.10.2/sis_preparacion/revolturas/funciones/tarimas_detalle.php?tar_id=' . $tar_id;
    $url = $rrc_id;
    // Diseño del código ZPL
    $zpl = <<<ZPL
    ^XA
    ^CI28
    ^PW815  // Ancho: 102 mm (815 puntos)
    ^LL1000  // Alto reducido para un solo bloque de información

    /* Bloque de información */
    ^FO100,90^A0N,70,70^FDCLIENTE: {$datos_empaque_cliente['cte_nombre']}^FS
    ^FO100,170^A0N,70,70^FDPRESENTACIÓN: {$datos_empaque_cliente['pres_descrip']}^FS

    /* Único QR en el centro */
    ^FO250,300^BQN,2,10^FDQA,{$url}^FS  

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



    $printer_ip = "192.168.1.9";
    $printer_port = 9100;
    if (sendToPrinter($printer_ip, $printer_port, $zpl)) {
        echo json_encode(['success' => "Etiqueta enviada correctamente a la impresora."]);
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
