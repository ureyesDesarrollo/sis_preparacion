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
        $blo_id = mysqli_real_escape_string($cnx, $_POST['blo_id']);
        $blo_ini = mysqli_real_escape_string($cnx, $_POST['blo_ini']);
        $blo_fin = mysqli_real_escape_string($cnx, $_POST['blo_fin']);
        $blo_etiqueta = mysqli_real_escape_string($cnx, $_POST['blo_etiqueta']);
        $blo_estatus = isset($_POST['chk_estatus']) ? 'A' : 'B';

        // Verificar si la descripción se ha modificado
        $current_sql = "SELECT blo_ini, blo_fin, blo_etiqueta, blo_estatus FROM rev_bloom WHERE blo_id = '$blo_id'";
        $current_result = mysqli_query($cnx, $current_sql);
        $current_row = mysqli_fetch_assoc($current_result);

        $current_ini = $current_row['blo_ini'];
        $current_fin = $current_row['blo_fin'];
        $current_etiqueta = $current_row['blo_etiqueta'];
        $current_estatus = $current_row['blo_estatus'];

        // Verificar si se realizaron cambios
        $ini_changed = $current_ini !== $blo_ini;
        $fin_changed = $current_fin !== $blo_fin;
        $etiqueta_changed = $current_etiqueta !== $blo_etiqueta;
        $estatus_changed = $current_estatus != $blo_estatus;

        // Verificar si existe otro registro con los mismos valores de blo_ini, blo_fin y blo_etiqueta
        $check_sql = "SELECT blo_id FROM rev_bloom WHERE LOWER(blo_ini) = LOWER('$blo_ini') AND LOWER(blo_fin) = LOWER('$blo_fin') AND LOWER(blo_etiqueta) = LOWER('$blo_etiqueta') AND blo_id != '$blo_id'";
        $check_result = mysqli_query($cnx, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // El registro ya existe
            $res = "Ya existe un registro con los mismos valores";
            echo json_encode(["error" => $res]);
            mysqli_close($cnx);
            exit();
        }

        // Construir la consulta de actualización
        $sql = "UPDATE rev_bloom SET blo_ini = '$blo_ini', blo_fin = '$blo_fin', blo_etiqueta = '$blo_etiqueta', blo_estatus = '$blo_estatus' WHERE blo_id = '$blo_id'";

        // Ejecutar la consulta de actualización
        if (mysqli_query($cnx, $sql)) {
            if ($ini_changed || $fin_changed || $etiqueta_changed || $estatus_changed) {
                $res = "Registro actualizado exitosamente";
            } else {
                $res = "No se realizaron cambios en los datos";
            }
            ins_bit_acciones($_SESSION['idUsu'], 'E', $blo_id, '37');
            echo json_encode(["success" => $res]);
        } else {
            $res = $sql . "<br>" . mysqli_error($cnx);
            echo json_encode(["error" => $res]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
}
