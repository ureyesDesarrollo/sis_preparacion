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
        $rac_descrip = mysqli_real_escape_string($cnx,$_POST['rac_descripcion']);
        $rac_color = mysqli_real_escape_string($cnx, urldecode($_POST['rac_color']));
        $rac_zona = mysqli_real_escape_string($cnx,$_POST['rac_zona']);

        // Normalizar la descripción (convertir a minúsculas y eliminar espacios innecesarios)
        $rac_descrip_normalized = strtolower($rac_descrip);
        $rac_zona_normalized = strtolower($rac_zona);

        $rac_des = mysqli_query($cnx, "SELECT rac_id FROM rev_racks 
        WHERE LOWER(rac_descripcion) = '$rac_descrip_normalized' AND LOWER(rac_zona) = '$rac_zona'");

        if (mysqli_num_rows($rac_des) > 0) {
            // El registro ya existe
            $res = "El registro ya existe en esta zona";
            echo json_encode(["error" => $res]);
        } else {
            // Insertar nuevo registro
            $sql = "INSERT INTO rev_racks (rac_descripcion, rac_color, rac_zona) 
            VALUES ('$rac_descrip','$rac_color','$rac_zona')";

            if (mysqli_query($cnx, $sql)) {
                $rac_id = $cnx->insert_id;

                $res = "Nuevo registro creado exitosamente";
                ins_bit_acciones($_SESSION['idUsu'], 'A', $rac_id, '39');
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
