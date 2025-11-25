<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Diciembre-2024 */

include "../../conexion/conexion.php";
$totalKilos = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'revolturas_terminadas') {
    try {
        // Conexión a la base de datos
        $cnx = Conectarse();

        if (!$cnx) {
            throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        // Ajustar el formato de la fecha a mes/dia usando DATE_FORMAT
        $sql = "SELECT r.rev_folio, 
        ROUND(r.rev_bloom) AS rev_bloom, 
        r.rev_viscosidad, 
        ROUND(r.rev_kilos) AS rev_kilos, 
        DATE_FORMAT(r.rev_fecha, '%d/%m/%y') AS rev_fecha, 
        r.rev_ph, r.rev_humedad, r.rev_cenizas, 
        ROUND(r.rev_trans) AS rev_trans, ROUND(r.rev_color) AS rev_color, 
        r.rev_malla_30, ROUND(r.rev_malla_45) AS rev_malla_45, 
        ROUND(r.rev_pe_1kg) AS rev_pe_1kg, ROUND(r.rev_par_extr) AS rev_par_extr, r.rev_redox, 
        c.cal_descripcion, c.cal_color
        FROM rev_revolturas r
        INNER JOIN rev_calidad c ON c.cal_id = r.cal_id
        WHERE r.rev_estatus = 2 AND r.rev_count_etiquetado > 0
        ORDER BY r.rev_bloom DESC";

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
            WHERE r.rev_estatus = 1 ORDER BY r.rev_bloom DESC";

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


/* if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tarimas_tomadas_para_revolver') {
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
            INNER JOIN rev_revolturas_tarimas rt ON rt.tar_id = t.tar_id
            LEFT JOIN rev_revolturas r ON r.rev_id = rt.rev_id
            WHERE t.tar_estatus = 2 OR r.rev_estatus = 0 ORDER BY t.tar_bloom DESC
        ";

        $resultado = mysqli_query($cnx, $sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
        }

        $tarimas_tomadas_para_revoltura = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $tarimas_tomadas_para_revoltura[] = $fila;
        }

        mysqli_close($cnx);

        // Retornar los resultados en JSON
        echo json_encode($tarimas_tomadas_para_revoltura, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        // Respuesta en caso de error
        echo json_encode([
            "error" => true,
            "message" => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
} */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tarimas_tomadas_para_revolver') {
    try {
        // Conexión a la base de datos
        $cnx = Conectarse();

        if (!$cnx) {
            throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        $sql = "SELECT *,DATE_FORMAT(rev_fecha, '%d/%m/%y') AS rev_fecha FROM rev_revolturas WHERE rev_estatus = 0";

        $resultado = mysqli_query($cnx, $sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
        }

        $tarimas_tomadas_para_revoltura = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $tarimas_tomadas_para_revoltura[] = $fila;
        }

        mysqli_close($cnx);

        echo json_encode($tarimas_tomadas_para_revoltura, JSON_UNESCAPED_UNICODE);
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
            WHERE t.tar_estatus = 1 AND t.tar_count_etiquetado > 0 AND t.cal_id IS NOT NULL ORDER BY t.tar_bloom DESC;
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tarimas_pesada_dia') {
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
            WHERE t.tar_estatus = 0 AND t.tar_count_etiquetado > 0 AND (t.cal_id IS NULL OR t.cal_id = '') AND t.pro_id != 1 ORDER BY t.tar_bloom DESC;
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
            WHERE t.tar_estatus = 0 AND t.tar_count_etiquetado > 0 AND (t.cal_id IS NOT NULL OR t.cal_id != '') AND t.pro_id != 1 AND t.tar_bloom != 0 ORDER BY t.tar_folio DESC;
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto terminado (sin empacar)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../../js/jquery.min.js"></script>
    <style>
        @media print {
            @page {
                size: A4;
                /* Especificar tamaño de la hoja */
                margin: 15mm 10mm 25mm 10mm;
                /* Márgenes: superior, derecho, inferior, izquierdo */


            }

            body::after {
                content: "Página " counter(page) " de " counter(pages);
                position: fixed;
                top: 10mm;
                right: 10mm;
                font-size: 12px;
                font-family: Arial, sans-serif;
            }

            body {
                font-size: 11px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 0;
                counter-reset: page;
            }

            .container {
                max-width: 210mm;
                margin: auto;
                padding: 10px;
            }

            h3 {
                font-size: 11px;
                text-align: center;
                margin-bottom: 3px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 7px;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 1px;
                text-align: center;
                word-wrap: break-word;
                white-space: normal;
            }

            th {
                background-color: #f2f2f2;
                font-size: 7px;
                padding: 2px;
            }

            .table-container {
                overflow-x: auto;
            }

            thead {
                display: table-header-group;
                /* Asegura que los encabezados se repitan si la tabla se corta */
            }

            tfoot {
                display: table-footer-group;
                /* Mantiene el pie de tabla en la parte inferior */
            }

            tbody {
                display: table-row-group;
            }

            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            #export {
                display: none;
            }
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
                <h2 class="fw-bold m-0">Producto terminado (sin empacar)</h2>
                <span id="fecha"></span>
            </div>
            
            <div class="col-md-4 mt-2">
                <button id="export" class="btn btn-success" onclick="exportTablesToExcel()">Exportar a Excel</button>
            </div>
            
        </div>
        <div class="container mb-4">
            <div class="print-area">
                
                <h3 style="color: #007bff;">Pesada del día - Kilos: <span id="total-kilos-pesada-dia-span"></span></h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="tarimas-pesada-dia">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarima</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <!-- <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Kilos</strong></td>
                                <td id="total-kilos-disponibles">0</td>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">Tarimas pendientes de enviar almacen - Kilos: <span id="total-pendiente-enviar-almacen-span"></span></h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="tarimas-pendiente-enviar-almacen">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarima</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <!-- <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Kilos</strong></td>
                                <td id="total-kilos-disponibles">0</td>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">Revolturas terminadas - Kilos: <span id="total-kilos-revolturas-span"></span></h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="revolturas-terminadas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Revoltura</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <!-- <tfoot>
                            <tr>
                                <td colspan="15" class="text-end"><strong>Total Kilos</strong></td>
                                <td id="total-kilos-revolturas"></td>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">En revolvedora - Kilos <span id="total-kilos-revolvedora-span"></span></h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="tarimas-revolvedora">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarima</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Kilos</th>
                                <th>Revoltura</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <!-- <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Kilos</strong></td>
                                <td id="total-kilos-revolvedora">0</td>
                                <td></td>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">Revolturas del Día - Kilos: <span id="total-kilos-tomadas-span"></span></h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="tarimas-tomadas-revoltura">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Revoltura</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <!--  <th>Calidad</th> -->
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <!--  <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Kilos</strong></td>
                                <td id="total-kilos-tomadas">0</td>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="print-area">
                <h3 style="color: #007bff;">Tarimas disponibles - Kilos: <span id="total-kilos-disponibles-span"></span></h3>
                <div class="container table-container">
                    <table class="table table-bordered" id="tarimas-disponibles">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarima</th>
                                <th>Fecha</th>
                                <th>Bloom</th>
                                <th>Visc</th>
                                <th>PH</th>
                                <th>Humed</th>
                                <th>Cenizas</th>
                                <th>Trans</th>
                                <th>Col</th>
                                <th>Malla #30</th>
                                <th>Malla #45</th>
                                <th>Ins. Ext</th>
                                <th>Ins Gelat</th>
                                <th>Redox</th>
                                <th>Calidad</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <!-- <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Kilos</strong></td>
                                <td id="total-kilos-disponibles">0</td>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="page-number"></div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
    function exportTablesToExcel() {
        const printAreas = document.querySelectorAll('.print-area');
        const workbook = XLSX.utils.book_new();

        printAreas.forEach((printArea) => {
            const title = printArea.querySelector('h3').innerText;
            const table = printArea.querySelector('table');
            const clonedTable = table.cloneNode(true);

            const titleRow = document.createElement('tr');
            const titleCell = document.createElement('th');
            titleCell.colSpan = table.querySelectorAll('th').length;
            titleCell.innerText = title;
            titleRow.appendChild(titleCell);

            clonedTable.querySelector('thead').insertBefore(titleRow, clonedTable.querySelector('thead').firstChild);

            const worksheet = XLSX.utils.table_to_sheet(clonedTable);
            XLSX.utils.book_append_sheet(workbook, worksheet, `Hoja ${workbook.SheetNames.length + 1}`);
        });

        XLSX.writeFile(workbook, 'Reporte.xlsx');
    }

    const formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    $(document).ready(function() {
        const fecha = new Date();
        $('#fecha').text(fecha.toLocaleDateString('es-MX', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }));
        cargarTarimasRevolvedora();
        cargarTarimasTomadasRevolturas();
        cargarTarimasDisponibles();
        cargarRevolturasTerminadas();
        cargarTarimasPesadaDia();
        cargarTarimasPendienteAlmacen();
    });

    function cargarRevolturasTerminadas() {
        const $tbody = $('#revolturas-terminadas').find('tbody');
        let totalKilos = 0;

        $.ajax({
            type: 'POST',
            url: 'reporte_inventario.php',
            data: {
                action: 'revolturas_terminadas'
            },
            success: function(response) {
                const data = JSON.parse(response);

                if (data.error) {
                    alert('Error: ' + data.message);
                    return;
                }

                $tbody.empty();
                data.forEach((revoltura, index) => {
                    $tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${revoltura.rev_folio}</td>
                            <td>${revoltura.rev_fecha}</td>
                            <td>${revoltura.rev_bloom ?? ''}</td>
                            <td>${revoltura.rev_viscosidad  ?? ''}</td>
                            <td>${revoltura.rev_ph  ?? ''}</td>
                            <td>${revoltura.rev_humedad  ?? ''}</td>
                            <td>${revoltura.rev_cenizas  ?? ''}</td>
                            <td>${revoltura.rev_trans  ?? ''}</td>
                            <td>${revoltura.rev_color  ?? ''}</td>
                            <td>${revoltura.rev_malla_30  ?? ''}</td>
                            <td>${revoltura.rev_malla_45  ?? ''}</td>
                            <td>${revoltura.rev_pe_1kg  ?? ''}</td>
                            <td>${revoltura.rev_par_extr  ?? ''}</td>
                            <td>${revoltura.rev_redox  ?? ''}</td>
                            <td style="background-color:${revoltura.cal_color  ?? ''}; color: #FFFFFF">${revoltura.cal_descripcion  ?? ''}</td>
                            <td>${revoltura.rev_kilos  ?? ''}</td>
                        </tr>
                    `);
                    totalKilos += Number(revoltura.rev_kilos);
                });

                sumaTotal += totalKilos;
                $('#total-kilos-revolturas').text(formatter.format(totalKilos));
                $('#total-kilos-revolturas-span').text(formatter.format(totalKilos));
            },
            error: function() {
                alert('Error al cargar las revolturas.');
            }
        });
    }

    function cargarTarimasRevolvedora() {
        const $tbody = $('#tarimas-revolvedora').find('tbody');
        let totalKilos = 0;

        $.ajax({
            type: 'POST',
            url: 'reporte_inventario.php',
            data: {
                action: 'tarimas_revolvedora'
            },
            success: function(response) {
                const data = JSON.parse(response);

                if (data.error) {
                    alert('Error: ' + data.message);
                    return;
                }

                $tbody.empty();
                data.forEach((tarima, index) => {
                    let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;
                    $tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>P${pro_id}T${tarima.tar_folio}</td>
                            <td>${tarima.tar_fecha}</td>
                            <td>${tarima.tar_bloom  ?? ''}</td>
                            <td>${tarima.tar_viscosidad  ?? ''}</td>
                            <td>${tarima.tar_ph  ?? ''}</td>
                            <td>${tarima.tar_humedad  ?? ''}</td>
                            <td>${tarima.tar_cenizas  ?? ''}</td>
                            <td>${tarima.tar_trans  ?? ''}</td>
                            <td>${tarima.tar_color  ?? ''}</td>
                            <td>${tarima.tar_malla_30  ?? ''}</td>
                            <td>${tarima.tar_malla_45  ?? ''}</td>
                            <td>${tarima.tar_pe_1kg  ?? ''}</td>
                            <td>${tarima.tar_par_extr  ?? ''}</td>
                            <td>${tarima.tar_redox  ?? ''}</td>
                            <td style="background-color:${tarima.cal_color  ?? ''}; color: #FFFFFF">${tarima.cal_descripcion  ?? ''}</td>
                            <td>${tarima.tar_kilos  ?? ''}</td>
                            <td>${tarima.rev_folio  ?? ''}</td>
                        </tr>
                    `);
                    totalKilos += Number(tarima.tar_kilos);
                });
                sumaTotal += totalKilos;

                $('#total-kilos-revolvedora').text(formatter.format(totalKilos));
                $('#total-kilos-revolvedora-span').text(formatter.format(totalKilos));
            },
            error: function() {
                alert('Error al cargar las tarimas.');
            }
        });
    }

    /* function cargarTarimasTomadasRevolturas() {
        const $tbody = $('#tarimas-tomadas-revoltura').find('tbody');
        let totalKilos = 0;

        $.ajax({
            type: 'POST',
            url: 'reporte_inventario.php',
            data: {
                action: 'tarimas_tomadas_para_revolver'
            },
            success: function(response) {
                const data = JSON.parse(response);

                if (data.error) {
                    alert('Error: ' + data.message);
                    return;
                }

                $tbody.empty();
                data.forEach((tarima) => {
                    let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;
                    $tbody.append(`
                        <tr>
                             <td>P${pro_id}T${tarima.tar_folio}</td>
                            <td>${tarima.tar_fecha}</td>
                            <td>${tarima.tar_bloom  ?? ''}</td>
                            <td>${tarima.tar_viscosidad  ?? ''}</td>
                            <td>${tarima.tar_ph  ?? ''}</td>
                            <td>${tarima.tar_humedad  ?? ''}</td>
                            <td>${tarima.tar_cenizas  ?? ''}</td>
                            <td>${tarima.tar_trans  ?? ''}</td>
                            <td>${tarima.tar_color  ?? ''}</td>
                            <td>${tarima.tar_malla_30  ?? ''}</td>
                            <td>${tarima.tar_malla_45  ?? ''}</td>
                            <td>${tarima.tar_pe_1kg  ?? ''}</td>
                            <td>${tarima.tar_par_extr  ?? ''}</td>
                            <td>${tarima.tar_redox  ?? ''}</td>
                            <td style="background-color:${tarima.cal_color  ?? ''}; color: #FFFFFF">${tarima.cal_descripcion  ?? ''}</td>
                            <td>${tarima.tar_kilos  ?? ''}</td>
                        </tr>
                    `);
                    totalKilos += Number(tarima.tar_kilos);
                });

                $('#total-kilos-tomadas').text(formatter.format(totalKilos));
                $('#total-kilos-tomadas-span').text(formatter.format(totalKilos));
            },
            error: function() {
                alert('Error al cargar las tarimas.');
            }
        });
    } */

    function cargarTarimasTomadasRevolturas() {
        const $tbody = $('#tarimas-tomadas-revoltura').find('tbody');
        let totalKilos = 0;

        $.ajax({
            type: 'POST',
            url: 'reporte_inventario.php',
            data: {
                action: 'tarimas_tomadas_para_revolver'
            },
            success: function(response) {
                const data = JSON.parse(response);

                if (data.error) {
                    alert('Error: ' + data.message);
                    return;
                }

                $tbody.empty();
                data.forEach((tarima, index) => {
                    //let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;
                    $tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>P${tarima.rev_folio}</td>
                            <td>${tarima.rev_fecha}</td>
                            <td>${tarima.rev_bloom  ?? ''}</td>
                            <td>${tarima.rev_viscosidad  ?? ''}</td>
                            <td>${tarima.rev_ph  ?? ''}</td>
                            <td>${tarima.rev_humedad  ?? ''}</td>
                            <td>${tarima.rev_cenizas  ?? ''}</td>
                            <td>${tarima.rev_trans  ?? ''}</td>
                            <td>${tarima.rev_color  ?? ''}</td>
                            <td>${tarima.rev_malla_30  ?? ''}</td>
                            <td>${tarima.rev_malla_45  ?? ''}</td>
                            <td>${tarima.rev_pe_1kg  ?? ''}</td>
                            <td>${tarima.rev_par_extr  ?? ''}</td>
                            <td>${tarima.rev_redox  ?? ''}</td>
                            <td>${tarima.rev_kilos  ?? ''}</td>
                        </tr>
                    `);
                    totalKilos += Number(tarima.rev_kilos);
                });
                sumaTotal += totalKilos;

                $('#total-kilos-tomadas').text(formatter.format(totalKilos));
                $('#total-kilos-tomadas-span').text(formatter.format(totalKilos));
            },
            error: function() {
                alert('Error al cargar las tarimas.');
            }
        });
    }

    function cargarTarimasDisponibles() {
        const $tbody = $('#tarimas-disponibles').find('tbody');
        let totalKilos = 0;

        $.ajax({
            type: 'POST',
            url: 'reporte_inventario.php',
            data: {
                action: 'tarimas_disponibles'
            },
            success: function(response) {
                const data = JSON.parse(response);

                if (data.error) {
                    alert('Error: ' + data.message);
                    return;
                }

                $tbody.empty();
                data.forEach((tarima, index) => {
                    let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;
                    if (pro_id == '1') pro_id = 'FINOS';

                    // Definir el color de fondo según la condición
                    let finos = (pro_id === 'FINOS') ? true : false;
                    if (finos) {
                        $tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>P${pro_id}T${tarima.tar_folio}</td>
                        <td colspan="14" class="text-center">Producto rechazado (NOVA)</td>
                        <td>${tarima.tar_kilos ?? ''}</td>
                    </tr>
                `);
                    } else {
                        $tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>P${pro_id}T${tarima.tar_folio}</td>
                        <td>${tarima.tar_fecha}</td>
                        <td>${tarima.tar_bloom ?? ''}</td>
                        <td>${tarima.tar_viscosidad ?? ''}</td>
                        <td>${tarima.tar_ph ?? ''}</td>
                        <td>${tarima.tar_humedad ?? ''}</td>
                        <td>${tarima.tar_cenizas ?? ''}</td>
                        <td>${tarima.tar_trans ?? ''}</td>
                        <td>${tarima.tar_color ?? ''}</td>
                        <td>${tarima.tar_malla_30 ?? ''}</td>
                        <td>${tarima.tar_malla_45 ?? ''}</td>
                        <td>${tarima.tar_pe_1kg ?? ''}</td>
                        <td>${tarima.tar_par_extr ?? ''}</td>
                        <td>${tarima.tar_redox ?? ''}</td>
                        <td style="background-color:${tarima.cal_color ?? ''}; color: #FFFFFF">${tarima.cal_descripcion ?? ''}</td>
                        <td>${tarima.tar_kilos ?? ''}</td>
                    </tr>
                `);
                    }

                    totalKilos += Number(tarima.tar_kilos);
                });
                $('#total-kilos-disponibles').text(formatter.format(totalKilos));
                $('#total-kilos-disponibles-span').text(formatter.format(totalKilos));
            },
            error: function() {
                alert('Error al cargar las tarimas.');
            }
        });
    }

    function cargarTarimasPesadaDia() {
        const $tbody = $('#tarimas-pesada-dia').find('tbody');
        let totalKilos = 0;

        $.ajax({
            type: 'POST',
            url: 'reporte_inventario.php',
            data: {
                action: 'tarimas_pesada_dia'
            },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.error) {
                    alert('Error: ' + data.message);
                    return;
                }

                $tbody.empty();
                data.forEach((tarima, index) => {
                    let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;
                    if (pro_id == '1') pro_id = 'FINOS';

                    // Definir el color de fondo según la condición
                    let finos = (pro_id === 'FINOS') ? true : false;
                    if (finos) {
                        $tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>P${pro_id}T${tarima.tar_folio}</td>
                        <td colspan="14" class="text-center">Producto rechazado (NOVA)</td>
                        <td>${tarima.tar_kilos ?? ''}</td>
                    </tr>
                `);
                    } else {
                        $tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>P${pro_id}T${tarima.tar_folio}</td>
                        <td>${tarima.tar_fecha}</td>
                        <td>${tarima.tar_bloom ?? ''}</td>
                        <td>${tarima.tar_viscosidad ?? ''}</td>
                        <td>${tarima.tar_ph ?? ''}</td>
                        <td>${tarima.tar_humedad ?? ''}</td>
                        <td>${tarima.tar_cenizas ?? ''}</td>
                        <td>${tarima.tar_trans ?? ''}</td>
                        <td>${tarima.tar_color ?? ''}</td>
                        <td>${tarima.tar_malla_30 ?? ''}</td>
                        <td>${tarima.tar_malla_45 ?? ''}</td>
                        <td>${tarima.tar_pe_1kg ?? ''}</td>
                        <td>${tarima.tar_par_extr ?? ''}</td>
                        <td>${tarima.tar_redox ?? ''}</td>
                        <td style="background-color:${tarima.cal_color ?? ''}; color: #FFFFFF">${tarima.cal_descripcion ?? ''}</td>
                        <td>${tarima.tar_kilos ?? ''}</td>
                    </tr>
                `);
                    }

                    totalKilos += Number(tarima.tar_kilos);
                });
                sumaTotal += totalKilos;

                $('#total-kilos-pesada-dia-span').text(formatter.format(totalKilos));
            },
            error: function() {
                alert('Error al cargar las tarimas.');
            }
        });
    }

    //tarimas_pendiente_enviar_almacen
    function cargarTarimasPendienteAlmacen() {
        const $tbody = $('#tarimas-pendiente-enviar-almacen').find('tbody');
        let totalKilos = 0;

        $.ajax({
            type: 'POST',
            url: 'reporte_inventario.php',
            data: {
                action: 'tarimas_pendiente_enviar_almacen'
            },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.error) {
                    alert('Error: ' + data.message);
                    return;
                }

                $tbody.empty();
                data.forEach((tarima, index) => {
                    let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;
                    if (pro_id == '1') pro_id = 'FINOS';

                    // Definir el color de fondo según la condición
                    let finos = (pro_id === 'FINOS') ? true : false;
                    if (finos) {
                        $tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>P${pro_id}T${tarima.tar_folio}</td>
                        <td colspan="14" class="text-center">Producto rechazado (NOVA)</td>
                        <td>${tarima.tar_kilos ?? ''}</td>
                    </tr>
                `);
                    } else {
                        $tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>P${pro_id}T${tarima.tar_folio}</td>
                        <td>${tarima.tar_fecha}</td>
                        <td>${tarima.tar_bloom ?? ''}</td>
                        <td>${tarima.tar_viscosidad ?? ''}</td>
                        <td>${tarima.tar_ph ?? ''}</td>
                        <td>${tarima.tar_humedad ?? ''}</td>
                        <td>${tarima.tar_cenizas ?? ''}</td>
                        <td>${tarima.tar_trans ?? ''}</td>
                        <td>${tarima.tar_color ?? ''}</td>
                        <td>${tarima.tar_malla_30 ?? ''}</td>
                        <td>${tarima.tar_malla_45 ?? ''}</td>
                        <td>${tarima.tar_pe_1kg ?? ''}</td>
                        <td>${tarima.tar_par_extr ?? ''}</td>
                        <td>${tarima.tar_redox ?? ''}</td>
                        <td style="background-color:${tarima.cal_color ?? ''}; color: #FFFFFF">${tarima.cal_descripcion ?? ''}</td>
                        <td>${tarima.tar_kilos ?? ''}</td>
                    </tr>
                `);
                    }

                    totalKilos += Number(tarima.tar_kilos);
                });
                sumaTotal += totalKilos;
                console.log(sumaTotal);
                //tarimas-pendiente-enviar-almacen
                $('#total-pendiente-enviar-almacen-span').text(formatter.format(totalKilos));
            },
            error: function() {
                alert('Error al cargar las tarimas.');
            }
        });
    }
</script>

</html>