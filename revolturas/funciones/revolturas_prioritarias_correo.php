<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";
include "../lib/EmailSender.php";
$mailSender = new MailSender();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$cnx = Conectarse();
$id = intval($_POST['rev_id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// -------------------------
# 1) Obtener datos de la revoltura
// -------------------------
$stmt = $cnx->prepare("
    SELECT rev_folio, DATE(rev_fecha) as rev_fecha, rev_fecha_prioritaria, rev_fecha_prioritaria_caducidad
    FROM rev_revolturas
    WHERE rev_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Revoltura no encontrada']);
    exit;
}

$revoltura = $result->fetch_assoc();

// 🧩 Desestructuración de PHP
[
    'rev_folio' => $folio,
    'rev_fecha' => $fecha,
    'rev_fecha_prioritaria' => $fechaPrioritaria,
    'rev_fecha_prioritaria_caducidad' => $fechaCaducidad
] = $revoltura;

// -------------------------
# 2) Actualizar como prioritario
// -------------------------
$update = $cnx->prepare("
    UPDATE rev_revolturas 
    SET rev_prioritario = 1 
    WHERE rev_id = ?
");
$update->bind_param("i", $id);

if (!$update->execute()) {
    echo json_encode(['error' => 'Error al marcar como prioritario']);
    exit;
}

// -------------------------
# 3) Construcción del correo HTML
// -------------------------
$body = '
<div style="font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; padding: 30px 20px; line-height: 1.5;">
  <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">

    <!-- Encabezado minimalista -->
    <div style="background: linear-gradient(135deg, #2a4d69 0%, #3a6d9e 100%); padding: 24px 32px;">
      <table width="100%">
        <tr>
          <td>
            <h2 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 600;">📦 Empaque Prioritario</h2>
          </td>
          <td style="text-align: right;">
            <div style="display: inline-block; background: rgba(255,255,255,0.15); color: #ffffff; font-size: 12px; padding: 4px 12px; border-radius: 20px; font-weight: 500;">Prioridad</div>
          </td>
        </tr>
      </table>
    </div>

    <!-- Contenido -->
    <div style="padding: 32px;">
      <p style="color: #4a5568; margin-top: 0; margin-bottom: 24px; font-size: 15px;">
        Se ha marcado una revoltura como <strong style="color: #2a4d69;">prioritaria</strong>. A continuación se muestran los detalles:
      </p>

      <!-- Tabla de datos -->
      <div style="background: #f8fafc; border-radius: 8px; padding: 4px;">
        <table style="width: 100%; border-collapse: collapse;">
          <tr>
            <td style="padding: 16px; color: #4a5568; width: 45%; font-weight: 500; border-bottom: 1px solid #e2e8f0;">Folio de la revoltura:</td>
            <td style="padding: 16px; color: #1a202c; border-bottom: 1px solid #e2e8f0;">' . $folio . '</td>
          </tr>
          <tr>
            <td style="padding: 16px; color: #4a5568; font-weight: 500; border-bottom: 1px solid #e2e8f0;">Fecha de revoltura:</td>
            <td style="padding: 16px; color: #1a202c; border-bottom: 1px solid #e2e8f0;">' . $fecha . '</td>
          </tr>
          <tr>
            <td style="padding: 16px; color: #4a5568; font-weight: 500; border-bottom: 1px solid #e2e8f0;">Fecha prioritaria:</td>
            <td style="padding: 16px; color: #1a202c; border-bottom: 1px solid #e2e8f0;">' . $fechaPrioritaria . '</td>
          </tr>
          <tr>
            <td style="padding: 16px; color: #4a5568; font-weight: 500;">Caducidad prioritaria:</td>
            <td style="padding: 16px; color: #1a202c;">' . $fechaCaducidad . '</td>
          </tr>
        </table>
      </div>

      <!-- Aviso -->
      <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0; text-align: center;">
        <p style="color: #718096; font-size: 12px; margin: 0;">
          Este es un mensaje generado automáticamente por el sistema de Producción · PROGEL Mexicana.
          <br>
          <span style="color: #a0aec0;">No responda a este correo.</span>
        </p>
      </div>
    </div>
  </div>
</div>
';

// -------------------------
# 4) Enviar correo
// -------------------------
$enviado = $mailSender->sendMailPrioritario("Empaque prioritario", $body);

if ($enviado) {
    echo json_encode(['success' => 'Correo enviado correctamente.']);
} else {
    echo json_encode(['error' => 'Marcado exitoso, pero fallo el envío del correo.']);
}
