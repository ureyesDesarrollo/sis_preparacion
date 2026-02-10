<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_parametros = mysqli_query(
        $cnx,
        "SELECT * FROM rev_parametros"
    );
    if (!$listado_parametros) {
        die("Error en la consulta: " . mysqli_error($cnx));
    }

    $parametros = array();

    while ($fila = mysqli_fetch_assoc($listado_parametros)) {
        $parametros[$fila['rp_parametro']] = $fila;
    }


    // Validación de los parámetros 
    $validaciones = [
        'bloom' => !($rev_bloom === '' || ($rev_bloom >= $parametros['bloom']['rp_inicio'] && $rev_bloom <= $parametros['bloom']['rp_fin'])),
        'viscosidad' => !($rev_viscosidad === '' || ($rev_viscosidad >= $parametros['viscosidad']['rp_inicio'] && $rev_viscosidad <= $parametros['viscosidad']['rp_fin'])),
        'ph' => !($rev_ph === '' || ($rev_ph >= $parametros['ph']['rp_inicio'] && $rev_ph <= $parametros['ph']['rp_fin'])),
        'trans' => !($rev_trans === '' || ($rev_trans >= $parametros['trans']['rp_inicio'] && $rev_trans <= $parametros['trans']['rp_fin'])),
        'porcentaje_t' => !($rev_porcentaje_t === '' || ($rev_porcentaje_t >= $parametros['por_t']['rp_inicio'] && $rev_porcentaje_t <= $parametros['por_t']['rp_fin'])),
        'ntu' => !($rev_ntu === '' || ($rev_ntu >= $parametros['ntu']['rp_inicio'] && $rev_ntu <= $parametros['ntu']['rp_fin'])),
        'humedad' => !($rev_humedad === '' || ($rev_humedad >= $parametros['humedad']['rp_inicio'] && $rev_humedad <= $parametros['humedad']['rp_fin'])),
        'cenizas' => !($rev_cenizas === '' || ($rev_cenizas >= $parametros['cenizas']['rp_inicio'] && $rev_cenizas <= $parametros['cenizas']['rp_fin'])),
        'ce' => !($rev_ce === '' || ($rev_ce >= $parametros['ce']['rp_inicio'] && $rev_ce <= $parametros['ce']['rp_fin'])),
        'redox' => !($rev_redox === '' || ($rev_redox >= $parametros['redox']['rp_inicio'] && $rev_redox <= $parametros['redox']['rp_fin'])),
        'color' => !($rev_color === '' || ($rev_color >= $parametros['color']['rp_inicio'] && $rev_color <= $parametros['color']['rp_fin'])),
        'olor' => !($rev_olor === '' || ($rev_olor >= $parametros['olor']['rp_inicio'] && $rev_olor <= $parametros['olor']['rp_fin'])),
        'pe_1kg' => !($rev_pe_1kg === '' || ($rev_pe_1kg >= $parametros['pe_1kg']['rp_inicio'] && $rev_pe_1kg <= $parametros['pe_1kg']['rp_fin'])),
        'par_extr' => !($rev_par_extr === '' || ($rev_par_extr >= $parametros['par_extr']['rp_inicio'] && $rev_par_extr <= $parametros['par_extr']['rp_fin'])),
        'par_ind' => !($rev_par_ind === '' || ($rev_par_ind >= $parametros['par_ind']['rp_inicio'] && $rev_par_ind <= $parametros['par_ind']['rp_fin'])),
        'malla_30' => !($rev_malla_30 === '' || ($rev_malla_30 >= $parametros['malla_30']['rp_inicio'] && $rev_malla_30 <= $parametros['malla_30']['rp_fin'])),
        'malla_45' => !($rev_malla_45 === '' || ($rev_malla_45 >= $parametros['malla_45']['rp_inicio'] && $rev_malla_45 <= $parametros['malla_45']['rp_fin'])),
    ];


    $parametros_fallidos = [];

    foreach ($validaciones as $key => $validacion) {
        if ($validacion) {
            isset($rev_rechazado) ? $rev_rechazado = 'R' : '';
            $parametros_fallidos[] = $key;
        }
    }
} catch (Exception $e) {
    echo json_encode($e->getMessage());
}
