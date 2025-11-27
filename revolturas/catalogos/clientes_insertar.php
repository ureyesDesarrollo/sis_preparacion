<?php
header('Content-Type: application/json');
require_once '../../conexion/conexion.php';
include '../../seguridad/user_seguridad.php';
include '../../funciones/funciones.php';

try {
    $cnx = Conectarse();

    // Recoger y sanear campos
    $nombre        = mysqli_real_escape_string($cnx, trim($_POST['cte_nombre'] ?? ''));
    $rfc           = strtoupper(mysqli_real_escape_string($cnx, trim($_POST['cte_rfc'] ?? '')));
    $razonSocial   = mysqli_real_escape_string($cnx, trim($_POST['cte_razon_social'] ?? ''));
    $ubicacion     = mysqli_real_escape_string($cnx, trim($_POST['cte_ubicacion'] ?? ''));
    $tipo          = mysqli_real_escape_string($cnx, trim($_POST['cte_tipo'] ?? ''));
    $clasificacion = mysqli_real_escape_string($cnx, trim($_POST['cte_clasificacion'] ?? ''));
    $tipo_bloom    = mysqli_real_escape_string($cnx, trim($_POST['cte_tipo_bloom'] ?? ''));
    $bloom_min     = mysqli_real_escape_string($cnx, trim($_POST['cte_bloom_min'] ?? ''));

    // Validaciones
    if ($nombre === '' || $rfc === '' || $razonSocial === '' || $tipo === '' || $clasificacion === '') {
        throw new Exception('Todos los campos marcados con * son obligatorios.');
    }
    if (!in_array($tipo, ['Comercial','Industrial','Ambos'])) {
        throw new Exception('Tipo de cliente inválido.');
    }
    if (!in_array($clasificacion, ['AA','AAA'])) {
        throw new Exception('Clasificación inválida.');
    }

    // Construir condición para RFC duplicado, excluyendo el genérico
    $condRfc = $rfc !== 'XAXX010101000'
        ? " OR cte_rfc = '$rfc'"
        : "";

    // Verificar duplicados por nombre o por RFC (si aplica)
    $checkSql = "
      SELECT COUNT(*) AS count
      FROM rev_clientes
      WHERE cte_nombre = '$nombre'
            $condRfc
      LIMIT 1
    ";
    $resChk = mysqli_query($cnx, $checkSql);
    $fila   = mysqli_fetch_assoc($resChk);
    if ($fila['count'] > 0) {
        throw new Exception('Ya existe un cliente con ese nombre' . ($condRfc ? ' o RFC.' : '.'));
    }

    // Insertar nuevo cliente
    $sql = "
      INSERT INTO rev_clientes
        (cte_nombre, cte_rfc, cte_razon_social, cte_ubicacion, cte_tipo, cte_clasificacion, cte_tipo_bloom, cte_bloom_min)
      VALUES
        ('$nombre', '$rfc', '$razonSocial', '$ubicacion', '$tipo', '$clasificacion','$tipo_bloom', '$bloom_min')
    ";
    if (!mysqli_query($cnx, $sql)) {
        throw new Exception('Error al registrar el cliente: ' . mysqli_error($cnx));
    }

    $cte_id = mysqli_insert_id($cnx);
    ins_bit_acciones($_SESSION['idUsu'], 'A', $cte_id, '49');

    echo json_encode(['success' => 'Cliente registrado correctamente.']);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
