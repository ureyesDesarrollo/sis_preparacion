<?php
include '../../conexion/conexion.php';


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $cnx = Conectarse();
        $data = json_decode(file_get_contents('php://input'), true);

        $slq = "SELECT rrc.rrc_ext_real,rrc.rrc_id, c.cte_nombre, c.cte_id,p.pres_id, p.pres_descrip
         FROM rev_revolturas_pt_cliente rrc
         INNER JOIN rev_clientes c ON rrc.cte_id = c.cte_id
         INNER JOIN rev_presentacion p ON rrc.pres_id = p.pres_id
         INNER JOIN rev_revolturas rev ON rev.rev_id = rrc.rev_id 
         LEFT JOIN rev_nivel_posicion_empaque_cliente npec ON rrc.rrc_id = npec.rrc_id
         LEFT JOIN rev_orden_embarque_detalle oed ON oed.rrc_id = rrc.rrc_id
         WHERE npec.npe_id IS NULL 
         AND rrc.rrc_ext_real != 0 
         AND rrc.rrc_ext_real IS NOT NULL 
         AND oed.rrc_id IS NULL
         AND rev.rev_count_etiquetado > 0 ORDER BY c.cte_nombre, p.pres_descrip";

        $empaques_cliente = [];
        $result = mysqli_query($cnx, $slq);
        while ($row = mysqli_fetch_assoc($result)) {
            $empaques_cliente[] = $row;
        }

        $res = [
            'status' => 'succes',
            'data' => $empaques_cliente
        ];

        echo json_encode($res);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
