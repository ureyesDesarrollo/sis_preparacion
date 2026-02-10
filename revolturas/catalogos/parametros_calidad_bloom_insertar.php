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
        $blo_ini = mysqli_real_escape_string($cnx, $_POST['blo_ini']);
        $blo_fin = mysqli_real_escape_string($cnx, $_POST['blo_fin']);
        $blo_etiqueta = mysqli_real_escape_string($cnx, $_POST['blo_etiqueta']);

        // Verificar si el registro ya existe
        $check_sql = "SELECT blo_id FROM rev_bloom WHERE blo_ini = '$blo_ini' AND blo_fin = '$blo_fin' AND blo_etiqueta = '$blo_etiqueta'";
        $check_result = mysqli_query($cnx, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // El registro ya existe
            $res = "El registro ya existe";
            echo json_encode(["error" => $res]);
        } else {
            // Insertar nuevo registro
            $sql = "INSERT INTO rev_bloom (blo_ini, blo_fin, blo_etiqueta) 
                    VALUES ('$blo_ini', '$blo_fin', '$blo_etiqueta')";

            if (mysqli_query($cnx, $sql)) {
                $blo_id = $cnx->insert_id;
                $res = "Nuevo registro creado exitosamente";
                ins_bit_acciones($_SESSION['idUsu'], 'A', $blo_id, '37');
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
