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
$rev_estatus = '0';
$e_estatus = '0';
$usu_id = $_SESSION['idUsu'];
if (isset($_POST['rev_hora_ini'])) {
    try {

        if (!isset($_POST['rev_mezcladora']) || empty($_POST['rev_mezcladora'])) {
            die(json_encode(['error' => 'Debes seleccionar una mezcladora. Si no te permite termina las revolturas pendientes.']));
        }
        $rev_hora_ini = new DateTime($_POST['rev_hora_ini']);
        $rev_hora_ini = $rev_hora_ini->format('H:i:s');

        if (!empty($_POST['rev_hora_fin'])) {
            $rev_hora_fin = new DateTime($_POST['rev_hora_fin']);
            $rev_hora_fin = $rev_hora_fin->format('H:i:s');

            $res = "Revoltura Terminada";
            $rev_estatus = '2'; // Terminado
            $e_estatus = '1'; // Liberado
        } else {

            $res = "Revoltura comenzada";
            $rev_estatus = '1'; // Proceso
            $e_estatus = '2'; // Ocupado
        }

        $sql = "UPDATE rev_revolturas SET rev_hora_ini = '$rev_hora_ini', rev_hora_fin = '$rev_hora_fin', 
        rev_imanes_limpios = '$rev_imanes_limpios',rev_sacos_limpios = '$rev_sacos_limpios', 
        rev_libre_sobrantes = '$rev_libre_sobrantes', rev_mezcladora = '$rev_mezcladora', usu_id = '$usu_id',rev_estatus = '$rev_estatus' WHERE rev_id = '$rev_id'";
        $ocupar_equipo = "UPDATE rev_equipos SET e_estatus = '$e_estatus' WHERE e_id = '$rev_mezcladora'";
        if (mysqli_query($cnx, $sql) && mysqli_query($cnx,$ocupar_equipo)) {

            ins_bit_acciones($_SESSION['idUsu'], 'E', $rev_id, '46');
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
