<?php
include "../../conexion/conexion.php";
header('Content-Type: application/json');

$cnx = Conectarse();
$data = json_decode(file_get_contents("php://input"), true);

$orden_id = isset($data['orden_id']) ? (int)$data['orden_id'] : 0;

if ($orden_id <= 0) {
  echo json_encode([
    "success" => false,
    "message" => "Orden no válida"
  ]);
  exit;
}

/*
|--------------------------------------------------------------------------
| DATOS FIJOS DEL REMITENTE
|--------------------------------------------------------------------------
| Si después quieres, esto puede salir de una tabla de configuración.
*/
$remitente_factura_nombre = "PROGEL MEXICANA S.A. DE C.V.";
$remitente_factura_rfc = "PME9111259H7";
$remitente_factura_direccion = "CIPRES NO. 306, COL. OBREGON C.P. 37320, LEÓN, GTO.";

$remitente_remision_nombre = "SR. JORGE GARCIA RAMIREZ";
$remitente_remision_rfc = "";
$remitente_remision_direccion = "MA. CRISTINA NO. 221, COL. LOMA BONITA ";

$lugar_expedicion = "LEÓN, GTO.";

/*
|--------------------------------------------------------------------------
| CONSULTA PRINCIPAL
|--------------------------------------------------------------------------
*/
$sql = "SELECT
    oe.oe_id AS orden_id,
    oe.oe_fecha AS fecha_creacion,
    oe.cte_id AS cliente_id,
    cte.cte_nombre AS cliente_nombre,
    cte.cte_rfc AS destinatario_rfc,
    cte.cte_direccion_fiscal AS destinatario_direccion_fiscal,
    oe.oe_estado AS estado,

    oed.oed_id,
    oed.cantidad AS cantidad_solicitada,
    oed.oed_tipo_producto AS tipo_producto,
    oed.bloom_vendido,

    CASE
        WHEN oed.oed_tipo_producto = 'EXTERNO' THEN pe.pe_lote
        WHEN rr.rev_id IS NOT NULL THEN rev.rev_folio
        WHEN rrc.rev_id IS NOT NULL THEN rrc_rev.rev_folio
        ELSE 'Producto General'
    END AS rev_folio,

    COALESCE(rr_pres.pres_id, rrc_pres.pres_id, pe_pres.pres_id) AS presentacion_id,
    COALESCE(rr_pres.pres_descrip, rrc_pres.pres_descrip, pe_pres.pres_descrip) AS presentacion_descripcion,
    COALESCE(rr_pres.pres_kg, rrc_pres.pres_kg, pe_pres.pres_kg, 0) AS pres_kg,

    CASE
        WHEN oed.oed_tipo_producto = 'EXTERNO' THEN 'EXTERNO'
        WHEN rr.rr_id IS NOT NULL THEN 'GENERAL'
        WHEN rrc.rrc_id IS NOT NULL THEN 'CLIENTE'
        ELSE 'GENERAL'
    END AS tipo_revoltura,

    (oed.cantidad * COALESCE(rr_pres.pres_kg, rrc_pres.pres_kg, pe_pres.pres_kg, 0)) AS total_kgs_partida

FROM rev_orden_embarque oe

INNER JOIN rev_orden_embarque_detalle oed
    ON oe.oe_id = oed.oe_id

LEFT JOIN rev_revolturas_pt rr
    ON rr.rr_id = oed.rr_id
LEFT JOIN rev_revolturas rev
    ON rev.rev_id = rr.rev_id
LEFT JOIN rev_presentacion rr_pres
    ON rr_pres.pres_id = rr.pres_id

LEFT JOIN rev_revolturas_pt_cliente rrc
    ON rrc.rrc_id = oed.rrc_id
LEFT JOIN rev_revolturas rrc_rev
    ON rrc_rev.rev_id = rrc.rev_id
LEFT JOIN rev_presentacion rrc_pres
    ON rrc_pres.pres_id = rrc.pres_id

LEFT JOIN producto_externo pe
    ON pe.pe_id = oed.pe_id
LEFT JOIN rev_presentacion pe_pres
    ON pe_pres.pres_id = pe.pres_id

LEFT JOIN rev_clientes cte
    ON oe.cte_id = cte.cte_id

WHERE oe.oe_id = $orden_id
ORDER BY oed.oed_id";

$datos = mysqli_query($cnx, $sql);

if (!$datos) {
  echo json_encode([
    "success" => false,
    "message" => "Error en la consulta principal",
    "error" => mysqli_error($cnx)
  ]);
  exit;
}

if (mysqli_num_rows($datos) === 0) {
  echo json_encode([
    "success" => false,
    "message" => "Orden no encontrada"
  ]);
  exit;
}

$detalle = [];
$total_kgs = 0;
$encabezado = null;

while ($row = mysqli_fetch_assoc($datos)) {
  if ($encabezado === null) {
    $encabezado = [
      "orden_id" => $row["orden_id"],
      "fecha_creacion" => $row["fecha_creacion"],
      "cliente_id" => $row["cliente_id"],
      "cliente_nombre" => $row["cliente_nombre"],
      "destinatario_nombre" => $row["cliente_nombre"],
      "destinatario_rfc" => $row["destinatario_rfc"],
      "destinatario_direccion_fiscal" => $row["destinatario_direccion_fiscal"],
      "estado" => $row["estado"],

      "remitente_nombre" => $remitente_factura_nombre,
      "remitente_rfc" => $remitente_factura_rfc,
      "remitente_direccion" => $remitente_factura_direccion,

      "remitente_factura_nombre" => $remitente_factura_nombre,
      "remitente_factura_rfc" => $remitente_factura_rfc,
      "remitente_factura_direccion" => $remitente_factura_direccion,

      "remitente_remision_nombre" => $remitente_remision_nombre,
      "remitente_remision_rfc" => $remitente_remision_rfc,
      "remitente_remision_direccion" => $remitente_remision_direccion,

      "lugar_expedicion" => $lugar_expedicion
    ];
  }

  $total_kgs += (float)$row["total_kgs_partida"];

  $detalle[] = [
    "oed_id" => $row["oed_id"],
    "cantidad_solicitada" => $row["cantidad_solicitada"],
    "tipo_producto" => $row["tipo_producto"],
    "bloom_vendido" => $row["bloom_vendido"],
    "rev_folio" => $row["rev_folio"],
    "presentacion_id" => $row["presentacion_id"],
    "presentacion_descripcion" => $row["presentacion_descripcion"],
    "pres_kg" => $row["pres_kg"],
    "tipo_revoltura" => $row["tipo_revoltura"],
    "total_kgs_partida" => $row["total_kgs_partida"]
  ];
}

/*
|--------------------------------------------------------------------------
| DIRECCIONES DE ENTREGA DEL CLIENTE
|--------------------------------------------------------------------------
*/
$direcciones_entrega = [];

$sql_direcciones = "SELECT
    id,
    direccion_entrega
FROM rev_clientes_direcciones_entrega
WHERE cte_id = " . (int)$encabezado["cliente_id"] . "
ORDER BY id";

$res_direcciones = mysqli_query($cnx, $sql_direcciones);

if ($res_direcciones) {
  while ($dir = mysqli_fetch_assoc($res_direcciones)) {
    $direcciones_entrega[] = [
      "id" => $dir["id"],
      "alias" => "DIRECCIÓN " . $dir["id"],
      "direccion" => $dir["direccion_entrega"]
    ];
  }
}

/*
|--------------------------------------------------------------------------
| TRANSPORTISTAS ACTIVOS
|--------------------------------------------------------------------------
*/
$transportistas = [];

$sql_transportistas = "SELECT
    trans_id,
    trans_nombre
FROM rev_transportistas
WHERE trans_estatus = 'A'
ORDER BY trans_nombre";

$res_transportistas = mysqli_query($cnx, $sql_transportistas);

if ($res_transportistas) {
  while ($trans = mysqli_fetch_assoc($res_transportistas)) {
    $transportistas[] = [
      "id" => $trans["trans_id"],
      "nombre" => $trans["trans_nombre"]
    ];
  }
}

echo json_encode([
  "success" => true,
  "encabezado" => $encabezado,
  "detalle" => $detalle,
  "total_kgs" => $total_kgs,
  "direcciones_entrega" => $direcciones_entrega,
  "transportistas" => $transportistas
]);
