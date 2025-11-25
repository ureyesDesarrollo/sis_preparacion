<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx = Conectarse();

date_default_timezone_set('America/Mexico_City');


$listado_tarimas = mysqli_query($cnx, "SELECT t.*,c.cal_descripcion
    FROM rev_tarimas t
    JOIN rev_mezclas_tarimas rt ON t.tar_id = rt.tar_id
    JOIN rev_mezcla m ON rt.mez_id = m.mez_id
    LEFT JOIN rev_calidad c ON c.cal_id = m.cal_id 
    WHERE t.tar_estatus = 1 AND m.mez_id = '" . $_GET['mez_id'] . "' LIMIT 1");

$listado_tarimas_hist = mysqli_query(
    $cnx,
    "SELECT th.*, t.tar_folio, t.pro_id, c.cal_descripcion,t.tar_kilos FROM rev_tarimas_hist th
    JOIN rev_tarimas t ON th.tar_id = t.tar_id 
    JOIN rev_calidad c ON th.cal_id = c.cal_id
    WHERE th.mez_id = '" . $_GET['mez_id'] . "'"
);

$listado_mezclas = "SELECT m.*, u.usu_nombre FROM rev_mezcla m
    JOIN usuarios u ON u.usu_id = m.usu_id";

try {
    $datos_tarimas = array();
    $datos_tarimas_hist = array();

    while ($fila = mysqli_fetch_assoc($listado_tarimas_hist)) {
        $datos_tarimas_hist[] = $fila;
    }

    if (count($datos_tarimas_hist) != 0) {
        while ($fila = mysqli_fetch_assoc($listado_tarimas)) {
            $datos_tarimas[] = $fila;
        }
    } else {
        $datos_tarimas = [];
    }

    $registro = mysqli_fetch_assoc(mysqli_query($cnx, $listado_mezclas));
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de mezcla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../../js/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header,
        .content {
            border-bottom: 1px solid #000;
            padding: 5px;
        }

        .header .title {
            font-weight: bold;
            font-size: 45px;
        }

        .content .left {
            width: 50%;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            font-size: 12px;
        }

        @media print {
            .print-button {
                display: none;
            }

            .table-responsive {
                overflow: visible !important;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="header mb-4">
            <div class="row align-items-center">
                <div class="col-3">
                    <img src="../../imagenes/logo_progel_v3.png" alt="Logo" class="img-fluid">
                </div>
                <div class="col-5">
                    <div class="title">Detalle de Mezcla: <?= $_GET['mez_folio'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <h2>Resultados de la mezcla</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead style="background: #000">
                    <tr>
                        <th>FECHA</th>
                        <th>BLOOM</th>
                        <th>VISC.</th>
                        <th>PH FINAL</th>
                        <th>TRANS.</th>
                        <th>%T (620)</th>
                        <th>NTU</th>
                        <th>HUMEDAD</th>
                        <th>CENIZAS</th>
                        <th>CONDUCT</th>
                        <th>REDOX</th>
                        <th>COLOR</th>
                        <th>MALLA #30</th>
                        <th>GRANO MALLA #45</th>
                        <th>OLOR</th>
                        <th>P.E EN 1 KG</th>
                        <th>PART. EXTRAÑAS</th>
                        <th>PART. IND. 6,66%</th>
                        <th>HIDRATACIÓN</th>
                        <th>ACEPTADO</th>
                        <th>RECHAZADO</th>
                        <th>CALIDAD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos_tarimas as $tarimas) { ?>
                        <tr>
                            <td><?= $tarimas['tar_fecha'] ?></td>
                            <td><?= $tarimas['tar_bloom'] ?></td>
                            <td><?= $tarimas['tar_viscosidad'] ?></td>
                            <td><?= $tarimas['tar_ph'] ?></td>
                            <td><?= $tarimas['tar_trans'] ?></td>
                            <td><?= $tarimas['tar_porcentaje_t'] ?></td>
                            <td><?= $tarimas['tar_ntu'] ?></td>
                            <td><?= $tarimas['tar_humedad'] ?></td>
                            <td><?= $tarimas['tar_cenizas'] ?></td>
                            <td><?= $tarimas['tar_ce'] ?></td>
                            <td><?= $tarimas['tar_redox'] ?></td>
                            <td><?= $tarimas['tar_color'] ?></td>
                            <td><?= $tarimas['tar_malla_30'] ?></td>
                            <td><?= $tarimas['tar_malla_45'] ?></td>
                            <td><?= $tarimas['tar_olor'] ?></td>
                            <td><?= $tarimas['tar_pe_1kg'] ?></td>
                            <td><?= $tarimas['tar_par_extr'] ?></td>
                            <td><?= $tarimas['tar_par_ind'] ?></td>
                            <td><?= $tarimas['tar_hidratacion'] ?></td>
                            <td><?= ($tarimas['tar_rechazado'] == 'A') ? 'X' : '' ?></td>
                            <td><?= ($tarimas['tar_rechazado'] == 'C') ? 'X' : '' ?></td>
                            <td><?= $tarimas['cal_descripcion'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-8">
            <h2>Historial parametros tarimas mezcladas</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead style="background: #000">
                    <tr>
                        <th>#</th>
                        <th>BLOOM</th>
                        <th>VISC.</th>
                        <th>PH FINAL</th>
                        <th>TRANS.</th>
                        <th>%T (620)</th>
                        <th>NTU</th>
                        <th>HUMEDAD</th>
                        <th>CENIZAS</th>
                        <th>CONDUCT</th>
                        <th>REDOX</th>
                        <th>COLOR</th>
                        <th>MALLA #30</th>
                        <th>GRANO MALLA #45</th>
                        <th>OLOR</th>
                        <th>P.E EN 1 KG</th>
                        <th>PART. EXTRAÑAS</th>
                        <th>PART. IND. 6,66%</th>
                        <th>HIDRATACIÓN</th>
                        <th>ACEPTADO</th>
                        <th>RECHAZADO</th>
                        <th>CALIDAD</th>
                        <th>Kilos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos_tarimas_hist as $tarimas) { ?>
                        <tr>
                            <td>P<?= $tarimas['pro_id'] ?>T<?= $tarimas['tar_folio'] ?></td>
                            <td><?= $tarimas['tar_bloom'] ?></td>
                            <td><?= $tarimas['tar_viscosidad'] ?></td>
                            <td><?= $tarimas['tar_ph'] ?></td>
                            <td><?= $tarimas['tar_trans'] ?></td>
                            <td><?= $tarimas['tar_porcentaje_t'] ?></td>
                            <td><?= $tarimas['tar_ntu'] ?></td>
                            <td><?= $tarimas['tar_humedad'] ?></td>
                            <td><?= $tarimas['tar_cenizas'] ?></td>
                            <td><?= $tarimas['tar_ce'] ?></td>
                            <td><?= $tarimas['tar_redox'] ?></td>
                            <td><?= $tarimas['tar_color'] ?></td>
                            <td><?= $tarimas['tar_malla_30'] ?></td>
                            <td><?= $tarimas['tar_malla_45'] ?></td>
                            <td><?= $tarimas['tar_olor'] ?></td>
                            <td><?= $tarimas['tar_pe_1kg'] ?></td>
                            <td><?= $tarimas['tar_par_extr'] ?></td>
                            <td><?= $tarimas['tar_par_ind'] ?></td>
                            <td><?= $tarimas['tar_hidratacion'] ?></td>
                            <td><?= ($tarimas['tar_rechazado'] == 'A') ? 'X' : '' ?> </td>
                            <td><?= ($tarimas['tar_rechazado'] == 'C') ? 'X' : '' ?> </td>
                            <td><?= $tarimas['cal_descripcion'] ?></td>
                            <td><?= $tarimas['tar_kilos'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <h3 class="mb-3 mt-3">Datos de mezcla</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <th>Fecha</th>
                        <th>Responable</th>
                        <th>Hora Inicio</th>
                        <th>Hora fin</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $registro['mez_fecha'] ?></td>
                            <td><?= $registro['usu_nombre'] ?></td>
                            <td><?= $registro['mez_hora_ini'] ?></td>
                            <td><?= $registro['mez_hora_fin'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th>¿Los imanes se encuentran limpios?</th>
                            <td><?= isset($registro['mez_imanes_limpios']) ? ($registro['mez_imanes_limpios'] === 'S' ? 'Si' : 'No') : '' ?></td>
                        </tr>
                        <tr>
                            <th>¿La base para los sacos se encuentra limpia?</th>
                            <td><?= isset($registro['mez_sacos_limpios']) ? ($registro['mez_sacos_limpios'] === 'S' ? 'Si' : 'No') : '' ?></td>
                        </tr>
                        <tr>
                            <th>¿La helicoidal está libre de sobrantes?</th>
                            <td><?= isset($registro['mez_libre_sobrantes']) ? ($registro['mez_libre_sobrantes'] === 'S' ? 'Si' : 'No') : '' ?></td>
                        </tr>
                        <tr>
                            <th>Número de mezcladora</th>
                            <td><?= $registro['mez_mezcladora'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>