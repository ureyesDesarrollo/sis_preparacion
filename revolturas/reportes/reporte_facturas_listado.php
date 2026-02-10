<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    $query = "SELECT 
    rpf.fe_factura,
    rpf.fe_cartaporte,
    rpf.fe_cantidad,
    rpf.fe_tipo,
    c.cte_nombre,
    rpf.fe_fecha,
    rev.rev_folio,
    rev.rev_id,
    rp.pres_descrip,
    rp.pres_kg,
    COALESCE(rr.rr_id, rrc.rrc_id) AS referencia_id  -- Mostrar el que exista
FROM 
    rev_revolturas_pt_facturas rpf
INNER JOIN 
    rev_clientes c ON c.cte_id = rpf.cte_id
LEFT JOIN 
    rev_revolturas_pt rr ON rr.rr_id = rpf.rr_id  -- Facturas con rr_id
LEFT JOIN 
    rev_revolturas_pt_cliente rrc ON rrc.rrc_id = rpf.rrc_id  -- Facturas con rrc_id
LEFT JOIN 
    rev_presentacion rp ON rp.pres_id = COALESCE(rr.pres_id, rrc.pres_id)  -- Tomar la presentación correcta
LEFT JOIN 
    rev_revolturas rev ON rev.rev_id = COALESCE(rr.rev_id, rrc.rev_id) ORDER BY rpf.fe_fecha DESC;";



    $listado_facturas = mysqli_query($cnx, $query);


    $res = array();
    while ($fila = mysqli_fetch_assoc($listado_facturas)) {
        $res[] = $fila;
    }

    echo json_encode($res);
} catch (Exception $e) {
    echo json_encode($e->getMessage());
}
