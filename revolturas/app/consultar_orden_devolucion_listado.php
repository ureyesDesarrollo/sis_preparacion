<?php
include "../../conexion/conexion.php";

$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $sql = "SELECT od.*,odd.*,cte.cte_nombre FROM orden_devolucion od 
        INNER JOIN rev_clientes cte ON cte.cte_id = od.cte_id
        INNER JOIN orden_devolucion_detalle odd ON odd.od_id = od.od_id
        WHERE od.od_estado = 'RECIBIDO'";

        $res = mysqli_query($cnx, $sql);

        if (!$res) {
            throw new Exception("Error al ejecutar la consulta de orden de voluciones: {$mysqli_error($cnx)}");
        }

        if (mysqli_num_rows($res) === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'No se encontraron devoluciones']);
            exit;
        }

        $devoluciones = [];

        while ($fila = mysqli_fetch_assoc($res)) {
            $devoluciones[] = $fila;
        }

        $response = [
            'success' => true,
            'data' => $devoluciones
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
