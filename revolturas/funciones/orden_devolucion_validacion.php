<?php

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$conn =  Conectarse();
try {
    $listado_parametros = mysqli_query(
        $conn,
        "SELECT * FROM rev_parametros"
    );
    if (!$listado_parametros) {
        die("Error en la consulta: " . mysqli_error($conn));
    }

    $parametros = array();

    while ($fila = mysqli_fetch_assoc($listado_parametros)) {
        $parametros[$fila['rp_parametro']] = $fila;
    }

    // Validación de los parámetros 
    $validaciones = [
        'bloom' => !($bloom === '' || ($bloom >= $parametros['bloom']['rp_inicio'] && $bloom <= $parametros['bloom']['rp_fin'])),
        'cenizas' => !($cenizas === '' || ($cenizas >= $parametros['cenizas']['rp_inicio'] && $cenizas <= $parametros['cenizas']['rp_fin'])),
        'viscosidad' => !($viscosidad === '' || ($viscosidad >= $parametros['viscosidad']['rp_inicio'] && $viscosidad <= $parametros['viscosidad']['rp_fin'])),
        'ph' => !($ph === '' || ($ph >= $parametros['ph']['rp_inicio'] && $ph <= $parametros['ph']['rp_fin'])),
        'trans' => !($trans === '' || ($trans >= $parametros['trans']['rp_inicio'] && $trans <= $parametros['trans']['rp_fin'])),
        'porcentaje_t' => !($porcentaje_t === '' || ($porcentaje_t >= $parametros['por_t']['rp_inicio'] && $porcentaje_t <= $parametros['por_t']['rp_fin'])),
        'ntu' => !($ntu === '' || ($ntu >= $parametros['ntu']['rp_inicio'] && $ntu <= $parametros['ntu']['rp_fin'])),
        'humedad' => !($humedad === '' || ($humedad >= $parametros['humedad']['rp_inicio'] && $humedad <= $parametros['humedad']['rp_fin'])),
        'ce' => !($ce === '' || ($ce >= $parametros['ce']['rp_inicio'] && $ce <= $parametros['ce']['rp_fin'])),
        'redox' => !($redox === '' || ($redox >= $parametros['redox']['rp_inicio'] && $redox <= $parametros['redox']['rp_fin'])),
        'color' => !($color === '' || ($color >= $parametros['color']['rp_inicio'] && $color <= $parametros['color']['rp_fin'])),
        'olor' => !($olor === '' || ($olor >= $parametros['olor']['rp_inicio'] && $olor <= $parametros['olor']['rp_fin'])),
        'pe_1kg' => !($pe_1kg === '' || ($pe_1kg >= $parametros['pe_1kg']['rp_inicio'] && $pe_1kg <= $parametros['pe_1kg']['rp_fin'])),
        'par_extr' => !($par_extr === '' || ($par_extr >= $parametros['par_extr']['rp_inicio'] && $par_extr <= $parametros['par_extr']['rp_fin'])),
        'par_ind' => !($par_ind === '' || ($par_ind >= $parametros['par_ind']['rp_inicio'] && $par_ind <= $parametros['par_ind']['rp_fin'])),
        'malla_30' => !($malla_30 === '' || ($malla_30 >= $parametros['malla_30']['rp_inicio'] && $malla_30 <= $parametros['malla_30']['rp_fin'])),
        'malla_45' => !($malla_45 === '' || ($malla_45 >= $parametros['malla_45']['rp_inicio'] && $malla_45 <= $parametros['malla_45']['rp_fin'])),
        'coliformes' => !($coliformes === '' || ($coliformes >= $parametros['coliformes']['rp_inicio'] && $coliformes <= $parametros['coliformes']['rp_fin'])),
        'ecoli' => !($ecoli === '' || ($ecoli >= $parametros['ecoli']['rp_inicio'] && $ecoli <= $parametros['ecoli']['rp_fin'])),
        'salmonella' => !($salmonella === '' || ($salmonella >= $parametros['salmonella']['rp_inicio'] && $salmonella <= $parametros['salmonella']['rp_fin'])),
        'saereus' => !($saereus === '' || ($saereus >= $parametros['saereus']['rp_inicio'] && $saereus <= $parametros['saereus']['rp_fin'])),
    ];


    $parametros_fallidos = [];

    foreach ($validaciones as $key => $validacion) {
        if ($validacion) {
            $parametros_fallidos[] = $key;

            if (in_array($key, ['coliformes', 'ecoli', 'salmonella', 'saereus'])) {
                $rechazado = 'R';
                break;
            } else {
                $rechazado = 'C';
            }
        }
    }
} catch (Exception $e) {
    echo json_encode($e->getMessage());
}
