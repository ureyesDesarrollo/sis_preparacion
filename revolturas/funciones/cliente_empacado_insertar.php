<?php
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx = Conectarse();

try {
    // Validación básica de los datos recibidos
    if (isset($_POST['rr_id'], $_POST['rrc_cantidad'], $_POST['cte_id'])) {

        // Escapar los valores para prevenir inyección SQL
        $rev_id = mysqli_real_escape_string($cnx, $_POST['rev_id']);
        $rr_id = mysqli_real_escape_string($cnx, $_POST['rr_id']);
        $rrc_cantidad = mysqli_real_escape_string($cnx, $_POST['rrc_cantidad']);
        $cte_id = mysqli_real_escape_string($cnx, $_POST['cte_id']);
        $pres_id = mysqli_real_escape_string($cnx, $_POST['pres_id']);

        // Nuevos campos (opcionalmente validar si están seteados)
        $cte_tipo = isset($_POST['cte_tipo']) ? mysqli_real_escape_string($cnx, $_POST['cte_tipo']) : null;
        $cte_clasificacion = isset($_POST['cte_clasificacion']) ? mysqli_real_escape_string($cnx, $_POST['cte_clasificacion']) : null;

        // Validar que la cantidad no sea 0
        if ($rrc_cantidad != '0') {
            // Insertar en rev_revolturas_pt_cliente
            $sql = "INSERT INTO rev_revolturas_pt_cliente (rev_id, pres_id, rrc_ext_inicial, rrc_ext_real, cte_id) 
                    VALUES ('$rev_id', '$pres_id', '$rrc_cantidad', '$rrc_cantidad', '$cte_id')";

            if (mysqli_query($cnx, $sql)) {
                // Actualizar rev_revolturas_pt
                $sql_update = "UPDATE rev_revolturas_pt 
                               SET rr_ext_real = rr_ext_real - '$rrc_cantidad' 
                               WHERE rr_id = '$rr_id'";

                if (mysqli_query($cnx, $sql_update)) {
                    // Si hay tipo y clasificación, actualizarlos en rev_clientes
                    if (!is_null($cte_tipo) && !is_null($cte_clasificacion)) {
                        $sql_cliente = "UPDATE rev_clientes 
                                        SET cte_tipo = '$cte_tipo', 
                                            cte_clasificacion = '$cte_clasificacion' 
                                        WHERE cte_id = '$cte_id'";
                        mysqli_query($cnx, $sql_cliente); // Se puede ignorar error si no es crítico
                    }

                    echo json_encode(["success" => "Apartado correctamente."]);
                } else {
                    $res = "Error al actualizar la cantidad: " . mysqli_error($cnx);
                    echo json_encode(["error" => $res]);
                }
            } else {
                $res = "Error al insertar el registro: " . mysqli_error($cnx);
                echo json_encode(["error" => $res]);
            }
        } else {
            echo json_encode(["error" => "La cantidad debe ser mayor que 0."]);
        }
    } else {
        echo json_encode(["error" => "Todos los campos son obligatorios"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
?>
