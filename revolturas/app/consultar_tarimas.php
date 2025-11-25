<?php
header('Content-Type: application/json');

include '../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $cnx = Conectarse();
        $sql = "SELECT t.tar_id,t.pro_id, t.tar_folio,t.pro_id_2,t.tar_estatus,DATE(t.tar_fecha) as tar_fecha,
        r.rac_descripcion,r.rac_zona, np.niv_codigo,np.niv_id,r.rac_id
        FROM rev_tarimas t 
        LEFT JOIN rev_nivel_posicion_detalle npd ON npd.tar_id = t.tar_id 
        LEFT JOIN rev_nivel_posicion np ON np.niv_id = npd.niv_id
        LEFT JOIN rev_racks r ON r.rac_id = np.rac_id 
        WHERE t.tar_count_etiquetado > 0 AND t.tar_estatus IN (0,1,2,3) AND t.pro_id NOT IN (1,2,3) ORDER BY t.tar_id DESC";

        $result = mysqli_query($cnx, $sql);
        $tarimas = [];

        while ($fila = mysqli_fetch_assoc($result)) {
            $pro_id_2_part = isset($fila['pro_id_2']) ? "/{$fila['pro_id_2']}" : '';

            $fila['tar_folio'] = "P{$fila['pro_id']}{$pro_id_2_part}T{$fila['tar_folio']}";

            $tarimas[] = $fila;
        }

        $res = [
            'status' => 'success',
            'data' =>  $tarimas
        ];

        echo json_encode($res);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }

    mysqli_close($cnx);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
