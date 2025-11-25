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
        $mez_folio = $_POST['mez_folio'];
        $usu_id = $_SESSION['idUsu'];
        $mez_kilos = $mez_kilos = $_POST['mez_kilos'];

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
        $sql_rev = "INSERT INTO rev_mezcla (mez_folio,usu_id,mez_kilos) VALUES ('$mez_folio','$usu_id','$mez_kilos')";

        if (mysqli_query($cnx, $sql_rev)) {
            $mez_id = $cnx->insert_id;

            // Insertar en rev_mezcla_tarimas para cada tarima
            foreach ($tarimas as $tar_id) {
                $sql_mez_tar = "INSERT INTO rev_mezclas_tarimas (mez_id, tar_id) VALUES ($mez_id, $tar_id)";
                $actualizar_tarima = "UPDATE rev_tarimas SET tar_estatus = 5 WHERE tar_id = '$tar_id'";
                if (!mysqli_query($cnx, $sql_mez_tar)) {
                    throw new Exception("Error al insertar en rev_mezclas_tarimas: " . $cnx->error);
                }
                if (!mysqli_query($cnx, $actualizar_tarima)) {
                    throw new Exception("Error al actualizar en estatus en tarimas: " . $cnx->error);
                }
            }


            $cnx->commit();
            $res = "Mezcla creada correctamente";
            ins_bit_acciones($_SESSION['idUsu'], 'A', $mez_id, '48');
            echo json_encode(["success" => $res]);
        } else {
            throw new Exception("Error al insertar en rev_mezcla: " . $cnx->error);
        }
    }
} catch (Exception $e) {
    // Si ocurre un error, revertir la transacción
    echo json_encode(["error" => $e->getMessage()]);
    $cnx->rollback();
} finally {
    mysqli_close($cnx);
}
