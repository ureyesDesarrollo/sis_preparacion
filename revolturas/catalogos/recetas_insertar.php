<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
//header("Content-Type: application/json");

include "../../seguridad/user_seguridad.php";
include "../../funciones/funciones.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    // Capturar el contenido del cuerpo de la solicitud
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);
    //var_dump($data);
    if (count($data['tarimas']) < 2) {
        echo json_encode(["status" => "error", "message" => "Debes agregar al menos dos tarimas."]);
        exit;
    }

    if ($data) {
        $descripcion = $data['descripcion'] ?? null;
        $cliente_id = $data['cliente_id'] ?? null;
        $tarimas = $data['tarimas'] ?? [];

        // Verificar que los datos clave no estén vacíos
        if ($descripcion && $cliente_id && !empty($tarimas)) {

            // Validar si ya existe la descripción para este cliente
            $sql_validacion = "SELECT 1 FROM rev_receta WHERE rre_descripcion = '" . mysqli_real_escape_string($cnx, $descripcion) . "' AND cte_id = '" . mysqli_real_escape_string($cnx, $cliente_id) . "'";
            $resultado_validacion = mysqli_query($cnx, $sql_validacion);
            if (mysqli_num_rows($resultado_validacion) > 0) {
                echo json_encode(["status" => "error", "message" => "La descripción ya existe para este cliente."]);
                exit;
            }

            $sql = "INSERT INTO rev_receta (rre_descripcion, cte_id) VALUES ('" . mysqli_real_escape_string($cnx, $descripcion) . "', '" . mysqli_real_escape_string($cnx, $cliente_id) . "')";
            if (mysqli_query($cnx, $sql)) {
                $rre_id = $cnx->insert_id; // Obtener el ID generado de la receta

                // Procesar las tarimas
                foreach ($tarimas as $tarima) {
                    $numero_tarima = mysqli_real_escape_string($cnx, $tarima['numero']);
                    $parametros = $tarima['parametros'];
                    $valores = $tarima['valor'];
                    $signos = $tarima['signo'];

                    // Insertar detalle de cada parámetro de la tarima
                    for ($i = 0; $i < count($parametros); $i++) {
                        $parametro = mysqli_real_escape_string($cnx, $parametros[$i]);
                        $valor = mysqli_real_escape_string($cnx, $valores[$i]);
                        $signo = mysqli_real_escape_string($cnx, $signos[$i]);

                        // Inserción de los detalles de la tarima
                        $sql_detalle = "INSERT INTO rev_receta_detalle (rrd_no_tarima, rp_id, rp_valor, rre_id, rrd_signo) 
                            VALUES ('$numero_tarima', '$parametro', '$valor', '$rre_id', '$signo')";
                        if (!mysqli_query($cnx, $sql_detalle)) {
                            echo json_encode(["status" => "error", "message" => "Error al insertar detalles de la tarima"]);
                            exit;
                        }
                    }
                }

                // Respuesta exitosa
                echo json_encode(["status" => "success", "message" => 'Receta creada correctamente']);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al crear la receta"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Datos JSON inválidos."]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
