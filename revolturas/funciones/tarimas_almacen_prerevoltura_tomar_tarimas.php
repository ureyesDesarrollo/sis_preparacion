<?php
header('Content-Type: application/json');
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

$tarimas = $_POST['tar_id'];



$data = [
    'status' => '',
    'message' => ''
];

// Validar que sea un arreglo
if (!is_array($tarimas)) {
    $data['status'] = 'error';
    $data['message'] = 'No es un arreglo válido.';
    die(json_encode($data));
}

// Verificar duplicados
if (count($tarimas) !== count(array_unique($tarimas))) {
    $data['status'] = 'error';
    $data['message'] = 'Existen tarimas duplicadas, por favor revisa las tarimas.';
    die(json_encode($data));
}

// Validar que sean numéricos
$tarimas = array_filter($tarimas, 'is_numeric');
if (empty($tarimas)) {
    $data['status'] = 'error';
    $data['message'] = 'No se encontraron tarimas válidas.';
    die(json_encode($data));
}

// Iniciar la transacción
mysqli_begin_transaction($cnx);

foreach ($tarimas as $tarima) {
    $sql = "UPDATE rev_tarimas SET tar_estatus = '2' WHERE tar_id = $tarima";
    
    // Si la consulta falla, hacer rollback y terminar
    if (!mysqli_query($cnx, $sql)) {
        $data['status'] = 'error';
        $data['message'] = 'Ocurrió un error al tomar las tarimas.';
        
        // Hacer rollback y devolver el error
        mysqli_rollback($cnx);
        die(json_encode($data));
    }
}

// Si todo fue bien, hacer commit
mysqli_commit($cnx);

// Respuesta exitosa
$data['status'] = 'success';
$data['message'] = 'Tarimas tomadas exitosamente.';
echo json_encode($data);

// Cerrar la conexión
mysqli_close($cnx);
?>
