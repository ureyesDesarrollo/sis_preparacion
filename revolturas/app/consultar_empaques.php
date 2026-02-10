<?php
include '../../conexion/conexion.php';


if($_SERVER['REQUEST_METHOD'] == 'GET'){
    try{
        $cnx = Conectarse();
    $data = json_decode(file_get_contents('php://input'), true);

    $slq = "(
  SELECT 
      rr.rr_ext_real AS cantidad,
      rr.rr_id,
      NULL AS rrc_id,
      p.pres_id,
      p.pres_descrip AS presentacion,
      74 AS cte_id,
      (SELECT cte_nombre FROM rev_clientes WHERE cte_id = 74) AS cliente,
      r.rev_folio,
      r.rev_id,
      'general' AS tipo
  FROM 
      rev_revolturas_pt rr
  INNER JOIN 
      rev_revolturas r ON rr.rev_id = r.rev_id
  INNER JOIN 
      rev_presentacion p ON rr.pres_id = p.pres_id
  LEFT JOIN 
      rev_orden_embarque_detalle oed ON oed.rr_id = rr.rr_id
  LEFT JOIN 
      rev_nivel_posicion_detalle nvd ON nvd.rr_id = rr.rr_id
  WHERE 
      oed.oed_id IS NULL
      AND nvd.nvd_id IS NULL
      AND rr.rr_ext_real != 0 
      AND rr.rr_ext_real IS NOT NULL
      AND r.rev_count_etiquetado > 0
)
UNION
(
  SELECT 
      rrc.rrc_ext_real AS cantidad,
      NULL AS rr_id,
      rrc.rrc_id,
      p.pres_id,
      p.pres_descrip AS presentacion,
      c.cte_id,
      c.cte_nombre AS cliente,
      r.rev_folio,
      r.rev_id,
      'cliente' AS tipo
  FROM 
      rev_revolturas_pt_cliente rrc
  INNER JOIN 
      rev_revolturas r ON rrc.rev_id = r.rev_id
  INNER JOIN 
      rev_presentacion p ON rrc.pres_id = p.pres_id
  INNER JOIN 
      rev_clientes c ON rrc.cte_id = c.cte_id
  LEFT JOIN 
      rev_orden_embarque_detalle oed ON oed.rrc_id = rrc.rrc_id
  LEFT JOIN 
      rev_nivel_posicion_detalle nvd ON nvd.rrc_id = rrc.rrc_id
  WHERE 
      oed.oed_id IS NULL
      AND nvd.nvd_id IS NULL
      AND rrc.rrc_ext_real != 0 
      AND rrc.rrc_ext_real IS NOT NULL
      AND r.rev_count_etiquetado > 0
)
ORDER BY rev_folio DESC;
";

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