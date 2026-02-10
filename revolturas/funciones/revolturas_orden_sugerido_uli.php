<?php
// Desarrollado por: CCA Consultores TI 
// Contacto: contacto@ccaconsultoresti.com 
// Actualizado: Agosto-2024
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    // Decodificar el JSON recibido
    $data = json_decode($_POST['data'], true);
    $reg_tot = count($data);

    // Si no se recibieron datos, redirigir a la página anterior o mostrar un mensaje
    if (!$data) {
        echo "No se recibieron datos.";
        exit;
    }


    // Calcular totales
    foreach ($data as $result) {
    }

    // Calcular promedios
    $promedioBloom = $count > 0 ? $totalBloom / $count : 0;
    $promedioVisc = $count > 0 ? $totalVisc / $count : 0;
    $promedioPH = $count > 0 ? $totalPH / $count : 0;
    $promedioTrans = $count > 0 ? $totalTrans / $count : 0;
    $promedioPorcentajeT = $count > 0 ? $totalPorcentajeT / $count : 0;
    $promedioNTU = $count > 0 ? $totalNTU / $count : 0;
    $promedioHumedad = $count > 0 ? $totalHumedad / $count : 0;
    $promedioCenizas = $count > 0 ? $totalCenizas / $count : 0;
    $promedioCE = $count > 0 ? $totalCE / $count : 0;
    $promedioRedox = $count > 0 ? $totalRedox / $count : 0;
    $promedioColor = $count > 0 ? $totalColor / $count : 0;
    $promedioMalla30 = $count > 0 ? $totalMalla30 / $count : 0;
    $promedioMalla45 = $count > 0 ? $totalMalla45 / $count : 0;
    $promedioOlor = $count > 0 ? $totalOlor / $count : 0;
    $promedioPE1kg = $count > 0 ? $totalPE1kg / $count : 0;
    $promedioParExtr = $count > 0 ? $totalParExtr / $count : 0;
    $promedioParInd = $count > 0 ? $totalParInd / $count : 0;
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revolturas sugeridas</title>
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
    </style>
    <script src="../../js/jquery.min.js"></script>
    <link href="../../assets/sweetalert/sweetalert.css" rel="stylesheet" />
    <script src="../../assets/sweetalert/sweetalert.js"></script>
    <script src="../../assets/sweetalert/sweetalert2.js"></script>
</head>

<body>

    <div class="header mb-4">
        <div class="row align-items-center">
            <div class="col-3">
                <img src="../../imagenes/logo_progel_v3.png" alt="Logo" class="img-fluid">
            </div>
            <div class="col-5">
                <div class="title">Revolturas sugeridas</div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="revolturasSugeridas" class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Ren</th>
                    <th>Fecha</th>
                    <th>Lote</th>
                    <th>Kilos</th>
                    <th>Bloom</th>
                    <th>Visc.</th>
                    <th>Ph final</th>
                    <th>Trans.</th>
                    <th>%T (620)</th>
                    <th>Ntu</th>
                    <th>Humedad</th>
                    <th>Cenizas</th>
                    <th>Conduct.</th>
                    <th>Redox</th>
                    <th>Color</th>
                    <th>Malla #30</th>
                    <th>Malla #45</th>
                    <th>Olor</th>
                    <th>P.e en 1 kg</th>
                    <th>Part. extrañas</th>
                    <th>Part. ind. 6,66%</th>
                    <th>Hidratación</th>
                    <th>Tomar</th>
                </tr>
            </thead>
            <tbody>
                <?php 
    // Inicializar variables de totales
    $totalBloom = $totalVisc = $totalPH = $totalTrans = $totalPorcentajeT = 0;
    $totalNTU = $totalHumedad = $totalCenizas = $totalCE = $totalRedox = 0;
    $totalColor = $totalMalla30 = $totalMalla45 = $totalOlor = $totalPE1kg = 0;
    $totalParExtr = $totalParInd = $totalKilos = 0;
    $count = count($data);
            $cont = 1;
foreach ($data as $registro) { 


        $totalBloom += (float)$registro['tar_bloom'];
        $totalVisc += (float)$registro['tar_viscosidad'];
        $totalPH += (float)$registro['tar_ph'];
        $totalTrans += (float)$registro['tar_trans'];
        $totalPorcentajeT += (float)$registro['tar_porcentaje_t'];
        $totalNTU += (float)$registro['tar_ntu'];
        $totalHumedad += (float)$registro['tar_humedad'];
        $totalCenizas += (float)$registro['tar_cenizas'];
        $totalCE += (float)$registro['tar_ce'];
        $totalRedox += (float)$registro['tar_redox'];
        $totalColor += (float)$registro['tar_color'];
        $totalMalla30 += (float)$registro['tar_malla_30'];
        $totalMalla45 += (float)$registro['tar_malla_45'];
        $totalOlor += (float)$registro['tar_olor'];
        $totalPE1kg += (float)$registro['tar_pe_1kg'];
        $totalParExtr += (float)$registro['tar_par_extr'];
        $totalParInd += (float)$registro['tar_par_ind'];
        $totalKilos += 1000;


?>                    <tr>
                        <td><?= $cont; ?></td>
                        <td><?= $registro['tar_fecha'] ?></td>
                        <td>P<?= $registro['pro_id'] ?>T<?= $registro['tar_folio'] ?></td>
                        <td>1000</td>
                        <td><?= $registro['tar_bloom'] ?></td>
                        <td><?= $registro['tar_viscosidad'] ?></td>
                        <td><?= $registro['tar_ph'] ?></td>
                        <td><?= $registro['tar_trans'] ?></td>
                        <td><?= $registro['tar_porcentaje_t'] ?></td>
                        <td><?= $registro['tar_ntu'] ?></td>
                        <td><?= $registro['tar_humedad'] ?></td>
                        <td><?= $registro['tar_cenizas'] ?></td>
                        <td><?= $registro['tar_ce'] ?></td>
                        <td><?= $registro['tar_redox'] ?></td>
                        <td><?= $registro['tar_color'] ?></td>
                        <td><?= $registro['tar_malla_30'] ?></td>
                        <td><?= $registro['tar_malla_45'] ?></td>
                        <td><?= $registro['tar_olor'] ?></td>
                        <td><?= $registro['tar_pe_1kg'] ?></td>
                        <td><?= $registro['tar_par_extr'] ?></td>
                        <td><?= $registro['tar_par_ind'] ?></td>
                        <td><?= $registro['tar_hidratacion'] ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-id="<?= $registro['tar_id'] ?>" onclick="tomar_tarima_revoltura(<?= $registro['tar_id'] ?>)">Tomar</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><strong>Promedio</strong></td>
                    <td><strong><?= $totalKilos ?></strong></td>
                    <td><strong><?= number_format($promedioBloom, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioVisc, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioPH, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioTrans, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioPorcentajeT, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioNTU, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioHumedad, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioCenizas, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioCE, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioRedox, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioColor, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioMalla30, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioMalla45, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioOlor, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioPE1kg, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioParExtr, 2) ?></strong></td>
                    <td><strong><?= number_format($promedioParInd, 2) ?></strong></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>

        </table>
    </div>
</body>

</html>

<script>
    function tomar_tarima_revoltura(id) {

        Swal.fire({
            title: "¿Seguro que deseas revolver la tarima?",
            text: '',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '../funciones/tarimas_almacen_tomar.php',
                    data: {
                        'tar_id': id,
                        action: 'revoltura'
                    },
                    success: function(result) {
                        let res = JSON.parse(result);
                        if (res.success) {
                            Swal.fire({
                                title: "Tomada!",
                                text: `${res.success}`,
                                icon: "success"
                            });
                            // Eliminar el renglón correspondiente de la tabla
                            $(`button[data-id='${id}']`).closest('tr').remove();
                            verificarData();
                        } else {
                            Swal.fire({
                                title: "Ocurrio un error!",
                                text: `${res.error}`,
                                icon: "error"
                            });
                        }
                    }
                });
            }
        });
    }

    function verificarData() {
        const rows = $('#revolturasSugeridas tbody tr').length;
        const noDataMessage = `
            <tr id="noDataRow">
                <td colspan="22" class="text-center">
                    No hay datos
                </td>
            </tr>
        `;

        if (rows === 0) {
            if ($('#noDataRow').length === 0) {
                $('#revolturasSugeridas tbody').append(noDataMessage);
                $('#revolturasSugeridas tfoot').hide(); // Ocultar el pie de tabla de promedios
            }
        } else {
            $('#noDataRow').remove();
            $('#revolturasSugeridas tfoot').show(); // Mostrar el pie de tabla de promedios
        }
    }

    $(document).ready(function() {
        verificarData(); // Verificar al cargar la página
    });
</script>