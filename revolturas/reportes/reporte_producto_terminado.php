<?php
// Desarrollado por: CCA Consultores TI
// Contacto: contacto@ccaconsultoresti.com
// Actualizado: Septiembre-2024

include "../../conexion/conexion.php";

$cnx = Conectarse();
if (!$cnx) {
    die("Error de conexión a la BD.");
}

/** Helper: ejecutar query con validación */
function q(mysqli $cnx, string $sql)
{
    $res = mysqli_query($cnx, $sql);
    if (!$res) {
        die("Error en consulta: " . mysqli_error($cnx) . "\nSQL:\n" . $sql);
    }
    return $res;
}

// ==================================================
//  PRESENTACIONES ACTIVAS (y mapa rápido descrip=>kg)
// ==================================================
$presentaciones_sql = "SELECT pres_id, pres_descrip, pres_kg FROM rev_presentacion WHERE pres_estatus = 'A'";
$res_pres = q($cnx, $presentaciones_sql);

$presentaciones_list = [];
$presentaciones_kg = []; // mapa: descrip => kg
while ($row = mysqli_fetch_assoc($res_pres)) {
    $desc = $row['pres_descrip'];
    $kg   = (float)$row['pres_kg'];

    $presentaciones_list[] = [
        'id'      => (int)$row['pres_id'],
        'descrip' => $desc,
        'kg'      => $kg
    ];
    $presentaciones_kg[$desc] = $kg;
}

// ==================================================
//  EXISTENCIAS GENERALES
// ==================================================
$query_generales = "SELECT
        rev.rev_folio as revoltura,
        rev.rev_id,
        cal.cal_descripcion,
        rev.rev_bloom,
        rev.rev_viscosidad,
        pres.pres_descrip,
        rr.rr_ext_inicial,
        rr.rr_ext_real
    FROM rev_revolturas_pt rr
    INNER JOIN rev_revolturas rev ON rev.rev_id = rr.rev_id
    INNER JOIN rev_presentacion pres ON pres.pres_id = rr.pres_id
    INNER JOIN rev_calidad cal ON cal.cal_id = rev.cal_id
    WHERE rr.rr_ext_real != 0
      AND rev.rev_count_etiquetado > 0
    ORDER BY pres.pres_descrip, rev.rev_folio";
$res_data = q($cnx, $query_generales);

$calidad_data = [];
while ($row = mysqli_fetch_assoc($res_data)) {
    $calidad      = $row['cal_descripcion'];
    $presentacion = $row['pres_descrip'];
    $revoltura    = $row['revoltura'];
    $ext_real     = (float)$row['rr_ext_real'];

    // kg por unidad (seguro)
    $kg_por_unidad = $presentaciones_kg[$presentacion] ?? 0.0;

    $kg_totales = $ext_real * $kg_por_unidad;

    if (!isset($calidad_data[$calidad])) {
        $calidad_data[$calidad] = [];
    }
    if (!isset($calidad_data[$calidad][$presentacion])) {
        $calidad_data[$calidad][$presentacion] = [];
    }
    if (!isset($calidad_data[$calidad][$presentacion][$revoltura])) {
        $calidad_data[$calidad][$presentacion][$revoltura] = [
            'rev_id'         => (int)$row['rev_id'],
            'rev_bloom'      => $row['rev_bloom'],
            'rev_viscosidad' => $row['rev_viscosidad'],
            'ext_inicial'    => (float)$row['rr_ext_inicial'],
            'ext_real'       => 0.0,
            'kg'             => 0.0
        ];
    }

    $calidad_data[$calidad][$presentacion][$revoltura]['ext_real'] += $ext_real;
    $calidad_data[$calidad][$presentacion][$revoltura]['kg']       += $kg_totales;
}

// Totales por calidad y presentación
$totales_generales = [];
foreach ($presentaciones_list as $pres) {
    $presentacion = $pres['descrip'];
    foreach ($calidad_data as $calidad => $presentaciones) {
        if (!isset($totales_generales[$calidad])) {
            $totales_generales[$calidad] = [];
        }
        if (!isset($totales_generales[$calidad][$presentacion])) {
            $totales_generales[$calidad][$presentacion] = ['ext_real' => 0.0, 'kg' => 0.0];
        }

        if (isset($calidad_data[$calidad][$presentacion])) {
            foreach ($calidad_data[$calidad][$presentacion] as $rev_data) {
                $totales_generales[$calidad][$presentacion]['ext_real'] += (float)$rev_data['ext_real'];
                $totales_generales[$calidad][$presentacion]['kg']       += (float)$rev_data['kg'];
            }
        }
    }
}

// ==================================================
//  EXISTENCIAS POR CLIENTE
// ==================================================
$query_clientes = "SELECT
        rev.rev_folio as revoltura,
        rev.rev_id,
        cal.cal_descripcion,
        rev.rev_bloom,
        rev.rev_viscosidad,
        pres.pres_descrip,
        rrc.rrc_ext_inicial,
        rrc.rrc_ext_real,
        cte.cte_nombre
    FROM rev_revolturas_pt_cliente rrc
    INNER JOIN rev_revolturas rev ON rev.rev_id = rrc.rev_id
    INNER JOIN rev_presentacion pres ON pres.pres_id = rrc.pres_id
    INNER JOIN rev_calidad cal ON cal.cal_id = rev.cal_id
    INNER JOIN rev_clientes cte ON cte.cte_id = rrc.cte_id
    WHERE rrc.rrc_ext_real != 0
      AND rev.rev_count_etiquetado > 0
    ORDER BY cte.cte_nombre, pres.pres_descrip, rev.rev_folio";
$res_data2 = q($cnx, $query_clientes);

$cliente_data = [];
while ($row = mysqli_fetch_assoc($res_data2)) {
    $cliente      = $row['cte_nombre'];
    $presentacion = $row['pres_descrip'];
    $revoltura    = $row['revoltura'];
    $ext_real     = (float)$row['rrc_ext_real'];

    $kg_por_unidad = $presentaciones_kg[$presentacion] ?? 0.0;
    $kg_totales    = $ext_real * $kg_por_unidad;

    if (!isset($cliente_data[$cliente])) {
        $cliente_data[$cliente] = [];
    }
    if (!isset($cliente_data[$cliente][$presentacion])) {
        $cliente_data[$cliente][$presentacion] = [];
    }
    if (!isset($cliente_data[$cliente][$presentacion][$revoltura])) {
        $cliente_data[$cliente][$presentacion][$revoltura] = [
            'rev_id'         => (int)$row['rev_id'],
            'rev_bloom'      => $row['rev_bloom'],
            'rev_viscosidad' => $row['rev_viscosidad'],
            'cal_descripcion' => $row['cal_descripcion'],
            'ext_inicial'    => (float)$row['rrc_ext_inicial'],
            'ext_real'       => 0.0,
            'kg'             => 0.0
        ];
    }

    $cliente_data[$cliente][$presentacion][$revoltura]['ext_real'] += $ext_real;
    $cliente_data[$cliente][$presentacion][$revoltura]['kg']       += $kg_totales;
}

// Totales por cliente y presentación
$totales_clientes = [];
foreach ($cliente_data as $cliente => $presentaciones) {
    $totales_clientes[$cliente] = [];
    foreach ($presentaciones as $presentacion => $revolturas) {
        $totales_clientes[$cliente][$presentacion] = ['ext_real' => 0.0, 'kg' => 0.0];
        foreach ($revolturas as $rev_data) {
            $totales_clientes[$cliente][$presentacion]['ext_real'] += (float)$rev_data['ext_real'];
            $totales_clientes[$cliente][$presentacion]['kg']       += (float)$rev_data['kg'];
        }
    }
}

// ==================================================
//  PRODUCTO EXTERNO (GRENETINA HIDROLIZADA)
//  NOTA: pae_existenica_inicial por error es "existenica" en la BD, se mantiene así para evitar errores
// ==================================================
$query_externo = "SELECT
        pe.pe_id,
        pe.pres_id,
        pres.pres_descrip,
        pres.pres_kg,
        pe.pe_lote,
        pe.pe_existenica_inicial,
        pe.pe_existencia_real
    FROM producto_externo pe
    INNER JOIN rev_presentacion pres ON pres.pres_id = pe.pres_id
    ORDER BY pres.pres_descrip, pe.pe_lote";
$res_externo = q($cnx, $query_externo);

$producto_externo_data = [];
$totales_producto_externo = [];

while ($row = mysqli_fetch_assoc($res_externo)) {
    $presentacion  = $row['pres_descrip'];
    $kg_por_unidad = (float)$row['pres_kg'];
    $ext_real      = (float)$row['pe_existencia_real'];

    $kg_totales = $ext_real * $kg_por_unidad;

    if (!isset($producto_externo_data[$presentacion])) {
        $producto_externo_data[$presentacion] = [
            'kg_por_unidad' => $kg_por_unidad,
            'items' => []
        ];
    }

    $producto_externo_data[$presentacion]['items'][] = [
        'pe_id'       => (int)$row['pe_id'],
        'pres_id'     => (int)$row['pres_id'],
        'presentacion' => $presentacion,
        'lote'        => $row['pe_lote'],
        'ext_inicial' => (float)$row['pe_existenica_inicial'],
        'ext_real'    => $ext_real,
        'kg'          => $kg_totales
    ];

    if (!isset($totales_producto_externo[$presentacion])) {
        $totales_producto_externo[$presentacion] = ['ext_real' => 0.0, 'kg' => 0.0];
    }
    $totales_producto_externo[$presentacion]['ext_real'] += $ext_real;
    $totales_producto_externo[$presentacion]['kg']       += $kg_totales;
}

// ==================================================
//  FECHA EN ESPAÑOL
// ==================================================
$meses = [
    1 => 'enero',
    'febrero',
    'marzo',
    'abril',
    'mayo',
    'junio',
    'julio',
    'agosto',
    'septiembre',
    'octubre',
    'noviembre',
    'diciembre'
];
$dia  = date('d');
$mes  = $meses[(int)date('m')];
$anio = date('Y');
$fecha_formateada = "$dia-$mes-$anio";

// ==================================================
//  TOTAL GLOBAL (empacado) y subtotales
// ==================================================
$total_global = [
    'kg'      => 0.0,
    'general' => 0.0,
    'cliente' => 0.0,
    'externo' => 0.0
];

// Sumar existencias generales
foreach ($totales_generales as $calidad => $presentaciones) {
    foreach ($presentaciones as $presentacion => $datos) {
        $total_global['kg']      += (float)$datos['kg'];
        $total_global['general'] += (float)$datos['kg'];
    }
}

// Sumar existencias por cliente
foreach ($totales_clientes as $cliente => $presentaciones) {
    foreach ($presentaciones as $presentacion => $datos) {
        $total_global['kg']     += (float)$datos['kg'];
        $total_global['cliente'] += (float)$datos['kg'];
    }
}

// Sumar existencias de producto externo
foreach ($totales_producto_externo as $presentacion => $datos) {
    $total_global['kg']      += (float)$datos['kg'];
    $total_global['externo'] += (float)$datos['kg'];
}

// ==================================================
//  BARREDURA
// ==================================================
$query_barredura = "SELECT IFNULL(SUM(tar_kilos),0) AS barredura FROM rev_tarimas WHERE tar_estatus = 8";
$res_barredura = q($cnx, $query_barredura);
$total_barredura = mysqli_fetch_assoc($res_barredura);
$total_barredura['barredura'] = (float)($total_barredura['barredura'] ?? 0);

// ==================================================
//  SALIDAS (KILOS FACTURADOS) "DÍA ANTERIOR" 7:00-7:00
// ==================================================
$query_facturas = "SELECT
  SUM(
    CASE
      WHEN rp.pres_kg IS NOT NULL THEN f.fe_cantidad * rp.pres_kg
      WHEN rpc.pres_kg IS NOT NULL THEN f.fe_cantidad * rpc.pres_kg
      ELSE 0
    END
  ) AS total_kilos_facturados
FROM rev_revolturas_pt_facturas f
LEFT JOIN rev_revolturas_pt rr ON f.rr_id = rr.rr_id
LEFT JOIN rev_presentacion rp ON rr.pres_id = rp.pres_id
LEFT JOIN rev_revolturas_pt_cliente rrc ON f.rrc_id = rrc.rrc_id
LEFT JOIN rev_presentacion rpc ON rrc.pres_id = rpc.pres_id
WHERE f.fe_fecha >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) + INTERVAL 7 HOUR
  AND f.fe_fecha <  CURDATE() + INTERVAL 7 HOUR";
$res_facturas = q($cnx, $query_facturas);
$fila = mysqli_fetch_assoc($res_facturas);
$total_salidas = (float)($fila['total_kilos_facturados'] ?? 0);

// ==================================================
//  DEVOLUCIONES EN PROCESO
// ==================================================
$sql_devoluciones_proceso = "SELECT
    IFNULL(SUM(p.pres_kg * odd.cantidad), 0) AS total_kilos
FROM orden_devolucion_detalle odd
LEFT JOIN rev_revolturas_pt pt
    ON pt.rr_id = odd.id_empaque
    AND odd.tipo_empaque = 'rr'
LEFT JOIN rev_revolturas_pt_cliente ptc
    ON ptc.rrc_id = odd.id_empaque
    AND odd.tipo_empaque = 'rrc'
INNER JOIN rev_presentacion p
    ON p.pres_id = COALESCE(pt.pres_id, ptc.pres_id)
WHERE odd.estado_lote != 'LIBERADA'";
$res_dev = q($cnx, $sql_devoluciones_proceso);
$row_dev = mysqli_fetch_assoc($res_dev);
$total_devoluciones_proceso = (float)($row_dev['total_kilos'] ?? 0);

// ==================================================
//  JSON (ruta segura) - total empacado + barredura
// ==================================================
$total = $total_global['kg'] + $total_barredura['barredura'];
$ok = file_put_contents(__DIR__ . '/datos_existencias.json', json_encode(['kg' => $total]));
if ($ok === false) {
    error_log("No se pudo escribir datos_existencias.json");
}

mysqli_close($cnx);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de producto terminado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../js/jquery.min.js"></script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 15mm 10mm 25mm 10mm;
            }

            body {
                font-size: 11px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 210mm;
                margin: auto;
                padding: 10px;
            }

            h3 {
                font-size: 13px !important;
                margin-bottom: 3px !important;
            }

            .titulo {
                font-size: 12px !important;
                margin-bottom: 3px !important;
                text-align: center !important;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 8px;
                max-width: 190mm;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 0px;
                text-align: center;
                word-wrap: break-word;
                white-space: normal;
                line-height: 1.2;
                max-width: 82px;
            }

            th {
                background-color: #f2f2f2;
                font-size: 8px;
                white-space: normal;
            }

            .table-container {
                transform: scale(0.95);
                transform-origin: top left;
                width: 100%;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .print-button {
                display: none;
            }
        }

        h3 {
            font-size: 25px;
            margin-bottom: 3px;
        }

        .titulo {
            font-size: 20px;
            margin-bottom: 3px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row align-items-center p-3">
            <div class="col-md-2 text-center">
                <img src="../../imagenes/logo_progel_v3.png" alt="Logo Progel" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="col-md-10 text-center">
                <h2 class="fw-bold m-0">Producto terminado (Empacado)</h2>
                <span><?= htmlspecialchars($fecha_formateada) ?></span>
            </div>
        </div>

        <!-- EXISTENCIAS GENERALES -->
        <div class="container mb-4">
            <div class="print-area">
                <div class="container table-container">
                    <?php foreach ($calidad_data as $calidad => $presentaciones): ?>
                        <h3 style="color:#007bff;">Calidad: <?= htmlspecialchars($calidad) ?></h3>

                        <?php foreach ($presentaciones as $presentacion => $revolturas): ?>
                            <?php $kg_unidad = $presentaciones_kg[$presentacion] ?? 0; ?>
                            <h2 class="titulo">
                                Presentación: <?= htmlspecialchars($presentacion) ?> (<?= htmlspecialchars($kg_unidad) ?> kg)
                            </h2>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Revoltura</th>
                                        <th>Bloom</th>
                                        <th>Viscosidad</th>
                                        <th>Empaques Inicial</th>
                                        <th>Empaques Real</th>
                                        <th>Kilos</th>
                                        <th class="print-button">Detalle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($revolturas as $revoltura => $data): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($revoltura) ?></td>
                                            <td><?= htmlspecialchars($data['rev_bloom']) ?></td>
                                            <td><?= htmlspecialchars($data['rev_viscosidad']) ?></td>
                                            <td><?= htmlspecialchars($data['ext_inicial']) ?></td>
                                            <td><?= htmlspecialchars($data['ext_real']) ?></td>
                                            <td><?= htmlspecialchars(number_format((float)$data['kg'])) ?></td>
                                            <td class="print-button">
                                                <a href="../funciones/revolturas_detalle.php?rev_id=<?= htmlspecialchars($data['rev_id']) ?>"
                                                    target="_blank"
                                                    class="btn btn-primary btn-sm"
                                                    title="Ver detalle revoltura">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <tr>
                                        <td colspan="4" class="text-end fw-bold" style="font-size:20px">Total</td>
                                        <td style="font-size:20px" class="fw-bold">
                                            <?= number_format((float)$totales_generales[$calidad][$presentacion]['ext_real']) ?>
                                        </td>
                                        <td style="font-size:20px" class="fw-bold">
                                            <?= number_format((float)$totales_generales[$calidad][$presentacion]['kg']) ?>
                                        </td>
                                        <td class="print-button"></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- EXISTENCIAS POR CLIENTE -->
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color:#007bff; text-align:center;">Existencias de producto terminado por cliente</h3>
                <div class="container table-container">
                    <?php foreach ($cliente_data as $cliente => $presentaciones): ?>
                        <?php foreach ($presentaciones as $presentacion => $revolturas): ?>
                            <?php $kg_unidad = $presentaciones_kg[$presentacion] ?? 0; ?>

                            <h3 style="color:#007bff;">Cliente: <?= htmlspecialchars($cliente) ?></h3>
                            <h2 class="titulo">
                                Presentación: <?= htmlspecialchars($presentacion) ?> (<?= htmlspecialchars($kg_unidad) ?> kg)
                            </h2>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Revoltura</th>
                                        <th>Bloom</th>
                                        <th>Viscosidad</th>
                                        <th>Calidad</th>
                                        <th>Empaques Inicial</th>
                                        <th>Empaques Real</th>
                                        <th>Kilos</th>
                                        <th class="print-button">Detalle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($revolturas as $revoltura => $data): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($revoltura) ?></td>
                                            <td><?= htmlspecialchars($data['rev_bloom']) ?></td>
                                            <td><?= htmlspecialchars($data['rev_viscosidad']) ?></td>
                                            <td><?= htmlspecialchars($data['cal_descripcion']) ?></td>
                                            <td><?= htmlspecialchars($data['ext_inicial']) ?></td>
                                            <td><?= htmlspecialchars($data['ext_real']) ?></td>
                                            <td><?= htmlspecialchars(number_format((float)$data['kg'])) ?></td>
                                            <td class="print-button">
                                                <a href="/../funciones/revolturas_detalle.php?rev_id=<?= htmlspecialchars($data['rev_id']) ?>"
                                                    target="_blank"
                                                    class="btn btn-primary btn-sm"
                                                    title="Ver detalle revoltura">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <tr>
                                        <td colspan="5" class="text-end fw-bold" style="font-size:20px">Total</td>
                                        <td style="font-size:20px" class="fw-bold">
                                            <?= number_format((float)$totales_clientes[$cliente][$presentacion]['ext_real']) ?>
                                        </td>
                                        <td style="font-size:20px" class="fw-bold">
                                            <?= number_format((float)$totales_clientes[$cliente][$presentacion]['kg']) ?>
                                        </td>
                                        <td class="print-button"></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>


            <!-- PRODUCTO EXTERNO -->
            <div class="container mb-4">
                <div class="print-area">
                    <h3 style="color:#007bff;">GRENETINA HIDROLIZADA</h3>
                    <div class="container table-container">
                        <?php foreach ($producto_externo_data as $presentacion => $pack): ?>
                            <?php $items = $pack['items'];
                            $kg_u = (float)$pack['kg_por_unidad']; ?>

                            <h2 class="titulo">
                                Presentación: <?= htmlspecialchars($presentacion) ?> (<?= htmlspecialchars(number_format($kg_u)) ?> kg)
                            </h2>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Lote</th>
                                        <th>Empaques Inicial</th>
                                        <th>Empaques Real</th>
                                        <th>Kilos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['lote']) ?></td>
                                            <td><?= htmlspecialchars($row['ext_inicial']) ?></td>
                                            <td><?= htmlspecialchars($row['ext_real']) ?></td>
                                            <td><?= htmlspecialchars(number_format((float)$row['kg'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <tr>
                                        <td colspan="2" class="text-end fw-bold" style="font-size:20px">Total</td>
                                        <td style="font-size:20px" class="fw-bold">
                                            <?= number_format((float)$totales_producto_externo[$presentacion]['ext_real']) ?>
                                        </td>
                                        <td style="font-size:20px" class="fw-bold">
                                            <?= number_format((float)$totales_producto_externo[$presentacion]['kg']) ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div style="display:flex; align-items:center;">
                <h2 style="color:#007bff; margin:0;">Total empacado: <?= number_format((float)$total_global['kg']); ?></h2>
                <span class="text-danger" style="margin-left:10px;">*</span>
                <span style="margin-left:2px;">1</span>
            </div>

            <!-- RESUMEN -->
            <div class="card shadow-sm mt-5">
                <div class="card-header bg-primary text-white fw-bold">Resumen</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Salida del día anterior:</strong>
                            <span id="total-kilos-salida-resumen" class="fw-semibold"><?= number_format((float)$total_salidas) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Kilos pesada día anterior:</strong>
                            <span id="total-kilos-pesada-dia-resumen" class="fw-semibold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Por liberación de calidad:</strong>
                            <span id="total-kilos-proceso-analis-resumen" class="fw-semibold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Pendiente de liberación microbiologica:</strong>
                            <span id="total-pendiente-enviar-almacen-resumen" class="fw-semibold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Kilos barredura:</strong>
                            <span id="total-kilos-barredura-resumen" class="fw-semibold"><?= number_format((float)$total_barredura['barredura']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Kilos revolturas terminadas:</strong>
                            <span id="total-kilos-revolturas-resumen" class="fw-semibold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Kilos revolturas del día:</strong>
                            <span id="total-kilos-revolturas-dia-reumen" class="fw-semibold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Kilos disponibles:</strong>
                            <span id="total-kilos-disponibles-resumen" class="fw-semibold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>Kilos por empaque:</strong> <sup class="text-danger">*</sup><sup>1</sup></span>
                            <span id="total-kilos-empaques-resumen" class="fw-semibold"><?= number_format((float)$total_global['general']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Kilos por cliente:</strong> <sup class="text-danger">*</sup><sup>1</sup></span>
                            <span id="total-kilos-clientes-resumen" class="fw-semibold"><?= number_format((float)$total_global['cliente']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Grenetina Hidrolizada:</strong> <sup class="text-danger">*</sup><sup>1</sup></span>
                            <span id="total-kilos-externo-resumen" class="fw-semibold"><?= number_format((float)$total_global['externo']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Devoluciones en proceso:</strong></span>
                            <span id="total-kilos-devoluciones-resumen" class="fw-semibold"><?= number_format((float)$total_devoluciones_proceso); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Total general:</strong>
                            <span id="total-general-resumen" class="fw-bold" style="font-size:20px"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="../../assets/fontawesome/fontawesome.js"></script>
    <script>
        const formatter = new Intl.NumberFormat('en-US');

        $(document).ready(function() {
            let totalSinEmpacarKilos = 0;
            let totalEmpaques = <?= json_encode((float)$total_global['kg']); ?>;
            let totalKilosBarredura = <?= json_encode((float)$total_barredura['barredura']); ?>;
            let totalDevolucionesProceso = <?= json_encode((float)$total_devoluciones_proceso); ?>;
            let totalSalidas = <?= json_encode((float)$total_salidas); ?>;
            let totalGlobalResumen = 0;

            Promise.all([
                cargarTarimasPesadaDia(),
                cargarTarimasProcesoAnalisis(),
                cargarTarimasPendienteAlmacen(),
                cargarRevolturasTerminadas(),
                cargarRevolturasDia(),
                cargarTarimasDisponibles(),
            ]).then(totales => {
                totalSinEmpacarKilos = totales.reduce((acc, total) => acc + total, 0);
                totalGlobalResumen = totalSinEmpacarKilos + totalEmpaques + totalKilosBarredura + totalDevolucionesProceso;
                $('#total-general-resumen').text(formatter.format(totalGlobalResumen));
            }).catch(error => {
                console.error('Error al cargar los datos:', error);
            });
        });

        function cargarTarimasPesadaDia() {
            return new Promise((resolve, reject) => {
                let totalKilos = 0;
                $.ajax({
                    type: 'POST',
                    url: 'reporte_inventario.controller.php',
                    data: {
                        action: 'tarimas_pesada_dia'
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.error) {
                            alert('Error: ' + data.message);
                            reject(data.message);
                            return;
                        }
                        data.forEach(t => totalKilos += Number(t.tar_kilos));
                        $('#total-kilos-pesada-dia-resumen').text(formatter.format(totalKilos));
                        resolve(totalKilos);
                    },
                    error: function() {
                        alert('Error al cargar las tarimas.');
                        reject('Error al cargar las tarimas.');
                    }
                });
            });
        }

        function cargarTarimasProcesoAnalisis() {
            return new Promise((resolve, reject) => {
                let totalKilos = 0;
                $.ajax({
                    type: 'POST',
                    url: 'reporte_inventario.controller.php',
                    data: {
                        action: 'tarimas_proceso_analisis'
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.error) {
                            alert('Error: ' + data.message);
                            reject(data.message);
                            return;
                        }
                        data.forEach(t => totalKilos += Number(t.tar_kilos));
                        $('#total-kilos-proceso-analis-resumen').text(formatter.format(totalKilos));
                        resolve(totalKilos);
                    },
                    error: function() {
                        alert('Error al cargar las tarimas.');
                        reject('Error al cargar las tarimas.');
                    }
                });
            });
        }

        function cargarTarimasPendienteAlmacen() {
            return new Promise((resolve, reject) => {
                let totalKilos = 0;
                $.ajax({
                    type: 'POST',
                    url: 'reporte_inventario.controller.php',
                    data: {
                        action: 'tarimas_pendiente_enviar_almacen'
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.error) {
                            alert('Error: ' + data.message);
                            reject(data.message);
                            return;
                        }
                        data.forEach(t => totalKilos += Number(t.tar_kilos));
                        $('#total-pendiente-enviar-almacen-resumen').text(formatter.format(totalKilos));
                        resolve(totalKilos);
                    },
                    error: function() {
                        alert('Error al cargar las tarimas.');
                        reject('Error al cargar las tarimas.');
                    }
                });
            });
        }

        function cargarRevolturasTerminadas() {
            return new Promise((resolve, reject) => {
                let totalKilos = 0;
                $.ajax({
                    type: 'POST',
                    url: 'reporte_inventario.controller.php',
                    data: {
                        action: 'revolturas_terminadas'
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.error) {
                            alert('Error: ' + data.message);
                            reject(data.message);
                            return;
                        }
                        data.forEach(r => totalKilos += Number(r.rev_kilos));
                        $('#total-kilos-revolturas-resumen').text(formatter.format(totalKilos));
                        resolve(totalKilos);
                    },
                    error: function() {
                        alert('Error al cargar las revolturas.');
                        reject('Error al cargar las revolturas.');
                    }
                });
            });
        }

        function cargarRevolturasDia() {
            return new Promise((resolve, reject) => {
                let totalKilos = 0;
                $.ajax({
                    type: 'POST',
                    url: 'reporte_inventario.controller.php',
                    data: {
                        action: 'revolturas_dia'
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.error) {
                            alert('Error: ' + data.message);
                            reject(data.message);
                            return;
                        }
                        data.forEach(r => totalKilos += Number(r.rev_kilos));
                        $('#total-kilos-revolturas-dia-reumen').text(formatter.format(totalKilos));
                        resolve(totalKilos);
                    },
                    error: function() {
                        alert('Error al cargar las revolturas.');
                        reject('Error al cargar las revolturas.');
                    }
                });
            });
        }

        function cargarTarimasDisponibles() {
            return new Promise((resolve, reject) => {
                let totalKilos = 0;
                $.ajax({
                    type: 'POST',
                    url: 'reporte_inventario.controller.php',
                    data: {
                        action: 'tarimas_disponibles'
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.error) {
                            alert('Error: ' + data.message);
                            reject(data.message);
                            return;
                        }
                        data.forEach(t => totalKilos += Number(t.tar_kilos));
                        $('#total-kilos-disponibles-resumen').text(formatter.format(totalKilos));
                        resolve(totalKilos);
                    },
                    error: function() {
                        alert('Error al cargar las tarimas.');
                        reject('Error al cargar las tarimas.');
                    }
                });
            });
        }
    </script>
</body>

</html>
