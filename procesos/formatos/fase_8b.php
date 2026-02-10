<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_8_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysql_error()."Error: en consultar procesos_fase_8_g ");
$regfg= mysqli_fetch_assoc($sqlfg);

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '21'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '21'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

$sql_lib_coc = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b_cocidos WHERE prol_id = '$regProLib[prol_id]'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$reg_lib_coc = mysqli_fetch_assoc($sql_lib_coc);

?>

<style>
  /*td{
  padding-left: 5px;padding-right: 5px
}*/
  
  @media print {
    .liberacion{
        width:305px;
    }
    
     .tablaMov{
        width:1015px;
    }
    
     @media print {
    .btn{
        display: none;
    }
}
</style>
<script language="javascript">
function AbreModalPaletoB(proceso, lavador, paleto, prop){
var datos={
	"pro_id": proceso,
	"lavador": lavador,
	"paleto": paleto,
	 "prop": prop,
}
	//alert($("hdd_pro_id").val());
	$.ajax({
		type:'post',
		url: 'modal_paleto.php',
		data: datos,
		//data: {nombre:n},
		success: function(result){
			$("#modalPaleto8b").html(result);
			$('#modalPaleto8b').modal('show')
		}	
	});
	return false;
}

//Bloquear boton modal de lavadores a paleto
    function confirmEnviar3() {

      formModalP.btn.disabled = true; 
      formModalP.btn.value = "Enviando...";

      setTimeout(function(){
        formModalP.btn.disabled = true;
        formModalP.btn.value = "Guardar";
      },2000);

      var statSend = false;
      return false;
    }
</script>

<div class="divProcesos">
  <table width="100%" style="margin:20px 0px 20px 0px">
    <tr>
      <td colspan="2"><table class="tablaMov" width="97%" border="1" style="background: #FCEFF2;font-size: 12px;">
        <tr>
          <td height="45">LAVADOS FINALES. Este proceso se utilizará: Agua recuperada de los ultimos lavados finales de otro paleto y/o agua limpia
            <div></div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><table class="tablaMov" width="97%" border="1">
          <tr>
            <td width="20%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha inicio</span></td>
            <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini'])?></td>
            <td><?php echo $regProAux['proa_hr_ini']?></td>
            <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" valign="top">
          <table class="tablaMov" width="1095" border="1" style="margin-top: 10px">
            <tr style="font-weight: bold;background: #e6e6e6">
              <td width="12%" style="font-size: small; text-align: center;">Tipo agua</td>
              <td style="font-size: small; text-align: center;">Movimiento</td>
              <td style="font-size: small; text-align: center;">Hora ini. llenado</td>
              <td style="font-size: small; text-align: center;">Hora fin. llenado</td>
              <td style="font-size: small; text-align: center;">Hora ini. drenado</td>
              <td style="font-size: small; text-align: center;">Hora fin. drenado</td>
              <td style="font-size: small; text-align: center;">Ph</td>
              <td style="font-size: small; text-align: center;">Ce</td>
              <td style="font-size: small; text-align: center;">Temp</td>
              <td width="12%" style="font-size: small; text-align: center;">Agua a</td>
              <td style="font-size: small; text-align: center;">Observaciones</td>
              <td style="font-size: small; text-align: center;">Capturo</td>
              <td rowspan="2" >Para hacer los lavados finales deben tardar de (<span style="background: #e6e6e6"><?php echo fnc_hora_de(21) ?> a <?php echo fnc_hora_a(21) ?></span> horas)</td>

            </tr>
            <?php
            $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_8_d WHERE pfg8_id = '$regfg[pfg8_id]'") or die(mysql_error()."Error: en consultar procesos_fase_8_d");
            $regfd= mysqli_fetch_assoc($sqlfd);

            do{


  $agua_a = mysqli_query($cnx, "SELECT * FROM tipos_agua_a WHERE taa_id = '$regfd[taa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
          $reg_aa= mysqli_fetch_assoc($agua_a);

           $tipo_agua = mysqli_query($cnx, "SELECT * FROM tipos_agua WHERE tpa_id = '$regfd[tpa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
          $reg_tipo_agua= mysqli_fetch_assoc($tipo_agua);

              ?>
              <tr>
                <td><?php echo $reg_tipo_agua['tpa_descripcion']?></td>
                <td><?php echo $regfd['pfd8_mov']?></td>
                <td><?php echo $regfd['pfd8_ini_llenado']?></td>
                <td><?php echo $regfd['pfd8_fin_llenado']?></td>
                <td><?php echo $regfd['pfd8_ini_dren']?></td>
                <td><?php echo $regfd['pfd8_fin_dren']?></td>
                <td><?php echo $regfd['pfd8_ph']?></td>
                <td><?php echo $regfd['pfd8_ce']?></td>
                <td><?php echo $regfd['pfd8_temp']?></td>
                <td><?php echo $reg_aa['taa_descripcion']?></td>
                <td><?php echo $regfd['pfd8_observaciones']?></td>
                <td><?php echo fnc_nom_usu($regfd['usu_id'])?></td>
              </tr>
            <?php } while($regfd= mysqli_fetch_assoc($sqlfd));?>
          </table>
          <p>
            <?php 
            $usu_aux = $regProAux['usu_sup'];
            $usu_proLib = $regProLib['usu_id'];
            ?>
          </p></td>
        </tr>
        <tr>
          <td width="50%" valign="top"><table border="1" width="690">
            <tr style="font-weight: bold;">
              <td width="205" style="background: #e6e6e6">Fecha termina lavados finales</td>
              <td width="94" style="background: #e6e6e6">Hora termina </td>
              <td width="111" style="background: #e6e6e6">Horas totales</td>
              <td width="109" style="background: #e6e6e6">Usuario</td>
              <td width="143" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(21) ?> a <?php echo fnc_hora_a(21) ?> horas)</td>
            </tr>
            <tr>
              <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin'])?></td>
              <td><?php echo $regProAux['proa_hr_fin']?></td>
              <td><?php echo $regfg['pfg8_hr_totales']?></td>
              <td><?php echo fnc_nom_usu($usu_aux)?></td>
              
            </tr>
          </table></td>
          <td width="37%" rowspan="3" valign="top"><table class="liberacion" width="384" border="1">
            <tr>
              <td width="36" rowspan="14" style="font-size:50px">8b</td>
              <td colspan="4" style="background: #e6e6e6;font-weight: bold;">LIBERACION PH LIBERACIÓN (PH <?php echo fnc_rango_de(21) ?> a <?php echo fnc_rango_a(21) ?>)</td>
            </tr>
           <tr>
              <td width="113">Fecha</td>
              <td width="81"><?php echo fnc_formato_fecha($regProLib['prol_fecha']) ?></td>
              <td width="69">Hora</td>
              <td width="81"><?php echo $regProLib['prol_hora'] ?></td>
            </tr>
            <tr style="font-weight: bold;">
              <td>Cocido ph</td>
              <td>Ce</td>
              <td>Cue sob</td>
              <td>% ext</td>
            </tr>
            <?php 

            do { ?>
             
             <tr>
              <td><?php echo  "<spam style='font-weight:bold'>L".$reg_lib_coc['prol_ren'].'</spam> '.fnc_formato_val($reg_lib_coc['prol_cocido']) ?></td>
              <td><?php echo fnc_formato_val($reg_lib_coc['prol_ce']) ?></td>
              <td><?php echo fnc_formato_val($reg_lib_coc['prol_cuero_sob']) ?></td>
              <td><?php echo fnc_formato_val($reg_lib_coc['prol_por_extrac']) ?></td>
            </tr>

           <?php } while ($reg_lib_coc = mysqli_fetch_assoc($sql_lib_coc)); ?>
            <!--<tr>
              <td>L1 Cocido</td>
              <td><?php echo $regProLib['prol_cocido_ph1'] ?></td>
              <td>Ce</td>
              <td><?php echo $regProLib['prol_ce1'] ?></td>
            </tr>
            <tr>
              <td>L2 Cocido</td>
              <td><?php echo fnc_formato_val($regProLib['prol_cocido_ph2'])?></td>
              <td>Ce</td>
              <td><?php echo fnc_formato_val($regProLib['prol_ce2'])?></td>
            </tr>-->
            <!--<tr>
              <td>Cocido Lib% ext</td>
              <td colspan="4"><?php echo  $regProLib['prol_cocido_lib']?></td>
            </tr>-->
            <tr>
              <td>Color</td>
              <td colspan="4"><?php echo  $regProLib['prol_color']?></td>
            </tr>
            <tr>
              <td>Color caldo</td>
              <td colspan="4"><?php echo  $regProLib['prol_color_caldo']?></td>
            </tr>
            <tr>
              <td>% de solidos</td>
              <td colspan="4"><?php echo  $regProLib['prol_solides']?></td>
            </tr>
            <tr>
              <td>Observaciones</td>
              <td colspan="4"><?php echo  $regProLib['prol_observaciones']?></td>
            </tr>
            <tr>
              <td>Nombre LCP</td>
              <td colspan="4"><?php echo fnc_nom_usu($usu_proLib)?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>El agua de este proceso se manda a (Pila 1) agua recuperada limpia</td>
        </tr>
        <tr>
          <td valign="top"><table border="1" width="701">
            <tr style="font-weight: bold;">
              <td width="210" style="background: #e6e6e6">Horas totales de todo el proceso</td>
              <td width="96" style="background: #e6e6e6">Usuario</td>
              <td width="124" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(21) ?> a <?php echo fnc_hora_a(21) ?> horas)</td>
            </tr>
            <tr>
              <td><?php echo $regfg['pfg8_hr_totales']?></td>
              <td><?php echo fnc_nom_usu($usu_aux)?></td>
            </tr>
          </table>
            <p>&nbsp;</p>
            <table border="1" width="700">
            <tr style="font-weight: bold;">
              <td width="142" style="background: #e6e6e6">Fecha sale a producción</td>
              <td width="95" style="background: #e6e6e6">Hora </td>
              <td width="107" style="background: #e6e6e6">Usuario</td>
              <td width="226" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(21) ?> a <?php echo fnc_hora_a(21) ?> horas)</td>
            </tr>
            <tr>
              <td><?php echo fnc_formato_fecha($regfg['pfg8_fe_lib_prod'])?></td>
              <td><?php echo $regfg['pfg8_hr_lib_prod']?></td>

              <td><?php echo fnc_nom_usu($usu_aux)?></td>
            </tr>
            <tr>
              <td colspan="6" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
            </tr>
            <tr>
              <td colspan="6"><?php echo $regProAux['proa_observaciones']?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
           <td style="padding-left:290px">
		   <?php if($_SESSION['privilegio'] == 4){	
		$cad = mysqli_query($cnx, "SELECT pl_id FROM procesos WHERE pro_id = '$idx_pro' ") or die(mysql_error()."Error: en consultar");
		$reg = mysqli_fetch_assoc($cad);
		
		$sqlPal = mysqli_query($cnx, "SELECT p.pp_id FROM procesos_paletos as p INNER JOIN procesos_paletos_d as d ON (p.prop_id = d.prop_id) WHERE d.pro_id = '$idx_pro' and prop_estatus = 1") or die(mysql_error()."Error: en consultar el proceso");
		$regPal = mysqli_fetch_assoc($sqlPal);
		
		if($regPal['pp_id'] == ''){$regPal['pp_id'] = 0;}
		if($idx_prop == ''){$idx_prop = 0;}
		
		if($regPal['pp_id'] > 2 or $regPal['pp_id'] == 0)
		{
		?>
		<!--<button  type="button" class="btn btn-success"><img src="../../iconos/procesos2.png" alt="">Paleto</button>-->
		<button type="button" class="btn btn-success" id="paleto" onClick="javascript:AbreModalPaletoB(<?php echo $idx_pro ?>, <?php echo $reg['pl_id'] ?>, <?php echo $regPal['pp_id'] ?>, <?php echo $idx_prop; ?>);"> 		
			<img src="../iconos/procesos2.png" alt="">Paleto
		</button>
		<?php } }?>
		   </td>
        </tr>
      </table>

<div class="modal" id="modalPaleto8b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

    </div>
