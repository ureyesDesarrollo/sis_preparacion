<?php
// Desarrollado por: CCA Consultores TI 
// Contacto: contacto@ccaconsultoresti.com 
// Actualizado: Septiembre-2024
include "../../conexion/conexion.php";

$cnx = Conectarse();

// Consulta para obtener las presentaciones activas
$presentaciones = "SELECT * FROM rev_presentacion WHERE pres_estatus = 'A'";
$res_pres = mysqli_query($cnx, $presentaciones);

// Crear un arreglo con las presentaciones y sus datos
$presentaciones_list = [];
while ($row = mysqli_fetch_assoc($res_pres)) {
    $presentaciones_list[] = [
        'descrip' => $row['pres_descrip'],
        'kg' => $row['pres_kg']
    ];
}

// Consulta para obtener las existencias generales
$query = "SELECT 
        rev.rev_folio as revoltura,
        rev.rev_id,
        cal.cal_descripcion,
        rev.rev_bloom,
        rev.rev_viscosidad,
        pres.pres_descrip,
        rr.rr_ext_inicial,
        rr.rr_ext_real
    FROM 
        rev_revolturas_pt rr 
    INNER JOIN 
        rev_revolturas rev ON rev.rev_id = rr.rev_id 
    INNER JOIN 
        rev_presentacion pres ON pres.pres_id = rr.pres_id
    INNER JOIN rev_calidad cal ON cal.cal_id = rev.cal_id
   WHERE rr.rr_ext_real != 0 AND rev.rev_count_etiquetado > 0
    ORDER BY 
        pres.pres_descrip, rev.rev_folio";
$res_data = mysqli_query($cnx, $query);

$calidad_data = [];
while ($row = mysqli_fetch_assoc($res_data)) {
    $calidad = $row['cal_descripcion'];
    $presentacion = $row['pres_descrip'];
    $revoltura = $row['revoltura'];
    $ext_real = $row['rr_ext_real'];

    // Encontrar el valor de kg para la presentación actual
    $kg_por_unidad = $presentaciones_list[array_search(
        $presentacion,
        array_column($presentaciones_list, 'descrip')
    )]['kg'];

    // Calcular los kg totales para la cantidad de ext_real
    $kg_totales = $ext_real * $kg_por_unidad;

    // Inicializar el arreglo para la calidad si aún no existe
    if (!isset($calidad_data[$calidad])) {
        $calidad_data[$calidad] = [];
    }

    // Inicializar el arreglo para la presentación si aún no existe
    if (!isset($calidad_data[$calidad][$presentacion])) {
        $calidad_data[$calidad][$presentacion] = [];
    }

    // Inicializar el arreglo para la revoltura si aún no existe
    if (!isset($calidad_data[$calidad][$presentacion][$revoltura])) {
        $calidad_data[$calidad][$presentacion][$revoltura] = [
            'rev_id' => $row['rev_id'],
            'rev_bloom' => $row['rev_bloom'],
            'rev_viscosidad' => $row['rev_viscosidad'],
            'ext_inicial' => $row['rr_ext_inicial'],
            'ext_real' => 0,
            'kg' => 0
        ];
    }

    // Acumulando los datos de cantidad y kg para cada revoltura dentro de la presentación y calidad
    $calidad_data[$calidad][$presentacion][$revoltura]['ext_real'] += $ext_real;
    $calidad_data[$calidad][$presentacion][$revoltura]['kg'] += $kg_totales;
}

// Calcular los totales por calidad y presentación (existencias generales)
$totales_generales = [];
foreach ($presentaciones_list as $pres) {
    $presentacion = $pres['descrip'];
    foreach ($calidad_data as $calidad => $presentaciones) {
        if (!isset($totales_generales[$calidad])) {
            $totales_generales[$calidad] = [];
        }
        if (!isset($totales_generales[$calidad][$presentacion])) {
            $totales_generales[$calidad][$presentacion] = ['ext_real' => 0, 'kg' => 0];
        }

        // Sumar los datos acumulados para cada presentación dentro de la calidad
        if (isset($calidad_data[$calidad][$presentacion])) {
            foreach ($calidad_data[$calidad][$presentacion] as $rev_data) {
                $totales_generales[$calidad][$presentacion]['ext_real'] += $rev_data['ext_real'];
                $totales_generales[$calidad][$presentacion]['kg'] += $rev_data['kg'];
            }
        }
    }
}

// Nueva consulta para obtener los datos por cliente
$query_1 = "SELECT 
        rev.rev_folio as revoltura,
        rev.rev_id,
        cal.cal_descripcion,
        rev.rev_bloom,
        rev.rev_viscosidad,
        pres.pres_descrip,
        rrc.rrc_ext_inicial,
        rrc.rrc_ext_real,
        cte.cte_nombre
    FROM 
        rev_revolturas_pt_cliente rrc 
    INNER JOIN 
        rev_revolturas rev ON rev.rev_id = rrc.rev_id 
    INNER JOIN 
        rev_presentacion pres ON pres.pres_id = rrc.pres_id
    INNER JOIN rev_calidad cal ON cal.cal_id = rev.cal_id
    INNER JOIN rev_clientes cte ON cte.cte_id = rrc.cte_id
   WHERE rrc.rrc_ext_real != 0 AND rev.rev_count_etiquetado > 0
    ORDER BY 
        cte.cte_nombre, pres.pres_descrip, rev.rev_folio";
$res_data = mysqli_query($cnx, $query_1);

// Reorganizar los datos por cliente, luego por presentación y finalmente por revoltura
$cliente_data = [];
while ($row = mysqli_fetch_assoc($res_data)) {
    $cliente = $row['cte_nombre'];
    $presentacion = $row['pres_descrip'];
    $revoltura = $row['revoltura'];
    $ext_real = $row['rrc_ext_real'];

    // Encontrar el valor de kg para la presentación actual
    $kg_por_unidad = $presentaciones_list[array_search(
        $presentacion,
        array_column($presentaciones_list, 'descrip')
    )]['kg'];

    // Calcular los kg totales para la cantidad de ext_real
    $kg_totales = $ext_real * $kg_por_unidad;

    // Inicializar el arreglo para el cliente si aún no existe
    if (!isset($cliente_data[$cliente])) {
        $cliente_data[$cliente] = [];
    }

    // Inicializar el arreglo para la presentación si aún no existe
    if (!isset($cliente_data[$cliente][$presentacion])) {
        $cliente_data[$cliente][$presentacion] = [];
    }

    // Inicializar el arreglo para la revoltura si aún no existe
    if (!isset($cliente_data[$cliente][$presentacion][$revoltura])) {
        $cliente_data[$cliente][$presentacion][$revoltura] = [
            'rev_id' => $row['rev_id'],
            'rev_bloom' => $row['rev_bloom'],
            'rev_viscosidad' => $row['rev_viscosidad'],
            'cal_descripcion' => $row['cal_descripcion'],
            'ext_inicial' => $row['rrc_ext_inicial'],
            'ext_real' => 0,
            'kg' => 0
        ];
    }

    // Acumulando los datos de cantidad y kg para cada revoltura dentro de la presentación y cliente
    $cliente_data[$cliente][$presentacion][$revoltura]['ext_real'] += $ext_real;
    $cliente_data[$cliente][$presentacion][$revoltura]['kg'] += $kg_totales;
}

// Calcular los totales por cliente y presentación (existencias por cliente)
$totales_clientes = [];
foreach ($cliente_data as $cliente => $presentaciones) {
    $totales_clientes[$cliente] = [];
    foreach ($presentaciones as $presentacion => $revolturas) {
        $totales_clientes[$cliente][$presentacion] = ['ext_real' => 0, 'kg' => 0];

        // Sumar los datos acumulados para cada presentación dentro del cliente
        foreach ($revolturas as $rev_data) {
            $totales_clientes[$cliente][$presentacion]['ext_real'] += $rev_data['ext_real'];
            $totales_clientes[$cliente][$presentacion]['kg'] += $rev_data['kg'];
        }
    }
}


// ==========================================
//   NUEVA SECCIÓN: EXISTENCIA PRODUCTO EXTERNO
// ==========================================

// Consulta para obtener producto externo con su presentación
$query_externo = "
    SELECT 
        pe.pe_id,
        pe.pres_id,
        pres.pres_descrip,
        pres.pres_kg,
        pe.pe_lote,
        pe.pe_existenica_inicial,
        pe.pe_existencia_real
    FROM producto_externo pe
    INNER JOIN rev_presentacion pres ON pres.pres_id = pe.pres_id
    ORDER BY pres.pres_descrip, pe.pe_lote
";

$res_externo = mysqli_query($cnx, $query_externo);

$producto_externo_data = [];
$totales_producto_externo = [];

while ($row = mysqli_fetch_assoc($res_externo)) {

    $presentacion = $row['pres_descrip'];
    $kg_por_unidad = $row['pres_kg'];
    $ext_real = $row['pe_existencia_real'];

    // Calcular KG totales
    $kg_totales = $ext_real * $kg_por_unidad;

    // Si no existe la presentación, inicializarla
    if (!isset($producto_externo_data[$presentacion])) {
        $producto_externo_data[$presentacion] = [];
    }

    $producto_externo_data[$presentacion][] = [
        'pe_id' => $row['pe_id'],
        'pres_id' => $row['pres_id'],
        'presentacion' => $presentacion,
        'lote' => $row['pe_lote'],
        'ext_inicial' => $row['pe_existenica_inicial'],
        'ext_real' => $ext_real,
        'kg' => $kg_totales
    ];

    // Totales por presentación
    if (!isset($totales_producto_externo[$presentacion])) {
        $totales_producto_externo[$presentacion] = [
            'ext_real' => 0,
            'kg' => 0
        ];
    }

    $totales_producto_externo[$presentacion]['ext_real'] += $ext_real;
    $totales_producto_externo[$presentacion]['kg'] += $kg_totales;
}



// Obtener la fecha actual en formato español
$meses = array(
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
);

$dia = date('d');  // Día actual
$mes = $meses[(int)date('m')];  // Nombre del mes en español
$anio = date('Y');  // Año actual

$fecha_formateada = "$dia-$mes-$anio";  // Fecha en formato "día-mes-año"


// Calcular el total global
$total_global = ['kg' => 0, 'general' => 0, 'cliente' => 0];

// Sumar existencias generales
foreach ($totales_generales as $calidad => $presentaciones) {
    foreach ($presentaciones as $presentacion => $datos) {
        $total_global['kg'] += $datos['kg'];
        $total_global['general'] += $datos['kg'];
    }
}

// Sumar existencias por cliente
foreach ($totales_clientes as $cliente => $presentaciones) {
    foreach ($presentaciones as $presentacion => $datos) {
        $total_global['kg'] += $datos['kg'];
        $total_global['cliente'] += $datos['kg'];
    }
}

// Sumar existencias de producto externo
$total_global['externo'] = 0;

foreach ($totales_producto_externo as $presentacion => $datos) {
    $total_global['kg'] += $datos['kg'];
    $total_global['externo'] += $datos['kg'];
}


//Obtener los datos de barredura
$query_barredura = "SELECT SUM(tar_kilos) AS barredura FROM rev_tarimas WHERE pro_id <= 3 AND tar_estatus  = 8";

$res_barredura = mysqli_query($cnx, $query_barredura);

$total_barredura = mysqli_fetch_assoc($res_barredura);

$total_barredura['barredura'] = $total_barredura['barredura'] ?? 0;
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
    AND f.fe_fecha < CURDATE() + INTERVAL 7 HOUR";

$res_facturas = mysqli_query($cnx, $query_facturas);
$total_salidas = 0;
while ($fila = mysqli_fetch_assoc($res_facturas)) {
    $total_salidas += $fila['total_kilos_facturados'];
}

mysqli_close($cnx);

$total = $total_global['kg'] + $total_barredura['barredura'];
file_put_contents('datos_existencias.json', json_encode(['kg' => $total]));

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de producto terminado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../../js/jquery.min.js"></script>
    <style>
        @media print {
            @page {
                size: A4;
                /* Especificar tamaño de la hoja */
                margin: 15mm 10mm 25mm 10mm;
                /* Márgenes: superior, derecho, inferior, izquierdo */

                @top-right {
                    content: "Página " counter(page) " de " counter(pages);
                    font-size: 11px;
                }
            }

            body {
                font-size: 11px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 0;
                counter-reset: page;
                margin: 0;
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
                /* Escala la tabla al 95% de su tamaño */
                transform-origin: top left;
                /* Asegura que la escala comience desde la esquina superior izquierda */
                width: 100%;
            }

            thead {
                display: table-header-group;
                /* Asegura que los encabezados se repitan si la tabla se corta */
            }

            tbody {
                display: table-row-group;
            }

            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            tfoot {
                display: table-row-group;
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
                <span><?= $fecha_formateada ?></span>
            </div>
        </div>
        <!-- Sección de existencias generales -->
        <div class="container mb-4">
            <div class="print-area">
                <div class="container table-container">
                    <?php foreach ($calidad_data as $calidad => $presentaciones): ?>
                        <h3 style="color: #007bff;">Calidad: <?php echo htmlspecialchars($calidad); ?></h3>
                        <?php foreach ($presentaciones as $presentacion => $revolturas): ?>
                            <h2 class="titulo">Presentación: <?php echo htmlspecialchars($presentacion); ?> (<?= htmlspecialchars($presentaciones_list[array_search($presentacion, array_column($presentaciones_list, 'descrip'))]['kg']) ?> kg)</h2>
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
                                            <td><?php echo htmlspecialchars($revoltura); ?></td>
                                            <td><?php echo htmlspecialchars($data['rev_bloom']); ?></td>
                                            <td><?php echo htmlspecialchars($data['rev_viscosidad']); ?></td>
                                            <td><?php echo htmlspecialchars($data['ext_inicial']); ?></td>
                                            <td><?php echo htmlspecialchars($data['ext_real']); ?></td>
                                            <td><?php echo htmlspecialchars($data['kg']); ?></td>
                                            <td class="print-button">
                                                <a href="../funciones/revolturas_detalle.php?rev_id=<?= htmlspecialchars($data['rev_id']) ?>" target="_blank"
                                                    class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalle revoltura">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold" style="font-size: 20px">Total</td>
                                        <td style="font-size: 20px" class="fw-bold"><?php echo number_format($totales_generales[$calidad][$presentacion]['ext_real']); ?></td>
                                        <td style="font-size: 20px" class="fw-bold"><?php echo number_format($totales_generales[$calidad][$presentacion]['kg']); ?></td>
                                        <td class="print-button"></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Sección de existencias por cliente -->
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff; text-align: center;">Existencias de producto terminado por cliente</h3>
                <div class="container table-container">
                    <?php foreach ($cliente_data as $cliente => $presentaciones): ?>
                        <?php foreach ($presentaciones as $presentacion => $revolturas): ?>
                            <h3 style="color: #007bff;">Cliente: <?php echo htmlspecialchars($cliente); ?></h3>
                            <h2 class="titulo">Presentación: <?php echo htmlspecialchars($presentacion); ?> (<?= htmlspecialchars($presentaciones_list[array_search($presentacion, array_column($presentaciones_list, 'descrip'))]['kg']) ?> kg)</h2>
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
                                            <td><?php echo htmlspecialchars($revoltura); ?></td>
                                            <td><?php echo htmlspecialchars($data['rev_bloom']); ?></td>
                                            <td><?php echo htmlspecialchars($data['rev_viscosidad']); ?></td>
                                            <td><?php echo htmlspecialchars($data['cal_descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($data['ext_inicial']); ?></td>
                                            <td><?php echo htmlspecialchars($data['ext_real']); ?></td>
                                            <td><?php echo htmlspecialchars($data['kg']); ?></td>
                                            <td class="print-button">
                                                <a href="/../funciones/revolturas_detalle.php?rev_id=<?= htmlspecialchars($data['rev_id']) ?>" target="_blank"
                                                    class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalle revoltura">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold" style="font-size: 20px">Total</td>
                                        <td style="font-size: 20px" class="fw-bold"><?php echo number_format($totales_clientes[$cliente][$presentacion]['ext_real']); ?></td>
                                        <td style="font-size: 20px" class="fw-bold"><?php echo number_format($totales_clientes[$cliente][$presentacion]['kg']); ?></td>
                                        <td class="print-button"></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Sección de existencias de producto externo -->
            <div class="container mb-4">
                <div class="print-area">
                    <h3 style="color: #007bff;">GRENETINA HIDROLIZADA</h3>
                    <div class="container table-container">

                        <?php foreach ($producto_externo_data as $presentacion => $items): ?>
                            <h2 class="titulo">
                                Presentación: <?= htmlspecialchars($presentacion) ?>
                                (<?= htmlspecialchars($items[0]['kg'] / $items[0]['ext_real']) ?> kg)
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
                                            <td><?= htmlspecialchars(number_format($row['kg'], 2)) ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <!-- Fila de totales -->
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold" style="font-size: 20px">Total</td>
                                        <td style="font-size: 20px" class="fw-bold">
                                            <?= number_format($totales_producto_externo[$presentacion]['ext_real']) ?>
                                        </td>
                                        <td style="font-size: 20px" class="fw-bold">
                                            <?= number_format($totales_producto_externo[$presentacion]['kg']) ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: center;">
                <h2 style="color: #007bff; margin: 0;">Total empacado: <?php echo number_format($total_global['kg']); ?></h2>
                <span class="text-danger" style="margin-left: 10px;">*</span>
                <span style="margin-left: 2px;">1</span>
            </div>

            <div class="card shadow-sm mt-5">
                <div class="card-header bg-primary text-white fw-bold">
                    Resumen
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Salida del día anterior:</strong>
                            <span id="total-kilos-salida-resumen" class="fw-semibold"><?= number_format($total_salidas) ?></span>
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
                            <span id="total-kilos-barredura-resumen" class="fw-semibold"><?= number_format($total_barredura['barredura']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Kilos revolturas terminadas:</strong>
                            <span id="total-kilos-revolturas-resumen" class="fw-semibold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Kilos en revolvedora:</strong>
                            <span id="total-kilos-revolvedora-resumen" class="fw-semibold"></span>
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
                            <span>
                                <strong>Kilos por empaque:</strong>
                                <sup class="text-danger">*</sup><sup>1</sup>
                            </span>
                            <span id="total-kilos-empaques-resumen" class="fw-semibold"><?= number_format($total_global['general']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>
                                <strong>Kilos por cliente:</strong>
                                <sup class="text-danger">*</sup><sup>1</sup>
                            </span>
                            <span id="total-kilos-clientes-resumen" class="fw-semibold"><?= number_format($total_global['cliente']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>
                                <strong>Grenetina Hidrolizada:</strong>
                                <sup class="text-danger">*</sup><sup>1</sup>
                            </span>
                            <span id="total-kilos-clientes-resumen" class="fw-semibold"><?= number_format($total_global['externo']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Total general:</strong>
                            <span id="total-general-resumen" class="fw-bold" style="font-size: 20px"></span>
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
            let totalEmpaques = <?= $total_global['kg']; ?>;
            let totalKilosBarredura = <?= $total_barredura['barredura']; ?>;
            let totalSalidas = <?= $total_salidas ?>;
            let totalGlobalResumen = 0;
            Promise.all([
                cargarTarimasPesadaDia(),
                cargarTarimasProcesoAnalisis(),
                cargarTarimasPendienteAlmacen(),
                cargarRevolturasTerminadas(),
                cargarTarimasRevolvedora(),
                cargarRevolturasDia(),
                cargarTarimasDisponibles(),
            ]).then(totales => {
                totalSinEmpacarKilos = totales.reduce((acc, total) => acc + total, 0);
                totalGlobalResumen = totalSinEmpacarKilos + totalEmpaques + totalKilosBarredura;
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
                        data.forEach((tarima, index) => totalKilos += Number(tarima.tar_kilos));

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

                        data.forEach((tarima, index) => totalKilos += Number(tarima.tar_kilos));
                        resolve(totalKilos);
                        $('#total-kilos-proceso-analis-resumen').text(formatter.format(totalKilos));
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

                        data.forEach((tarima, index) => totalKilos += Number(tarima.tar_kilos));
                        resolve(totalKilos);
                        $('#total-pendiente-enviar-almacen-resumen').text(formatter.format(totalKilos));
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

                        data.forEach((revoltura, index) => totalKilos += Number(revoltura.rev_kilos));

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

        function cargarTarimasRevolvedora() {
            return new Promise((resolve, reject) => {
                let totalKilos = 0;
                $.ajax({
                    type: 'POST',
                    url: 'reporte_inventario.controller.php',
                    data: {
                        action: 'tarimas_revolvedora'
                    },
                    success: function(response) {
                        const data = JSON.parse(response);

                        if (data.error) {
                            alert('Error: ' + data.message);
                            reject(data.message);
                            return;
                        }

                        data.forEach((tarima, index) => totalKilos += Number(tarima.tar_kilos));

                        $('#total-kilos-revolvedora-resumen').text(formatter.format(totalKilos));
                        resolve(totalKilos);
                    },
                    error: function() {
                        alert('Error al cargar las tarimas.');
                        reject('Error al cargar las tarimas.');
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

                        data.forEach((revoltura, index) => totalKilos += Number(revoltura.rev_kilos));

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
                        data.forEach((tarima, index) => totalKilos += Number(tarima.tar_kilos));
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