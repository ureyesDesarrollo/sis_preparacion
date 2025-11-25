<?php
include "../../conexion/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tarimas_pesada_dia') {
    try {
        // Conexión a la base de datos
        $cnx = Conectarse();

        if (!$cnx) {
            throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        $sql = "SELECT 
                t.pro_id,
                t.pro_id_2,
                t.tar_folio,
                t.tar_fino,
                DATE_FORMAT(t.tar_fecha, '%d/%m/%y') AS tar_fecha,
                ROUND(t.tar_kilos) AS tar_kilos,
                ROUND(t.tar_bloom) AS tar_bloom,
                t.tar_viscosidad,
                t.tar_ph,
                t.tar_humedad,
                t.tar_cenizas,
                ROUND(t.tar_trans) AS tar_trans,
                ROUND(t.tar_color) AS tar_color,
                t.tar_malla_30,
                ROUND(t.tar_malla_45) AS tar_malla_45,
                ROUND(t.tar_pe_1kg) AS tar_pe_1kg,
                ROUND(t.tar_par_extr) AS tar_par_extr,
                t.tar_redox,
                c.cal_descripcion,
                c.cal_color
            FROM rev_tarimas t
            LEFT JOIN rev_calidad c ON c.cal_id = t.cal_id
            WHERE t.tar_estatus = 0
            AND t.tar_count_etiquetado > 0 
            AND t.tar_fecha >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) + INTERVAL 7 HOUR  -- Desde las 7:00 AM del día anterior
            AND t.tar_fecha < CURDATE() + INTERVAL 7 HOUR  -- Hasta las 7:00 AM del día actual
            AND t.pro_id NOT IN (1,2,3)
            ORDER BY t.tar_bloom DESC";

        $resultado = mysqli_query($cnx, $sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
        }

        $tarimas_disponibles = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $tarimas_disponibles[] = $fila;
        }

        mysqli_close($cnx);

        echo json_encode($tarimas_disponibles, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        // Respuesta en caso de error
        echo json_encode([
            "error" => true,
            "message" => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tarimas_proceso_analisis') {
    try {
        // Conexión a la base de datos
        $cnx = Conectarse();

        if (!$cnx) {
            throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        $sql = "SELECT 
                t.pro_id,
                t.tar_folio,
                t.pro_id_2,
                t.tar_fino,
                DATE_FORMAT(t.tar_fecha, '%d/%m/%y') AS tar_fecha,
                ROUND(t.tar_kilos) AS tar_kilos,
                ROUND(t.tar_bloom) AS tar_bloom,
                t.tar_viscosidad,
                t.tar_ph,
                t.tar_humedad,
                t.tar_cenizas,
                ROUND(t.tar_trans) AS tar_trans,
                ROUND(t.tar_color) AS tar_color,
                t.tar_malla_30,
                ROUND(t.tar_malla_45) AS tar_malla_45,
                ROUND(t.tar_pe_1kg) AS tar_pe_1kg,
                ROUND(t.tar_par_extr) AS tar_par_extr,
                t.tar_redox,
                c.cal_descripcion,
                c.cal_color,
                c.cal_id
            FROM rev_tarimas t
            LEFT JOIN rev_calidad c ON c.cal_id = t.cal_id
            WHERE t.tar_estatus = 0 AND t.tar_count_etiquetado > 0
            AND t.tar_fecha < DATE_SUB(CURDATE(), INTERVAL 1 DAY) + INTERVAL 7 HOUR
            AND (t.cal_id IS NULL OR t.cal_id = '') 
            AND t.pro_id NOT IN (0,1,2,3) ORDER BY t.tar_folio DESC;
        ";

        $resultado = mysqli_query($cnx, $sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
        }

        $tarimas_disponibles = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $tarimas_disponibles[] = $fila;
        }

        mysqli_close($cnx);

        echo json_encode($tarimas_disponibles, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        // Respuesta en caso de error
        echo json_encode([
            "error" => true,
            "message" => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tarimas_pendiente_enviar_almacen') {
    try {
        // Conexión a la base de datos
        $cnx = Conectarse();

        if (!$cnx) {
            throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        $sql = "SELECT 
                t.pro_id,
                t.tar_folio,
                t.pro_id_2,
                t.tar_fino,
                DATE_FORMAT(t.tar_fecha, '%d/%m/%y') AS tar_fecha,
                ROUND(t.tar_kilos) AS tar_kilos,
                ROUND(t.tar_bloom) AS tar_bloom,
                t.tar_viscosidad,
                t.tar_ph,
                t.tar_humedad,
                t.tar_cenizas,
                ROUND(t.tar_trans) AS tar_trans,
                ROUND(t.tar_color) AS tar_color,
                t.tar_malla_30,
                ROUND(t.tar_malla_45) AS tar_malla_45,
                ROUND(t.tar_pe_1kg) AS tar_pe_1kg,
                ROUND(t.tar_par_extr) AS tar_par_extr,
                t.tar_redox,
                c.cal_descripcion,
                c.cal_color
            FROM rev_tarimas t
            INNER JOIN rev_calidad c ON c.cal_id = t.cal_id
            WHERE t.tar_estatus = 0 
            AND t.tar_count_etiquetado > 0 
            AND (
                t.pro_id NOT IN (0,1,2,3) 
                OR t.tar_bloom = 0
            )
            ORDER BY t.tar_folio DESC;
            ";

        $resultado = mysqli_query($cnx, $sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
        }

        $tarimas_disponibles = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $tarimas_disponibles[] = $fila;
        }

        mysqli_close($cnx);

        echo json_encode($tarimas_disponibles, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        // Respuesta en caso de error
        echo json_encode([
            "error" => true,
            "message" => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'revolturas_terminadas') {
    try {
        // Conexión a la base de datos
        $cnx = Conectarse();

        if (!$cnx) {
            throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        $sql = "SELECT r.rev_folio, 
        ROUND(r.rev_bloom) AS rev_bloom, 
        r.rev_viscosidad, 
        ROUND(r.rev_kilos) AS rev_kilos, 
        DATE_FORMAT(r.rev_fecha, '%d/%m/%y') AS rev_fecha, 
        r.rev_ph, r.rev_humedad, r.rev_cenizas, 
        ROUND(r.rev_trans) AS rev_trans, ROUND(r.rev_color) AS rev_color, 
        r.rev_malla_30, ROUND(r.rev_malla_45) AS rev_malla_45, 
        ROUND(r.rev_pe_1kg) AS rev_pe_1kg, ROUND(r.rev_par_extr) AS rev_par_extr, r.rev_redox,
        ROUND(r.rev_teo_bloom) AS rev_teo_bloom, r.rev_teo_viscosidad, 
        ca.cal_descripcion AS rev_teo_calidad, cte.cte_nombre AS rev_teo_cliente,
        ca.cal_color AS rev_teo_color_cal,
        c.cal_descripcion, c.cal_color
        FROM rev_revolturas r
        LEFT JOIN rev_calidad c ON c.cal_id = r.cal_id
        LEFT JOIN rev_calidad ca ON ca.cal_id = r.rev_teo_calidad
        LEFT JOIN rev_clientes cte ON cte.cte_id = r.rev_teo_cliente
        WHERE r.rev_estatus = 2 AND r.rev_count_etiquetado > 0 
        ORDER BY r.rev_bloom DESC;";


        $resultado = mysqli_query($cnx, $sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
        }

        $revolturas_terminadas = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $revolturas_terminadas[] = $fila;
        }

        mysqli_close($cnx);

        echo json_encode($revolturas_terminadas, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        // Respuesta en caso de error
        echo json_encode([
            "error" => true,
            "message" => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tarimas_revolvedora') {
    try {
        // Conexión a la base de datos
        $cnx = Conectarse();

        if (!$cnx) {
            throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        $sql = "SELECT 
                t.pro_id,
                t.pro_id_2,
                t.tar_folio,
                t.tar_fino,
                DATE_FORMAT(t.tar_fecha, '%d/%m/%y') AS tar_fecha,
                ROUND(t.tar_kilos) AS tar_kilos,
                ROUND(t.tar_bloom) AS tar_bloom,
                t.tar_viscosidad,
                t.tar_ph,
                t.tar_humedad,
                t.tar_cenizas,
                ROUND(t.tar_trans) AS tar_trans,
                ROUND(t.tar_color) AS tar_color,
                t.tar_malla_30,
                ROUND(t.tar_malla_45) AS tar_malla_45,
                ROUND(t.tar_pe_1kg) AS tar_pe_1kg,
                ROUND(t.tar_par_extr) AS tar_par_extr,
                t.tar_redox,
                c.cal_descripcion,
                c.cal_color,
                r.rev_folio
            FROM rev_tarimas t
            LEFT JOIN rev_calidad c ON c.cal_id = t.cal_id
            LEFT JOIN rev_revolturas_tarimas rt ON rt.tar_id = t.tar_id
            LEFT JOIN rev_revolturas r ON r.rev_id = rt.rev_id 
            WHERE r.rev_estatus = 1 ORDER BY t.tar_bloom DESC";

        $resultado = mysqli_query($cnx, $sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
        }

        $tarimas_revolviendose = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $tarimas_revolviendose[] = $fila;
        }

        mysqli_close($cnx);

        echo json_encode($tarimas_revolviendose, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        // Respuesta en caso de error
        echo json_encode([
            "error" => true,
            "message" => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'revolturas_dia') {
    try {
        // Conexión a la base de datos
        $cnx = Conectarse();

        if (!$cnx) {
            throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        $sql = "SELECT r.*,
                DATE_FORMAT(r.rev_fecha, '%d/%m/%y') AS rev_fecha,
                ROUND(r.rev_kilos) AS rev_kilos,
                ROUND(r.rev_teo_bloom) AS rev_teo_bloom, r.rev_teo_viscosidad, 
                ca.cal_descripcion AS rev_teo_calidad, cte.cte_nombre AS rev_teo_cliente, 
                ca.cal_color AS rev_teo_color_cal
                FROM rev_revolturas r
                LEFT JOIN rev_calidad ca ON ca.cal_id = r.rev_teo_calidad
                LEFT JOIN rev_clientes cte ON cte.cte_id = r.rev_teo_cliente
                WHERE r.rev_estatus = 0 AND r.rev_count_etiquetado > 0";

        $resultado = mysqli_query($cnx, $sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
        }

        $revolturas_dia = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $revolturas_dia[] = $fila;
        }

        mysqli_close($cnx);

        echo json_encode($revolturas_dia, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        // Respuesta en caso de error
        echo json_encode([
            "error" => true,
            "message" => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tarimas_disponibles') {
    try {
        // Conexión a la base de datos
        $cnx = Conectarse();

        if (!$cnx) {
            throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        $sql = "SELECT 
                t.pro_id,
                t.pro_id_2,
                t.tar_folio,
                t.tar_fino,
                DATE_FORMAT(t.tar_fecha, '%d/%m/%y') AS tar_fecha,
                ROUND(t.tar_kilos) AS tar_kilos,
                ROUND(t.tar_bloom) AS tar_bloom,
                t.tar_viscosidad,
                t.tar_ph,
                t.tar_humedad,
                t.tar_cenizas,
                ROUND(t.tar_trans) AS tar_trans,
                ROUND(t.tar_color) AS tar_color,
                t.tar_malla_30,
                ROUND(t.tar_malla_45) AS tar_malla_45,
                ROUND(t.tar_pe_1kg) AS tar_pe_1kg,
                ROUND(t.tar_par_extr) AS tar_par_extr,
                t.tar_redox,
                c.cal_descripcion,
                c.cal_color
            FROM rev_tarimas t
            INNER JOIN rev_calidad c ON c.cal_id = t.cal_id
            WHERE t.tar_estatus = 1 AND t.tar_count_etiquetado > 0 
            AND t.pro_id NOT IN (1,2,3)
            ORDER BY t.tar_bloom DESC;
        ";

        $resultado = mysqli_query($cnx, $sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
        }

        $tarimas_disponibles = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $tarimas_disponibles[] = $fila;
        }

        mysqli_close($cnx);

        echo json_encode($tarimas_disponibles, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        // Respuesta en caso de error
        echo json_encode([
            "error" => true,
            "message" => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tarimas_disponibles_excel') {
    function generarArchivo($datos, $filename)
    {
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment;filename={$filename}");
        $salida = fopen('php://output', 'w');

        // Obtener y escribir los nombres de las columnas como encabezados
        if (!empty($datos)) {
            $encabezados = array_keys($datos[0]); // Obtener las claves del primer elemento como encabezados
            fputcsv($salida, $encabezados);
        }

        // Escribir los datos
        foreach ($datos as $fila) {
            fputcsv($salida, $fila);
        }

        fclose($salida);
    }

    try {
        // Conexión a la base de datos
        $cnx = Conectarse();

        if (!$cnx) {
            throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        $sql = "SELECT
                CONCAT(t.pro_id, t.tar_folio) AS Lote,
                DATE_FORMAT(t.tar_fecha, '%d/%m/%y') AS Fecha,
                ROUND(t.tar_kilos) AS Kilos,
                ROUND(t.tar_bloom) AS Bloom,
                t.tar_viscosidad AS Visc,
                t.tar_ph AS PH,
                t.tar_humedad AS Humed,
                t.tar_cenizas AS Ceniza,
                ROUND(t.tar_trans) AS Tran,
                ROUND(t.tar_color) AS Col,
                t.tar_malla_30 AS Malla_30,
                ROUND(t.tar_malla_45) AS Malla_45,
                CASE 
                WHEN t.tar_fino = 'F' THEN 'FINOS'
                WHEN t.tar_fino = 'N' THEN ''
                ELSE 'Desconocido' 
                END AS Ext,
                CASE 
                WHEN t.tar_fino = 'F' THEN 'FINOS'
                WHEN t.tar_fino = 'N' THEN ''
                ELSE 'Desconocido' 
                END AS Gelat,
                t.tar_redox AS Redox,
                c.cal_descripcion AS Calidad
            FROM rev_tarimas t
            LEFT JOIN rev_calidad c ON c.cal_id = t.cal_id
            WHERE t.tar_estatus = 1 AND t.tar_count_etiquetado > 0 
            AND t.cal_id IS NOT NULL 
            AND t.pro_id NOT IN (1,2,3)
            ORDER BY t.tar_bloom DESC;
        ";

        $resultado = mysqli_query($cnx, $sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
        }

        $tarimas_disponibles = array();
        $index = 1; // Inicializar el índice

        while ($fila = mysqli_fetch_assoc($resultado)) {
            // Agregar el índice como la primera columna
            $fila_con_indice = array('#' => $index) + $fila;
            $tarimas_disponibles[] = $fila_con_indice;
            $index++; // Incrementar el índice
        }

        mysqli_close($cnx);

        // Generar y descargar el archivo CSV
        generarArchivo($tarimas_disponibles, 'Tarimas_disponibles.csv');
        exit;
    } catch (Exception $e) {
        // Respuesta en caso de error
        echo json_encode([
            "error" => true,
            "message" => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }
}

?>