<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_3b_g as pf3bg INNER JOIN procesos as p  on(pf3bg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysql_error()."Error: en consultar procesos_fase_3b_g ");
$regfg= mysqli_fetch_assoc($sqlfg);




/*$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$idx_pro' AND pe_id = $regfg[pe_id]") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);*/

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '6'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '6'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

?>


<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
<style>
/*td{
  padding-left: 5px;padding-right: 5px
}*/
</style>


  <div class="divProcesos">
    <table width="100%" style="margin:20px 0px 20px 0px">
      <tr>
        <td colspan="2"><table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
          <tr>
            <td height="45" colspan="10">ADICION A SOSANota:Estar revisando los chequeos durante las 32 horas</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" border="1">
          <tr>
            <td width="9%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha de inicio</span></td>
            <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
            <td width="12%"  style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini'])?></td>
            <td><?php echo $regProAux['proa_hr_ini'] ?></td>
            <td><?php echo $regfg['pfg3_temp_ag'] ?></td>
            <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>
          </tr>
          <tr>
            <td  style="font-weight: bold;background: #e6e6e6"><span>Agrega lts sosa</span></td>
            <td style="font-weight: bold;background: #e6e6e6">Ph</td>
            <td  style="font-weight: bold;background: #e6e6e6">Temp </td>
            <td style="font-weight: bold;background: #e6e6e6">Norm</td>
          </tr>
          <tr>
            <td><?php echo $regfg['pfg3_lts']?></td>
            <td><?php echo $regfg['pfg3_ph']?></td>
            <td><?php echo $regfg['pfg3_temp']?></td>
            <td><?php echo $regfg['pfg3_norm']?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="61%" valign="top">
          <table width="96%" border="1">
            <tr style="font-weight: bold;background: #e6e6e6">
              <td width="29%" style="font-size: small; text-align: center;">Chequeo</td>
              <td width="31%" style="font-size: small; text-align: center;">Fecha</td>
              <td width="40%" style="font-size: small; text-align: center;">Hora</td>
              <td width="40%" style="font-size: small; text-align: center;">Temp</td>
              <td width="40%" style="font-size: small; text-align: center;">Norm</td>
              <td width="40%" style="font-size: small; text-align: center;">Sosa</td>
              <td width="40%" style="font-size: small; text-align: center;">Movimeinto</td>
              <td width="40%" style="font-size: small; text-align: center;">Reposo</td>
              <td width="40%" style="font-size: small; text-align: center;">Capturo</td>
            </tr>
            <?php 
           $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_3b_d as pf3bd WHERE pf3bd.pfg3_id = '$regfg[pfg3_id]'") or die(mysql_error()."Error: en consultar procesos_fase_3b_d");
          $regfd= mysqli_fetch_assoc($sqlfd);
do{

         
            ?>
            <tr>
              <td><?php echo $regfd['pfd3_ren']?></td>
              <td><?php echo fnc_formato_fecha($regfd['pfd3_fecha'])?></td>
              <td><?php echo $regfd['pfd3_hr']?></td>
              <td><?php echo fnc_formato_val($regfd['pfd3_temp'])?></td>
              <td><?php echo fnc_formato_val($regfd['pfd3_norm'])?></td>
              <td><?php echo fnc_formato_val($regfd['pfd3_sosa'])?></td>
              <td><?php echo fnc_formato_val($regfd['pfd3_movimiento'])?></td>
              <td><?php echo fnc_formato_val($regfd['pfd3_reposo'])?></td>
              <td><?php echo fnc_nom_usu($regfd['usu_id'])?></td>
            </tr>
            <?php } while($regfd= mysqli_fetch_assoc($sqlfd));?>
          </table>
          <p>Revisar estado de material.Si ya esta LIBERAR</p>
         
        </td>
        <td width="39%" rowspan="2">
          <table border="1" width="435">
          <tr>
            <td colspan="4" align="center" style="font-weight: bold;background: #e6e6e6;">CP CHEQUEOS DE NORMALIDAD</td>
          </tr>
          <tr>
            <td width="137" style="font-weight: bold;background: #e6e6e6">Norm. solución</td>
            <td width="103"><?php echo $regfg['pfg3_norm1'] ?></td>
            <td width="74" style="font-weight: bold;background: #e6e6e6">Horas</td>
            <td width="93"><?php echo $regfg['pfg3_hr1'] ?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Nombre LCP</td>
            <td><?php echo fnc_nom_usu($regfg['pfg3_usu1']) ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Norm. solución</td>
            <td><?php echo fnc_formato_val($regfg['pfg3_norm2'])?></td>
            <td style="font-weight: bold;background: #e6e6e6">Horas</td>
            <td><?php echo fnc_formato_val($regfg['pfg3_hr2'])?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Nombre LCP</td>
            <td><?php echo fnc_nom_usu($regfg['pfg3_usu2']) ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        <p>Nota: Seguir anotando los chequeos después de que cumpla su tiempo (32-36) o cuando se inicia abara normalida</p>
           <?php 
        $usu_aux = $regProAux['usu_sup'];
        $usu_proLib = $regProLib['usu_id'];
         ?>
        <table width="436" border="1">
          <tr>
            <td width="75" rowspan="4" style="font-size:50px">3b</td>
            <td colspan="2" style="background: #e6e6e6;font-weight: bold;">Liberación <?php echo fnc_rango_de(6) ?> a <?php echo fnc_rango_a(6) ?>horas</td>
          </tr>
          <tr>
            <td width="169"></td>
            <td width="170"></td>
          </tr>
          <tr>
            <td>Horas totales</td>
            <td><?php echo $regProLib['prol_hr_totales'] ?></td>
          </tr>
          <tr>
            <td>Nombre LCP</td>
            <td><?php echo fnc_nom_usu($usu_proLib)?></td>
          </tr>
        </table>
        <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td valign="top"><table border="1" width="96%">
          <tr style="font-weight: bold;">
            <td width="266" style="background: #e6e6e6">Fecha termina sosa</td>
            <td width="168" style="background: #e6e6e6">Hora termina sosa</td>
            <td width="175" style="background: #e6e6e6">Usuario</td>
          </tr>
          <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin'])?></td>
            <td><?php echo $regProAux['proa_hr_fin']?></td>
            <td><?php echo fnc_nom_usu($usu_aux)?></td>
          <tr>
            <td colspan="3">El agua de este proceso se manda a agua recuperada semilimpia (pila 2)</td>
          </tr>
          <tr>
            <td colspan="3" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          </tr>
          <tr>
            <td colspan="3"><?php echo $regProAux['proa_observaciones']?></td>
          </tr>
          </table>
          <p>&nbsp;</p>
          <table width="434" border="1">
            <tr>
              <td  style="font-weight: bold;background: #e6e6e6"><label for="inputPassword4" >Horas totales de todo el proceso</label></td>
              <td  style="font-weight: bold;background: #e6e6e6">Revisó</td>
              <td  style="font-weight: bold;background: #e6e6e6">(<?php echo fnc_hora_de(6) ?> a <?php echo fnc_hora_a(6) ?> Horas)</td>
            </tr>
            <tr>
              <td><?php echo $regProLib['prol_hr_totales']?></td>
              <td><?php echo fnc_nom_usu($regProLib['usu_id'])?></td>
              <td></td>
            </tr>
          </table>
        <p>&nbsp;</p></td>
      </tr>
    </table>
</div>
