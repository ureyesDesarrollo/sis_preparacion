<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tarimas = isset($_POST['tarimas'])  ? $_POST['tarimas'] : [];

        $rev_folio = $_POST['rev_folio'];
        $usu_id = $_SESSION['idUsu'];
        $rev_kilos = $_POST['rev_kilos'];
        $rev_teo_bloom = $_POST['rev_teo_bloom'];
        $rev_teo_viscosidad = $_POST['rev_teo_viscosidad'];
        $rev_teo_calidad = $_POST['rev_teo_calidad'];
        $rev_teo_cliente = $_POST['rev_teo_cliente'];

        if (empty($tarimas)) {
            echo json_encode(["error" => 'No hay tarimas seleccionadas']);
            exit();
        }

        if(count($tarimas) < 2){
            echo json_encode(["error" => 'Debes seleccionar al menos 2 tarimas']);
            exit();
        }
        // Iniciar una transacción
        $cnx->begin_transaction();
        $sql_rev = "INSERT INTO rev_revolturas (rev_folio,usu_id,rev_kilos,rev_teo_bloom, rev_teo_viscosidad, 
        rev_teo_calidad,rev_teo_cliente,rev_count_etiquetado) 
        VALUES ('$rev_folio','$usu_id','$rev_kilos','$rev_teo_bloom','$rev_teo_viscosidad','$rev_teo_calidad','$rev_teo_cliente',1)";

        if (mysqli_query($cnx, $sql_rev)) {
            $rev_id = $cnx->insert_id;

            // Insertar en rev_revolturas_tarimas para cada tarima
            foreach ($tarimas as $tar_id) {
                $sql_rev_tar = "INSERT INTO rev_revolturas_tarimas (rev_id, tar_id) VALUES ($rev_id, $tar_id)";
                $actualizar_tarima = "UPDATE rev_tarimas SET tar_estatus = 3 WHERE tar_id = '$tar_id'";
                if (!mysqli_query($cnx, $sql_rev_tar)) {
                    throw new Exception("Error al insertar en rev_revolturas_tarimas: " . $cnx->error);
                }
                if (!mysqli_query($cnx, $actualizar_tarima)) {
                    throw new Exception("Error al actualizar en estatus en tarimas: " . $cnx->error);
                }
            }


            $cnx->commit();
            $res = "Revoltura creada correctamente";
            ins_bit_acciones($_SESSION['idUsu'], 'A', $rev_id, '46');
            echo json_encode(["success" => $res, "rev_id" => $rev_id]);
        } else {
            throw new Exception("Error al insertar en rev_revolturas: " . $cnx->error);
        }
    }
} catch (Exception $e) {
    // Si ocurre un error, revertir la transacción
    echo json_encode(["error" => $e->getMessage()]);
    $cnx->rollback();
} finally {
    mysqli_close($cnx);
}
