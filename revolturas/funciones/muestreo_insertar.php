<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Agosto-2024 */

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();
try {
    // Decodifica los datos recibidos
    $data = json_decode($_POST['data'], true);
    $muestras = $data['muestra'];
    $rev_id = $data['rev_id'];
    $pres_ids = $data['pres_id']; // Array de pres_ids para cada muestra

    $muestras_fallidas = [];
    $cnx->begin_transaction();

    foreach ($muestras as $index => $muestra) {
        $renglon = $index + 1;
        $pres_id = $pres_ids[$index]; // Obtener el pres_id correspondiente para cada muestra

        // Obtener pres_kg para el pres_id actual
        $query_pres_kg = "SELECT pres_kg FROM rev_presentacion WHERE pres_id = '$pres_id'";
        $result_pres_kg = mysqli_query($cnx, $query_pres_kg);
        if (!$result_pres_kg) {
            throw new Exception("Error al consultar pres_kg: " . mysqli_error($cnx));
        }
        $row_pres_kg = mysqli_fetch_assoc($result_pres_kg);
        $pres_kg = $row_pres_kg['pres_kg'];

        // Verificar si la muestra está dentro del rango permitido
        if ((float)$muestra > (float)$pres_kg || (float)$muestra < (float)$pres_kg) {
            $muestras_fallidas[] = $renglon;
        }

        // Insertar datos en la base de datos
        $query_insert = "INSERT INTO rev_revolturas_pt_muestreo (rm_ren, rev_id, pres_id, rm_kilos) 
                         VALUES ('$renglon', '$rev_id', '$pres_id', '$muestra')";

        if (!mysqli_query($cnx, $query_insert)) {
            throw new Exception("Error al insertar en rev_revolturas_pt_muestreo: " . mysqli_error($cnx));
        }
    }

    $cnx->commit();
    $res = "Muestras registradas correctamente";
    echo json_encode(["success" => $res, "fallidos" => $muestras_fallidas]);
} catch (Exception $e) {
    $cnx->rollback();
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
