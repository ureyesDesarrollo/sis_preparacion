<?php

/* require "PHPMailer/Exception.php";
require "PHPMailer/PHPMailer.php";
require "PHPMailer/SMTP.php";

 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

$mail->isSMTP();                                            //Send using SMTP
/*
$mail->Host       = "mail.ccaconsultoresti.com";                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = "mensajes@ccaconsultoresti.com";                     //SMTP username
$mail->Password   = "CCA2021&01a#";                               //SMTP password
$mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set 
*/
$mail->Host       = "smtp.office365.com";                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = "sistemapreparacion@progel.com.mx"; // sistemapreparacion@progel.com.mx                   //SMTP username
$mail->Password   = "Progel#2023";                               //SMTP password
$mail->Port       = 587;   

$destinatario = "car170@hotmail.com"; // escribe aqui tu correo, es el correo al que deseas que te lleguen los correos
$destinatario_cc = "caruc.91@gmail.com"; // direccion de correo para copia
$destinatario_bcc = "mc.munoz.rz@gmail.com"; // direccion de correo para copia oculta

$asunto  = "v2 - Sistema preparacion Progel #" . $clave2;

// Mensajes 
$enviado_bien = "Tu mensaje ha sido enviado!";
$enviado_mal  = "ERROR: No se pudo enviar";

// RECOGER DATOS 
//reset ($_POST);
$mensaje = "<h2><mark>Correo de Alerta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</mark></h2>";

$mensaje .= "Fecha y Hora: " . date("d/m/Y H:i:s") . "<br><br>";
$mensaje .= "<table border=\"1\" cellspacing='0'>";
$mensaje .= "<tr><th align='left'>Proceso:</th><td>" . $proceso . "&nbsp;&nbsp;</td><th align='left'>Etapa:</th><td>" . $reg_etapas2['pep_nombre'] . "(" . $reg_etapas['pep_descripcion'] . ")&nbsp;&nbsp;</td></tr>";
$mensaje .= "<tr><th align='left'>Parametro:</th><td>" . $parametro2 . "&nbsp;&nbsp;</td><th>Valor:</th><td bgcolor='#E10600' align='center'><font color='#ffffff'>" . $valor2 . "</font></td></tr>";
$mensaje .= "<tr><th align='left'>Lavador:</th><td colspan='3'>" . $str_lavador . "</td></tr>";
$mensaje .= "<tr><th align='left'>Paleto:</th><td colspan='3'>" . $str_paleto . "</td></tr>";
$mensaje .= "<tr><th align='left'>Usuario:</th><td colspan='3'>" . $nombre_usuario . "</td></tr>";
$mensaje .= "</table>";

$mensaje .= "<br><br>";
$mensaje .= "<table border=\"1\" cellspacing='0'>";
$mensaje .= "<tr><th align='left' colspan='4'>Parametros</th></tr>";
$mensaje .= "<tr><th align='left'>Inicio:</th><td>" . $reg_etapas2['pep_inicio'] . "&nbsp;&nbsp;</td><th align='left'>Fin:</th><td>" . $reg_etapas2['pep_fin'] . "&nbsp;&nbsp;</td></tr>";
$mensaje .= "</table>";

$mensaje .= "<br><br>";
$mensaje .= "<h5>No responder a este correo.<br>";
$mensaje .= "Revisar información dentro del Sistema de preparación.</h5>";

if ($email != "") {
  $mail->From = $email; //Dirección del remitente
  $mail->FromName = $nombre; //Nombre del remitente
}
if ($destinatario_cc != "")
  $mail->AddCC($destinatario_cc); // Copia
if ($destinatario_bcc != "")
  $mail->AddBCC($destinatario_bcc); // Copia oculta

$mail->IsHTML(true); // El correo se envía como HTML
$mail->Subject = $asunto;
$mail->AddAddress($destinatario); // Esta es la dirección a donde enviamos

$mail->Body = $mensaje;
if ($mail->Send()) {
  //echo $enviado_bien;
  $enviado_bien;
} else {
  //echo $enviado_mal;
  $enviado_mal;
}
//$mail->close();

//*********************************************************************
// EMAIL 2
/*
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;

$mail->Host = "mail.ccaconsultoresti.com"; 
$mail->Username = "mensajes@ccaconsultoresti.com"; 
$mail->Password = "CCA2021&01a#";
$mail->Port = 587;

$destinatario = "mc.munoz.rz@gmail.com"; // escribe aqui tu correo, es el correo al que deseas que te lleguen los correos
$destinatario_cc = "caruc.91@gmail.com"; // direccion de correo para copia
$destinatario_bcc = "car170@hotmail.com"; // direccion de correo para copia oculta

$asunto  = "v2 - Sistema preparacion PRUEBAS #".$clave; 

// Mensajes 
$enviado_bien = "Tu mensaje ha sido enviado!";
$enviado_mal  = "ERROR: No se pudo enviar";

// RECOGER DATOS 
//reset ($_POST);
$mensaje = "<h2><mark>Correo de Alerta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</mark></h2>";

$mensaje .= "Fecha y Hora: ".date("d/m/Y H:i:s")."<br><br>";  
$mensaje .= "<table border=\"1\" cellspacing='0'>";
$mensaje .= "<tr><th align='left'>Proceso:</th><td>" . $proceso . "&nbsp;&nbsp;</td><th align='left'>Etapa:</th><td>" . $reg_etapas['pep_nombre'] . "&nbsp;&nbsp;</td></tr>";
$mensaje .= "<tr><th align='left'>Parametro:</th><td>" . $parametro . "&nbsp;&nbsp;</td><th>Valor:</th><td bgcolor='#E10600' align='center'><font color='#ffffff'>" . $valor . "</font></td></tr>";
$mensaje .= "<tr><th align='left'>Lavador:</th><td colspan='3'>" . $str_lavador. "</td></tr>";
$mensaje .= "<tr><th align='left'>Paleto:</th><td colspan='3'>" . $str_paleto . "</td></tr>";
$mensaje .= "<tr><th align='left'>Usuario:</th><td colspan='3'>" . $nombre_usuario . "</td></tr>";
$mensaje .= "</table>";

$mensaje .= "<br><br>";
$mensaje .= "<table border=\"1\" cellspacing='0'>";
$mensaje .= "<tr><th align='left' colspan='4'>Parametros</th></tr>";
$mensaje .= "<tr><th align='left'>Inicio:</th><td>" . $reg_etapas['pep_inicio'] . "&nbsp;&nbsp;</td><th align='left'>Fin:</th><td>" . $reg_etapas['pep_fin'] . "&nbsp;&nbsp;</td></tr>";
$mensaje .= "</table>";

$mensaje .= "<br><br>";
$mensaje .= "<h5>No responder a este correo.<br>";
$mensaje .= "Revisar información dentro del Sistema de preparación.</h5>";  

if ($email!= "") 
{
  $mail->From = $email; //Dirección del remitente
  $mail->FromName = $nombre; //Nombre del remitente
}
if ($destinatario_cc != "") 
  $mail->AddCC($destinatario_cc); // Copia
if ($destinatario_bcc != "") 
  $mail->AddBCC($destinatario_bcc); // Copia oculta

$mail->IsHTML(true); // El correo se envía como HTML
$mail->Subject = $asunto;
$mail->AddAddress($destinatario); // Esta es la dirección a donde enviamos

$mail->Body = $mensaje;
if ($mail->Send()) 
{
	//echo $enviado_bien;
	 $enviado_bien;
} else 
{
	//echo $enviado_mal;
	$enviado_mal;
}
//$mail->close();*/
