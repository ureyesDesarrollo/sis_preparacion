<?php
header("Content-Type: application/json");
include '../../conexion/conexion.php';
include "../../funciones/funciones.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cnx = Conectarse();
    $data = json_decode(file_get_contents("php://input"), true);
    $rev_id = $data['rev_id'];
    $total_kilos_empacados = $data['total_kilos'];

    // Ejecutar la consulta para obtener el valor de rev_kilos
    $query = mysqli_query($cnx, "SELECT rev_kilos FROM rev_revolturas WHERE rev_id = '$rev_id'");

    // Verificar si la consulta devolvió resultados
    if ($row = mysqli_fetch_assoc($query)) {
        $rev_kilos = $row['rev_kilos'];
        $rev_merma = $rev_kilos - $total_kilos_empacados;

        // Actualizar la merma
        $sql_merma = "UPDATE rev_revolturas SET rev_merma = '$rev_merma' WHERE rev_id = '$rev_id'";
        $sql = mysqli_query($cnx, $sql_merma);

        if ($sql) {
            echo json_encode(["success" => true, "mensaje" => "Merma actualizada correctamente: $rev_merma kg"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "mensaje" => "Error al actualizar la merma: " . mysqli_error($cnx)]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "mensaje" => "Revoltura no encontrada"]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
