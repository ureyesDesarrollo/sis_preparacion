<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $rac_id = mysqli_real_escape_string($cnx, $_POST['rac_id']);
        $filas = explode(',', strtoupper(str_replace(' ', '', $_POST['filas']))); // Convertir a array y eliminar espacios
        $niveles = intval($_POST['niveles']);

        if (empty($filas) || $niveles <= 0) {
            echo json_encode(["error" => "Debes ingresar filas y niveles válidos"]);
            exit();
        }

        // Define los racks que tendrán doble fondo
        $racks_doble_fondo = [13, 18];

        foreach ($filas as $fila) {
            for ($nivel = 1; $nivel <= $niveles; $nivel++) {

                // Si el rack es de doble fondo
                if (in_array($rac_id, $racks_doble_fondo)) {
                    $niv_codigo_frontal = "{$fila}{$nivel}F";
                    $niv_codigo_trasero = "{$fila}{$nivel}T";

                    // Insertar posición frontal
                    $sql_check_f = "SELECT * FROM rev_nivel_posicion WHERE rac_id = $rac_id AND niv_codigo = '$niv_codigo_frontal'";
                    $result_check_f = mysqli_query($cnx, $sql_check_f);
                    if (mysqli_num_rows($result_check_f) == 0) {
                        $sql_insert_f = "INSERT INTO rev_nivel_posicion (rac_id, niv_codigo) VALUES ($rac_id, '$niv_codigo_frontal')";
                        mysqli_query($cnx, $sql_insert_f);
                        $niv_id = $cnx->insert_id;
                        ins_bit_acciones($_SESSION['idUsu'], 'A', $niv_id, '40');
                    }

                    // Insertar posición trasera
                    $sql_check_t = "SELECT * FROM rev_nivel_posicion WHERE rac_id = $rac_id AND niv_codigo = '$niv_codigo_trasero'";
                    $result_check_t = mysqli_query($cnx, $sql_check_t);
                    if (mysqli_num_rows($result_check_t) == 0) {
                        $sql_insert_t = "INSERT INTO rev_nivel_posicion (rac_id, niv_codigo) VALUES ($rac_id, '$niv_codigo_trasero')";
                        mysqli_query($cnx, $sql_insert_t);
                        $niv_id = $cnx->insert_id;
                        ins_bit_acciones($_SESSION['idUsu'], 'A', $niv_id, '40');
                    }
                } else {
                    // Si no es doble fondo, inserta normalmente como A1, B2...
                    $niv_codigo = "{$fila}{$nivel}";

                    $sql_check = "SELECT * FROM rev_nivel_posicion WHERE rac_id = $rac_id AND niv_codigo = '$niv_codigo'";
                    $result_check = mysqli_query($cnx, $sql_check);
                    if (mysqli_num_rows($result_check) == 0) {
                        $sql_insert = "INSERT INTO rev_nivel_posicion (rac_id, niv_codigo) VALUES ($rac_id, '$niv_codigo')";
                        mysqli_query($cnx, $sql_insert);
                        $niv_id = $cnx->insert_id;
                        ins_bit_acciones($_SESSION['idUsu'], 'A', $niv_id, '40');
                    }
                }
            }
        }

        echo json_encode(["success" => "Posiciones registradas correctamente"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
}
