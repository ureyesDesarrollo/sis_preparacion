<?php
// Desarrollado por: CCA Consultores TI 
// Contacto: contacto@ccaconsultoresti.com 
// Actualizado: Agosto-2024
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();
extract($_GET);

$listado_tarimas = mysqli_query($cnx, "SELECT DATE(r.rev_fecha) as rev_fecha,r.rev_folio FROM 
    rev_revolturas r WHERE  r.rev_id = '" . $_GET['rev_id'] . "'");

try {
    $res = array();
    while ($fila = mysqli_fetch_assoc($listado_tarimas)) {
        $res[] = $fila;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

try {
    // Consulta los datos de las tarimas
    $consulta = mysqli_query($cnx, "SELECT t.*,DATE(t.tar_fecha) as tar_fecha FROM 
    rev_revolturas_tarimas rt JOIN rev_tarimas t ON rt.tar_id = t.tar_id WHERE 
    rt.rev_id = '" . $_GET['rev_id'] . "'");

    $registros = array();
    while ($fila = mysqli_fetch_assoc($consulta)) {
        $registros[] = $fila;
    }
    $reg_tot = count($registros);

    // Inicializar variables de totales y conteo
    $totalBloom = $totalVisc = $totalPH = $totalTrans = 0;
    $totalColor = $totalParExtr = $totalParInd = $totalRedox = 0;
    $totalCenizas = $totalMalla30 = $totalMalla45 = $totalHumedad = 0;
    $totalKilos = 0;
    $count = $reg_tot;

    // Calcular totales
    foreach ($registros as $result) {
        $totalBloom += (float)$result['tar_bloom'];
        $totalVisc += (float)$result['tar_viscosidad'];
        $totalPH += (float)$result['tar_ph'];
        $totalTrans += (float)$result['tar_trans'];
        $totalColor += (float)$result['tar_color'];
        $totalParExtr += (float)$result['tar_par_extr'];
        $totalParInd += (float)$result['tar_par_ind'];
        $totalRedox += (float)$result['tar_redox'];
        $totalCenizas += (float)$result['tar_cenizas'];
        $totalMalla30 += (float)$result['tar_malla_30'];
        $totalMalla45 += (float)$result['tar_malla_45'];
        $totalHumedad += (float)$result['tar_humedad'];
        $totalKilos += (float)$result['tar_kilos'];
    }

    // Calcular promedios
    $promedioBloom = $count > 0 ? $totalBloom / $count : 0;
    $promedioVisc = $count > 0 ? $totalVisc / $count : 0;
    $promedioPH = $count > 0 ? $totalPH / $count : 0;
    $promedioTrans = $count > 0 ? $totalTrans / $count : 0;
    $promedioColor = $count > 0 ? $totalColor / $count : 0;
    $promedioParExtr = $count > 0 ? $totalParExtr / $count : 0;
    $promedioParInd = $count > 0 ? $totalParInd / $count : 0;
    $promedioRedox = $count > 0 ? $totalRedox / $count : 0;
    $promedioCenizas = $count > 0 ? $totalCenizas / $count : 0;
    $promedioMalla30 = $count > 0 ? $totalMalla30 / $count : 0;
    $promedioMalla45 = $count > 0 ? $totalMalla45 / $count : 0;
    $promedioHumedad = $count > 0 ? $totalHumedad / $count : 0;
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Revolturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        }

        .content .left {
            width: 50%;
        }

        .table-responsive {
            margin-top: 20px;
        }

        @media print {
            .print-button {
                display: none;
            }

            .table-responsive {
                overflow: visible !important;
            }
        }

        /* Oculta los campos por defecto en la pantalla */
        .campos-impresion {
            display: none;
        }

        /* Solo muestra los campos al imprimir */
        @media print {
            .campos-impresion {
                display: block;
            }

            /* Aplica estilo a las líneas */
            .linea {
                border-bottom: 1px solid black;
                width: 180px;
                display: inline-block;
            }
        }
    </style>
</head>

<body>

    <div class="header mb-4">
        <div class="row align-items-center">
            <div class="col-3">
                <img src="../../imagenes/logo_progel_v3.png" alt="Logo" class="img-fluid">
            </div>
            <div class="col-5">
                <div class="title">Orden de Revolturas <?= $res[0]['rev_fecha'] ?></div>
            </div>
            <div class="col-4 text-right">
                <div><strong>Formato:</strong> RIVF 002</div>
                <div><strong>REV:</strong> 003</div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="row">
            <div class="col-6">
                <div><strong>Revoltura No.:</strong> <?= $res[0]['rev_folio'] ?></div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div><strong>Cantidad a Cargar:</strong> <?= $reg_tot > 0 ? ($totalKilos) . " kg" : "0 kg" ?></div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Lote</th>
                    <th>Kilos</th>
                    <th>Bloom</th>
                    <th>Visc</th>
                    <th>PH</th>
                    <th>Trans</th>
                    <th>Color</th>
                    <th>Part. Ext.</th>
                    <th>Ind</th>
                    <th>Redox</th>
                    <th>Ceniza</th>
                    <th>Malla 45 NO (hacia abajo)</th>
                    <th>Malla 46 SI (hacia arriba)</th>
                    <th>Humedad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $registro) { ?>
                    <tr>
                        <td><?= $registro['tar_fecha'] ?></td>
                        <td>P<?= ($registro['pro_id_2'] === null) ? $registro['pro_id'] : "{$registro['pro_id']}/{$registro['pro_id_2']}" ?>T<?= $registro['tar_folio'] ?></td>
                        <td><?= $registro['tar_kilos'] ?></td>
                        <td><?= $registro['tar_bloom'] ?></td>
                        <td><?= $registro['tar_viscosidad'] ?></td>
                        <td><?= $registro['tar_ph'] ?></td>
                        <td><?= $registro['tar_trans'] ?></td>
                        <td><?= $registro['tar_color'] ?></td>
                        <td><?= $registro['tar_par_extr'] ?></td>
                        <td><?= $registro['tar_par_ind'] ?></td>
                        <td><?= $registro['tar_redox'] ?></td>
                        <td><?= $registro['tar_cenizas'] ?></td>
                        <td><?= $registro['tar_malla_30'] ?></td>
                        <td><?= $registro['tar_malla_45'] ?></td>
                        <td><?= $registro['tar_humedad'] ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td><strong><?= $totalKilos ?></td>
                    <td><strong><?= number_format($promedioBloom, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioVisc, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioPH, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioTrans, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioColor, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioParExtr, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioParInd, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioRedox, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioCenizas, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioMalla30, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioMalla45, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioHumedad, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <div class="row" style="margin-top: 70px;">

            <div class="col-6 campos-impresion">
                <p>Tiempo de Mezcla Inicial: <span class="linea"></span></p>
                <p>Tiempo de Mezcla Final: <span class="linea"></span></p>
                <p>KG de ácido sórbico: <span class="linea"></span></p>
            </div>

            <div class="col-6 campos-impresion">
                <p>Autorizo: <span class="linea"></span></p>
                <p>Cliente: <span class="linea"></span></p>
                <p>Presentación: <span class="linea"></span></p>
            </div>
        </div>


    </div>

    <button class="btn btn-primary print-button mt-4" onclick="window.print()">Imprimir</button>

</body>

</html>