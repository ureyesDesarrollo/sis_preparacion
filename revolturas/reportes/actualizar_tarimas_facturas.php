<?php
require_once "../../conexion/conexion.php";
$cnx = Conectarse();

$tarimas = json_decode($_POST['tarimas'], true);

if (!$tarimas) {
  echo json_encode(["status" => "error"]);
  exit;
}

foreach ($tarimas as $t) {

  $tar_id  = (int)$t['tar_id'];
  $kilos   = (float)$t['ft_kilos_facturados'];
  $factura = mysqli_real_escape_string($cnx, $t['ft_factura']);

  $sql = "
        UPDATE rev_tarimas_facturas
        SET
            ft_kilos_facturados = $kilos,
            ft_factura = '$factura'
        WHERE tar_id = $tar_id
    ";

  mysqli_query($cnx, $sql);
}

echo json_encode(["status" => "success"]);
