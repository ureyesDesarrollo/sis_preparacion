<?php
// Desarrollado por: CCA Consultores TI 
// Contacto: contacto@ccaconsultoresti.com 
// Actualizado: Agosto-2024
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    // Decodificar el JSON recibido
    $res = json_decode($_POST['data'], true);
    $data = $res['datos'];
    $ordenamiento = $res['ordenamiento'];
    $ordenColumna = $res['ordenColumna'];
    $reg_tot = count($data);
    if (!$data) {
        echo "<div style='display: flex; align-items: center; justify-content: center; height: 100vh;'>
        <div style='text-align: center;'>
            <h2 style='font-family: Arial, sans-serif; color: #555;'>No existen datos por mostrar con ese criterio de busqueda.</h2>
            <button style='padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;' onclick='window.history.back();'>Volver</button>
        </div>
      </div>";
        exit;
    }


    // Calcular totales
    /* foreach ($data as $result) {
    }*/
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
        <div class="row justify-content-end">
            <div class="d-md-flex justify-content-md-end">
                <span class='mt-3 me-3'>Ordenado de: <?= $ordenamiento === 'ASC' ? 'Menor a mayor' : 'Mayor a menor' ?></span>
                <button class="btn btn-primary me-md-2" type="button" onclick="window.location.href='/sis_preparacion/revolturas/index_inicio.php#'">
                    <i class="fa-solid fa-arrow-left"></i> Regresar
                </button>
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
                    <th class="<?= $ordenColumna == 'tar_bloom' ? 'text-danger' : '' ?>">Bloom</th>
                    <th class="<?= $ordenColumna == 'tar_viscosidad' ? 'text-danger' : '' ?>">Visc.</th>
                    <th class="<?= $ordenColumna == 'tar_ph' ? 'text-danger' : '' ?>">Ph final</th>
                    <th class="<?= $ordenColumna == 'tar_trans' ? 'text-danger' : '' ?>">Trans.</th>
                    <th class="<?= $ordenColumna == 'tar_porcentaje_t' ? 'text-danger' : '' ?>">%T (620)</th>
                    <th class="<?= $ordenColumna == 'tar_ntu' ? 'text-danger' : '' ?>">Ntu</th>
                    <th class="<?= $ordenColumna == 'tar_humedad' ? 'text-danger' : '' ?>">Humedad</th>
                    <th class="<?= $ordenColumna == 'tar_cenizas' ? 'text-danger' : '' ?>">Cenizas</th>
                    <th class="<?= $ordenColumna == 'tar_ce' ? 'text-danger' : '' ?>">Conduct.</th>
                    <th class="<?= $ordenColumna == 'tar_redox' ? 'text-danger' : '' ?>">Redox</th>
                    <th class="<?= $ordenColumna == 'tar_color' ? 'text-danger' : '' ?>">Color</th>
                    <th class="<?= $ordenColumna == 'tar_malla_30' ? 'text-danger' : '' ?>">Malla #30</th>
                    <th class="<?= $ordenColumna == 'tar_malla_45' ? 'text-danger' : '' ?>">Malla #45</th>
                    <th class="<?= $ordenColumna == 'tar_olor' ? 'text-danger' : '' ?>">Olor</th>
                    <th class="<?= $ordenColumna == 'tar_pe_1kg' ? 'text-danger' : '' ?>">P.e en 1 kg</th>
                    <th class="<?= $ordenColumna == 'tar_par_extr' ? 'text-danger' : '' ?>">Part. extrañas</th>
                    <th class="<?= $ordenColumna == 'tar_par_ind' ? 'text-danger' : '' ?>">Part. ind. 6,66%</th>
                    <th>Hidratación</th>
                    <th>Calidad</th>
                    <th>Tomar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grupo = 5; // Número de renglones por grupo
                $totalBloom = $totalVisc = $totalPH = $totalTrans = $totalPorcentajeT = 0;
                $totalNTU = $totalHumedad = $totalCenizas = $totalCE = $totalRedox = 0;
                $totalColor = $totalMalla30 = $totalMalla45 = $totalOlor = $totalPE1kg = 0;
                $totalParExtr = $totalParInd = $totalKilos = 0;
                $contador = 0; // Contador de renglones en el grupo actual

                foreach ($data as $index => $registro) {
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

                    $contador++;

                    // Imprimir la fila del registro
                    if ($registro['tar_fino'] === 'F' && (float)$registro['tar_kilos'] < 1000.00) {
                        $rowClass = 'table-success';
                    } else {
                        $rowClass = $registro['tar_fino'] === 'F' ? 'table-warning' : '';
                        $rowClass .= (float)$registro['tar_kilos'] < 1000.00 ? ' table-secondary' : '';
                    }
                    echo "<tr class='{$rowClass}'>
                        <td>" . ($index + 1) . "</td>
                        <td>{$registro['tar_fecha']}</td>
                        <td>P{$registro['pro_id']} T{$registro['tar_folio']}</td>
                        <td>{$registro['tar_kilos']}</td>
                        <td>{$registro['tar_bloom']}</td>
                        <td>{$registro['tar_viscosidad']}</td>
                        <td>{$registro['tar_ph']}</td>
                        <td>{$registro['tar_trans']}</td>
                        <td>{$registro['tar_porcentaje_t']}</td>
                        <td>{$registro['tar_ntu']}</td>
                        <td>{$registro['tar_humedad']}</td>
                        <td>{$registro['tar_cenizas']}</td>
                        <td>{$registro['tar_ce']}</td>
                        <td>{$registro['tar_redox']}</td>
                        <td>{$registro['tar_color']}</td>
                        <td>{$registro['tar_malla_30']}</td>
                        <td>{$registro['tar_malla_45']}</td>
                        <td>{$registro['tar_olor']}</td>
                        <td>{$registro['tar_pe_1kg']}</td>
                        <td>{$registro['tar_par_extr']}</td>
                        <td>{$registro['tar_par_ind']}</td>
                        <td>{$registro['tar_hidratacion']}</td>
                        <td>{$registro['cal_descripcion']}</td>
                        <td><button class='btn btn-primary btn-sm' data-id='{$registro['tar_id']}' onclick='tomar_tarima_revoltura({$registro['tar_id']},{$registro['tar_kilos']})'>Tomar</button></td>
                    </tr>";

                    // Si se ha alcanzado el número de renglones por grupo o es el último registro
                    if ($contador == $grupo || $index == count($data) - 1) {
                        $promedioBloom = $contador > 0 ? $totalBloom / $contador : 0;
                        $promedioVisc = $contador > 0 ? $totalVisc / $contador : 0;
                        $promedioPH = $contador > 0 ? $totalPH / $contador : 0;
                        $promedioTrans = $contador > 0 ? $totalTrans / $contador : 0;
                        $promedioPorcentajeT = $contador > 0 ? $totalPorcentajeT / $contador : 0;
                        $promedioNTU = $contador > 0 ? $totalNTU / $contador : 0;
                        $promedioHumedad = $contador > 0 ? $totalHumedad / $contador : 0;
                        $promedioCenizas = $contador > 0 ? $totalCenizas / $contador : 0;
                        $promedioCE = $contador > 0 ? $totalCE / $contador : 0;
                        $promedioRedox = $contador > 0 ? $totalRedox / $contador : 0;
                        $promedioColor = $contador > 0 ? $totalColor / $contador : 0;
                        $promedioMalla30 = $contador > 0 ? $totalMalla30 / $contador : 0;
                        $promedioMalla45 = $contador > 0 ? $totalMalla45 / $contador : 0;
                        $promedioOlor = $contador > 0 ? $totalOlor / $contador : 0;
                        $promedioPE1kg = $contador > 0 ? $totalPE1kg / $contador : 0;
                        $promedioParExtr = $contador > 0 ? $totalParExtr / $contador : 0;
                        $promedioParInd = $contador > 0 ? $totalParInd / $contador : 0;

                        // Imprimir la fila de promedios
                        echo "<tr>
                            <td></td>
                            <td colspan='2'><strong>Promedio</strong></td>
                            <td><strong>{$totalKilos}</strong></td>
                            <td><strong>" . number_format($promedioBloom, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioVisc, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioPH, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioTrans, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioPorcentajeT, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioNTU, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioHumedad, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioCenizas, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioCE, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioRedox, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioColor, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioMalla30, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioMalla45, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioOlor, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioPE1kg, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioParExtr, 2) . "</strong></td>
                            <td><strong>" . number_format($promedioParInd, 2) . "</strong></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                        <td collspan='12'>&nbsp;</td>
                        </tr>";

                        // Reiniciar contadores y acumuladores
                        $totalBloom = $totalVisc = $totalPH = $totalTrans = $totalPorcentajeT = 0;
                        $totalNTU = $totalHumedad = $totalCenizas = $totalCE = $totalRedox = 0;
                        $totalColor = $totalMalla30 = $totalMalla45 = $totalOlor = $totalPE1kg = 0;
                        $totalParExtr = $totalParInd = $totalKilos = 0;
                        $contador = 0;
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <!-- Este pie de tabla se usará para mostrar mensajes cuando no haya datos -->
                <tr id="noDataRow" style="display: none;">
                    <td colspan="22" class="text-center">No hay datos</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
<script src="../../assets/fontawesome/fontawesome.js"></script>
<script>
    async function tomar_tarima_revoltura(id, tar_kilos) {
        try {
            // Obtener las tarimas
            const tarimas = await obtenerTarimasRevoltura();

            let kilos = 0;
            $.each(tarimas, function(index, item) {
                kilos += parseFloat(item.tar_kilos);
            });

            let kilos_t = kilos + parseFloat(tar_kilos);

            if (kilos_t > 5000.00) {
                Swal.fire({
                    title: "¡Ya se han tomado los 5000 kilos para la revoltura o al agregar la tarima excede los 5000 kilos permitidos!",
                    text: "",
                    icon: "info"
                });
                return;
            }
            Swal.fire({
                title: "¿Seguro que deseas revolver la tarima?",
                text: '',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí",
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
                                    title: "¡Tomada!",
                                    text: `${res.success}`,
                                    icon: "success"
                                });
                                // Eliminar el renglón correspondiente de la tabla
                                $(`button[data-id='${id}']`).closest('tr').remove();
                                verificarData();
                            } else {
                                Swal.fire({
                                    title: "¡Ocurrió un error!",
                                    text: `${res.error}`,
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });
        } catch (e) {
            console.error('Error al obtener las tarimas:', e);
            Swal.fire({
                title: "¡Error!",
                text: "No se pudieron obtener las tarimas.",
                icon: "error"
            });
        }
    }

    function obtenerTarimasRevoltura() {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: '../funciones/tarimas_almacen_modal_crear_revoltura.php',
                data: {
                    action: 'obtener_tarimas'
                },
                success: function(data) {
                    try {
                        let res = JSON.parse(data);
                        resolve(res);
                    } catch (e) {
                        reject(e);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    reject(errorThrown);
                }
            });
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