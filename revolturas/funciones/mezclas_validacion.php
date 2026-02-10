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
        'bloom' => !($mez_bloom === '' || ($mez_bloom >= $parametros['bloom']['rp_inicio'] && $mez_bloom <= $parametros['bloom']['rp_fin'])),
        'viscosidad' => !($mez_viscosidad === '' || ($mez_viscosidad >= $parametros['viscosidad']['rp_inicio'] && $mez_viscosidad <= $parametros['viscosidad']['rp_fin'])),
        'ph' => !($mez_ph === '' || ($mez_ph >= $parametros['ph']['rp_inicio'] && $mez_ph <= $parametros['ph']['rp_fin'])),
        'trans' => !($mez_trans === '' || ($mez_trans >= $parametros['trans']['rp_inicio'] && $mez_trans <= $parametros['trans']['rp_fin'])),
        'porcentaje_t' => !($mez_porcentaje_t === '' || ($mez_porcentaje_t >= $parametros['por_t']['rp_inicio'] && $mez_porcentaje_t <= $parametros['por_t']['rp_fin'])),
        'ntu' => !($mez_ntu === '' || ($mez_ntu >= $parametros['ntu']['rp_inicio'] && $mez_ntu <= $parametros['ntu']['rp_fin'])),
        'humedad' => !($mez_humedad === '' || ($mez_humedad >= $parametros['humedad']['rp_inicio'] && $mez_humedad <= $parametros['humedad']['rp_fin'])),
        'cenizas' => !($mez_cenizas === '' || ($mez_cenizas >= $parametros['cenizas']['rp_inicio'] && $mez_cenizas <= $parametros['cenizas']['rp_fin'])),
        'ce' => !($mez_ce === '' || ($mez_ce >= $parametros['ce']['rp_inicio'] && $mez_ce <= $parametros['ce']['rp_fin'])),
        'redox' => !($mez_redox === '' || ($mez_redox >= $parametros['redox']['rp_inicio'] && $mez_redox <= $parametros['redox']['rp_fin'])),
        'color' => !($mez_color === '' || ($mez_color >= $parametros['color']['rp_inicio'] && $mez_color <= $parametros['color']['rp_fin'])),
        'olor' => !($mez_olor === '' || ($mez_olor >= $parametros['olor']['rp_inicio'] && $mez_olor <= $parametros['olor']['rp_fin'])),
        'pe_1kg' => !($mez_pe_1kg === '' || ($mez_pe_1kg >= $parametros['pe_1kg']['rp_inicio'] && $mez_pe_1kg <= $parametros['pe_1kg']['rp_fin'])),
        'par_extr' => !($mez_par_extr === '' || ($mez_par_extr >= $parametros['par_extr']['rp_inicio'] && $mez_par_extr <= $parametros['par_extr']['rp_fin'])),
        'par_ind' => !($mez_par_ind === '' || ($mez_par_ind >= $parametros['par_ind']['rp_inicio'] && $mez_par_ind <= $parametros['par_ind']['rp_fin'])),
        'malla_30' => !($mez_malla_30 === '' || ($mez_malla_30 >= $parametros['malla_30']['rp_inicio'] && $mez_malla_30 <= $parametros['malla_30']['rp_fin'])),
        'malla_45' => !($mez_malla_45 === '' || ($mez_malla_45 >= $parametros['malla_45']['rp_inicio'] && $mez_malla_45 <= $parametros['malla_45']['rp_fin'])),
    ];


    $parametros_fallidos = [];

    foreach ($validaciones as $key => $validacion) {
        if ($validacion) {
            isset($mez_rechazado) ? $mez_rechazado = 'R' : '';
            $parametros_fallidos[] = $key;
        }
    }
} catch (Exception $e) {
    echo json_encode($e->getMessage());
}
