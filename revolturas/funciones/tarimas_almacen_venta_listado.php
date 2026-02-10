<?php
/* Desarrollado por: Ulises Reyes */
/* Contacto: desarrollo@progel.com.mx */
/* Actualizado: Marzo-2025 */

//include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $cnx = Conectarse();
    $cnx->set_charset("utf8mb4");
    
    $query = "SELECT 
                tar_id,
                DATE(tar_fecha) AS tar_fecha,
                pro_id,
                pro_id_2,
                tar_fino,
                tar_folio,
                tar_kilos
              FROM rev_tarimas 
              WHERE tar_estatus = ?";
    
    $stmt = $cnx->prepare($query);
    $tar_estatus = 8;
    $stmt->bind_param("i", $tar_estatus);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $datos_tarimas_almacen = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($datos_tarimas_almacen, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (mysqli_sql_exception $e) {
    echo json_encode(["error" => "Error en la consulta. Contacte con el administrador. {$e->getMessage()}"]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($cnx)) {
        $cnx->close();
    }
}
?>
