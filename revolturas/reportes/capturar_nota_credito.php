<?php
header('Content-Type: application/json');

try {
  require_once "../../conexion/conexion.php";

  $folio_nota_credito = trim($_POST['nota_credito'] ?? '');

  if ($folio_nota_credito === '') {
    throw new Exception("Por favor ingresa el folio de la nota de crédito.");
  }

  $cnx = Conectarse();
  $conn = Conectarse2();

  $folio_nota_credito_sql = mysqli_real_escape_string($conn, $folio_nota_credito);

  $sql_consultar_nota = "
        SELECT *
        FROM creditos
        WHERE NO_NOTA = '{$folio_nota_credito_sql}'
        LIMIT 1
    ";

  $res_nota = mysqli_query($conn, $sql_consultar_nota);

  if (!$res_nota) {
    throw new Exception("Error al consultar nota de crédito: " . mysqli_error($conn));
  }

  if (mysqli_num_rows($res_nota) === 0) {
    throw new Exception("No se encontró la nota de crédito con el folio proporcionado.");
  }

  $row_nota = mysqli_fetch_assoc($res_nota);

  $total_nota = 0;

  if ((int)$row_nota['CVE_MON'] !== 1) {
    $total_nota = (float)$row_nota['TOT_NOTA'] * (float)$row_nota['TIP_CAM'];
  } else {
    $total_nota = (float)$row_nota['TOT_NOTA'];
  }

  $fecha_nota = mysqli_real_escape_string($cnx, $row_nota['FECHA']);
  $folio_nota_sql = mysqli_real_escape_string($cnx, $folio_nota_credito);

  $sql_insert_nota = "
        INSERT INTO notas_credito
            (fecha, folio_nota, tipo, total)
        VALUES
            ('{$fecha_nota}', '{$folio_nota_sql}', 'DESCUENTO', {$total_nota})
    ";

  if (!mysqli_query($cnx, $sql_insert_nota)) {
    throw new Exception("Error al insertar nota de crédito: " . mysqli_error($cnx));
  }

  echo json_encode([
    "success" => true,
    "message" => "Nota de crédito registrada correctamente.",
    "nota_credito" => $folio_nota_credito,
    "total" => $total_nota
  ]);
} catch (Exception $e) {
  echo json_encode([
    "success" => false,
    "error" => $e->getMessage()
  ]);
}
