<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST);
$res = "";
$mez_estatus = '0';
$usu_id = $_SESSION['idUsu'];
if (isset($_POST['mez_hora_ini'])) {
    try {

        $mez_hora_ini = new DateTime($_POST['mez_hora_ini']);
        $mez_hora_ini = $mez_hora_ini->format('H:i:s');

        if (!empty($_POST['mez_hora_fin'])) {
            $mez_hora_fin = new DateTime($_POST['mez_hora_fin']);
            $mez_hora_fin = $mez_hora_fin->format('H:i:s');

            $res = "Mezcla Terminada";
            $mez_estatus = '2'; // Terminado
        } else {

            $res = "Mezcla comenzada";
            $mez_estatus = '1'; // Proceso
        }

        $sql = "UPDATE rev_mezcla SET mez_hora_ini = '$mez_hora_ini', mez_hora_fin = '$mez_hora_fin', 
        mez_imanes_limpios = '$mez_imanes_limpios',mez_sacos_limpios = '$mez_sacos_limpios', 
        mez_libre_sobrantes = '$mez_libre_sobrantes', mez_mezcladora = '$mez_mezcladora', usu_id = '$usu_id',mez_estatus = '$mez_estatus' WHERE mez_id = '$mez_id'";

        if (mysqli_query($cnx, $sql)) {

            ins_bit_acciones($_SESSION['idUsu'], 'E', $mez_id, '46');
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
