<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

header('Content-Type: application/json; charset=utf-8');

try {
    $sql = "
        SELECT 
            v.cte_id,
            c.cte_nombre,
            COUNT(v.rrc_id) AS total_presentaciones,
            SUM(v.cantidad_disponible) AS total_empaques,
            SUM(v.cantidad_disponible * pres.pres_kg) AS total_kilos
        FROM vw_rev_revolturas_pt_cliente_disponible v
        INNER JOIN rev_revolturas rev 
            ON rev.rev_id = v.rev_id
        INNER JOIN rev_presentacion pres 
            ON pres.pres_id = v.pres_id
        INNER JOIN rev_clientes c 
            ON c.cte_id = v.cte_id
        WHERE rev.rev_count_etiquetado > 0
          AND v.cantidad_disponible > 0
        GROUP BY 
            v.cte_id,
            c.cte_nombre
        ORDER BY 
            c.cte_nombre ASC
    ";

    $listado_empacado_cliente = mysqli_query($cnx, $sql);

    if (!$listado_empacado_cliente) {
        throw new Exception(mysqli_error($cnx));
    }

    $datos = array();

    while ($fila = mysqli_fetch_assoc($listado_empacado_cliente)) {
        $fila['total_presentaciones'] = intval($fila['total_presentaciones']);
        $fila['total_empaques'] = floatval($fila['total_empaques']);
        $fila['total_kilos'] = floatval($fila['total_kilos']);

        $datos[] = $fila;
    }

    echo json_encode($datos);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
} finally {
    mysqli_close($cnx);
}