<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
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
        'bloom' => !($tar_bloom === '' || ($tar_bloom >= $parametros['bloom']['rp_inicio'] && $tar_bloom <= $parametros['bloom']['rp_fin'])),
        'viscosidad' => !($tar_viscosidad === '' || ($tar_viscosidad >= $parametros['viscosidad']['rp_inicio'] && $tar_viscosidad <= $parametros['viscosidad']['rp_fin'])),
        'ph' => !($tar_ph === '' || ($tar_ph >= $parametros['ph']['rp_inicio'] && $tar_ph <= $parametros['ph']['rp_fin'])),
        'trans' => !($tar_trans === '' || ($tar_trans >= $parametros['trans']['rp_inicio'] && $tar_trans <= $parametros['trans']['rp_fin'])),
        'porcentaje_t' => !($tar_porcentaje_t === '' || ($tar_porcentaje_t >= $parametros['por_t']['rp_inicio'] && $tar_porcentaje_t <= $parametros['por_t']['rp_fin'])),
        'ntu' => !($tar_ntu === '' || ($tar_ntu >= $parametros['ntu']['rp_inicio'] && $tar_ntu <= $parametros['ntu']['rp_fin'])),
        'humedad' => !($tar_humedad === '' || ($tar_humedad >= $parametros['humedad']['rp_inicio'] && $tar_humedad <= $parametros['humedad']['rp_fin'])),
        'cenizas' => !($tar_cenizas === '' || ($tar_cenizas >= $parametros['cenizas']['rp_inicio'] && $tar_cenizas <= $parametros['cenizas']['rp_fin'])),
        'ce' => !($tar_ce === '' || ($tar_ce >= $parametros['ce']['rp_inicio'] && $tar_ce <= $parametros['ce']['rp_fin'])),
        'redox' => !($tar_redox === '' || ($tar_redox >= $parametros['redox']['rp_inicio'] && $tar_redox <= $parametros['redox']['rp_fin'])),
        'color' => !($tar_color === '' || ($tar_color >= $parametros['color']['rp_inicio'] && $tar_color <= $parametros['color']['rp_fin'])),
        'olor' => !($tar_olor === '' || ($tar_olor >= $parametros['olor']['rp_inicio'] && $tar_olor <= $parametros['olor']['rp_fin'])),
        'pe_1kg' => !($tar_pe_1kg === '' || ($tar_pe_1kg >= $parametros['pe_1kg']['rp_inicio'] && $tar_pe_1kg <= $parametros['pe_1kg']['rp_fin'])),
        'par_extr' => !($tar_par_extr === '' || ($tar_par_extr >= $parametros['par_extr']['rp_inicio'] && $tar_par_extr <= $parametros['par_extr']['rp_fin'])),
        'par_ind' => !($tar_par_ind === '' || ($tar_par_ind >= $parametros['par_ind']['rp_inicio'] && $tar_par_ind <= $parametros['par_ind']['rp_fin'])),
        'malla_30' => !($tar_malla_30 === '' || ($tar_malla_30 >= $parametros['malla_30']['rp_inicio'] && $tar_malla_30 <= $parametros['malla_30']['rp_fin'])),
        'malla_45' => !($tar_malla_45 === '' || ($tar_malla_45 >= $parametros['malla_45']['rp_inicio'] && $tar_malla_45 <= $parametros['malla_45']['rp_fin'])),
        'coliformes' => !($tar_coliformes === '' || ($tar_coliformes >= $parametros['coliformes']['rp_inicio'] && $tar_coliformes <= $parametros['coliformes']['rp_fin'])),
        'ecoli' => !($tar_ecoli === '' || ($tar_ecoli >= $parametros['ecoli']['rp_inicio'] && $tar_ecoli <= $parametros['ecoli']['rp_fin'])),
        'salmonella' => !($tar_salmonella === '' || ($tar_salmonella >= $parametros['salmonella']['rp_inicio'] && $tar_salmonella <= $parametros['salmonella']['rp_fin'])),
        'saereus' => !($tar_saereus === '' || ($tar_saereus >= $parametros['saereus']['rp_inicio'] && $tar_saereus <= $parametros['saereus']['rp_fin'])),
    ];

    $parametros_fallidos = [];

    foreach ($validaciones as $key => $validacion) {
        if ($validacion) {
            $parametros_fallidos[] = $key;

            if (in_array($key, ['coliformes', 'ecoli', 'salmonella', 'saereus'])) {
                $tar_rechazado = 'R';
                break;
            }else{
                $tar_rechazado = 'C';
            }
        }
    }

} catch (Exception $e) {
    echo json_encode($e->getMessage());
}