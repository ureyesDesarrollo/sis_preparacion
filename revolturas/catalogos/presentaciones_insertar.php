<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pres_descrip = $_POST['pres_descrip'];
        $pres_kg = $_POST['pres_kg'];

        // Normalizar la descripción (convertir a minúsculas y eliminar espacios innecesarios)
        $pres_descrip_normalized = trim(strtolower($pres_descrip));
        $pres_kg_normalized = trim(strtolower($pres_kg));


        $pres_des = mysqli_query($cnx, "SELECT pres_id FROM rev_presentacion WHERE LOWER(TRIM(pres_descrip)) = '$pres_descrip_normalized' 
        AND LOWER(TRIM(pres_kg)) = '$pres_kg_normalized'");

        if (mysqli_num_rows($pres_des) > 0) {
            // El registro ya existe
            $res = "El registro ya existe";
            echo json_encode(["error" => $res]);
        } else {
            // Insertar nuevo registro
            $sql = "INSERT INTO rev_presentacion (pres_descrip,pres_kg) VALUES ('$pres_descrip','$pres_kg')";

            if (mysqli_query($cnx, $sql)) {
                $pres_id = $cnx->insert_id;

                $res = "Nuevo registro creado exitosamente";
                ins_bit_acciones($_SESSION['idUsu'], 'A', $pres_id, '36');
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
