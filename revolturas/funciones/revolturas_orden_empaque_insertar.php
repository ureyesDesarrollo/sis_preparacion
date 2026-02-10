<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $rev_id = $_POST['rev_id'];
        $cantidades = $_POST['cantidad']; // Array de cantidades
        $presentaciones = $_POST['presentacion']; // Array de presentaciones

        // Validar que no haya cantidades en 0
        foreach ($cantidades as $cantidad) {
            if ($cantidad <= 0) {
                echo json_encode(["error" => "No se permiten cantidades en 0"]);
                exit;
            }
        }

        // Obtener los kilos disponibles de la revoltura
        $kilos = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT rev_kilos FROM rev_revolturas WHERE rev_id = '$rev_id'"));

        // Calcular el total de kilos a empacar
        $total_nuevo_empaques = 0;
        foreach ($cantidades as $index => $cantidad) {
            $pres_id = htmlspecialchars($presentaciones[$index]);
            $result = mysqli_query($cnx, "SELECT pres_kg FROM rev_presentacion WHERE pres_id = '$pres_id'");
            $pres_kg = mysqli_fetch_assoc($result)['pres_kg'];
            $total_nuevo_empaques += $cantidad * $pres_kg;
        }

        // Verificar si el total a empacar supera los kilos disponibles
        if ($total_nuevo_empaques > $kilos['rev_kilos']) {
            echo json_encode(["error" => "El total de kilos a empacar supera los kilos disponibles"]);
            exit;
        }

        $cnx->begin_transaction();

        // Crear la orden de empaque
        $sql_insert_orden = "INSERT INTO rev_orden_empaque (roe_estado) VALUES ('PENDIENTE')";
        if (!mysqli_query($cnx, $sql_insert_orden)) {
            throw new Exception("Error al crear la orden de empaque: " . $cnx->error);
        }
        $roe_id = mysqli_insert_id($cnx); // Obtener el ID de la orden recién creada

        // Insertar los detalles de la orden de empaque
        foreach ($cantidades as $index => $cantidad) {
            $pres_id = htmlspecialchars($presentaciones[$index]);
            $sql_insert_detalle = "INSERT INTO rev_orden_empaque_detalle (roe_id, rev_id, pres_id, roed_cantidad) 
                                   VALUES ('$roe_id', '$rev_id', '$pres_id', '$cantidad')";
            if (!mysqli_query($cnx, $sql_insert_detalle)) {
                throw new Exception("Error al insertar detalle de la orden: " . $cnx->error);
            }
        }

        $cnx->commit();
        echo json_encode(["success" => "Orden de empaque creada con éxito", "roe_id" => $roe_id]);

    } catch (Exception $e) {
        $cnx->rollback();
        echo json_encode(["error" => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
}
