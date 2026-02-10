<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";
$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $rac_id = $data['rac_id'];
        $sql = "SELECT 
        n.*, 
    r.*, 
    rpc.cte_id, 
    cte.cte_nombre AS cte_nombre_cliente,
    rr.rr_id , 
    rr.rr_ext_real, 
    rrc.rrc_id, 
    rrc.rrc_ext_real, 
    p.pres_id, 
    p.pres_descrip,
    npe.npe_id, 
    npec.npe_id,
    c.cte_nombre AS cte_nombre_pos_cliente
    FROM 
        rev_nivel_posicion n
    INNER JOIN 
        rev_racks r ON n.rac_id = r.rac_id
    LEFT JOIN 
        racks_posiciones_clientes rpc ON n.niv_id = rpc.niv_id
    LEFT JOIN 
        rev_clientes cte ON rpc.cte_id = cte.cte_id
    LEFT JOIN 
        rev_nivel_posicion_empaque npe ON n.niv_id = npe.niv_id
    LEFT JOIN 
        rev_revolturas_pt rr ON npe.rr_id = rr.rr_id  -- Unión con rev_revolturas_pt
    LEFT JOIN 
        rev_nivel_posicion_empaque_cliente npec ON n.niv_id = npec.niv_id
    LEFT JOIN 
        rev_revolturas_pt_cliente rrc ON npec.rrc_id = rrc.rrc_id  -- Unión con rev_revolturas_pt_cliente
    LEFT JOIN 
        rev_clientes c ON npec.cte_id = c.cte_id
    LEFT JOIN 
        rev_presentacion p ON rr.pres_id = p.pres_id OR rrc.pres_id = p.pres_id
    WHERE 
        r.rac_zona = 'EMBARQUE' 
        AND r.rac_id = $rac_id
    ORDER BY 
        SUBSTRING(n.niv_codigo, 2) DESC, n.niv_codigo ASC;";


        $result = mysqli_query($cnx, $sql);
        if (!$result) {
            http_response_code(404);
            echo json_encode(['error' => 'Rack no encontrado']);
            exit;
        }

        $rack_data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rack_data[] = $row;
        }

        $res = [
            'status' => 'success',
            'data' => [
                'rack' => $rack_data
            ]
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
