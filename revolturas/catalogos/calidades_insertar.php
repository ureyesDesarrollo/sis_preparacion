<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $cal_descripcion = mysqli_real_escape_string($cnx, $_POST['cal_descripcion']);
        $cal_color = mysqli_real_escape_string($cnx, urldecode($_POST['cal_color']));

        // Normalizar la descripción (convertir a minúsculas y eliminar espacios innecesarios)
        $cal_descripcion_normalized = trim(strtolower($cal_descripcion));

        $cal_des = mysqli_query($cnx, "SELECT cal_id FROM rev_calidad WHERE LOWER(TRIM(cal_descripcion)) = '$cal_descripcion_normalized'");

        if (mysqli_num_rows($cal_des) > 0) {
            // El registro ya existe
            $res = "El registro ya existe";
            echo json_encode(["error" => $res]);
        } else {
            // Insertar nuevo registro
            $sql = "INSERT INTO rev_calidad (cal_descripcion,cal_color) VALUES ('$cal_descripcion','$cal_color')";

            if (mysqli_query($cnx, $sql)) {
                $pres_id = $cnx->insert_id;

                $res = "Nuevo registro creado exitosamente";
                ins_bit_acciones($_SESSION['idUsu'], 'A', $pres_id, '42');
                echo json_encode(["success" => $res]);
            } else {
                $res = $sql . "<br>" . mysqli_error($cnx);
                echo json_encode(["error" => $res]);
            }
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
}
