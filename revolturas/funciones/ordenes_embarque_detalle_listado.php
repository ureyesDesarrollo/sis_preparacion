
<?php
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  try {
    $sql = "SELECT
            oe.oe_id AS orden_id,
            oe.oe_fecha AS fecha_creacion,
            oe.cte_id AS cliente_id,
            cte.cte_nombre AS cliente_nombre,
            oe.oe_estado AS estado,
            cte.cte_rfc AS rfc,
            f.fe_factura as factura
        FROM
            rev_orden_embarque oe
        LEFT JOIN
            rev_clientes cte ON oe.cte_id = cte.cte_id
        LEFT JOIN rev_revolturas_pt_facturas f ON oe.oe_id = f.orden_embarque_id
        GROUP BY
            oe.oe_id
        ORDER BY
            oe.oe_id DESC";

    $listado_ordenes_embarque = mysqli_query($cnx, $sql);

    $datos_ordenes_embarque = array();

    while ($fila = mysqli_fetch_assoc($listado_ordenes_embarque)) {
      $datos_ordenes_embarque[] = $fila;
    }

    echo json_encode($datos_ordenes_embarque);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
  }
} else {
  http_response_code(405);
  echo json_encode(['error' => 'Método no permitido']);
}
