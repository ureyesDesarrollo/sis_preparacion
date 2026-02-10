<?php
header('Content-Type: application/json');
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = Conectarse();
        $data = json_decode(file_get_contents('php://input'), true);
        $oda_id        = $data['oda_id'];
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
        $bloom         = $data['bloom'];
        $cenizas       = $data['cenizas'];
        $coliformes    = $data['coliformes'];
        $ecoli         = $data['ecoli'];
        $salmonella    = $data['salmonella'];
        $saereus       = $data['saereus'];
        $cal_id        = $data['cal_id'];
        $rechazado = 'A';

        include 'orden_devolucion_validacion.php';

        $sql = "UPDATE orden_devolucion_analisis SET
            bloom = '$bloom',
            viscosidad = '$viscosidad',
            ph = '$ph',
            trans = '$trans',
            ntu = '$ntu',
            humedad = '$humedad',
            cenizas = '$cenizas',
            ce = '$ce',
            redox = '$redox',
            color = '$color',
            olor = '$olor',
            pe_1kg = '$pe_1kg',
            par_extr = '$par_extr',
            par_ind = '$par_ind',
            hidratacion = '$hidratacion',
            porcentaje_t = '$porcentaje_t',
            malla_30 = '$malla_30',
            malla_45 = '$malla_45',
            malla_60 = '$malla_60',
            malla_100 = '$malla_100',
            malla_200 = '$malla_200',
            malla_base = '$malla_base',
            coliformes = '$coliformes',
            ecoli = '$ecoli',
            salmonella = '$salmonella',
            saereus = '$saereus',
            rechazado = '$rechazado',
            cal_id = '$cal_id',
            fecha_analisis = NOW()
        WHERE oda_id = '$oda_id'";

        if (mysqli_query($conn, $sql)) {
            if ($rechazado === 'C') {
                $res = 'Parametros actualizados exitosamente, algunos datos no cumplen con el mínimo establecido. Se marcará como cuarentena.';
            } elseif ($rechazado === 'R') {
                $res = 'Parametros actualizados exitosamente, algunos datos no cumplen con el mínimo establecido. Se marcará como rechazo.';
            } else {
                $res = "Parametros actualizados exitosamente";
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
