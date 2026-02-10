<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx = Conectarse();

try {
    // Validar parámetros
    if (!isset($_POST['parametros']) || !isset($_POST['valores'])) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Debes seleccionar al menos un parametro']);
        exit();
    }

    $parametros = $_POST['parametros'];
    $valores = $_POST['valores'];

    $calidad = (isset($_POST['calidad']) && !empty($_POST['calidad'])) ? intval($_POST['calidad']) : null;
    $tipo = (isset($_POST['tipo']) && !empty($_POST['tipo'])) ? mysqli_real_escape_string($cnx, $_POST['tipo']) : null;
    
    $ordenColumna = (isset($_POST['orden_columna']) && !empty($_POST['orden_columna'])) ? mysqli_real_escape_string($cnx, $_POST['orden_columna']) : 'tar_id';
    $ordenDireccion = (isset($_POST['orden_direccion']) && !empty($_POST['orden_direccion'])) ? mysqli_real_escape_string($cnx, $_POST['orden_direccion']) : 'ASC';
    

    // Construir condiciones WHERE dinámicamente
    $condicionesWhere = [];
    foreach ($parametros as $parametro) {
        if (isset($valores[$parametro . '_min']) && isset($valores[$parametro . '_max'])) {
            $valorMinimo = floatval($valores[$parametro . '_min']);
            $valorMaximo = floatval($valores[$parametro . '_max']);
            $condicionesWhere[] = "t.tar_$parametro BETWEEN $valorMinimo AND $valorMaximo";
        }
    }

    // Agregar condiciones para cal_id y tar_fino si están presentes
    if (!is_null($calidad)) {
        $condicionesWhere[] = "c.cal_id = $calidad";
    }
    if (!is_null($tipo)) {
        $condicionesWhere[] = "t.tar_fino = '$tipo'";
    }

    // Construir la cláusula WHERE
    $clausulaWhere = implode(' AND ', $condicionesWhere);

    // Construir la cláusula ORDER BY
    $ordenamiento = "ORDER BY t.$ordenColumna $ordenDireccion, t.tar_fecha ASC";

    $consulta = "SELECT t.*, c.cal_descripcion
                 FROM rev_tarimas t
                 INNER JOIN rev_calidad c ON t.cal_id = c.cal_id
                 WHERE t.tar_estatus = 1"
        . (!empty($clausulaWhere) ? " AND $clausulaWhere" : '')
        . " $ordenamiento";

    $resultadoConsulta = mysqli_query($cnx, $consulta);

    if (!$resultadoConsulta) {
        throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
    }

    $datosTarimas = array();
    while ($fila = mysqli_fetch_assoc($resultadoConsulta)) {
        $datosTarimas[] = $fila;
    }

    // Responder con los datos en formato JSON
    $response = [
        'status' => 'success',
        'datos' => $datosTarimas,
        'ordenColumna' => $ordenColumna,
        'ordenamiento' => $ordenDireccion
    ];

    echo json_encode($response);
} catch (Exception $e) {
    // Captura errores y responde con el mensaje
    echo json_encode(['status' => 'error', 'mensaje' => $e->getMessage()]);
} finally {
    // Cerrar conexión a la base de datos
    mysqli_close($cnx);
}
