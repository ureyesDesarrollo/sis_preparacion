<?php
$orden_id = isset($_GET['orden_id']) ? (int)$_GET['orden_id'] : 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recibo de Embarque | PROGEL MEXICANA</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      margin: 0;
      padding: 10px;
      font-family: Arial, Helvetica, sans-serif;
      background: #f3f4f6;
      color: #1f2937;
      font-size: 13px;
      line-height: 1.3;
    }

    .acciones,
    .panel-config {
      max-width: 900px;
      margin: 0 auto 10px auto;
    }

    .acciones {
      display: flex;
      justify-content: flex-end;
      gap: 8px;
    }

    .btn {
      background: #1f2937;
      color: #fff;
      border: none;
      padding: 8px 14px;
      border-radius: 4px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .btn:hover {
      background: #374151;
      transform: translateY(-1px);
    }

    .btn-secundario {
      background: #fff;
      color: #1f2937;
      border: 1px solid #cbd5e1;
    }

    .btn-secundario:hover {
      background: #f8fafc;
    }

    .panel-config {
      background: #fff;
      border: 1px solid #d1d5db;
      padding: 12px;
    }

    .panel-titulo {
      font-size: 15px;
      font-weight: 700;
      margin-bottom: 10px;
      color: #111827;
    }

    .config-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 10px 12px;
      align-items: start;
    }

    .campo-form label {
      display: block;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      color: #6b7280;
      margin-bottom: 4px;
      letter-spacing: 0.2px;
    }

    .campo-form select,
    .campo-form textarea {
      width: 100%;
      border: 1px solid #cbd5e1;
      padding: 8px 9px;
      font-size: 13px;
      color: #111827;
      background: #fff;
      font-family: Arial, Helvetica, sans-serif;
      transition: border-color 0.2s ease;
    }

    .campo-form select:focus,
    .campo-form textarea:focus {
      outline: none;
      border-color: #0056a7;
      box-shadow: 0 0 0 2px rgba(0, 86, 167, 0.1);
    }

    .campo-form textarea {
      min-height: 58px;
      resize: vertical;
    }

    .opciones-flete {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      min-height: 34px;
      align-items: center;
      border: 1px solid #cbd5e1;
      background: #fff;
      padding: 7px 8px;
    }

    .opciones-flete label {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 13px;
      font-weight: 700;
      color: #374151;
      text-transform: none;
      margin-bottom: 0;
    }

    .documento {
      max-width: 900px;
      margin: 0 auto;
      background: #fff;
      border: 1px solid #d1d5db;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .barra-superior {
      height: 3px;
      background: #0056a7;
    }

    .encabezado {
      padding: 12px 16px 8px 16px;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      border-bottom: 1px solid #d1d5db;
    }

    .logo-area {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo-placeholder {
      width: 48px;
      height: 48px;
      background: #f1f5f9;
      border: 1px solid #e2e8f0;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      color: #94a3b8;
      text-transform: uppercase;
      font-weight: 700;
    }

    .logo-imagen {
      max-width: 60px;
      max-height: 60px;
      object-fit: contain;
    }

    .empresa-info {
      flex: 1;
    }

    .empresa-nombre {
      font-size: 20px;
      font-weight: 700;
      color: #111827;
      line-height: 1.15;
    }

    .empresa-sub {
      font-size: 11px;
      color: #6b7280;
      margin-top: 2px;
    }

    .orden-destacada {
      background: #f9fafb;
      padding: 8px 10px;
      border: 1px solid #d1d5db;
      text-align: right;
      min-width: 155px;
    }

    .orden-label {
      font-size: 10px;
      color: #6b7280;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      margin-bottom: 2px;
    }

    .orden-numero {
      font-size: 18px;
      font-weight: 700;
      color: #111827;
      line-height: 1;
    }

    .titulo-seccion {
      padding: 10px 16px 4px 16px;
    }

    .titulo-principal {
      font-size: 17px;
      font-weight: 700;
      color: #111827;
      display: inline-block;
      border-bottom: 2px solid #0056a7;
      padding-bottom: 2px;
    }

    .info-tarjeta {
      margin: 8px 16px 10px 16px;
      background: #fafbfc;
      border: 1px solid #e5e7eb;
      padding: 10px 12px;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px 14px;
    }

    .info-bloque {
      display: flex;
      flex-direction: column;
      gap: 2px;
      min-width: 0;
    }

    .info-bloque .etiqueta {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      color: #6b7280;
    }

    .info-bloque .valor {
      font-size: 13px;
      font-weight: 700;
      color: #111827;
      line-height: 1.25;
      white-space: pre-line;
      word-break: break-word;
    }

    .info-bloque .valor-secundario {
      font-size: 11px;
      color: #6b7280;
      white-space: pre-line;
      line-height: 1.2;
      word-break: break-word;
    }

    .info-bloque-compacto {
      gap: 3px;
    }

    .valor-principal-inline {
      display: block;
      white-space: normal;
    }

    .valor-secundario-inline {
      display: flex;
      flex-wrap: wrap;
      gap: 3px 10px;
      align-items: center;
      white-space: normal;
      line-height: 1.15;
    }

    .dato-inline {
      display: inline-block;
      min-width: 0;
    }

    .dato-inline.dato-direccion {
      flex: 1 1 240px;
    }

    .badge-fecha {
      display: inline-block;
      background: #fff;
      border: 1px solid #d1d5db;
      padding: 4px 8px;
      font-size: 12px;
      font-weight: 700;
      color: #111827;
    }

    .observaciones-container {
      margin: 0 16px 16px 16px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      overflow: hidden;
      background: #ffffff;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .observaciones-header {
      background: #f8fafc;
      padding: 10px 14px;
      border-bottom: 1px solid #e2e8f0;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .observaciones-header-icon {
      font-size: 13px;
      font-weight: 700;
      color: #0056a7;
    }

    .observaciones-header-title {
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #1e293b;
    }

    .observaciones-content {
      padding: 14px;
      background: #ffffff;
    }

    .observacion-item {
      padding: 9px 12px;
      margin-bottom: 8px;
      border-left: 3px solid;
      background: #fefefe;
      transition: all 0.2s ease;
    }

    .observacion-item:last-child {
      margin-bottom: 0;
    }

    .observacion-item.entrega {
      border-left-color: #10b981;
      background: linear-gradient(90deg, #f0fdf4 0%, #ffffff 100%);
    }

    .observacion-item.manual {
      border-left-color: #f59e0b;
      background: linear-gradient(90deg, #fffbeb 0%, #ffffff 100%);
    }

    .observacion-item.sin-observaciones {
      border-left-color: #94a3b8;
      background: #f8fafc;
      color: #64748b;
      font-style: italic;
      text-align: center;
    }

    .observacion-label {
      display: inline-block;
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      padding: 2px 6px;
      border-radius: 3px;
      margin-bottom: 6px;
    }

    .observacion-label.entrega-label {
      background: #d1fae5;
      color: #065f46;
    }

    .observacion-label.manual-label {
      background: #fed7aa;
      color: #92400e;
    }

    .observacion-texto {
      font-size: 13px;
      line-height: 1.45;
      color: #1f2937;
      word-break: break-word;
      white-space: pre-line;
    }

    .observacion-texto.sin-observaciones-texto {
      color: #94a3b8;
      font-size: 12px;
      font-style: italic;
      text-align: center;
    }

    .observacion-divider {
      height: 1px;
      background: #f1f5f9;
      margin: 8px 0;
    }

    .tabla-contenedor {
      margin: 6px 16px 8px 16px;
      border: 1px solid #d1d5db;
      overflow: hidden;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }

    th {
      background: #eef2f7;
      color: #111827;
      font-weight: 700;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.2px;
      padding: 7px 6px;
      border-bottom: 1px solid #cbd5e1;
      text-align: left;
    }

    td {
      padding: 7px 6px;
      border-bottom: 1px solid #e5e7eb;
      color: #1f2937;
      vertical-align: top;
      line-height: 1.25;
    }

    tr:last-child td {
      border-bottom: none;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .font-mono {
      font-family: "Courier New", monospace;
      font-weight: 700;
    }

    .totales-rapidos {
      background: #fafbfc;
      padding: 8px 10px;
      border-top: 1px solid #d1d5db;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 18px;
      flex-wrap: wrap;
    }

    .total-parcial {
      display: flex;
      align-items: baseline;
      gap: 6px;
    }

    .total-parcial .label {
      font-size: 11px;
      color: #6b7280;
      text-transform: uppercase;
      font-weight: 700;
    }

    .total-parcial .numero {
      font-size: 17px;
      font-weight: 700;
      color: #111827;
      font-family: "Courier New", monospace;
    }

    .total-parcial .unidad {
      font-size: 11px;
      color: #6b7280;
    }

    .flete-condiciones {
      margin: 8px 16px 6px 16px;
      padding: 8px 10px;
      background: #f8fafc;
      border-left: 4px solid #0056a7;
      border-top: 1px solid #e5e7eb;
      border-right: 1px solid #e5e7eb;
      border-bottom: 1px solid #e5e7eb;
      font-weight: 700;
      color: #1f2937;
      font-size: 12px;
      line-height: 1.25;
    }

    .firma-recibi {
      margin: 18px 16px 12px auto;
      width: 220px;
      text-align: center;
    }

    .firma-linea {
      border-top: 1px solid #94a3b8;
      margin-bottom: 6px;
      height: 18px;
    }

    .firma-titulo {
      font-weight: 700;
      font-size: 11px;
      color: #111827;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      margin-bottom: 2px;
    }

    .firma-sub {
      font-size: 11px;
      color: #6b7280;
      line-height: 1.15;
    }

    .pie-documento {
      background: #fafbfc;
      padding: 6px 12px;
      display: flex;
      justify-content: space-between;
      font-size: 9px;
      color: #6b7280;
      border-top: 1px solid #e5e7eb;
    }

    .pie-documento span:last-child {
      font-family: "Courier New", monospace;
    }

    .loading,
    .error {
      max-width: 900px;
      margin: 30px auto;
      background: #fff;
      border: 1px solid #d1d5db;
      padding: 24px;
      text-align: center;
      font-size: 15px;
    }

    .loading {
      color: #0056a7;
    }

    .error {
      color: #b91c1c;
      background: #fff7f7;
      border-color: #f0caca;
    }

    @media (max-width: 768px) {
      body {
        padding: 8px;
      }

      .config-grid,
      .info-tarjeta {
        grid-template-columns: 1fr;
      }

      .valor-secundario-inline {
        display: block;
      }

      .dato-inline {
        display: block;
        margin-bottom: 2px;
      }

      .dato-inline:last-child {
        margin-bottom: 0;
      }

      .encabezado {
        flex-direction: column;
        gap: 10px;
      }

      .logo-area {
        width: 100%;
      }

      .orden-destacada {
        text-align: left;
        min-width: auto;
      }

      .observaciones-container {
        margin: 0 12px 12px 12px;
      }

      .observaciones-content {
        padding: 10px;
      }

      .observacion-item {
        padding: 7px 10px;
      }

      .pie-documento {
        flex-direction: column;
        gap: 3px;
      }

      .firma-recibi {
        width: 100%;
        max-width: 220px;
      }
    }

    @media print {
      body {
        background: #fff;
        padding: 0;
        font-size: 12px;
        line-height: 1.2;
      }

      .acciones,
      .panel-config {
        display: none;
      }

      .documento {
        max-width: 100%;
        border: none;
        box-shadow: none;
      }

      .encabezado {
        padding: 10px 14px 7px 14px;
      }

      .empresa-nombre {
        font-size: 18px;
      }

      .empresa-sub {
        font-size: 10px;
      }

      .orden-destacada {
        padding: 7px 9px;
      }

      .orden-label {
        font-size: 9px;
      }

      .orden-numero {
        font-size: 17px;
      }

      .titulo-seccion {
        padding: 8px 14px 3px 14px;
      }

      .titulo-principal {
        font-size: 16px;
      }

      .info-tarjeta {
        grid-template-columns: 0.85fr 0.95fr 1.1fr 1.1fr;
        gap: 5px 9px;
        margin: 6px 14px 7px 14px;
        padding: 7px 9px;
      }

      .info-bloque {
        gap: 1px;
      }

      .info-bloque .etiqueta {
        font-size: 8.5px;
      }

      .info-bloque .valor {
        font-size: 11.6px;
        line-height: 1.12;
      }

      .info-bloque .valor-secundario {
        font-size: 10px;
        line-height: 1.08;
      }

      .valor-secundario-inline {
        gap: 2px 7px;
      }

      .dato-inline.dato-direccion {
        flex-basis: 175px;
      }

      .badge-fecha {
        padding: 2px 6px;
        font-size: 10.5px;
      }

      .observaciones-container {
        margin: 0 14px 10px 14px;
      }

      .observaciones-header {
        padding: 7px 10px;
      }

      .observaciones-header-title {
        font-size: 10px;
      }

      .observaciones-content {
        padding: 8px 10px;
      }

      .observacion-item {
        padding: 6px 9px;
        margin-bottom: 6px;
      }

      .observacion-label {
        font-size: 8.5px;
        margin-bottom: 4px;
      }

      .observacion-texto {
        font-size: 11.2px;
        line-height: 1.28;
      }

      .tabla-contenedor {
        margin: 5px 14px 7px 14px;
      }

      table {
        font-size: 11.2px;
      }

      th {
        font-size: 9.5px;
        padding: 6px 5px;
      }

      td {
        padding: 5px 5px;
        line-height: 1.15;
      }

      .totales-rapidos {
        padding: 6px 9px;
        gap: 14px;
      }

      .total-parcial .label {
        font-size: 9.5px;
      }

      .total-parcial .numero {
        font-size: 15px;
      }

      .total-parcial .unidad {
        font-size: 9.5px;
      }

      .flete-condiciones {
        margin: 7px 14px 5px 14px;
        padding: 7px 9px;
        font-size: 10.8px;
        line-height: 1.15;
      }

      .firma-recibi {
        margin: 14px 14px 8px auto;
        width: 210px;
      }

      .firma-linea {
        margin-bottom: 5px;
        height: 15px;
      }

      .firma-titulo {
        font-size: 9.5px;
      }

      .firma-sub {
        font-size: 9.5px;
      }

      .pie-documento {
        padding: 5px 10px;
        font-size: 8px;
      }

      .barra-superior,
      .info-tarjeta,
      .observaciones-container,
      th {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .encabezado,
      .titulo-seccion,
      .info-tarjeta,
      .observaciones-container,
      .tabla-contenedor,
      .flete-condiciones,
      .firma-recibi,
      .pie-documento {
        page-break-inside: avoid;
      }

      @page {
        size: letter portrait;
        margin: 0.8cm;
      }
    }
  </style>
</head>

<body>
  <div class="acciones">
    <button type="button" class="btn btn-secundario" onclick="aplicarDatosFormulario()">ACTUALIZAR DOCUMENTO</button>
    <button type="button" class="btn" onclick="window.print()">IMPRIMIR RECIBO</button>
  </div>

  <div class="panel-config">
    <div class="panel-titulo">Selección del embarque</div>

    <div class="config-grid">
      <div class="campo-form">
        <label>Tipo de documento</label>
        <div class="opciones-flete">
          <label>
            <input type="radio" name="tipo_documento" value="FACTURA" checked>
            Factura
          </label>
          <label>
            <input type="radio" name="tipo_documento" value="REMISION">
            Remisión
          </label>
        </div>
      </div>

      <div class="campo-form">
        <label for="transportista_select">Transportista</label>
        <select id="transportista_select"></select>
      </div>

      <div class="campo-form">
        <label for="direccion_entrega_select">Dirección de entrega</label>
        <select id="direccion_entrega_select"></select>
      </div>

      <div class="campo-form">
        <label>Condición de flete</label>
        <div class="opciones-flete">
          <label>
            <input type="radio" name="flete_cobro" value="REMITE" checked>
            Remitente
          </label>
          <label>
            <input type="radio" name="flete_cobro" value="DESTINO">
            Destinatario
          </label>
        </div>
      </div>

      <div class="campo-form" style="grid-column: 1 / -1;">
        <label for="observaciones_manual">Observaciones adicionales</label>
        <textarea id="observaciones_manual" placeholder="Escribe observaciones adicionales del embarque"></textarea>
      </div>
    </div>
  </div>

  <div id="app" class="loading">Cargando información del embarque...</div>

  <script>
    const ENDPOINT = 'recibo_embarque_consulta.php';

    let datosOrden = null;
    let direccionesEntrega = [];
    let transportistas = [];

    function formatearFecha(fecha) {
      if (!fecha) return '';
      const f = new Date(String(fecha).replace(' ', 'T'));
      if (isNaN(f.getTime())) return fecha;

      const meses = [
        'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO',
        'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'
      ];

      return `${String(f.getDate()).padStart(2, '0')} DE ${meses[f.getMonth()]} DE ${f.getFullYear()}`;
    }

    function numero(valor, decimales = 2) {
      const n = parseFloat(valor || 0);
      return n.toLocaleString('es-MX', {
        minimumFractionDigits: decimales,
        maximumFractionDigits: decimales
      });
    }

    function escaparHtml(texto) {
      return String(texto ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function obtenerConfiguracionPresentacion(item) {
      const presentacionId = parseInt(item.presentacion_id || 0, 10);

      switch (presentacionId) {
        case 2:
          return {
            singular: 'SACO DE 25 KG',
              plural: 'SACOS DE 25 KG',
              unidadesPorPresentacion: 1
          };
        case 3:
          return {
            singular: 'CAJA DE 12 KG (12 EMPAQUES DE 1 KG)',
              plural: 'CAJAS DE 12 KG (12 EMPAQUES DE 1 KG)',
              unidadesPorPresentacion: 12
          };
        case 4:
          return {
            singular: 'CAJA DE 12 KG (48 EMPAQUES DE 1/4 KG)',
              plural: 'CAJAS DE 12 KG (48 EMPAQUES DE 1/4 KG)',
              unidadesPorPresentacion: 48
          };
        case 5:
          return {
            singular: 'SACO DE 1000 KG',
              plural: 'SACOS DE 1000 KG',
              unidadesPorPresentacion: 1
          };
        case 6:
          return {
            singular: 'CAJA DE 10 KG (20 EMPAQUES DE 1/2 KG)',
              plural: 'CAJAS DE 10 KG (20 EMPAQUES DE 1/2 KG)',
              unidadesPorPresentacion: 20
          };
        default:
          return {
            singular: item.presentacion_descripcion || 'PRESENTACIÓN',
              plural: item.presentacion_descripcion || 'PRESENTACIÓN',
              unidadesPorPresentacion: 1
          };
      }
    }

    function formatearCantidadPresentacion(cantidad) {
      const n = parseFloat(cantidad || 0);

      if (Number.isInteger(n)) {
        return String(n);
      }

      return n.toFixed(2).replace(/\.00$/, '');
    }

    function obtenerDescripcionPresentacion(item) {
      const cantidadBase = parseFloat(item.cantidad_solicitada || 0);
      const config = obtenerConfiguracionPresentacion(item);

      const cantidadPresentaciones = cantidadBase / config.unidadesPorPresentacion;
      const textoCantidad = formatearCantidadPresentacion(cantidadPresentaciones);
      const textoPresentacion = cantidadPresentaciones === 1 ? config.singular : config.plural;

      return `${textoCantidad} ${textoPresentacion}`;
    }

    function descripcionPartida(item) {
      const presentacion = obtenerDescripcionPresentacion(item);
      const bloom = item.bloom_vendido ? `BLOOM ${item.bloom_vendido}` : '';
      const folio = item.rev_folio ? `LOTE: ${item.rev_folio}` : '';

      return [presentacion, bloom, folio].filter(Boolean).join(' • ');
    }

    function obtenerTipoDocumento() {
      const seleccionado = document.querySelector('input[name="tipo_documento"]:checked');
      return seleccionado ? seleccionado.value : 'FACTURA';
    }

    function obtenerDatosRemitente(encabezado) {
      const tipoDocumento = obtenerTipoDocumento();

      const remitentes = {
        FACTURA: {
          nombre: encabezado.remitente_factura_nombre || encabezado.remitente_nombre || 'PROGEL MEXICANA S.A. DE C.V.',
          rfc: encabezado.remitente_factura_rfc ? `RFC: ${encabezado.remitente_factura_rfc}` : (encabezado.remitente_rfc || ''),
          direccion: encabezado.remitente_factura_direccion || encabezado.remitente_direccion || '',
          subtitulo: 'DOCUMENTO DE FACTURA'
        },
        REMISION: {
          nombre: encabezado.remitente_remision_nombre || encabezado.remitente_nombre || 'PROGEL MEXICANA S.A. DE C.V.',
          rfc: encabezado.remitente_remision_rfc ? `RFC: ${encabezado.remitente_remision_rfc}` : '',
          direccion: encabezado.remitente_remision_direccion || encabezado.remitente_direccion || '',
          subtitulo: 'DOCUMENTO DE REMISIÓN'
        }
      };

      return remitentes[tipoDocumento] || remitentes.FACTURA;
    }

    function obtenerFleteTexto() {
      const seleccionado = document.querySelector('input[name="flete_cobro"]:checked');
      if (!seleccionado) return 'FLETE POR COBRAR AL REMITENTE';

      return seleccionado.value === 'DESTINO' ?
        'FLETE POR COBRAR AL DESTINATARIO' :
        'FLETE POR COBRAR AL REMITENTE';
    }

    function obtenerObservacionesManual() {
      const el = document.getElementById('observaciones_manual');
      return el ? el.value.trim() : '';
    }

    function poblarTransportistas(lista) {
      const select = document.getElementById('transportista_select');
      select.innerHTML = '';

      const optionVacio = document.createElement('option');
      optionVacio.value = '';
      optionVacio.textContent = 'SELECCIONAR TRANSPORTISTA';
      optionVacio.selected = true;
      select.appendChild(optionVacio);

      if (!Array.isArray(lista) || lista.length === 0) {
        return;
      }

      lista.forEach(item => {
        const option = document.createElement('option');
        option.value = item.nombre || item.id || '';
        option.textContent = item.nombre || item.id || '';
        select.appendChild(option);
      });
    }

    function poblarDirecciones(lista) {
      const select = document.getElementById('direccion_entrega_select');
      select.innerHTML = '';

      const optionVacio = document.createElement('option');
      optionVacio.value = '';
      optionVacio.textContent = 'SIN DIRECCIÓN DE ENTREGA';
      select.appendChild(optionVacio);

      if (!Array.isArray(lista) || lista.length === 0) {
        return;
      }

      lista.forEach((item, index) => {
        const option = document.createElement('option');
        option.value = String(item.id ?? index);
        option.textContent = item.alias ?
          `${item.alias} - ${item.direccion || ''}` :
          (item.direccion || `DIRECCIÓN ${index + 1}`);
        select.appendChild(option);
      });
    }

    function obtenerDireccionSeleccionada() {
      const select = document.getElementById('direccion_entrega_select');
      const valor = select ? select.value : '';

      if (!valor) {
        return null;
      }

      if (!Array.isArray(direccionesEntrega) || direccionesEntrega.length === 0) {
        return null;
      }

      return direccionesEntrega.find((item, index) => String(item.id ?? index) === String(valor)) || null;
    }

    function obtenerTransportistaSeleccionado() {
      const select = document.getElementById('transportista_select');
      return select ? select.value : '';
    }

    function construirObservacionEntrega(direccionSeleccionada) {
      if (!direccionSeleccionada || !direccionSeleccionada.direccion) {
        return '';
      }

      return `ENTREGAR EN: ${direccionSeleccionada.direccion}`;
    }

    function construirObservacionesHtml() {
      const direccionEntrega = obtenerDireccionSeleccionada();
      const observacionEntrega = construirObservacionEntrega(direccionEntrega);
      const observacionesManual = obtenerObservacionesManual();

      let observacionesHtml = '';

      if (observacionEntrega) {
        observacionesHtml += `
          <div class="observacion-item entrega">
            <span class="observacion-label entrega-label">DIRECCIÓN DE ENTREGA</span>
            <div class="observacion-texto">${escaparHtml(observacionEntrega)}</div>
          </div>
        `;
      }

      if (observacionesManual) {
        if (observacionEntrega) {
          observacionesHtml += `<div class="observacion-divider"></div>`;
        }

        observacionesHtml += `
          <div class="observacion-item manual">
            <span class="observacion-label manual-label">OBSERVACIÓN ADICIONAL</span>
            <div class="observacion-texto">${escaparHtml(observacionesManual).replace(/\n/g, '<br>')}</div>
          </div>
        `;
      }

      if (!observacionEntrega && !observacionesManual) {
        observacionesHtml = `
          <div class="observacion-item sin-observaciones">
            <div class="observacion-texto sin-observaciones-texto">Sin observaciones registradas para este embarque</div>
          </div>
        `;
      }

      return observacionesHtml;
    }

    function actualizarObservaciones() {
      if (!datosOrden) return;

      const contenedor = document.getElementById('observacionesDinamicas');
      if (!contenedor) return;

      contenedor.innerHTML = construirObservacionesHtml();
    }

    function renderizar(data) {
      const app = document.getElementById('app');
      const encabezado = data.encabezado || {};
      const detalle = Array.isArray(data.detalle) ? data.detalle : [];
      const totalKgs = parseFloat(data.total_kgs || 0);
      const fechaActual = new Date().toLocaleDateString('es-MX');

      const remitente = obtenerDatosRemitente(encabezado);

      const remitenteNombre = remitente.nombre;
      const remitenteRfc = remitente.rfc;
      const remitenteDireccion = remitente.direccion;

      const destinatarioNombre = encabezado.destinatario_nombre || encabezado.cliente_nombre || '';
      const destinatarioRfc = encabezado.destinatario_rfc || '';
      const destinatarioFiscal = encabezado.destinatario_direccion_fiscal || '';
      const lugarExpedicion = encabezado.lugar_expedicion || 'LEÓN, GTO.';
      const transportista = obtenerTransportistaSeleccionado();
      const fleteTexto = obtenerFleteTexto();
      const observacionesHtml = construirObservacionesHtml();

      const filas = detalle.map((item, index) => `
        <tr>
          <td class="text-center" style="width: 28px;">${index + 1}</td>
          <td class="text-center" style="width: 42px; font-weight: 700;">${escaparHtml(item.cantidad_solicitada)}</td>
          <td>${escaparHtml(descripcionPartida(item))}</td>
          <td class="text-right font-mono" style="width: 70px;">${numero(item.pres_kg, 2)}</td>
          <td class="text-right font-mono" style="width: 78px;">${numero(item.total_kgs_partida, 2)}</td>
        </tr>
      `).join('');

      app.className = '';
      app.innerHTML = `
        <div class="documento">
          <div class="barra-superior"></div>

          <div class="encabezado">
            <div class="logo-area">
              <div id="logoContainer" class="logo-placeholder">
                LOGO
              </div>
              <div class="empresa-info">
                <div class="empresa-nombre">PROGEL MEXICANA S.A. DE C.V.</div>
                <div class="empresa-sub">RECIBO INTERNO DE EMBARQUE</div>
              </div>
            </div>
            <div class="orden-destacada">
              <div class="orden-label">ORDEN DE EMBARQUE</div>
              <div class="orden-numero">#${escaparHtml(String(encabezado.orden_id || '').padStart(6, '0'))}</div>
            </div>
          </div>

          <div class="titulo-seccion">
            <h2 class="titulo-principal">RECIBO DE EMBARQUE</h2>
          </div>

          <div class="info-tarjeta">
            <div class="info-bloque">
              <span class="etiqueta">Fecha de expedición</span>
              <span class="valor"><span class="badge-fecha">${escaparHtml(formatearFecha(encabezado.fecha_creacion))}</span></span>
              <span class="valor-secundario">${escaparHtml(lugarExpedicion)}</span>
            </div>

            <div class="info-bloque">
              <span class="etiqueta">Transportista</span>
              <span class="valor">${escaparHtml(transportista)}</span>
              <span class="valor-secundario">${escaparHtml(fleteTexto)}</span>
            </div>

            <div class="info-bloque info-bloque-compacto">
              <span class="etiqueta">Remitente</span>
              <span class="valor valor-principal-inline">${escaparHtml(remitenteNombre)}</span>
              <div class="valor-secundario valor-secundario-inline">
                ${remitenteRfc ? `<span class="dato-inline">${escaparHtml(remitenteRfc)}</span>` : ''}
                ${remitenteDireccion ? `<span class="dato-inline dato-direccion">${escaparHtml(remitenteDireccion)}</span>` : ''}
              </div>
            </div>

            <div class="info-bloque info-bloque-compacto">
              <span class="etiqueta">Destinatario</span>
              <span class="valor valor-principal-inline">${escaparHtml(destinatarioNombre)}</span>
              <div class="valor-secundario valor-secundario-inline">
                ${destinatarioRfc ? `<span class="dato-inline">RFC: ${escaparHtml(destinatarioRfc)}</span>` : ''}
                ${destinatarioFiscal ? `<span class="dato-inline dato-direccion">${escaparHtml(destinatarioFiscal)}</span>` : ''}
              </div>
            </div>
          </div>

          <div class="observaciones-container">
            <div class="observaciones-header">
              <span class="observaciones-header-icon">●</span>
              <span class="observaciones-header-title">Información adicional del embarque</span>
            </div>
            <div class="observaciones-content" id="observacionesDinamicas">
              ${observacionesHtml}
            </div>
          </div>

          <div class="tabla-contenedor">
            <table>
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Cant.</th>
                  <th>Descripción de material</th>
                  <th class="text-right">Peso unit.</th>
                  <th class="text-right">Peso total</th>
                </tr>
              </thead>
              <tbody>
                ${filas || '<tr><td colspan="5" class="text-center" style="padding: 18px;">No hay materiales registrados en esta orden.</td></tr>'}
              </tbody>
            </table>

            <div class="totales-rapidos">
              <div class="total-parcial">
                <span class="label">Partidas:</span>
                <span class="numero">${detalle.length}</span>
              </div>
              <div class="total-parcial">
                <span class="label">Peso total:</span>
                <span class="numero">${numero(totalKgs, 2)}</span>
                <span class="unidad">KG</span>
              </div>
            </div>
          </div>

          <div class="flete-condiciones">
            CONDICIÓN DE FLETE: ${escaparHtml(fleteTexto)}
          </div>

          <div class="firma-recibi">
            <div class="firma-linea"></div>
            <div class="firma-titulo">RECIBÍ</div>
            <div class="firma-sub">${escaparHtml(transportista)}</div>
          </div>

          <div class="pie-documento">
            <span>Documento controlado • PROGEL MEXICANA S.A. DE C.V.</span>
            <span>Emitido: ${fechaActual}</span>
          </div>
        </div>
      `;

      cargarLogo();
    }

    function cargarLogo() {
      const logoContainer = document.getElementById('logoContainer');
      const logoUrl = '../../imagenes/logo_empresa.png';

      const img = new Image();
      img.onload = function() {
        logoContainer.innerHTML = '';
        const logoImg = document.createElement('img');
        logoImg.src = logoUrl;
        logoImg.alt = 'Logo PROGEL MEXICANA';
        logoImg.className = 'logo-imagen';
        logoContainer.appendChild(logoImg);
        logoContainer.classList.remove('logo-placeholder');
      };
      img.onerror = function() {
        console.log('Logo no encontrado, usando placeholder');
      };
      img.src = logoUrl;
    }

    function aplicarDatosFormulario() {
      if (datosOrden) {
        renderizar(datosOrden);
      }
    }

    async function cargarRecibo() {
      const app = document.getElementById('app');
      const ordenId = <?php echo $orden_id; ?>;

      if (!ordenId || ordenId <= 0) {
        app.className = 'error';
        app.innerHTML = 'Número de orden no válido o no especificado.';
        return;
      }

      try {
        const response = await fetch(ENDPOINT, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            orden_id: ordenId
          })
        });

        const data = await response.json();

        if (!data.success) {
          app.className = 'error';
          app.innerHTML = `${escaparHtml(data.message || 'No se pudo obtener la información de la orden.')}`;
          return;
        }

        datosOrden = data;
        direccionesEntrega = Array.isArray(data.direcciones_entrega) ? data.direcciones_entrega : [];
        transportistas = Array.isArray(data.transportistas) ? data.transportistas : [];

        poblarTransportistas(transportistas);
        poblarDirecciones(direccionesEntrega);
        renderizar(data);
      } catch (error) {
        app.className = 'error';
        app.innerHTML = 'Error de conexión con el servidor.';
        console.error(error);
      }
    }

    document.addEventListener('input', function(e) {
      if (e.target.id === 'observaciones_manual') {
        actualizarObservaciones();
      }
    });

    document.addEventListener('change', function(e) {
      if (
        e.target.id === 'transportista_select' ||
        e.target.id === 'direccion_entrega_select' ||
        e.target.name === 'flete_cobro' ||
        e.target.name === 'tipo_documento'
      ) {
        aplicarDatosFormulario();
      }
    });

    cargarRecibo();
  </script>
</body>

</html>
