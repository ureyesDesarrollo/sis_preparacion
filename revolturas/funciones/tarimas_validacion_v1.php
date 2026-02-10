<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

// Parámetros mínimos y máximos
$min_bloom = 200;
$min_vis = 25;
$max_vis = 60;
$min_ph = 5.5;
$max_ph = 6.0;
$min_trans = 18;
$max_ntu = 80;
$min_humedad = 9;
$max_humedad = 12;
$max_cenizas = 1.6;
$min_ce = 4;
$max_redox = 30;
$max_color = 3;
$fino = 5;
$max_olor = 3;
$max_pe_1kg = 10;
$max_par_extr = 25;
$max_par_ind = 10;
$min_por_t = 70;

// Validación de los parámetros 
$validaciones = [
    'bloom' => (($tar_bloom !== '' && $tar_bloom !== '0.00') && $tar_bloom < $min_bloom),
    'viscosidad' => (($tar_viscosidad !== '' && $tar_viscosidad !== '0.00') && ($tar_viscosidad < $min_vis || $tar_viscosidad > $max_vis)),
    'ph' => ($tar_ph !== '' && ($tar_ph < $min_ph || $tar_ph > $max_ph)),
    'transparencia' => ($tar_transparencia !== '' && $tar_transparencia < $min_trans),
    'porcentaje_t' => ($tar_porcentaje_t !== '' && $tar_porcentaje_t < $min_por_t),
    'ntu' => ($tar_ntu !== '' && $tar_ntu > $max_ntu),
    'humedad' => ($tar_humedad !== '' && ($tar_humedad < $min_humedad || $tar_humedad > $max_humedad)),
    'cenizas' => ($tar_cenizas !== '' && $tar_cenizas > $max_cenizas),
    'ce' => ($tar_ce !== '' && $tar_ce < $min_ce),
    'redox' => ($tar_redox !== '' && $tar_redox > $max_redox),
    'color' => ($tar_color !== '' && $tar_color > $max_color),
    'fino' => ($tar_fino !== '' && $tar_fino > $fino),
    'olor' => ($tar_olor !== '' && $tar_olor >= $max_olor),
    'pe_1kg' => ($tar_pe_1kg !== '' && $tar_pe_1kg > $max_pe_1kg),
    'par_extr' => ($tar_par_extr !== '' && $tar_par_extr > $max_par_extr),
    'par_ind' => ($tar_par_ind !== '' && $tar_par_ind > $max_par_ind),
    'hidratacion' => ($tar_hidratacion !== '' && $tar_hidratacion === 'MAL')
];


$parametros_fallidos = [];

foreach ($validaciones as $key => $validacion) {
    if ($validacion) {
        isset($tar_rechazado) ? $tar_rechazado = 'R' : '';
        $parametros_fallidos[] = $key;
    }
}
