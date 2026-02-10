<?php
/**
 * Devuelve la descripción de calidad (bloom) a partir del cal_id.
 *
 * @param int $cal_id
 * @return string
 */
function obtenerBloomPorCalidad($cal_id)
{
    $map = [
        1 => '250 BLOOM',
        2 => '280 BLOOM',
        3 => '315 BLOOM',
        4 => '300 BLOOM',
        6 => '265 BLOOM',
        7 => '230 BLOOM'
    ];

    return $map[(int)$cal_id] ?? 'SIN CLASIFICAR';
}

function numeroALetras($numero)
{
    // Definición de palabras
    $UNIDADES = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE', 'DIEZ',
        'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE', 'VEINTE'];
    $DECENAS = ['', '', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
    $CENTENAS = [
        '', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS',
        'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'
    ];

    // Aseguramos el formato y separación de decimales
    $numero = number_format($numero, 2, '.', '');
    list($entero, $decimales) = explode('.', $numero);
    $entero = intval($entero);
    $texto = '';

    if ($entero == 0) {
        $texto = 'CERO';
    } else {
        $texto = convertirNumero($entero, $UNIDADES, $DECENAS, $CENTENAS);
    }

    // Formato de decimales
    $texto = trim($texto);
    $texto .= ' PESOS ' . str_pad($decimales, 2, '0', STR_PAD_RIGHT) . '/100 M.N.';
    return mb_strtoupper($texto, 'UTF-8');
}

// --- Función auxiliar para manejar los grupos de números ---
function convertirNumero($numero, $UNIDADES, $DECENAS, $CENTENAS)
{
    $resultado = '';

    if ($numero >= 1000000) {
        $millones = floor($numero / 1000000);
        $resultado .= convertirNumero($millones, $UNIDADES, $DECENAS, $CENTENAS);
        $resultado .= $millones == 1 ? 'MILLÓN ' : 'MILLONES ';
        $numero %= 1000000;
    }
    if ($numero >= 1000) {
        $miles = floor($numero / 1000);
        if ($miles == 1) {
            $resultado .= 'MIL ';
        } else {
            $resultado .= convertirNumero($miles, $UNIDADES, $DECENAS, $CENTENAS) . 'MIL ';
        }
        $numero %= 1000;
    }
    if ($numero > 0) {
        if ($numero == 100) {
            $resultado .= 'CIEN ';
        } else {
            $centenas = floor($numero / 100);
            $decenas = $numero % 100;
            if ($centenas > 0) {
                $resultado .= $CENTENAS[$centenas] . ' ';
            }
            if ($decenas <= 20) {
                $resultado .= $UNIDADES[$decenas] . ' ';
            } else {
                $de = floor($decenas / 10);
                $un = $decenas % 10;
                $resultado .= $DECENAS[$de];
                if ($un > 0) {
                    $resultado .= ' Y ' . $UNIDADES[$un];
                }
                $resultado .= ' ';
            }
        }
    }
    return $resultado;
}



function descripcionCajas($kilos, $empaque, $presentacion) {
    $cajas = $kilos / 12;
    if (fmod($cajas, 1) == 0) {
        $cajas = intval($cajas);
    } else {
        $cajas = number_format($cajas, 2, '.', '');
    }
    if($empaque == '25 KG'){
        $sacos = $kilos / 25;
        return "{$sacos} {$presentacion} DE {$empaque} C/U";
    }
    return "{$cajas} {$presentacion} DE 12 KG C/U PRESENTACIÓN: {$empaque}";
}
