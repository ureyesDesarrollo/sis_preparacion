<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        if (isset($_POST['action']) && $_POST['action'] == 'terminar') {
            $rev_id = $_POST['rev_id'];
            $rev_terminado = isset($_POST['estatus']) ? $_POST['estatus'] : 0;
            $sql_terminar_revoltura = "UPDATE rev_revolturas SET rev_estatus = '3', rev_fecha_empacado = NOW() WHERE rev_id = '$rev_id'";
            $listado_tarimas = mysqli_query($cnx, "SELECT t.tar_id
            FROM rev_tarimas t
            JOIN rev_revolturas_tarimas rt ON t.tar_id = rt.tar_id
            JOIN rev_revolturas r ON rt.rev_id = r.rev_id
            WHERE t.tar_estatus = 3 AND r.rev_id = '$rev_id'");
            $datos_tarimas = array();

            while ($fila = mysqli_fetch_assoc($listado_tarimas)) {
                $datos_tarimas[] = $fila['tar_id'];
            }


            if ($rev_terminado == '1') {
                if (!mysqli_query($cnx, $sql_terminar_revoltura)) {
                    throw new Exception("Error al actualizar estatus revoltura: " . $cnx->error);
                }

                //Estatus 6: tarima empacada
                foreach ($datos_tarimas as $tarima) {
                    if (!mysqli_query($cnx, "UPDATE rev_tarimas SET tar_estatus = 6 WHERE tar_id = '$tarima'")) {
                        throw new Exception("Error al actualizar estatus revoltura: " . $cnx->error);
                    }
                }
            }
        } else {

            $rev_id = $_POST['rev_id'];
            $cantidades = $_POST['cantidad'];
            $presentaciones = $_POST['presentacion'];
            $rev_terminado = 0;

            // Validar que no haya cantidades en 0
            foreach ($cantidades as $cantidad) {
                if ($cantidad <= 0) {
                    echo json_encode(["error" => "No se permiten cantidades en 0"]);
                    exit;
                }
            }

            // Obtener kilos de la revoltura
            $kilos = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT rev_kilos FROM rev_revolturas WHERE rev_id = '$rev_id'"));

            // Obtener los empaques ya existentes en la revoltura
            $empaques = mysqli_query($cnx, "SELECT rp.rr_ext_inicial, p.pres_kg FROM rev_revolturas_pt rp 
            JOIN rev_presentacion p ON rp.pres_id = p.pres_id WHERE rp.rev_id = '$rev_id'");

            // Obtener el total de los nuevos empaques a agregar
            $total_nuevo_empaques = 0;
            foreach ($cantidades as $index => $cantidad) {
                $presentacion = htmlspecialchars($presentaciones[$index]);

                $result = mysqli_query($cnx, "SELECT pres_kg FROM rev_presentacion WHERE pres_id = '$presentacion'");
                $pres_kg = mysqli_fetch_assoc($result)['pres_kg'];

                // Calcular los kilos totales para la cantidad y presentación actual
                $total_nuevo_empaques += $cantidad * $pres_kg;
            }

            // Verificar si el total de kilos a insertar supera los kilos disponibles
            if ($total_nuevo_empaques > $kilos['rev_kilos']) {
                echo json_encode(["error" => "El total de kilos a empacar supera los kilos disponibles"]);
                exit;
            }

            // Total de empaques ya registrados
            $total_empaques = 0;
            while ($fila = mysqli_fetch_assoc($empaques)) {
                $kg = $fila['rr_ext_inicial'] * $fila['pres_kg'];
                $total_empaques += $kg;
            }

            // Verificar si la suma de los kilos ya empaquetados más los nuevos supera los kilos disponibles
            if (($total_empaques + $total_nuevo_empaques) > $kilos['rev_kilos']) {
                echo json_encode(["error" => "No puedes tomar más kilos de la revoltura. Total máximo: " . $kilos['rev_kilos'] . " kg"]);
                exit;
            }

            $cnx->begin_transaction();

            foreach ($cantidades as $index => $cantidad) {
                $presentacion = htmlspecialchars($presentaciones[$index]);
                $sql = "INSERT INTO rev_revolturas_pt (rev_id, pres_id, rr_ext_inicial, rr_ext_real) VALUES ('$rev_id', '$presentacion', '$cantidad', '$cantidad')";

                if (!mysqli_query($cnx, $sql)) {
                    throw new Exception("Error al insertar en rev_revolturas_pt: " . $cnx->error);
                }
            }

            $cnx->commit();
            $res = "Empacado correctamente";
            #ins_bit_acciones($_SESSION['idUsu'], 'A', $rev_id, '46');
            echo json_encode(["success" => $res]);
        }
    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        echo json_encode(["error" => $e->getMessage()]);
        $cnx->rollback();
    } finally {
        mysqli_close($cnx);
    }
}
