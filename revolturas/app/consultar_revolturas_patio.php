<?php
header('Content-Type: application/json');
include '../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    try {
        $cnx = Conectarse();
        $sql = "SELECT 
        re.rev_id,
        re.rev_folio,
        re.rev_fecha,
        re.rev_estatus
        FROM rev_revolturas_tarimas rt 
        INNER JOIN rev_tarimas t ON rt.tar_id = t.tar_id
        INNER JOIN rev_nivel_posicion_detalle npd ON npd.tar_id = t.tar_id 
        INNER JOIN rev_nivel_posicion np ON np.niv_id = npd.niv_id
        INNER JOIN rev_racks r ON r.rac_id = np.rac_id
        INNER JOIN rev_revolturas re ON re.rev_id = rt.rev_id
        WHERE (re.rev_estatus = 0 OR re.rev_estatus = 1) AND r.rac_id = 2
        GROUP BY re.rev_id, re.rev_folio, re.rev_fecha, re.rev_estatus";
        
        $result = mysqli_query($cnx, $sql);
        $revolturas = [];

        while ($fila = mysqli_fetch_assoc($result)) {
            $revolturas[] = $fila;
        }

        $res = [
            'status' => 'success',
            'data' =>  $revolturas
        ];

        echo json_encode($res);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
