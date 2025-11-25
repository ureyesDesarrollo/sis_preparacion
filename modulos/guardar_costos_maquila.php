<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/

include "../seguridad/user_seguridad.php";
include "../funciones/funciones.php";
include "../conexion/conexion.php";
$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $ids = $_POST['ids'];
        $costos = $_POST['costos'];

        // Iniciar la transacción
        mysqli_begin_transaction($cnx);

        // Recorrer los datos y guardarlos
        for ($i = 0; $i < count($ids); $i++) {
            $id = $ids[$i];
            $costo = $costos[$i];

            // Asegúrate de que $costo sea un valor numérico antes de usarlo en la consulta
            if (!is_numeric($costo)) {
                throw new Exception('Costo no válido para el ID: ' . $id);
            }

            $sql = "UPDATE inventario 
                    SET inv_costo_mql = $costo 
                    WHERE inv_id = $id";

            $res = mysqli_query($cnx, $sql);

            // Verificar si la consulta falló
            if (!$res) {
                throw new Exception('Ocurrió un error al registrar el costo para el ID: ' . $id);
            }
        }

        // Si todas las consultas se ejecutaron correctamente, confirmar la transacción
        mysqli_commit($cnx);
        echo json_encode(['status' => 'success', 'message' => 'Costos de Maquila registrados con éxito']);
    } catch (Exception $e) {
        // Si hubo un error, revertir la transacción
        mysqli_rollback($cnx);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
