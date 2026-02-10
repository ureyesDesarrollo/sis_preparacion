<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    $listado_empacado_cliente = mysqli_query($cnx, "SELECT 
    rrc.cte_id,
    c.cte_nombre,
    COUNT(rrc.rrc_id) AS total_presentaciones,
    SUM(rrc.rrc_ext_real) AS total_empaques,
    SUM(rrc.rrc_ext_real * pres.pres_kg) AS total_kilos 
FROM 
    rev_revolturas_pt_cliente rrc
INNER JOIN 
    rev_revolturas rev ON rev.rev_id = rrc.rev_id 
INNER JOIN 
    rev_presentacion pres ON pres.pres_id = rrc.pres_id
INNER JOIN 
    rev_clientes c ON c.cte_id = rrc.cte_id
WHERE  
    rev.rev_count_etiquetado > 0
    AND rrc.rrc_ext_real > 0
GROUP BY 
    c.cte_id");

    $datos = array();

    while ($fila = mysqli_fetch_assoc($listado_empacado_cliente)) {
        $datos[] = $fila;
    }

    $json_empacado = json_encode($datos);

    echo $json_empacado;
} catch (Exception $e) {
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
