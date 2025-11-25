<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();
$pro_id_1 = isset($_POST['pro_id_1']) ? mysqli_real_escape_string($cnx, $_POST['pro_id_1']) : '';
$pro_id_2 = isset($_POST['pro_id_2']) ? mysqli_real_escape_string($cnx, $_POST['pro_id_2']) : '';
$usu_id = isset($_POST['usu_id']) ? mysqli_real_escape_string($cnx, $_POST['usu_id']) : '';
$pro_id_pa = "{$pro_id_1}/{$pro_id_2}";
$isChecked = $_POST['isChecked'];
function procesoContieneRecorteOPrepacionAcida($cnx, $pro_id)
{
    // Escapar la variable para evitar inyecciones SQL
    $pro_id = mysqli_real_escape_string($cnx, $pro_id);

    // Consulta para verificar si el proceso tiene materiales tipo "Recorte" o "Preparación Ácida" 
    $query = "
    SELECT 1
    FROM procesos_materiales AS pm
    INNER JOIN inventario AS inv ON inv.inv_id = pm.inv_id
    INNER JOIN materiales AS m ON m.mat_id = pm.mat_id
    INNER JOIN materiales_tipo AS t ON m.mt_id = t.mt_id
    INNER JOIN procesos AS p ON p.pro_id = pm.pro_id
    INNER JOIN preparacion_tipo AS pt ON pt.pt_id = p.pt_id
    WHERE pm.pro_id = '$pro_id'
      AND (m.mat_id = 4 OR pt.pt_id = 7)
    LIMIT 1
";


    // Ejecutar la consulta
    $result = mysqli_query($cnx, $query);

    if (!$result) {
        throw new Exception("Error al verificar el proceso con ID $pro_id: " . mysqli_error($cnx));
    }

    // Devolver True si existe al menos un registro, False en caso contrario
    return mysqli_num_rows($result) > 0;
}
try {
    // Verificar si ambos procesos existen en la base de datos
    $query_pa_1 = "SELECT pa_id FROM procesos_agrupados WHERE pro_id = '$pro_id_1'";
    $query_pa_2 = "SELECT pa_id FROM procesos_agrupados WHERE pro_id = '$pro_id_2'";


    $result_1 = mysqli_query($cnx, $query_pa_1);
    $result_2 = mysqli_query($cnx, $query_pa_2);

    if (!$result_1 || !$result_2) {
        throw new Exception("Error al verificar los procesos: " . mysqli_error($cnx));
    }

    /* $esRecorte1 = procesoContieneRecorteOPrepacionAcida($cnx, $pro_id_1);
    $esRecorte2 = procesoContieneRecorteOPrepacionAcida($cnx, $pro_id_2);

    if (!$esRecorte1 && !$esRecorte2) {
        echo json_encode(["error" => "Ninguno de los procesos contiene materiales de tipo 'Recorte' o la tipo de preparación acida. Al menos uno debe serlo."]);
        exit;
    } */

    $pa_id_1 = mysqli_fetch_assoc($result_1)['pa_id'] ?? null;
    $pa_id_2 = mysqli_fetch_assoc($result_2)['pa_id'] ?? null;

    if (!$pa_id_1 || !$pa_id_2) {
        throw new Exception("Uno o ambos procesos no existen en la base de datos.");
    }

    // Actualizar los procesos agrupados
    $update_1 = "UPDATE procesos_agrupados SET pro_id_pa = '$pro_id_pa', usu_id_auth = '$usu_id' WHERE pa_id = '$pa_id_1'";
    $update_2 = "UPDATE procesos_agrupados SET pro_id_pa = '$pro_id_pa', usu_id_auth = '$usu_id' WHERE pa_id = '$pa_id_2'";
    $msg = '';
    if($isChecked === 'true') {	
        $update_cerrar_proceso2 = "UPDATE lotes_anio SET lote_estatus = '3' WHERE lote_id = (select lote_id from procesos_agrupados where pro_id = '$pro_id_2')";
        if(mysqli_query($cnx,$update_cerrar_proceso2)){
            $msg = "Se ha cerrado el proceso de recorte o preparación acida.";
        }else{
            throw new Exception("Error al cerrar el proceso de recorte: " . mysqli_error($cnx));
        }
    }

    if (mysqli_query($cnx, $update_1) && mysqli_query($cnx, $update_2)) {
        $res = "Se ha agrupado correctamente los procesos.".$msg;
        echo json_encode(["success" => $res]);
    } else {
        throw new Exception("Error al actualizar los procesos: " . mysqli_error($cnx));
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
