<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../funciones/funciones.php";
include "../../conexion/conexion.php";

    $cnx = Conectarse();
try{
    
    $idReceta = $_POST['id_receta'];
    $query = "SELECT 
    c.cte_nombre AS Cliente,
    c.cte_id AS ID_Cliente,
    rre.rre_descripcion AS Descripcion_Receta,
    rre.rre_id AS ID_Receta,
    rrd.rrd_no_tarima AS No_Tarima,
    rp.rp_campo AS Parametro,
    rp.rp_parametro AS Nombre_parametro,
    rp.rp_id AS ID_Parametro,
    rrd.rp_valor AS Valor,
    rrd.rrd_signo AS Comparacion
    FROM rev_receta rre
    INNER JOIN rev_clientes c ON rre.cte_id = c.cte_id
    LEFT JOIN rev_receta_detalle rrd ON rre.rre_id = rrd.rre_id
    LEFT JOIN rev_parametros rp ON rrd.rp_id = rp.rp_id
    WHERE rrd.rre_id = '$idReceta' ORDER BY rrd.rrd_no_tarima ASC, rrd.rrd_id ASC";

    $tarimas = array();

    $listado = mysqli_query($cnx, $query);

    while ($fila = mysqli_fetch_assoc($listado)) {
        $tarimas[] = $fila;
    }

    echo json_encode($tarimas);
    exit;
}catch(Exception $e){
    echo json_encode($e->getMessage());
}finally{
    mysqli_close($cnx);
}
?>