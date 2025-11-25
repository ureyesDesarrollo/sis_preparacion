<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Enero-2025 */
session_start();
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
include "../../conexion/conexion.php";
include "../utils/funciones.php";
//ini_set('display_errors', 0);
$oe_id = intval($_POST['oe_id']);
$empaque_id = intval($_POST['empaque_id']);
$cantidad_etiquetas = intval($_POST['cantidad_etiquetas']);

$cnx = Conectarse();


function actualizar_estado_embarque($cnx, $oe_id)
{
    $sql = "UPDATE rev_orden_embarque SET oe_estado = 'ETIQUETA LIBERADA' WHERE oe_id = $oe_id";
    $query = mysqli_query($cnx, $sql);
    if ($query) {
        return true;
    } else {
        return false;
    }
}
try {
    $listado_orden = "SELECT 
    CASE 
        WHEN rr.rev_id IS NOT NULL THEN rev.rev_folio
        WHEN rrc.rev_id IS NOT NULL THEN rrc_rev.rev_folio
        ELSE 'Producto General'
    END AS rev_folio,
            COALESCE(rev.cal_id, rrc_rev.cal_id) AS rev_calidad,

            CASE 
                WHEN rr.rr_id IS NOT NULL THEN 'GENERAL'
                WHEN rrc.rrc_id IS NOT NULL THEN 'CLIENTE'
                ELSE 'GENERAL'
            END AS tipo_revoltura
            FROM 
                rev_orden_embarque oe
            INNER JOIN 
                rev_orden_embarque_detalle oed ON oe.oe_id = oed.oe_id
            LEFT JOIN 
                rev_revolturas_pt rr ON rr.rr_id = oed.rr_id
            LEFT JOIN 
                rev_revolturas rev ON rev.rev_id = rr.rev_id
            LEFT JOIN 
                rev_presentacion rr_pres ON rr_pres.pres_id = rr.pres_id
            LEFT JOIN 
                rev_revolturas_pt_cliente rrc ON rrc.rrc_id = oed.rrc_id
            LEFT JOIN 
                rev_revolturas rrc_rev ON rrc_rev.rev_id = rrc.rev_id
            LEFT JOIN 
                rev_presentacion rrc_pres ON rrc_pres.pres_id = rrc.pres_id
            LEFT JOIN 
                rev_clientes cte ON oe.cte_id = cte.cte_id
            LEFT JOIN
                rev_calidad cal ON cal.cal_id = rev.cal_id 
            WHERE 
            oe.oe_id = '$oe_id' AND COALESCE(rr.rr_id, rrc.rrc_id) = '$empaque_id'";

    $listado_detalle_embarque = mysqli_query($cnx, $listado_orden);

    $datos_detalle_embarque = array();

    while ($fila = mysqli_fetch_assoc($listado_detalle_embarque)) {
        $datos_detalle_embarque[] = $fila;
    }

    $rev_folio = $datos_detalle_embarque[0]['rev_folio'];
    $libero_calidad = strtoupper($_SESSION['nombre']);
    $calidad = obtenerBloomPorCalidad($datos_detalle_embarque[0]['rev_calidad']);
    // Diseño del código ZPL
    $zpl = <<<ZPL

    CT~~CD,~CC^~CT~
    ^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ
    ^XA
    ^MMT
    ^PW815
    ^LL1622
    ^LS0
    ^FO32,608^GFA,09984,09984,00024,:Z64:
    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:9C69
    ^FT306,1421^A0B,79,108^FH\^FDLIBERACION DE PRODUCTO^FS
    ^FT388,1407^A0B,62,62^FH\^FDPRODUCTO: $calidad^FS
    ^FT464,1411^A0B,62,62^FH\^FDLOTE: $rev_folio^FS
    ^FT530,1407^A0B,62,62^FH\^FDINTEGRIDAD EMPLAYADO: CUMPLE^FS
    ^FT606,1407^A0B,62,62^FH\^FDINTEGRIDAD TARIMA: CUMPLE^FS
    ^FT677,1407^A0B,62,62^FH\^FDLIBERO CALIDAD: $libero_calidad^FS
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

    $printer_ip = "192.168.1.98";
    $printer_port = 9100;
    $impresiones_ok = 0;

    for ($i = 0; $i < intval($cantidad_etiquetas); $i++) {
        if (sendToPrinter($printer_ip, $printer_port, $zpl)) {
            $impresiones_ok++;
        } else {
            break;
        }
    }

    if ($impresiones_ok == intval($cantidad_etiquetas)) {
        if (actualizar_estado_embarque($cnx, $oe_id)) {
            echo json_encode(['success' => "Se imprimieron $impresiones_ok de $cantidad_etiquetas etiquetas."]);
        } else {
            echo json_encode(['error' => "Error al actualizar el estado del embarque."]);
        }
    } else {
        echo json_encode(['error' => "Se imprimieron $impresiones_ok de $cantidad_etiquetas etiquetas."]);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    mysqli_close($cnx);
    restore_error_handler();
    exit();
}
