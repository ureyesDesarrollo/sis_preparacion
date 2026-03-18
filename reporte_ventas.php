<?php
require 'conexion/conexion.php';
$conexion = Conectarse();
$conexion2 = Conectarse2();

$fechaInicio = $_GET['fecha_inicio'] ?? date('Y-01-01');
$fechaFin    = $_GET['fecha_fin'] ?? date('Y-m-d');

$sql = "
WITH RECURSIVE meses AS (
    SELECT CAST(DATE_FORMAT(?, '%Y-%m-01') AS DATE) AS mes
    UNION ALL
    SELECT DATE_ADD(mes, INTERVAL 1 MONTH)
    FROM meses
    WHERE mes < CAST(DATE_FORMAT(?, '%Y-%m-01') AS DATE)
),

facturas_totales AS (
    SELECT
        DATE_FORMAT(fecha_factura, '%Y-%m-01') AS mes,
        SUM(COALESCE(total_real, 0)) AS monto_fiscal
    FROM facturas_sai
    WHERE fecha_factura BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(fecha_factura, '%Y-%m-01')
),

facturas_kilos AS (
    SELECT
        DATE_FORMAT(f.fecha_factura, '%Y-%m-01') AS mes,
        SUM(COALESCE(d.cantidad, 0)) AS kilos_fiscal
    FROM facturas_sai f
    INNER JOIN factura_sai_detalle d
        ON d.factura_id = f.id
    WHERE f.fecha_factura BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(f.fecha_factura, '%Y-%m-01')
),

remisiones_totales AS (
    SELECT
        DATE_FORMAT(fecha_remision, '%Y-%m-01') AS mes,
        SUM(COALESCE(total_real, 0)) AS monto_remision
    FROM remisiones
    WHERE fecha_remision BETWEEN ? AND ?
      AND UPPER(cliente_nombre) NOT LIKE '%LUIS FRANCISCO ARBAIZA%'
      AND UPPER(cliente_nombre) NOT LIKE '%LUIS ARBAIZA%'
    GROUP BY DATE_FORMAT(fecha_remision, '%Y-%m-01')
),

remisiones_kilos AS (
    SELECT
        DATE_FORMAT(r.fecha_remision, '%Y-%m-01') AS mes,
        SUM(COALESCE(d.cantidad, 0)) AS kilos_remision
    FROM remisiones r
    INNER JOIN remision_detalle d
        ON d.remision_id = r.id
    WHERE r.fecha_remision BETWEEN ? AND ?
      AND UPPER(r.cliente_nombre) NOT LIKE '%LUIS FRANCISCO ARBAIZA%'
      AND UPPER(r.cliente_nombre) NOT LIKE '%LUIS ARBAIZA%'
    GROUP BY DATE_FORMAT(r.fecha_remision, '%Y-%m-01')
)

SELECT
    YEAR(m.mes) AS anio,
    MONTH(m.mes) AS mes_num,
    CASE MONTH(m.mes)
        WHEN 1 THEN 'ENERO'
        WHEN 2 THEN 'FEBRERO'
        WHEN 3 THEN 'MARZO'
        WHEN 4 THEN 'ABRIL'
        WHEN 5 THEN 'MAYO'
        WHEN 6 THEN 'JUNIO'
        WHEN 7 THEN 'JULIO'
        WHEN 8 THEN 'AGOSTO'
        WHEN 9 THEN 'SEPTIEMBRE'
        WHEN 10 THEN 'OCTUBRE'
        WHEN 11 THEN 'NOVIEMBRE'
        WHEN 12 THEN 'DICIEMBRE'
    END AS mes_nombre,

    COALESCE(fk.kilos_fiscal, 0) AS kilos_fiscal,
    COALESCE(ft.monto_fiscal, 0) AS monto_fiscal,

    COALESCE(rk.kilos_remision, 0) AS kilos_remision,
    COALESCE(rt.monto_remision, 0) AS monto_remision,

    COALESCE(fk.kilos_fiscal, 0) + COALESCE(rk.kilos_remision, 0) AS venta_total_kg,
    COALESCE(ft.monto_fiscal, 0) + COALESCE(rt.monto_remision, 0) AS venta_total_monto

FROM meses m
LEFT JOIN facturas_totales ft   ON ft.mes = DATE_FORMAT(m.mes, '%Y-%m-01')
LEFT JOIN facturas_kilos fk     ON fk.mes = DATE_FORMAT(m.mes, '%Y-%m-01')
LEFT JOIN remisiones_totales rt ON rt.mes = DATE_FORMAT(m.mes, '%Y-%m-01')
LEFT JOIN remisiones_kilos rk   ON rk.mes = DATE_FORMAT(m.mes, '%Y-%m-01')
ORDER BY m.mes
";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
  die('Error al preparar consulta: ' . $conexion->error);
}

$stmt->bind_param(
  'ssssssssss',
  $fechaInicio,
  $fechaFin,
  $fechaInicio,
  $fechaFin,
  $fechaInicio,
  $fechaFin,
  $fechaInicio,
  $fechaFin,
  $fechaInicio,
  $fechaFin
);

$stmt->execute();
$resultado = $stmt->get_result();

$rows = [];
while ($fila = $resultado->fetch_assoc()) {
  $rows[] = $fila;
}

$sqlCobranzaR = "
    SELECT
        DATE_FORMAT(fecha_remision, '%Y-%m-01') AS mes,
        SUM(COALESCE(total_real, 0)) AS cobranza_r
    FROM remisiones
    WHERE fecha_remision BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(fecha_remision, '%Y-%m-01')
";

$stmtCobranzaR = $conexion->prepare($sqlCobranzaR);

if (!$stmtCobranzaR) {
  die('Error al preparar consulta de cobranza R: ' . $conexion->error);
}

$stmtCobranzaR->bind_param('ss', $fechaInicio, $fechaFin);
$stmtCobranzaR->execute();
$resultadoCobranzaR = $stmtCobranzaR->get_result();

$cobranzaRPorMes = [];
while ($fila = $resultadoCobranzaR->fetch_assoc()) {
  $cobranzaRPorMes[$fila['mes']] = (float)$fila['cobranza_r'];
}

$sqlCobranza = "
    SELECT
        DATE_FORMAT(FEC_REA, '%Y-%m-01') AS mes,
        SUM(COALESCE(CANTIDAD, 0)) AS cobranza_f
    FROM pagos2
    WHERE FEC_REA BETWEEN ? AND ?
      AND TIPO != 'Nota Cred'
    GROUP BY DATE_FORMAT(FEC_REA, '%Y-%m-01')
";

$stmtCobranza = $conexion2->prepare($sqlCobranza);

if (!$stmtCobranza) {
  die('Error al preparar consulta de cobranza: ' . $conexion2->error);
}

$stmtCobranza->bind_param('ss', $fechaInicio, $fechaFin);
$stmtCobranza->execute();
$resultadoCobranza = $stmtCobranza->get_result();

$cobranzaPorMes = [];
while ($fila = $resultadoCobranza->fetch_assoc()) {
  $cobranzaPorMes[$fila['mes']] = (float)$fila['cobranza_f'];
}

function num($n)
{
  return number_format((float)$n, 2);
}

function money($n)
{
  return '$ ' . number_format((float)$n, 2);
}

function precioPromedio($monto, $kg)
{
  $kg = (float)$kg;
  $monto = (float)$monto;

  if ($kg <= 0) {
    return '$ 0.00';
  }

  return '$ ' . number_format($monto / $kg, 2);
}

function initTotales()
{
  return [
    'kilos_fiscal'      => 0,
    'monto_fiscal'      => 0,
    'kilos_remision'    => 0,
    'monto_remision'    => 0,
    'venta_total_kg'    => 0,
    'venta_total_monto' => 0,
    'cobranza_f'        => 0,
    'cobranza_r'        => 0,
    'cobranza_total'    => 0,
  ];
}

function acumular(&$t, $r)
{
  $t['kilos_fiscal']      += (float)$r['kilos_fiscal'];
  $t['monto_fiscal']      += (float)$r['monto_fiscal'];
  $t['kilos_remision']    += (float)$r['kilos_remision'];
  $t['monto_remision']    += (float)$r['monto_remision'];
  $t['venta_total_kg']    += (float)$r['venta_total_kg'];
  $t['venta_total_monto'] += (float)$r['venta_total_monto'];
  $t['cobranza_f']        += (float)($r['cobranza_f'] ?? 0);
  $t['cobranza_r']        += (float)($r['cobranza_r'] ?? 0);
  $t['cobranza_total']    += (float)($r['cobranza_total'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Reporte de ventas mensual</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 16px;
      color: #000;
    }

    h2 {
      margin-bottom: 15px;
    }

    .filtros {
      margin-bottom: 15px;
      padding: 10px;
      background: #f4f4f4;
      border: 1px solid #ccc;
    }

    .filtros input,
    .filtros button {
      padding: 6px 10px;
      margin-right: 10px;
    }

    .contenedor-tabla {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    th,
    td {
      border: 1px solid #999;
      padding: 6px 8px;
      white-space: nowrap;
    }

    th {
      text-align: center;
      background: #dbe7bf;
      color: #000;
    }

    th.remision {
      background: #f2d2b6;
      color: #000;
    }

    th.total {
      background: #c4bd97;
      color: #000;
    }

    th.salidas {
      background: #c5d9f1;
      color: #000;
    }

    th.precio {
      background: #c2f1e6;
      color: #000;
    }

    td {
      text-align: right;
    }

    td.texto {
      text-align: left;
    }

    tr.subtotal td {
      background: #ececec;
      font-weight: bold;
    }

    tr:hover td {
      background: #fafafa;
    }

    .rango-impresion {
      display: none;
    }

    .no-print {
      display: inline-block;
    }

    @media print {
      @page {
        size: landscape;
        margin: 6mm;
      }

      html,
      body {
        margin: 0;
        padding: 0;
        font-size: 9px;
        color: #000;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      body {
        font-family: Arial, sans-serif;
      }

      h2 {
        margin: 0 0 6px 0;
        font-size: 13px;
        text-align: center;
      }

      .filtros,
      .no-print {
        display: none !important;
      }

      .rango-impresion {
        display: block;
        margin-bottom: 6px;
        font-size: 10px;
        font-weight: bold;
        text-align: left;
      }

      .contenedor-tabla {
        overflow: visible !important;
        width: 100%;
      }

      table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        font-size: 8px;
      }

      th,
      td {
        border: 1px solid #000;
        padding: 3px 4px;
        vertical-align: middle;
      }

      th {
        text-align: center;
        font-weight: bold;
        white-space: normal !important;
        word-break: break-word;
        line-height: 1.1;
        background: #dbe7bf !important;
        color: #000 !important;
      }

      td {
        text-align: right;
        white-space: nowrap !important;
      }

      td.texto {
        text-align: left;
        white-space: normal !important;
      }

      th.remision {
        background: #f2d2b6 !important;
        color: #000 !important;
      }

      th.total {
        background: #c4bd97 !important;
        color: #000 !important;
      }

      th.salidas {
        background: #c5d9f1 !important;
        color: #000 !important;
      }

      th.precio {
        background: #c2f1e6 !important;
        color: #000 !important;
      }

      tr.subtotal td {
        background: #ececec !important;
        color: #000 !important;
        font-weight: bold;
      }

      thead {
        display: table-header-group;
      }

      tfoot {
        display: table-footer-group;
      }

      tr,
      td,
      th {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
      }

      tr:hover td {
        background: transparent !important;
      }

      /* Ajuste de anchos por columna para que quepa mejor */
      th:nth-child(1),
      td:nth-child(1) {
        width: 13%;
      }

      th:nth-child(2),
      td:nth-child(2) {
        width: 9%;
      }

      th:nth-child(3),
      td:nth-child(3) {
        width: 6%;
      }

      th:nth-child(4),
      td:nth-child(4) {
        width: 7%;
      }

      th:nth-child(5),
      td:nth-child(5) {
        width: 9%;
      }

      th:nth-child(6),
      td:nth-child(6) {
        width: 6%;
      }

      th:nth-child(7),
      td:nth-child(7) {
        width: 7%;
      }

      th:nth-child(8),
      td:nth-child(8) {
        width: 7%;
      }

      th:nth-child(9),
      td:nth-child(9) {
        width: 8%;
      }

      th:nth-child(10),
      td:nth-child(10) {
        width: 7%;
      }

      th:nth-child(11),
      td:nth-child(11) {
        width: 7%;
      }

      th:nth-child(12),
      td:nth-child(12) {
        width: 8%;
      }

      th:nth-child(13),
      td:nth-child(13) {
        width: 8%;
      }

      th:nth-child(14),
      td:nth-child(14) {
        width: 8%;
      }
    }
  </style>
</head>

<body>

  <h2>Reporte de ventas mensual</h2>

  <div class="no-print" style="margin-bottom: 12px;">
    <button type="button" onclick="window.print()">Imprimir</button>
  </div>

  <div class="rango-impresion">
    PERIODO: <?= htmlspecialchars($fechaInicio) ?> AL <?= htmlspecialchars($fechaFin) ?>
  </div>

  <form method="GET" class="filtros">
    <label>Fecha inicio:</label>
    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fechaInicio) ?>">

    <label>Fecha fin:</label>
    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fechaFin) ?>">

    <button type="submit">Filtrar</button>
  </form>

  <div class="contenedor-tabla">
    <table>
      <thead>
        <tr>
          <th>MES</th>
          <th>VENTA FISCAL</th>
          <th>KILOS</th>
          <th>$</th>
          <th class="remision">VENTA REMISION</th>
          <th class="remision">KILOS</th>
          <th class="remision">$</th>
          <th class="total">VENTA TOTAL KG</th>
          <th class="total">VENTA TOTAL EN $</th>
          <th>COBRANZA F</th>
          <th class="remision">COBRANZA R</th>
          <th class="total">COBRANZA TOTAL</th>
          <th class="salidas">SALIDAS DE PRODUCTO</th>
          <th class="precio">PRECIO PROMEDIO</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $anioActual = null;
        $totales = initTotales();

        foreach ($rows as $row):
          if ($anioActual !== null && $anioActual != $row['anio']):
        ?>
            <tr class="subtotal">
              <td class="texto"><?= $anioActual ?></td>
              <td></td>
              <td><?= num($totales['kilos_fiscal']) ?></td>
              <td><?= money($totales['monto_fiscal']) ?></td>
              <td></td>
              <td><?= num($totales['kilos_remision']) ?></td>
              <td><?= money($totales['monto_remision']) ?></td>
              <td><?= num($totales['venta_total_kg']) ?></td>
              <td><?= money($totales['venta_total_monto']) ?></td>
              <td><?= money($totales['cobranza_f']) ?></td>
              <td><?= money($totales['cobranza_r']) ?></td>
              <td><?= money($totales['cobranza_total']) ?></td>
              <td></td>
              <td></td>
              <td><?= precioPromedio($totales['venta_total_monto'], $totales['venta_total_kg']) ?></td>
            </tr>
          <?php
            $totales = initTotales();
          endif;

          $anioActual = $row['anio'];

          $mesKey = sprintf('%04d-%02d-01', $row['anio'], $row['mes_num']);

          $row['cobranza_f'] = $cobranzaPorMes[$mesKey] ?? 0;
          $row['cobranza_r'] = $cobranzaRPorMes[$mesKey] ?? 0;
          $row['cobranza_total'] = $row['cobranza_f'] + $row['cobranza_r'];

          acumular($totales, $row);
          ?>
          <tr>
            <td class="texto"><?= $row['mes_nombre'] . ' ' . $row['anio'] ?></td>
            <td class="texto">VENTA F PROGEL</td>
            <td><?= num($row['kilos_fiscal']) ?></td>
            <td><?= money($row['monto_fiscal']) ?></td>
            <td class="texto">VENTA R PROGEL</td>
            <td><?= num($row['kilos_remision']) ?></td>
            <td><?= money($row['monto_remision']) ?></td>
            <td><?= num($row['venta_total_kg']) ?></td>
            <td><?= money($row['venta_total_monto']) ?></td>
            <td><?= money($row['cobranza_f']) ?></td>
            <td><?= money($row['cobranza_r']) ?></td>
            <td><?= money($row['cobranza_total']) ?></td>
            <td></td>
            <td><?= precioPromedio($row['venta_total_monto'], $row['venta_total_kg']) ?></td>
          </tr>
        <?php endforeach; ?>

        <?php if (!empty($rows)): ?>
          <tr class="subtotal">
            <td class="texto"><?= $anioActual ?></td>
            <td></td>
            <td><?= num($totales['kilos_fiscal']) ?></td>
            <td><?= money($totales['monto_fiscal']) ?></td>
            <td></td>
            <td><?= num($totales['kilos_remision']) ?></td>
            <td><?= money($totales['monto_remision']) ?></td>
            <td><?= num($totales['venta_total_kg']) ?></td>
            <td><?= money($totales['venta_total_monto']) ?></td>
            <td><?= money($totales['cobranza_f']) ?></td>
            <td><?= money($totales['cobranza_r']) ?></td>
            <td><?= money($totales['cobranza_total']) ?></td>
            <td></td>
            <td></td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>

</html>
