<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>

</body>
</html>

<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
<style>
td{
  padding-left: 5px;padding-right: 5px
}
</style>


<div class="col-md-7">
  <table border="1" style="background: #FCEFF2;font-size: 12px">
    <tr>
      <td colspan="8">
      Lavados inicales.
      Este proceso se puede hacer con aguar recuperada limpia (pila 1).
      Lavados finales de paleto a paleto y en el ultimo lavado utilizar agua limpia si se necesita bajar CE</td>
    </tr>
  </table>
  <p></p>
</div>
<div class="col-md-12" style="margin-bottom: 30px">
  <table border="1">
    <tr>
      <td style="font-weight: bold;">Fecha inicia lavados</td>
      <td style="font-weight: bold;">Hora inicio</td>
      <td style="font-weight: bold;">Temp agua utilizada</td>
      <td width="29%" style="border-top: 1px solid#fff;border-bottom: 1px solid#fff;"></td>
      <td rowspan="2" style="background: #e6e6e6;font-weight: bold;">Bajar CE A 3.0</td>
    </tr>
    <tr>
      <td>24/5/18</td>
      <td>15:00</td>
      <td>16:00</td>
      <td style="border-top: 1px solid#fff;border-bottom: 1px solid#fff;border-right: 1px solid#fff;font-weight: bold;"></td>
    </tr>
  </table>
  <p></p>
  <table width="770" border="1">
    <tr style="font-weight: bold;">
      <td width="10%" style="font-size: small; text-align: center;">LAV TIPO AGUA</td>
      <td width="10%" style="font-size: small; text-align: center;">TEMP</td>
      <td width="10%" style="font-size: small; text-align: center;">HORA INICIA LLENADO</td>
      <td width="10%" style="font-size: small; text-align: center;">HORA TERMINA LLENADO</td>
      <td width="10%" style="font-size: small; text-align: center;">HORA INICIA MOVIMIENTO</td>
      <td width="10%" style="font-size: small; text-align: center;">HORA TERMINA MOVIMIENTO</td>
      <td width="10%"  style="font-size: small; text-align: center;">PH</td>
      <td width="10%"  style="font-size: small; text-align: center;">CE</td>
    </tr>
    <tr>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
   </tr>
   <tr>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small"></td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
   </tr>
   <tr>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small"></td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
   </tr>
   <tr>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
   </tr>
   <tr>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
     <td style="font-size: small">&nbsp;</td>
   </tr>
 </table>
</div>

<div class="col-md-12" style="margin-bottom: 20px">
<table>
  <tr><td></td></tr>
</table>
</div>

<div class="col-md-12" style="margin-bottom: 20px">
  <table border="1" width="770">
    <tr style="font-weight: bold;">
      <td>Fecha termina</td>
      <td>Hora termina</td>
      <td>Realizó</td>
      <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
      <td  style="border:1px solid#fff">7 a 13 horas</td>
      <td width="7%" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
      <td rowspan="5" width="30px" style="font-size:50px">1</td>
      <td colspan="3" style="background: #e6e6e6;font-weight: bold;">Liberación CE a 3MAX</td>
    </tr>
    <tr>
      <td>x</td>
      <td>x</td>
      <td>x</td>
      <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
      <td style="border:1px solid#fff"></td>
      <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
      <td>CE de liberación</td>
      <td width="15%" colspan="2">datos</td>

    </tr>
    <tr>
      <td></td>
      <td>x</td>
      <td>x</td>
      <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
      <td style="border:1px solid#fff"></td>
      <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
      <td>Horas totales </td>
      <td width="15%" colspan="2">datos</td>

    </tr>
    <tr>
      <td>x</td>
      <td>x</td>
      <td>x</td>
      <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
      <td style="border:1px solid#fff"></td>
      <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
      <td>Nombre LCP </td>
      <td width="15%" colspan="2">datos</td>

    </tr>
    <tr>
      <td>x</td>
      <td>x</td>
      <td>x</td>
      <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
      <td style="border:1px solid#fff"></td>
      <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
      <td>Firma LCP </td>
      <td width="15%" colspan="2">datos</td>
    </tr>
  </table>
</div>

<div class="col-md-12" style="margin-bottom: 20px">
<table>
  <tr><td></td></tr>
</table>
</div>

<div class="col-md-12">
<table border="1">
  <tr  style="font-weight: bold;">
    <td width="50%" colspan="8">Observaciones</td>
  </tr>
  <tr>
    <td>Aqui tus observaciones</td>
  </tr>
</table>
</div>
<?php /*';

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=listado_pt_".date("Y_m_d").".xls");
header("Pragma: no-cache");
header("Expires: 0");
echo $tbHtml; */

?>

