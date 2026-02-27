<?php
header('Content-Type: application/json');
require_once "../../conexion/conexion.php";

$cnx = Conectarse();

$tipo  = $_POST['ft_tipo'] ?? '';
$valor = $_POST['valor'] ?? '';

if (empty($tipo) || empty($valor)) {
  echo json_encode([
    "status" => "error",
    "message" => "Tipo y valor son requeridos."
  ]);
  exit;
}

/* Sanitizar valor */
$valor = mysqli_real_escape_string($cnx, $valor);

/* Definir campo */
if ($tipo === 'V') {
  $campo = "f.ft_vale_salida";
} else {
  $campo = "f.ft_factura";
}

$sql = "
    SELECT
        f.tar_id,
        f.ft_factura,
        f.ft_vale_salida,
        t.tar_folio,
        t.pro_id,
        t.pro_id_2,
        t.tar_kilos,
        t.tar_estatus,
        t.tar_fecha
    FROM rev_tarimas_facturas f
    INNER JOIN rev_tarimas t
        ON t.tar_id = f.tar_id
    WHERE $campo = '$valor'
";

$result = mysqli_query($cnx, $sql);

if (!$result) {
  echo json_encode([
    "status" => "error",
    "message" => mysqli_error($cnx)
  ]);
  exit;
}

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}

echo json_encode([
  "status" => "success",
  "tipo"   => $tipo,
  "valor"  => $valor,
  "total"  => count($data),
  "data"   => $data
]);
