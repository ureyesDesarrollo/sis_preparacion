<?php
header('Content-Type: application/json');

include "../../conexion/conexion.php";

$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $tar_id = $data['tar_id'];
        $sql = "SELECT tar_rechazado, tar_folio, pro_id, pro_id_2, tar_fino,cal_id,tar_estatus,tar_count_etiquetado FROM rev_tarimas WHERE tar_id = '$tar_id'";

        $result = mysqli_fetch_assoc(mysqli_query($cnx, $sql));

        // Verifica si no hay resultados para el tar_id
        if (!$result) {
            http_response_code(404);
            echo json_encode(['error' => 'Tarima no encontrada']);
            exit;
        }

        if($result['tar_count_etiquetado'] === null || $result['tar_count_etiquetado'] === '0'){ 
            http_response_code(404);
            echo json_encode(['error' => 'Tarima sin etiquetar']);
            exit;
        }
        // Determinar el estatus de la tarima
        $estatus = '';
        if ($result['cal_id'] != '0') {
            switch ($result['tar_rechazado']) {
                case 'R':
                    $estatus = 'Rechazada';
                    break;
                case 'C':
                    $estatus = 'Cuarentena';
                    break;
                case 'A':
                    $estatus = 'Aceptada';
                    break;
                default:
                    if (is_null($result['tar_rechazado'])) {
                        $estatus = 'En proceso';
                    }
                    break;
            }
        } else {
            
            $estatus = 'En proceso';
        }

        if($result['tar_estatus'] === '3') $estatus = 'Tarima ya procesada en revoltura';
        $posicion = [];
        $sql_pos = "SELECT n.niv_codigo,r.rac_zona,r.rac_descripcion 
            FROM rev_nivel_posicion n 
            INNER JOIN rev_racks r ON n.rac_id = r.rac_id
            LEFT JOIN rev_nivel_posicion_detalle npd ON npd.niv_id = n.niv_id
            LEFT JOIN rev_tarimas t ON t.tar_id = npd.tar_id
            WHERE t.tar_id = '$tar_id'";

        $result_pos = mysqli_query($cnx, $sql_pos);

        if (mysqli_num_rows($result_pos) > 0) {
            $estatus = "En almacen";

            // Obtenemos los datos de la primera fila
            $result_data = mysqli_fetch_assoc($result_pos);

            // Almacenamos los datos en el array $posicion
            $posicion['niv_codigo'] = $result_data['niv_codigo'];
            $posicion['rac_zona'] = $result_data['rac_zona'];
            $posicion['rac_descripcion'] = $result_data['rac_descripcion'];
        }

        $pro_id_2_part = isset($result['pro_id_2']) ? "/{$result['pro_id_2']}" : '';
        // Concatenar tarima para la respuesta
        $tarima = "P{$result['pro_id']}{$pro_id_2_part}T{$result['tar_folio']}";
        $fino = ($result['tar_fino'] === 'F') ? 'FINO' : '';

        // Respuesta con status y datos de la tarima
        $res = [
            'status' => 'success',
            'data' => [
                'tarima' => $tarima,
                'estatus' => $estatus,
                'fino' => $fino,
                'posicion' => $posicion,
            ]
        ];

        // Enviar respuesta como JSON
        echo json_encode($res);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    finally{
        mysqli_close($cnx);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
