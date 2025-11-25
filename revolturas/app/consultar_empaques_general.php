<?php
include '../../conexion/conexion.php';


if($_SERVER['REQUEST_METHOD'] == 'GET'){
    try{
        $cnx = Conectarse();
    $data = json_decode(file_get_contents('php://input'), true);

    $slq = "SELECT 
    rr.rr_ext_real, 
    rr.rr_id, 
    p.pres_id, 
    p.pres_descrip, 
    r.rev_folio
FROM 
    rev_revolturas_pt rr
INNER JOIN 
    rev_revolturas r ON rr.rev_id = r.rev_id
INNER JOIN 
    rev_presentacion p ON rr.pres_id = p.pres_id
LEFT JOIN 
    rev_nivel_posicion_empaque npe ON rr.rr_id = npe.rr_id
LEFT JOIN 
    rev_orden_embarque_detalle oed ON oed.rrc_id = rr.rr_id
WHERE 
    npe.npe_id IS NULL
    AND rr.rr_ext_real != 0 
    AND rr.rr_ext_real IS NOT NULL
    AND r.rev_count_etiquetado > 0 ORDER BY r.rev_folio DESC";

    $empaques = [];
    $result = mysqli_query($cnx, $slq);
    while($row = mysqli_fetch_assoc($result)){
        $empaques[] = $row;
    }

    $res = [
        'status' => 'succes',
        'data' => $empaques
    ];

    echo json_encode($res);
    }catch(Exception $e){
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    finally{
        mysqli_close($cnx);
    }
}else{
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}