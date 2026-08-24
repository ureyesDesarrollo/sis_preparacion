<?php

include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Método no permitido');
    }

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data) {
        throw new Exception('JSON inválido');
    }

    $titulo = trim($data['titulo'] ?? '');
    $fecha = trim($data['fecha'] ?? '');
    $observaciones = trim($data['observaciones'] ?? '');

    if ($titulo === '') {
        throw new Exception('El título es obligatorio');
    }

    if ($fecha === '') {
        throw new Exception('La fecha es obligatoria');
    }

    if (empty($data['muestras']) || !is_array($data['muestras'])) {
        throw new Exception('Debe capturar al menos una muestra');
    }

    mysqli_begin_transaction($cnx);

    /*
     * 1. Insertar reporte principal
     */
    $sql_reporte = "INSERT INTO lab_reportes 
                    (
                        titulo,
                        fecha,
                        observaciones
                    )
                    VALUES (?, ?, ?)";

    $stmt_reporte = mysqli_prepare($cnx, $sql_reporte);
    mysqli_stmt_bind_param($stmt_reporte, "sss", $titulo, $fecha, $observaciones);
    mysqli_stmt_execute($stmt_reporte);

    $reporte_id = mysqli_insert_id($cnx);

    /*
     * 2. Consultar parámetros activos.
     * Aquí también traemos los límites default para copiarlos al reporte.
     */
    $parametros = array();

    $sql_parametros = "SELECT 
                            parametro_id,
                            clave,
                            limite_texto_default,
                            valor_min_default,
                            valor_max_default
                       FROM lab_parametros
                       WHERE activo = 1";

    $res_parametros = mysqli_query($cnx, $sql_parametros);

    while ($fila_parametro = mysqli_fetch_assoc($res_parametros)) {
        $parametros[$fila_parametro['clave']] = array(
            'parametro_id' => (int)$fila_parametro['parametro_id'],
            'limite_texto' => $fila_parametro['limite_texto_default'],
            'valor_min' => $fila_parametro['valor_min_default'],
            'valor_max' => $fila_parametro['valor_max_default']
        );
    }

    /*
     * 3. Insertar límites automáticos del reporte.
     * Se copian desde lab_parametros para conservar histórico.
     */
    $limites_guardados = array();

    $sql_limite = "INSERT INTO lab_reporte_limites
                   (
                        reporte_id,
                        parametro_id,
                        limite_texto,
                        valor_min,
                        valor_max
                   )
                   VALUES (?, ?, ?, ?, ?)";

    $stmt_limite = mysqli_prepare($cnx, $sql_limite);

    foreach ($parametros as $clave => $parametro) {
        $parametro_id = $parametro['parametro_id'];
        $limite_texto = $parametro['limite_texto'] ?? '';

        $valor_min = null;
        $valor_max = null;

        if ($parametro['valor_min'] !== null && $parametro['valor_min'] !== '') {
            $valor_min = (float)$parametro['valor_min'];
        }

        if ($parametro['valor_max'] !== null && $parametro['valor_max'] !== '') {
            $valor_max = (float)$parametro['valor_max'];
        }

        if ($limite_texto === '' && $valor_min === null && $valor_max === null) {
            continue;
        }

        mysqli_stmt_bind_param(
            $stmt_limite,
            "iisdd",
            $reporte_id,
            $parametro_id,
            $limite_texto,
            $valor_min,
            $valor_max
        );

        mysqli_stmt_execute($stmt_limite);

        $limites_guardados[$clave] = array(
            'valor_min' => $valor_min,
            'valor_max' => $valor_max
        );
    }

    /*
     * 4. Preparar statements para muestras, resultados y comportamiento
     */
    $stmt_buscar_muestra = mysqli_prepare(
        $cnx,
        "SELECT muestra_id FROM lab_muestras WHERE nombre = ? LIMIT 1"
    );

    $stmt_insert_muestra = mysqli_prepare(
        $cnx,
        "INSERT INTO lab_muestras (nombre, tipo) VALUES (?, ?)"
    );

    $stmt_reporte_muestra = mysqli_prepare(
        $cnx,
        "INSERT INTO lab_reporte_muestras
     (
        reporte_id,
        muestra_id,
        nombre_muestra,
        orden,
        marca_estatus
     )
     VALUES (?, ?, ?, ?, 'PENDIENTE')"
    );

    $stmt_resultado = mysqli_prepare(
        $cnx,
        "INSERT INTO lab_resultados
         (
            reporte_muestra_id,
            parametro_id,
            valor,
            fuera_limite
         )
         VALUES (?, ?, ?, ?)"
    );

    $stmt_comportamiento = mysqli_prepare(
        $cnx,
        "INSERT INTO lab_comportamiento
         (
            reporte_muestra_id,
            condicion,
            aspecto,
            valor
         )
         VALUES (?, ?, ?, ?)"
    );

    /*
     * 5. Insertar muestras, resultados y comportamiento
     */
    foreach ($data['muestras'] as $muestra) {
        $nombre_muestra = trim($muestra['nombre'] ?? '');
        $tipo_muestra = trim($muestra['tipo'] ?? 'OTRO');
        $orden = (int)($muestra['orden'] ?? 1);

        if ($nombre_muestra === '') {
            continue;
        }

        $tipos_permitidos = array('TESTIGO', 'REGIA', 'DUCHE', 'WILSON', 'OTRO');

        if (!in_array($tipo_muestra, $tipos_permitidos)) {
            $tipo_muestra = 'OTRO';
        }

        /*
         * Buscar muestra existente
         */
        mysqli_stmt_bind_param($stmt_buscar_muestra, "s", $nombre_muestra);
        mysqli_stmt_execute($stmt_buscar_muestra);

        $res_muestra = mysqli_stmt_get_result($stmt_buscar_muestra);

        if ($fila_muestra = mysqli_fetch_assoc($res_muestra)) {
            $muestra_id = (int)$fila_muestra['muestra_id'];
        } else {
            mysqli_stmt_bind_param($stmt_insert_muestra, "ss", $nombre_muestra, $tipo_muestra);
            mysqli_stmt_execute($stmt_insert_muestra);

            $muestra_id = mysqli_insert_id($cnx);
        }

        /*
         * Relacionar muestra con reporte
         */
        mysqli_stmt_bind_param(
            $stmt_reporte_muestra,
            "iisi",
            $reporte_id,
            $muestra_id,
            $nombre_muestra,
            $orden
        );

        mysqli_stmt_execute($stmt_reporte_muestra);

        $reporte_muestra_id = mysqli_insert_id($cnx);

        /*
         * Insertar resultados de laboratorio
         */
        if (!empty($muestra['resultados']) && is_array($muestra['resultados'])) {
            foreach ($muestra['resultados'] as $clave => $valor) {
                if (!isset($parametros[$clave])) {
                    continue;
                }

                if ($valor === '' || $valor === null) {
                    continue;
                }

                $parametro_id = $parametros[$clave]['parametro_id'];
                $valor_num = (float)$valor;

                $fuera_limite = calcular_fuera_limite_prueba(
                    $clave,
                    $valor_num,
                    $limites_guardados
                );

                mysqli_stmt_bind_param(
                    $stmt_resultado,
                    "iidi",
                    $reporte_muestra_id,
                    $parametro_id,
                    $valor_num,
                    $fuera_limite
                );

                mysqli_stmt_execute($stmt_resultado);
            }
        }

        /*
         * Insertar comportamiento
         */
        if (!empty($muestra['comportamiento']) && is_array($muestra['comportamiento'])) {
            $comportamientos = array(
                array(
                    'condicion' => 'FUERA',
                    'aspecto' => 'COLORANTE',
                    'valor' => $muestra['comportamiento']['fuera']['colorante'] ?? null
                ),
                array(
                    'condicion' => 'FUERA',
                    'aspecto' => 'NATURAL',
                    'valor' => $muestra['comportamiento']['fuera']['natural'] ?? null
                ),
                array(
                    'condicion' => 'DENTRO',
                    'aspecto' => 'COLORANTE',
                    'valor' => $muestra['comportamiento']['dentro']['colorante'] ?? null
                ),
                array(
                    'condicion' => 'DENTRO',
                    'aspecto' => 'NATURAL',
                    'valor' => $muestra['comportamiento']['dentro']['natural'] ?? null
                )
            );

            foreach ($comportamientos as $item_comportamiento) {
                if ($item_comportamiento['valor'] === null || $item_comportamiento['valor'] === '') {
                    continue;
                }

                $condicion = $item_comportamiento['condicion'];
                $aspecto = $item_comportamiento['aspecto'];
                $valor_comportamiento = (float)$item_comportamiento['valor'];

                mysqli_stmt_bind_param(
                    $stmt_comportamiento,
                    "issd",
                    $reporte_muestra_id,
                    $condicion,
                    $aspecto,
                    $valor_comportamiento
                );

                mysqli_stmt_execute($stmt_comportamiento);
            }
        }
    }

    mysqli_commit($cnx);

    echo json_encode(array(
        'success' => true,
        'message' => 'Prueba guardada correctamente',
        'reporte_id' => $reporte_id
    ));
} catch (Throwable $e) {
    if (isset($cnx)) {
        mysqli_rollback($cnx);
    }

    http_response_code(500);

    echo json_encode(array(
        'success' => false,
        'message' => $e->getMessage()
    ));
}

function calcular_fuera_limite_prueba($clave, $valor, $limites)
{
    if (!isset($limites[$clave])) {
        return 0;
    }

    $valor_min = $limites[$clave]['valor_min'];
    $valor_max = $limites[$clave]['valor_max'];

    if ($valor_min !== null && $valor < (float)$valor_min) {
        return 1;
    }

    if ($valor_max !== null && $valor > (float)$valor_max) {
        return 1;
    }

    return 0;
}
