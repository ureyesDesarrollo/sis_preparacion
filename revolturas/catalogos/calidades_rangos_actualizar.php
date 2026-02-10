<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $blo_ini = $_POST['blo_ini'];
        $blo_fin = $_POST['blo_fin'];
        $vis_ini = $_POST['vis_ini'];
        $vis_fin = $_POST['vis_fin'];
        $cal_id = $_POST['cal_id'];
        $cr_id = $_POST['cr_id']; // ID del registro a actualizar

        $check_query = "SELECT cr_id FROM rev_calidad_rango 
                        WHERE blo_ini = '$blo_ini' AND blo_fin = '$blo_fin' 
                        AND vis_ini = '$vis_ini' AND vis_fin = '$vis_fin' 
                        AND cal_id = '$cal_id' AND cr_id != '$cr_id'";

        $check_result = mysqli_query($cnx, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Otro registro con los mismos valores ya existe
            $res = "Otro registro con los mismos valores ya existe";
            echo json_encode(["error" => $res]);
        } else {
            // Actualizar registro existente
            $sql = "UPDATE rev_calidad_rango 
                    SET blo_ini = '$blo_ini', blo_fin = '$blo_fin', vis_ini = '$vis_ini', vis_fin = '$vis_fin', cal_id = '$cal_id' 
                    WHERE cr_id = '$cr_id'";

            if (mysqli_query($cnx, $sql)) {
                $res = "Registro actualizado exitosamente";
                ins_bit_acciones($_SESSION['idUsu'], 'E', $cr_id, '43');
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
