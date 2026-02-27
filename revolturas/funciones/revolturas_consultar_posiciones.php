<?php
include "../../conexion/conexion.php";

$conn = Conectarse();

header('Content-Type: application/json');

if (!isset($_POST['rev_id']) || empty($_POST['rev_id'])) {
  echo json_encode([]);
  exit;
}

$rev_id = intval($_POST['rev_id']);

$sql = "SELECT
    r.rev_folio,
    r.rev_kilos,
    np.niv_codigo,
    rr.rac_descripcion
FROM rev_revolturas r
INNER JOIN rev_nivel_posicion_detalle npd
    ON r.rev_id = npd.rev_id
INNER JOIN rev_nivel_posicion np
    ON np.niv_id = npd.niv_id
INNER JOIN rev_racks rr
    ON rr.rac_id = np.rac_id
WHERE r.rev_id = $rev_id
ORDER BY
    rr.rac_descripcion,
    CAST(SUBSTRING(np.niv_codigo, 2) AS UNSIGNED),
    SUBSTRING(np.niv_codigo, 1, 1)
";

$result = $conn->query($sql);

$data = [];

if ($result) {
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
}

echo json_encode($data);
