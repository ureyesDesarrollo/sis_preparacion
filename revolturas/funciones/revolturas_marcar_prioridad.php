<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cnx = Conectarse();
        $id = $_POST['rev_id'];
        
        $sqlFechaFabricacion = mysqli_fetch_assoc(mysqli_query($cnx, 
        "SELECT DATE(rev_fecha) as rev_fecha FROM rev_revolturas WHERE rev_id = '$id'"))['rev_fecha'];  
        
        $fechaFabricacion = new DateTime($sqlFechaFabricacion);
        $fechaFabricacionNew = clone $fechaFabricacion;
        $fechaFabricacionNew->modify('-5 days');
        $fechaCaducidad = clone $fechaFabricacionNew;
        $fechaCaducidad->modify('+5 years');
        $fechaFabricacionNewFormateada = $fechaFabricacionNew->format('Y-m-d');
        $fechaCaducidadFormateada = $fechaCaducidad->format('Y-m-d');
        
        $sql = "UPDATE rev_revolturas SET rev_prioritario = '1', 
        rev_fecha_prioritaria = '$fechaFabricacionNewFormateada',
        rev_fecha_prioritaria_caducidad = '$fechaCaducidadFormateada' 
        WHERE rev_id = '$id'";

        if (mysqli_query($cnx, $sql)) {
            echo json_encode(['success' => 'Marcado como prioritario con exito.']);
        } else {
            echo json_encode(['error' => 'Error al marcar como prioritario. ' . mysqli_error($cnx)]);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => "Error {$e->getMessage()}"]);
    }finally{
        mysqli_close($cnx);
    }
}
