<?php
header('Content-Type: application/json');
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = Conectarse();
        $data = json_decode(file_get_contents('php://input'), true);
        $odd_id        = $data['odd_id'];
        $viscosidad    = $data['viscosidad'];
        $ph            = $data['ph'];
        $trans         = $data['trans'];
        $ntu           = $data['ntu'];
        $humedad       = $data['humedad'];
        $ce            = $data['ce'];
        $redox         = $data['redox'];
        $color         = $data['color'];
        $olor          = $data['olor'];
        $pe_1kg        = $data['pe_1kg'];
        $par_extr      = $data['par_extr'];
        $par_ind       = $data['par_ind'];
        $hidratacion   = $data['hidratacion'];
        $porcentaje_t  = $data['porcentaje_t'];
        $malla_30      = $data['malla_30'];
        $malla_45      = $data['malla_45'];
        $malla_60      = $data['malla_60'];
        $malla_100     = $data['malla_100'];
        $malla_200     = $data['malla_200'];
        $malla_base    = $data['malla_base'];
        $bloom         = $data['bloom'] ?? '0';
        $cenizas       = $data['cenizas'] ?? '0';
        $coliformes    = $data['coliformes'] ?? '0';
        $ecoli         = $data['ecoli'] ?? '0';
        $salmonella    = $data['salmonella'] ?? '0';
        $saereus       = $data['saereus'] ?? '0';
        $rechazado = 'A';

        include 'orden_devolucion_validacion.php';


        $sql = "INSERT INTO orden_devolucion_analisis (
                odd_id, viscosidad, ph, trans, ntu, humedad, ce, redox, color, olor,
                pe_1kg, par_extr, par_ind, hidratacion, porcentaje_t,
                malla_30, malla_45, malla_60, malla_100, malla_200, malla_base, rechazado
            ) VALUES (
                '$odd_id', '$viscosidad', '$ph', '$trans', '$ntu', '$humedad', '$ce', '$redox', '$color', '$olor',
                '$pe_1kg', '$par_extr', '$par_ind', '$hidratacion', '$porcentaje_t',
                '$malla_30', '$malla_45', '$malla_60', '$malla_100', '$malla_200','$malla_base', '$rechazado'
            )";


        $sql_update_orden_detalle = "UPDATE orden_devolucion_detalle SET estado_lote = 'PROCESO DE ANALISIS' WHERE odd_id = $odd_id";
        if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql_update_orden_detalle)) {
            if ($rechazado === 'C') {
                $res = 'Parametros registrados exitosamente, algunos datos no cumplen con el mínimo establecido. Se marcará como cuarentena.';
            } elseif ($rechazado === 'R') {
                $res = 'Parametros registrados exitosamente, algunos datos no cumplen con el mínimo establecido. Se marcará como rechazo.';
            } else {
                $res = "Parametros registrados exitosamente";
            }
            echo json_encode(["success" => true, "message" => $res, "fallidos" => $parametros_fallidos]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    } finally {
        mysqli_close($conn);
    }
}

