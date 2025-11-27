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
            END AS tipo_revoltura,
            cte.cte_tipo_bloom
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
    //$calidad1 = obtenerBloomPorCalidad($datos_detalle_embarque[0]['rev_calidad']);
    $calidad = $datos_detalle_embarque[0]['cte_tipo_bloom'] . ' BLOOM';
    // Diseño del código ZPL
    /* $zpl = <<<ZPL

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
    ZPL; */

    $zpl = <<<ZPL
    CT~~CD,~CC^~CT~
    ^XA
    ~TA000
    ~JSN
    ^LT0
    ^MNW
    ^MTT
    ^PON
    ^PMN
    ^LH0,0
    ^JMA
    ^PR4,4
    ~SD15
    ^JUS
    ^LRN
    ^CI27
    ^PA0,1,1,0
    ^XZ
    ^XA
    ^MMT
    ^PW799
    ^LL1319
    ^LS0
    ^FO8,528^GFA,2833,5580,20,:Z64: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:5EF7
    ^FT225,1191^A0B,57,94^FH\^CI28^FDLIBERACION DE PRODUCTO - P^FS^CI27
    ^FT310,1191^A0B,54,53^FH\^CI28^FDPRODUCTO: $calidad^FS^CI27
    ^FT390,1191^A0B,54,53^FH\^CI28^FDLOTE: $rev_folio^FS^CI27
    ^FT478,1191^A0B,54,53^FH\^CI28^FDINTEGRIDAD EMPLAYADO: CUMPLE^FS^CI27
    ^FT573,1191^A0B,54,53^FH\^CI28^FDINTEGRIDAD TARIMA: CUMPLE^FS^CI27
    ^FT661,1191^A0B,54,53^FH\^CI28^FDLIBERO CALIDAD: $libero_calidad^FS^CI27
    ^PQ1,0,1,Y
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
