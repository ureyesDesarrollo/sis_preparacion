<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Junio-2024 */
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $vis_id = mysqli_real_escape_string($cnx, $_POST['vis_id']);
        $vis_descrip = mysqli_real_escape_string($cnx, $_POST['vis_descrip']);
        $vis_min_val = mysqli_real_escape_string($cnx, $_POST['vis_min_val']);
        $vis_max_val = mysqli_real_escape_string($cnx, $_POST['vis_max_val']);
        $vis_color = mysqli_real_escape_string($cnx, urldecode($_POST['vis_color']));

        // Verificar si existe otro registro con los mismos valores nuevos
        $check_sql = "SELECT vis_id FROM rev_viscosidades WHERE vis_descrip = '$vis_descrip' AND vis_min_val = '$vis_min_val' AND vis_max_val = '$vis_max_val' AND vis_color = '$vis_color' AND vis_id != '$vis_id'";
        $check_result = mysqli_query($cnx, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // El registro ya existe
            $res = "Ya existe un registro con los mismos valores";
            echo json_encode(["error" => $res]);
        } else {
            // Proceder con la actualización
            $sql = "UPDATE rev_viscosidades SET vis_descrip = '$vis_descrip', vis_min_val = '$vis_min_val', 
                    vis_max_val = '$vis_max_val', vis_color = '$vis_color' WHERE vis_id = '$vis_id'";

            if (mysqli_query($cnx, $sql)) {
                $res = "Registro actualizado exitosamente";
                ins_bit_acciones($_SESSION['idUsu'], 'E', $vis_id, '38');
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
