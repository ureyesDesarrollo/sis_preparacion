<?php
extract($_POST);
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx = Conectarse();

$inv_id = mysqli_real_escape_string($cnx, $_POST['inv_id']);
$ep_id = mysqli_real_escape_string($cnx, $_POST['ep_id']);
$usu_id = mysqli_real_escape_string($cnx, $_SESSION['idUsu']);

try {
    $ep_fecha = new DateTime($_POST['ep_fecha']);
    $ep_fecha_remojo = new DateTime($_POST['ep_fecha_remojo']);
    $ep_hora_ini_remojo = new DateTime($_POST['ep_hora_ini_remojo']);
    $ep_hora_ini_carga = new DateTime($_POST['ep_hora_ini_carga']);
    $ep_hora_fin_carga = new DateTime($_POST['ep_hora_fin_carga']);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Formato de fecha u hora inválido."]);
    exit;
}

$ep_fecha = $ep_fecha->format('Y-m-d H:i:s');
$ep_fecha_remojo = $ep_fecha_remojo->format('Y-m-d H:i:s');
$ep_hora_ini_remojo = $ep_hora_ini_remojo->format('H:i:s');
$ep_hora_ini_carga = $ep_hora_ini_carga->format('H:i:s');
$ep_hora_fin_carga = $ep_hora_fin_carga->format('H:i:s');

try {
    $query = "INSERT INTO inventario_pelambre (inv_id, ep_id, ip_fecha_envio, ip_fecha_remojo, ip_hora_ini_remojo, ip_hora_ini_carga, ip_hora_fin_carga, ip_ban,usu_id) 
          VALUES ('$inv_id', '$ep_id', '$ep_fecha', '$ep_fecha_remojo', '$ep_hora_ini_remojo', '$ep_hora_ini_carga', '$ep_hora_fin_carga','1', '$usu_id')";
    $result = mysqli_query($cnx, $query);

    mysqli_query($cnx, "UPDATE inventario SET inv_enviado = '6' WHERE inv_id = '$inv_id' ") or die(mysqli_error($cnx) . " Error al actualizar el inventario");

    $uptade_equipo = "UPDATE equipos_preparacion SET le_id = 10 WHERE ep_id = $ep_id";
    $resp_equipo = mysqli_query($cnx, $uptade_equipo);

    if ($result && $uptade_equipo) { //&& $res_up
        echo json_encode(["success" => 'Etapa 1 Registrada']);
    } else {
        echo json_encode(["success" => false, "error" => mysqli_error($cnx)]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Error en el servidor"]);
    exit;
} finally {
    mysqli_close($cnx);
}
