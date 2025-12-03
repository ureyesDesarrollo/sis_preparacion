<?php
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailSender
{
    private $mail;

    public function __construct()
    {
        // Crear instancia de PHPMailer
        $this->mail = new PHPMailer(true);

        // Configuración SMTP
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.office365.com'; // Servidor SMTP
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'sistemapreparacion@progel.com.mx'; // Usuario SMTP
        $this->mail->Password = 'Progel#2023'; // Contraseña SMTP
        $this->mail->SMTPSecure = 'tls'; // Encriptación
        $this->mail->Port = 587; // Puerto SMTP
        $this->mail->CharSet = 'UTF-8'; // Codificación de caracteres


        // Configurar remitente predeterminado
        $this->mail->setFrom('sistemapreparacion@progel.com.mx', 'Sistemas');
    }

    // Método para enviar correos
    public function sendMail($asunto, $body, $pdf1, $pdf2)
    {
        try {
            // Configurar destinatario
			$this->mail->addAddress("concepcion@progel.com.mx");
			$this->mail->addAddress("bgonzalez@progel.com.mx");
			//$this->mail->addAddress("gerentemejoracontinua@progel.com.mx");
			$this->mail->addAddress("jefaturati@progel.com.mx");
			$this->mail->addAddress("gerentedeventas@progel.com.mx");
			$this->mail->addAddress("ventas3@progel.com.mx");
			$this->mail->addAddress("fernando.rull@progel.com.mx");
            //$this->mail->addAddress("desarrollo@progel.com.mx");
            //$this->mail->addCC("jefaturati@progel.com.mx");
            //$this->mail->addCC('');  
            // Configurar asunto y cuerpo del correo
            $this->mail->isHTML(true);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $body;
            $this->mail->addAttachment($pdf1);
            $this->mail->addAttachment($pdf2);

            // Enviar correo
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Manejar errores de envío
            return false;
        }
    }

    // NUEVO método para orden de devolución
    public function sendOrdenDevolucion($ordenInfo, $destinatarios = [])
    {
        try {
            $base64File = __DIR__ . '/logo_base64.txt'; // Ruta absoluta desde el directorio actual
            $logoBase64 = file_get_contents($base64File);

            if ($logoBase64 === false) {
                die("Error: No se pudo leer logo_base64.txt en: " . $base64File);
            }
            $this->mail->clearAddresses();
			$this->mail->addAddress("bgonzalez@progel.com.mx");
			$this->mail->addAddress("jefaturati@progel.com.mx");
			//$this->mail->addAddress("gerentedeventas@progel.com.mx");
			$this->mail->addAddress("ventas3@progel.com.mx");
			$this->mail->addAddress("fernando.rull@progel.com.mx");
			$this->mail->addAddress("almacen@progel.com.mx");
			$this->mail->addAddress("contacto@gprot.com.mx");
            //$this->mail->addAddress("desarrollo@progel.com.mx");
            #$this->mail->addCC("jefaturati@progel.com.mx");

            if (!empty($destinatarios)) {
                foreach ($destinatarios as $email) {
                    $this->mail->addCC($email);
                }
            }
            $this->mail->isHTML(true);
            $this->mail->Subject = "Orden de Devolución - {$ordenInfo['od_id']}";

            // Construir el cuerpo HTML del correo
            $body = '
<!DOCTYPE html>
<html>
<head>
     <style>
        body {
            font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
            background-color: #f9fafb;
        }
        .document-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eaeaea;
            position: relative;
        }
        .header h3 {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 600;
        }
        .company-info {
            margin-bottom: 25px;
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.5;
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            background-color: #f1f5f9;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge.completed {
            background-color: #d4edda;
            color: #155724;
        }
        .info-section {
            margin-bottom: 30px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #3498db;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            min-width: 150px;
            color: #4a5568;
        }
        .info-value {
            color: #2d3748;
        }
        .section-title {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #eaeaea;
        }
        .table-container {
            width: 100%;
            margin: 20px 0 30px 0;
            border-collapse: collapse;
            font-size: 14px;
        }
        .table-container thead {
            background-color: #2c3e50;
            color: white;
        }
        .table-container th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
        }
        .table-container td {
            padding: 12px 15px;
            border-bottom: 1px solid #eaeaea;
        }
        .table-container tfoot td {
            font-weight: 600;
            background-color: #f8f9fa;
            border-top: 2px solid #eaeaea;
        }
        /* Elimina el hover en las filas de la tabla */
        .table-container tbody tr:hover {
            background-color: transparent;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #eaeaea;
            padding-top: 20px;
        }
        .logo {
            max-width: 180px;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        .text-right {
            text-align: right;
        }
        .document-number {
            position: absolute;
            top: 0;
            right: 0;
            background-color: #f1f5f9;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="document-container">
        <div class="header">
            <div class="document-number">ORDEN-' . $ordenInfo['od_id'] . '</div>
            <img src="' . $logoBase64 . '" alt="Logo progel" class="logo">
            <h3>Orden de Devolución</h3>
            <div class="badge ' . strtolower($ordenInfo['od_estado'] ?? 'pending') . '">' . ($ordenInfo['od_estado'] ?? 'Pendiente') . '</div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Cliente:</div>
                <div class="info-value">' . $ordenInfo['cte_nombre'] . '</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de emisión:</div>
                <div class="info-value">' . date('d/m/Y H:i', strtotime($ordenInfo['od_fecha'])) . '</div>
            </div>
            <div class="info-row">
                <div class="info-label">Motivo:</div>
                <div class="info-value">' . $ordenInfo['od_motivo'] . '</div>
            </div>
            <div class="info-row">
                <div class="info-label">Responsable:</div>
                <div class="info-value">' . ($ordenInfo['responsable'] ?? 'Sistema Automático') . '</div>
            </div>
        </div>

        <div class="section-title">Detalles de la Devolución</div>
        <table class="table-container">
            <thead>
                <tr>
                    <th style="width: 15%;">Factura</th>
                    <th style="width: 15%;">Lote</th>
                    <th style="width: 50%;">Presentación</th>
                    <th style="width: 20%; text-align: right;">Cantidad</th>
                </tr>
            </thead>
            <tbody>';

            foreach ($ordenInfo['detalles'] as $item) {
                $body .= '
                <tr>
                    <td>' . $item['factura'] . '</td>
                    <td>' . $item['lote'] . '</td>
                    <td>' . $item['pres_descrip'] . '</td>
                    <td class="text-right">' . number_format($item['cantidad'], 2) . '</td>
                </tr>';
            }

            $body .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Total productos:</td>
                    <td class="text-right">' . count($ordenInfo['detalles']) . '</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">Total unidades:</td>
                    <td class="text-right">' . number_format(array_sum(array_column($ordenInfo['detalles'], 'cantidad')), 2) . '</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Documento generado automáticamente el ' . date('d/m/Y H:i') . '</p>
            <p>© ' . date('Y') . ' Progel Mexicana. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>';

            $this->mail->Body = $body;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendMailPrioritario($asunto, $body)
    {
        try {
            // Configurar destinatario
            $this->mail->addAddress("desarrollo@progel.com.mx");
            //$this->mail->addAddress("gerentedecalidad@progel.com.mx");
            //$this->mail->addCC("fjmuro@progel.com.mx");
            //$this->mail->addCC('');
            // Configurar asunto y cuerpo del correo
            $this->mail->isHTML(true);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $body;
            
            // Enviar correo
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Manejar errores de envío
            return false;
        }
    }
}
