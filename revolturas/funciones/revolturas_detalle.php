<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Revolturas</title>
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

        h3 {
            color: #007bff;
        }
    </style>
</head>
<?php
// Desarrollado por: CCA Consultores TI 
// Contacto: contacto@ccaconsultoresti.com 
// Actualizado: Agosto-2024
include "../../conexion/conexion.php";

$cnx = Conectarse();
$clausulaWhere = '';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['rev_id'])) {
        $clausulaWhere = "WHERE r.rev_id = '" . $_GET['rev_id'] . "'";
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function renderEstatus($tar_estatus)
    {
        $estatus = [
            '0' => 'En el registro inicial',
            '1' => 'En almacén',
            '2' => 'Tomada para una revoltura',
            '3' => 'Control revolturas',
            '4' => 'Tomada para una mezcla',
            '5' => 'Control mezclas',
            '6' => 'Empacado',
        ];

        // Devuelve el estado correspondiente o un valor predeterminado
        return $estatus[$tar_estatus] ?? 'Enviado';
    }


    if (isset($_POST['txt_filtro_Revoltura'])) {
        $clausulaWhere = "WHERE r.rev_folio = '" . $_POST['txt_filtro_Revoltura'] . "'";
    } else if (isset($_POST['txt_filtro_Factura'])) {
        $clausulaWhere = "WHERE r.rev_factura = '" . $_POST['txt_filtro_Factura'] . "'";
    } else if (isset($_POST['txt_filtro_tarima'], $_POST['txt_filtro_proceso'])) {
        $tarima = mysqli_real_escape_string($cnx, $_POST['txt_filtro_tarima']);
        $proceso = mysqli_real_escape_string($cnx, $_POST['txt_filtro_proceso']);

        // Primera consulta: buscar todas las ocurrencias del folio de tarima
        $query = "SELECT pro_id, tar_folio, tar_estatus 
                  FROM rev_tarimas 
                  WHERE tar_folio = '$tarima'";
        $res = mysqli_query($cnx, $query);

        if ($res && mysqli_num_rows($res) > 0) {
            $tarimaEncontrada = false;
            $procesoDiferente = false;
            $tarimaEnAlmacen = false;
            $tar_estatus = '';

            // Recorrer todos los resultados para buscar coincidencias específicas
            while ($row = mysqli_fetch_assoc($res)) {
                if ($row['pro_id'] == $proceso) {
                    $tarimaEncontrada = true;
                    $tar_estatus = $row['tar_estatus'];

                    // Verificar el estado de la tarima
                    if ($row['tar_estatus'] == '1') {
                        $tarimaEnAlmacen = true;
                    }
                    break;
                } else {
                    $procesoDiferente = true;
                }
            }

            if ($tarimaEncontrada) {
                // Tarima encontrada con el proceso correcto
                if ($tarimaEnAlmacen) {
                    echo '<div class="d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #f8f9fa;">
                            <div class="text-center p-4" style="background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); max-width: 500px;">
                                <h3 class="mb-3" style="color: #343a40;">La tarima ' . $tarima . ' con proceso ' . $proceso . ' se encuentra en almacén.</h3>
                            </div>
                        </div>';
                    exit;
                } else {
                    $tar_rev = "SELECT t.pro_id,t.tar_folio,tar_bloom,t.tar_viscosidad,c.cal_descripcion, r.rev_folio,r.rev_id FROM rev_tarimas t
                    LEFT JOIN rev_calidad c ON c.cal_id = t.cal_id
                    INNER JOIN rev_revolturas_tarimas rt ON rt.tar_id = t.tar_id
                    INNER JOIN rev_revolturas r ON r.rev_id = rt.rev_id 
                    WHERE t.tar_folio = '$tarima' AND pro_id = '$proceso'";
                    $res = mysqli_fetch_assoc(mysqli_query($cnx, $tar_rev));
                    if ($res != null) {
                        echo
                        '<div class="d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #f8f9fa;">
                            <div class="text-center p-4" style="background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); max-width: 500px;">
                            <h3 class="mb-3" style="color: #343a40;">La tarima ' . $tarima . ' con proceso ' . $proceso . ' fue procesada en la revoltura: ' . $res['rev_folio'] . '.</h3>
                            <a href="revolturas_detalle.php?rev_id=' . htmlspecialchars($res['rev_id']) . '" target="_blank"
                            class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalle revoltura">
                            <i class="fas fa-eye"></i> Ver detalle
                            </a>
                        </div>
                    </div>';
                        exit;
                    } else {
                        $msg = renderEstatus($tar_estatus);

                        echo '<div class="d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #f8f9fa;">
                        <div class="text-center p-4" style="background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); max-width: 500px;">
                            <h3 class="mb-3" style="color: #343a40;">La tarima ' . $tarima . ' con proceso ' . $proceso . ' esta en : ' . $msg . '</h3>
                        </div>
                    </div>';
                        exit;
                    }
                }
            } elseif ($procesoDiferente) {
                // Tarima encontrada pero pertenece a un proceso diferente
                echo '<div class="d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #f8f9fa;">
                        <div class="text-center p-4" style="background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); max-width: 500px;">
                            <h3 class="mb-3" style="color: #343a40;">La tarima ' . $tarima . ' pertenece a un proceso diferente.</h3>
                        </div>
                    </div>';
                exit;
            }
        } else {
            // La tarima no existe en la base de datos
            echo '<div class="d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #f8f9fa;">
                    <div class="text-center p-4" style="background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); max-width: 500px;">
                        <h3 class="mb-3" style="color: #343a40;">Tarima no registrada</h3>
                        <p>La tarima <strong>' . $tarima . '</strong> no está registrada en el sistema.</p>
                    </div>
                </div>';
            exit;
        }
    }
}

$listado_tarimas = mysqli_query($cnx, "SELECT DATE(r.rev_fecha) as rev_fecha,r.rev_folio,r.rev_id FROM 
    rev_revolturas r " . $clausulaWhere);


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
    $consulta = mysqli_query($cnx, "SELECT t.*,DATE(t.tar_fecha) as tar_fecha, 
    r.*,DATE(r.rev_fecha) as rev_fecha,u.usu_nombre, c.cal_descripcion
    FROM rev_revolturas_tarimas rt 
    INNER JOIN rev_tarimas t ON rt.tar_id = t.tar_id 
    INNER JOIN rev_revolturas r ON rt.rev_id = r.rev_id
    INNER JOIN usuarios u ON u.usu_id = r.usu_id
    LEFT JOIN rev_calidad c ON c.cal_id = t.cal_id " . $clausulaWhere);

    $registros = array();
    while ($fila = mysqli_fetch_assoc($consulta)) {
        $registros[] = $fila;
    }

    $consultaR = mysqli_query($cnx, "SELECT r.*,c.cal_descripcion 
    FROM rev_revolturas r
    INNER JOIN usuarios u ON u.usu_id = r.usu_id
    LEFT JOIN rev_calidad c ON c.cal_id = r.cal_id " . $clausulaWhere);

    $registrosR = array();
    while ($fila = mysqli_fetch_assoc($consultaR)) {
        $registrosR[] = $fila;
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
        $totalKilos += 1000;
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

if (!empty($res)) {
    $listado_presentaciones = mysqli_query($cnx, "SELECT rp.*,p.pres_descrip,p.pres_kg FROM rev_revolturas_pt rp 
        JOIN rev_presentacion p ON rp.pres_id = p.pres_id WHERE rp.rev_id = '" . $res[0]['rev_id'] . "'");

    $listado_presentaciones_cliente = mysqli_query(
        $cnx,
        "SELECT rpc.*, p.pres_descrip, p.pres_kg, c.cte_nombre 
    FROM rev_revolturas_pt_cliente rpc
    JOIN rev_presentacion p ON rpc.pres_id = p.pres_id
    JOIN rev_clientes c ON rpc.cte_id = c.cte_id
    WHERE rpc.rev_id = '" . $res[0]['rev_id'] . "'
"
    );
} else {
    echo '
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="text-center">
            <h3>No se encontraron resultados para la búsqueda.</h3>
        </div>
    </div>';
    exit();
}
try {
    $datos_presentacion = array();

    while ($fila = mysqli_fetch_assoc($listado_presentaciones)) {
        $datos_presentacion[] = $fila;
    }

    $datos_presentacion_cliente = array();
    while ($fila = mysqli_fetch_assoc($listado_presentaciones_cliente)) {
        $datos_presentacion_cliente[] = $fila;
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

try {
    $listado_muestras = mysqli_query($cnx, "SELECT rm.rm_kilos,p.pres_descrip FROM rev_revolturas_pt_muestreo rm
    JOIN rev_presentacion p ON rm.pres_id = p.pres_id
    WHERE rev_id = '" . $res[0]['rev_id'] . "'");

    $muestras = array();

    while ($fila = mysqli_fetch_assoc($listado_muestras)) {
        $muestras[] = $fila;
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}


try {
    $listado_facturas = mysqli_query(
        $cnx,
        "SELECT DATE(rpf.fe_fecha) AS fe_fecha,rpf.fe_factura,rpf.fe_id,rpf.fe_tipo,c.cte_nombre,rev.*
    FROM rev_revolturas AS rev
    INNER JOIN rev_revolturas_pt AS rp ON rp.rev_id = rev.rev_id
    INNER JOIN rev_revolturas_pt_facturas AS rpf ON rpf.rr_id = rp.rr_id
    INNER JOIN rev_clientes AS c ON c.cte_id = rpf.cte_id
    WHERE rev.rev_id = '" . $res[0]['rev_id'] . "'"
    );

    $facturas = array();
    while ($fila = mysqli_fetch_assoc($listado_facturas)) {
        $facturas[] = $fila;
    }

    $listado_facturas_cliente = mysqli_query(
        $cnx,
        "SELECT DATE(rpfc.fe_fecha) AS fe_fecha, rpfc.fe_factura, rpfc.fe_id, rpfc.fe_tipo, c.cte_nombre, rev.*
    FROM rev_revolturas AS rev
    INNER JOIN rev_revolturas_pt_cliente AS rpc ON rpc.rev_id = rev.rev_id
    INNER JOIN rev_revolturas_pt_facturas AS rpfc ON rpfc.rrc_id = rpc.rrc_id
    INNER JOIN rev_clientes AS c ON c.cte_id = rpfc.cte_id
    WHERE rev.rev_id = '" . $res[0]['rev_id'] . "'"
    );

    $facturas_cliente = array();
    while ($fila = mysqli_fetch_assoc($listado_facturas_cliente)) {
        $facturas_cliente[] = $fila;
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}


$currentDir = dirname($_SERVER['REQUEST_URI']);


?>


<body>

    <div class="header mb-4">
        <div class="row align-items-center">
            <div class="col-3">
                <img src="../../imagenes/logo_progel_v3.png" alt="Logo" class="img-fluid">
            </div>
            <div class="col-5">
                <div class="title">Detalle de Revolturas <?= $res[0]['rev_fecha'] ?></div>
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
                <div><strong>Cantidad a Cargar:</strong> <?= $reg_tot > 0 ? ($reg_tot * 1000) . " kg" : "0 kg" ?></div>
            </div>
        </div>
    </div>
    <h3 class="mb-3 mt-3">Parámetros de revoltura</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
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
                    <th>Calidad</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    <td><?= $registrosR[0]['rev_bloom'] ?></td>
                    <td><?= $registrosR[0]['rev_viscosidad'] ?></td>
                    <td><?= $registrosR[0]['rev_ph'] ?></td>
                    <td><?= $registrosR[0]['rev_trans'] ?></td>
                    <td><?= $registrosR[0]['rev_color'] ?></td>
                    <td><?= $registrosR[0]['rev_par_extr'] ?></td>
                    <td><?= $registrosR[0]['rev_par_ind'] ?></td>
                    <td><?= $registrosR[0]['rev_redox'] ?></td>
                    <td><?= $registrosR[0]['rev_cenizas'] ?></td>
                    <td><?= $registrosR[0]['rev_malla_30'] ?></td>
                    <td><?= $registrosR[0]['rev_malla_45'] ?></td>
                    <td><?= $registrosR[0]['rev_humedad'] ?></td>
                    <td><?= $registrosR[0]['cal_descripcion'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <h3 class="mb-3 mt-3">Parámetros de tarimas</h3>
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
                    <th>Calidad</th>
                    <th>Tarima</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $registro) { ?>
                    <tr>
                        <td><?= $registro['tar_fecha'] ?></td>
                        <td>P<?= $registro['pro_id'] ?>T<?= $registro['tar_folio'] ?></td>
                        <td>1000</td>
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
                        <td><?= $registro['cal_descripcion'] ?></td>
                        <td><a href="<?= $currentDir ?>/tarimas_detalle.php?tar_id=<?= $registro['tar_id'] ?>"
                                class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalle tarima">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan='2'><strong>Promedio</strong></td>
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
                    <td colspan='2'></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-md-4">
            <h3 class="mb-3 mt-3">Datos de revoltura</h3>
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
                            <td><?= $registro['rev_fecha'] ?></td>
                            <td><?= $registro['usu_nombre'] ?></td>
                            <td><?= $registro['rev_hora_ini'] ?></td>
                            <td><?= $registro['rev_hora_fin'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th>¿Los imanes se encuentran limpios?</th>
                            <td><?= $registro['rev_imanes_limpios'] === 'S' ? 'Si' : 'No' ?></td>
                        </tr>
                        <tr>
                            <th>¿La base para los sacos se encuentra limpia?</th>
                            <td><?= $registro['rev_sacos_limpios'] === 'S' ? 'Si' : 'No' ?></td>
                        </tr>
                        <tr>
                            <th>¿La helicoidal está libre de sobrantes?</th>
                            <td><?= $registro['rev_libre_sobrantes'] === 'S' ? 'Si' : 'No' ?></td>
                        </tr>
                        <tr>
                            <th>Número de mezcladora</th>
                            <td><?= $registro['rev_mezcladora'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <h3 class="mb-3 mt-3">Información de empacado</h3>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Cantidad</th>
                            <th>Presentación</th>
                            <th>KG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($datos_presentacion as $registro) {
                            $kg = floatval($registro['rr_ext_inicial']) * floatval($registro['pres_kg']);
                            $total += $kg;
                        ?>
                            <tr>
                                <td><?= $registro['rr_ext_inicial'] ?></td>
                                <td><?= $registro['pres_descrip'] ?></td>
                                <td><?= $kg ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="2"><strong>Total General</strong></td>
                            <td><strong><?= $total ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($datos_presentacion_cliente)) { ?>
                <h5 class="mt-4 mb-2">Por Cliente</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Cantidad</th>
                                <th>Presentación</th>
                                <th>KG</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_cliente = 0;
                            foreach ($datos_presentacion_cliente as $registro) {
                                $kg = floatval($registro['rrc_ext_inicial']) * floatval($registro['pres_kg']);
                                $total_cliente += $kg;
                            ?>
                                <tr>
                                    <td><?= $registro['cte_nombre'] ?></td>
                                    <td><?= $registro['rrc_ext_inicial'] ?></td>
                                    <td><?= $registro['pres_descrip'] ?></td>
                                    <td><?= $kg ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="3"><strong>Total Por Cliente</strong></td>
                                <td><strong><?= $total_cliente ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

        </div>
        <div class="col-md-4">
            <h3 class="mb-3 mt-3">Información de muestreo</h3>
            <span> <?= isset($muestras[0]['pres_descrip']) ? 'Muestra de: ' . $muestras[0]['pres_descrip'] : 'Sin muestreo registrado' ?></span>

            <table class="table table-bordered" id="muestras">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kilos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($muestras as $index => $registro) {
                    ?>
                        <tr>
                            <td><?= ($index + 1) ?></td>
                            <td><?= $registro['rm_kilos'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3 class="mb-3 mt-3">Información de facturas</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Consultar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($facturas)) {
                        foreach ($facturas as $index => $registro) {
                    ?>
                            <tr>
                                <td><?= $registro['fe_factura'] ?></td>
                                <td><?= $registro['fe_fecha'] ?></td>
                                <td><?= $registro['cte_nombre'] ?></td>
                                <td><?= $registro['fe_tipo'] == 'F' ? 'Factura' : 'Remisión' ?></td>
                                <td><a href="<?= $currentDir ?>/facturas_empacado_detalle.php?fe_factura=<?= $registro['fe_factura'] ?>"
                                        class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalle tarima" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5">La revoltura aun no se factura</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php if (!empty($facturas_cliente)) { ?>
                <h5 class="mt-4 mb-2">Facturas por Cliente</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Factura</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Consultar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($facturas_cliente as $registro) { ?>
                                <tr>
                                    <td><?= $registro['fe_factura'] ?></td>
                                    <td><?= $registro['fe_fecha'] ?></td>
                                    <td><?= $registro['cte_nombre'] ?></td>
                                    <td><?= $registro['fe_tipo'] == 'F' ? 'Factura' : 'Remisión' ?></td>
                                    <td>
                                        <a href="<?= $currentDir ?>/facturas_empacado_detalle.php?fe_factura=<?= $registro['fe_factura'] ?>"
                                            class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalle factura" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

        </div>
    </div>
    <script src="../../assets/fontawesome/fontawesome.js"></script>
</body>

</html>