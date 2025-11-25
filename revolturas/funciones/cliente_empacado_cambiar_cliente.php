<?php
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx = Conectarse();

try {
    // Validación básica de los datos recibidos
    if (isset($_POST['cte_id'], $_POST['rrc_id'])) {
        $cte_id = mysqli_real_escape_string($cnx, $_POST['cte_id']);
        $rrc_id = mysqli_real_escape_string($cnx, $_POST['rrc_id']);
        $sql = "UPDATE rev_revolturas_pt_cliente SET cte_id = '$cte_id' WHERE rrc_id = '$rrc_id'";

        if(!mysqli_query($cnx,$sql)){
            echo json_encode(["error" => 'Error al cambiar de cliente']);
        }

        echo json_encode(['success' => 'Empaque cambiado de cliente']);
    } else {
        $res = "Todos los campos son obligatorios";
        echo json_encode(["error" => $res]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
?>
