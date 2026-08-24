<?php
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";


$cnx = Conectarse();

$reporte_id = isset($_GET['reporte_id']) ? (int)$_GET['reporte_id'] : 0;

if ($reporte_id <= 0) {
    die('Reporte inválido');
}

/*
 * Datos principales del reporte
 */
$sql_reporte = "SELECT 
                    reporte_id,
                    titulo,
                    fecha,
                    observaciones
                FROM lab_reportes
                WHERE reporte_id = $reporte_id
                LIMIT 1";

$res_reporte = mysqli_query($cnx, $sql_reporte);
$reporte = mysqli_fetch_assoc($res_reporte);

if (!$reporte) {
    die('Reporte no encontrado');
}

/*
 * Parámetros activos
 */
$parametros = array();

$sql_parametros = "SELECT 
                        parametro_id,
                        clave,
                        nombre,
                        unidad,
                        decimales,
                        orden
                   FROM lab_parametros
                   WHERE activo = 1
                   ORDER BY orden ASC";

$res_parametros = mysqli_query($cnx, $sql_parametros);

while ($fila = mysqli_fetch_assoc($res_parametros)) {
    $parametros[] = $fila;
}

/*
 * Límites del reporte
 */
$limites = array();

$sql_limites = "SELECT 
                    l.parametro_id,
                    l.limite_texto,
                    l.valor_min,
                    l.valor_max
                FROM lab_reporte_limites l
                WHERE l.reporte_id = $reporte_id";

$res_limites = mysqli_query($cnx, $sql_limites);

while ($fila = mysqli_fetch_assoc($res_limites)) {
    $limites[$fila['parametro_id']] = $fila;
}

/*
 * Muestras del reporte
 */
$muestras = array();

$sql_muestras = "SELECT
                    rm.reporte_muestra_id,
                    rm.orden,
                    m.muestra_id,
                    COALESCE(rm.nombre_muestra, m.nombre) AS nombre,
                    m.tipo,
                    COALESCE(ma.nombre, 'PENDIENTE') AS marca_nombre
                 FROM lab_reporte_muestras rm
                 INNER JOIN lab_muestras m
                    ON rm.muestra_id = m.muestra_id
                 LEFT JOIN lab_marcas ma
                    ON rm.marca_id = ma.marca_id
                 WHERE rm.reporte_id = $reporte_id
                 ORDER BY rm.orden ASC, rm.reporte_muestra_id ASC";

$res_muestras = mysqli_query($cnx, $sql_muestras);

while ($fila = mysqli_fetch_assoc($res_muestras)) {
    $fila['resultados'] = array();

    $fila['comportamiento'] = array(
        'FUERA' => array(
            'COLORANTE' => null,
            'NATURAL' => null
        ),
        'DENTRO' => array(
            'COLORANTE' => null,
            'NATURAL' => null
        )
    );

    $muestras[$fila['reporte_muestra_id']] = $fila;
}

/*
 * Resultados
 */
if (!empty($muestras)) {
    $ids_reporte_muestra = implode(',', array_keys($muestras));

    $sql_resultados = "SELECT
                            resultado_id,
                            reporte_muestra_id,
                            parametro_id,
                            valor,
                            fuera_limite
                       FROM lab_resultados
                       WHERE reporte_muestra_id IN ($ids_reporte_muestra)";

    $res_resultados = mysqli_query($cnx, $sql_resultados);

    while ($fila = mysqli_fetch_assoc($res_resultados)) {
        $reporte_muestra_id = $fila['reporte_muestra_id'];
        $parametro_id = $fila['parametro_id'];

        $muestras[$reporte_muestra_id]['resultados'][$parametro_id] = $fila;
    }
}

/*
 * Comportamiento
 */
if (!empty($muestras)) {
    $ids_reporte_muestra = implode(',', array_keys($muestras));

    $sql_comportamiento = "SELECT
                                reporte_muestra_id,
                                condicion,
                                aspecto,
                                valor
                           FROM lab_comportamiento
                           WHERE reporte_muestra_id IN ($ids_reporte_muestra)";

    $res_comportamiento = mysqli_query($cnx, $sql_comportamiento);

    while ($fila = mysqli_fetch_assoc($res_comportamiento)) {
        $reporte_muestra_id = $fila['reporte_muestra_id'];
        $condicion = $fila['condicion'];
        $aspecto = $fila['aspecto'];

        if (
            isset($muestras[$reporte_muestra_id]) &&
            isset($muestras[$reporte_muestra_id]['comportamiento'][$condicion]) &&
            array_key_exists($aspecto, $muestras[$reporte_muestra_id]['comportamiento'][$condicion])
        ) {
            $muestras[$reporte_muestra_id]['comportamiento'][$condicion][$aspecto] = $fila['valor'];
        }
    }
}

/*
 * Catálogo color / olor
 */
$catalogo_color_olor = array();

$sql_catalogo = "SELECT
                    descripcion,
                    cal_color,
                    cal_olor
                 FROM lab_catalogo_color_olor
                 WHERE activo = 1
                 ORDER BY cal_color ASC";

$res_catalogo = mysqli_query($cnx, $sql_catalogo);

while ($fila = mysqli_fetch_assoc($res_catalogo)) {
    $catalogo_color_olor[] = $fila;
}

function formato_numero_prueba($valor, $decimales)
{
    if ($valor === null || $valor === '') {
        return '';
    }

    return number_format((float)$valor, (int)$decimales, '.', '');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Prueba de comportamiento <?php echo htmlspecialchars($reporte['reporte_id']); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


    <style>
        body {
            font-size: 12px;
            background: #ffffff;
        }

        .titulo-reporte {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitulo-reporte {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            font-size: 11px;
        }

        th {
            text-align: center;
            vertical-align: middle !important;
            background: #f2f2f2;
        }

        td {
            vertical-align: middle !important;
        }

        .valor-fuera-limite {
            background: #f8d7da !important;
            color: #842029 !important;
            font-weight: bold;
        }

        .tabla-principal th,
        .tabla-principal td {
            white-space: nowrap;
        }

        .seccion-titulo {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 8px;
            font-size: 14px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 10px;
            }

            table {
                font-size: 9px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-3 mb-3">

        <div class="no-print mb-3 text-end">
            <button class="btn btn-secondary btn-sm" onclick="window.close();">
                Cerrar
            </button>
        </div>

        <div class="titulo-reporte">
            <?php echo htmlspecialchars($reporte['titulo']); ?>
        </div>

        <div class="subtitulo-reporte">
            Fecha: <?php echo htmlspecialchars($reporte['fecha']); ?>
            <?php if (!empty($reporte['observaciones'])) { ?>
                <br>
                Observaciones: <?php echo htmlspecialchars($reporte['observaciones']); ?>
            <?php } ?>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm tabla-principal">
                <thead>
                    <tr>
                        <th>Muestra / Lote</th>
                        <th>Marca</th>

                        <?php foreach ($parametros as $parametro) { ?>
                            <th>
                                <?php echo htmlspecialchars($parametro['nombre']); ?>
                                <?php if (!empty($parametro['unidad'])) { ?>
                                    <br>
                                    <small><?php echo htmlspecialchars($parametro['unidad']); ?></small>
                                <?php } ?>
                            </th>
                        <?php } ?>

                        <th>Colorante fuera<br><small>Fuera refrigerador</small></th>
                        <th>Natural fuera<br><small>Fuera refrigerador</small></th>
                        <th>Colorante refrigeración<br><small>Refrigeración</small></th>
                        <th>Natural refrigeración<br><small>Refrigeración</small></th>
                    </tr>

                    <tr>
                        <th>Límites</th>
                        <th></th>

                        <?php foreach ($parametros as $parametro) { ?>
                            <th>
                                <?php
                                $parametro_id = $parametro['parametro_id'];

                                if (isset($limites[$parametro_id])) {
                                    echo htmlspecialchars($limites[$parametro_id]['limite_texto']);
                                }
                                ?>
                            </th>
                        <?php } ?>

                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($muestras as $muestra) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($muestra['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($muestra['marca_nombre']); ?></td>

                            <?php foreach ($parametros as $parametro) { ?>
                                <?php
                                $parametro_id = $parametro['parametro_id'];
                                $valor = '';
                                $fuera_limite = 0;

                                if (isset($muestra['resultados'][$parametro_id])) {
                                    $valor = formato_numero_prueba(
                                        $muestra['resultados'][$parametro_id]['valor'],
                                        $parametro['decimales']
                                    );

                                    $fuera_limite = (int)$muestra['resultados'][$parametro_id]['fuera_limite'];
                                }
                                ?>

                                <td class="text-end <?php echo $fuera_limite == 1 ? 'valor-fuera-limite' : ''; ?>">
                                    <?php echo htmlspecialchars($valor); ?>
                                </td>
                            <?php } ?>

                            <td class="text-end">
                                <?php echo formato_numero_prueba($muestra['comportamiento']['FUERA']['COLORANTE'], 0); ?>
                            </td>

                            <td class="text-end">
                                <?php echo formato_numero_prueba($muestra['comportamiento']['FUERA']['NATURAL'], 0); ?>
                            </td>

                            <td class="text-end">
                                <?php echo formato_numero_prueba($muestra['comportamiento']['DENTRO']['COLORANTE'], 0); ?>
                            </td>

                            <td class="text-end">
                                <?php echo formato_numero_prueba($muestra['comportamiento']['DENTRO']['NATURAL'], 0); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="seccion-titulo">Catálogo color / olor</div>

                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Cal. Color</th>
                            <th>Cal. Olor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($catalogo_color_olor as $item) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['descripcion']); ?></td>
                                <td class="text-end"><?php echo formato_numero_prueba($item['cal_color'], 0); ?></td>
                                <td class="text-end"><?php echo formato_numero_prueba($item['cal_olor'], 0); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-7">
                <div class="seccion-titulo">Comportamiento</div>

                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th rowspan="2">Muestra / Lote</th>
                            <th colspan="2">Fuera del refrigerador</th>
                            <th colspan="2">Refrigeración</th>
                        </tr>
                        <tr>
                            <th>Color</th>
                            <th>Sabor</th>
                            <th>Color</th>
                            <th>Sabor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($muestras as $muestra) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($muestra['nombre']); ?></td>

                                <td class="text-end">
                                    <?php echo formato_numero_prueba($muestra['comportamiento']['FUERA']['COLORANTE'], 0); ?>
                                </td>

                                <td class="text-end">
                                    <?php echo formato_numero_prueba($muestra['comportamiento']['FUERA']['NATURAL'], 0); ?>
                                </td>

                                <td class="text-end">
                                    <?php echo formato_numero_prueba($muestra['comportamiento']['DENTRO']['COLORANTE'], 0); ?>
                                </td>

                                <td class="text-end">
                                    <?php echo formato_numero_prueba($muestra['comportamiento']['DENTRO']['NATURAL'], 0); ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>

</html>