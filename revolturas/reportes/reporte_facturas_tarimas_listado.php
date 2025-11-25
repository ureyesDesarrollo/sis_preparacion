<?php

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    $sql = "SELECT tf.*,t.*, cte.cte_nombre FROM rev_tarimas_facturas tf 
    INNER JOIN rev_tarimas t ON t.tar_id = tf.tar_id 
    INNER JOIN rev_clientes cte ON cte.cte_id = tf.cte_id";

    $facturas_tarimas = [];

    $result = mysqli_query($cnx, $sql);

    while ($fila = mysqli_fetch_assoc($result)) {
        $facturas_tarimas[] = $fila;
    }

    echo json_encode($facturas_tarimas);
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
