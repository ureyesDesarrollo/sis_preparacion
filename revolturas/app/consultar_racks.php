<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";
$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $rack_id = $data['rac_id'];

        $sql = "SELECT 
    n.*, 
    r.*, 
    d.tipo,
    d.cantidad,
    d.tar_id, 
    d.rev_id,
    t.tar_folio, t.pro_id, t.pro_id_2,t.tar_rechazado,
    d.rr_id, rg.rev_id AS rev_general_id, p1.pres_descrip AS pres_general,
    d.rrc_id, rc.rev_id AS rev_cliente_id, p2.pres_descrip AS pres_cliente,
    rv1.rev_folio AS rev_folio_general,
    rv2.rev_folio AS rev_folio_cliente,
    rv3.rev_folio AS rev_folio_directo,
    -- Cliente lógico según rr_id o rrc_id
    CASE 
        WHEN d.rr_id IS NOT NULL THEN 74
        ELSE rc.cte_id
    END AS cliente_id,

    CASE 
        WHEN d.rr_id IS NOT NULL THEN c2.cte_nombre
        ELSE c1.cte_nombre
    END AS cte_nombre
    FROM rev_nivel_posicion n
    INNER JOIN rev_racks r ON n.rac_id = r.rac_id
    LEFT JOIN rev_nivel_posicion_detalle d ON d.niv_id = n.niv_id
    LEFT JOIN rev_tarimas t ON t.tar_id = d.tar_id
    -- Revoltura general
    LEFT JOIN rev_revolturas_pt rg ON rg.rr_id = d.rr_id
    LEFT JOIN rev_revolturas rv1 ON rv1.rev_id = rg.rev_id
    LEFT JOIN rev_presentacion p1 ON p1.pres_id = rg.pres_id
    -- Revoltura cliente
    LEFT JOIN rev_revolturas_pt_cliente rc ON rc.rrc_id = d.rrc_id
    LEFT JOIN rev_revolturas rv2 ON rv2.rev_id = rc.rev_id
    LEFT JOIN rev_presentacion p2 ON p2.pres_id = rc.pres_id
    -- Revoltura directa
    LEFT JOIN rev_revolturas rv3 ON rv3.rev_id = d.rev_id
    -- Cliente por cliente
    LEFT JOIN rev_clientes c1 ON c1.cte_id = rc.cte_id
    -- Cliente por defecto (cte_id 74)
    LEFT JOIN rev_clientes c2 ON c2.cte_id = 74
    WHERE n.rac_id = $rack_id
    ORDER BY SUBSTRING(n.niv_codigo, 2) DESC, n.niv_codigo ASC;";
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
