<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

$parametros_fallidos = [];
$tar_rechazado = '';

try {

    $query = "SELECT * FROM rev_parametros";
    $listado_parametros = mysqli_query($cnx, $query);

    if (!$listado_parametros) {
        throw new Exception("Error en la consulta: " . mysqli_error($cnx));
    }

    $parametros = [];

    while ($fila = mysqli_fetch_assoc($listado_parametros)) {
        $parametros[$fila['rp_parametro']] = $fila;
    }

    // Relación parámetro BD → variable PHP
    $variables = [
        'bloom'        => $tar_bloom ?? '',
        'viscosidad'   => $tar_viscosidad ?? '',
        'ph'           => $tar_ph ?? '',
        'trans'        => $tar_trans ?? '',
        'por_t'        => $tar_porcentaje_t ?? '',
        'ntu'          => $tar_ntu ?? '',
        'humedad'      => $tar_humedad ?? '',
        'cenizas'      => $tar_cenizas ?? '',
        'ce'           => $tar_ce ?? '',
        'redox'        => $tar_redox ?? '',
        'color'        => $tar_color ?? '',
        'olor'         => $tar_olor ?? '',
        'pe_1kg'       => $tar_pe_1kg ?? '',
        'par_extr'     => $tar_par_extr ?? '',
        'par_ind'      => $tar_par_ind ?? '',
        'malla_30'     => $tar_malla_30 ?? '',
        'malla_45'     => $tar_malla_45 ?? '',
        'coliformes'   => $tar_coliformes ?? '',
        'ecoli'        => $tar_ecoli ?? '',
        'salmonella'   => $tar_salmonella ?? '',
        'saereus'      => $tar_saereus ?? '',
        'bma'          => $tar_bma ?? ''
    ];

    $microbiologia = ['coliformes', 'ecoli', 'salmonella', 'saereus', 'bma'];

    foreach ($variables as $parametro => $valor) {

        if (!isset($parametros[$parametro])) {
            continue;
        }

        $inicio = $parametros[$parametro]['rp_inicio'];
        $fin    = $parametros[$parametro]['rp_fin'];

        if ($valor !== '' && ($valor < $inicio || $valor > $fin)) {

            $parametros_fallidos[] = $parametro;

            if (in_array($parametro, $microbiologia)) {
                $tar_rechazado = 'R';
                break;
            }

            if ($tar_rechazado !== 'R') {
                $tar_rechazado = 'C';
            }
        }
    }
} catch (Exception $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
