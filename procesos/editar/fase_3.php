<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

require '../../conexion/conexion.php';
require '../funciones_procesos.php';
include ('../../seguridad/user_seguridad.php');
$cnx =  Conectarse();

$reg_pro['pro_id'] = $_POST['pro_id'];

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 5");
$reg_aux = mysqli_fetch_array($cad_aux);

/*$cad_fa1 = mysqli_query($cnx, "SELECT * FROM procesos_fase_3_g WHERE pro_id = '$reg_pro[pro_id]'");
$reg_fa1 = mysqli_fetch_array($cad_fa1);*/

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_3_g WHERE pro_id = '$reg_aux[pro_id]'");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 5");
$reg_lib = mysqli_fetch_array($cad_lib);

/*if($_SESSION['privilegio'] == 3 ){$strProp1 = 'disabled';$strProp6 = '';}else{$strProp1 = '';}//Operador
if($_SESSION['privilegio'] == 4 ){$strProp2 = 'readonly';}else{$strProp2 = '';}//Supervidor
if($_SESSION['privilegio'] == 6 ){$strProp3 = 'readonly';}else{$strProp3 = '';}//Laboratorio

//Para capturar primero los datos generales
if($reg_aux['proa_id'] == ''){$strProp4 = 'readonly';}else{$strProp4 = '';}

//Para bloquear las observaciones si capturo los datos el supervisor.
if($reg_aux['proa_fe_fin'] != ''){$strProp5 = 'readonly';}else{$strProp5 = '';}*/
?>
<script>
/*	a = 3;
	function agregarCampoFase3(){
		a++;
		var div = document.createElement('div');
		div.setAttribute('class', 'form-row');
		//div.setAttribute('style', 'margin-top:-20px');

		div.innerHTML = 
		'<div  class="form-group col-md-1" style="width: 30px"><label style="margin-bottom: 15px">'+a+'o</label></div>'+
		'<div class="form-group col-md-1"  style="width: 130px"><input class="form-control" name="txtLavTipAgua'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1"><input class="form-control" name="txtTemp'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 150px"><input class="form-control" name="txtHraIni'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 160px"><input class="form-control" name="txtHraFin'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 180px"><input class="form-control" name="txtHraIniMov'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 190px"><input class="form-control" name="txtHraFinMov'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-2"><input class="form-control" name="txtPh'+a+'" type="text"/></div>';
		document.getElementById('camposFase3').appendChild(div);document.getElementById('camposFase3').appendChild(div);
	}*/
	
		$(document).ready(function()
	{
		$("#formFase3E").submit(function(){

			var formData = $(this).serialize();
			$.ajax({
				url: "editar/fase_3_actualizar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase3EOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase3E').each (function(){this.reset();});  
					//setTimeout(location.reload(), 23000);
					setTimeout("location.reload()", 2000);	 
				}
			});
			return confirmEnviar5();
			return false;
			
		});
	});

	function AbreModalAgregarR(proceso, etapa, uren){
	var datos={
		"pro_id": proceso,
		"pe_id": etapa,
		"uren": uren
	}
		//alert($("hdd_pro_id").val());
		$.ajax({
			type:'post',
			url: 'modal_renglon.php',
			data: datos,
			//data: {nombre:n},
			success: function(result){
				$("#modalRenglon3").html(result);
				$('#modalRenglon3').modal('show')
			}	
		});
		return false;
	}
	
	function AbreModalPaleto(proceso, lavador){
	var datos={
		"pro_id": proceso,
		"lavador": lavador
	}
		//alert($("hdd_pro_id").val());
		$.ajax({
			type:'post',
			url: 'modal_paleto.php',
			data: datos,
			//data: {nombre:n},
			success: function(result){
				$("#modalPaleto3").html(result);
				$('#modalPaleto3').modal('show')
			}	
		});
		return false;
	}	

//Bloquear boton guardar
		function confirmEnviar5() {

			formFase3E.btn.disabled = true; 
			formFase3E.btn.value = "Enviando...";

			setTimeout(function(){
				formFase3E.btn.disabled = true;
				formFase3E.btn.value = "Guardar";
			},2000);

			var statSend = false;
			return false;
		}
</script>
<div class="modal-dialog modal-lg" role="document" style="width: 1200px">
	<div class="modal-content">
		<div class="divProcesos">
<form id="formFase3E" name="formFase3E">
	<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id"/>
	<input name="hdd_pe_id" type="hidden" value="5" id="hdd_pe_id"/>
	<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>"/>
	<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa1['pfg1_id']; ?>"/>

	<div class="headerdivProcesos">
		<div class="col-md-2" >LAVADOS DE BLANQUEO </div>
		<div class="col-md-5" >Este proceso se puede hacer con aguar recuperada limpia (pila 1)</div>
		<div class="col-md-4" >Lavados finales, 1er ACIDO, PALETO A PALETO </div>
	</div>
	
	<!--tiempos-->
	<div class="row" style="margin-bottom: 10px">
			<label class="col-md-1" style="width: 200px;">Fecha que inicia</label>
			<div class="col-md-2 tiempos">
				<input type="date" class="form-control" id="txtFeIni" name="txtFeIni" placeholder="__________" value="<?php if($reg_aux['proa_fe_ini'] == ''){ echo date("Y-m-d"); $str_prop = '';}else{echo $reg_aux['proa_fe_ini']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; ?>>
			</div>
			
			<label class="col-md-1" style="width: 110px">Hora inicio</label>
			<div class="col-md-2 tiempos">
				<input type="time" class="form-control" id="txtHrIni" name="txtHrIni" placeholder="__________" value="<?php if($reg_aux['proa_hr_ini'] == ''){echo date("H:i"); $str_prop = '';}else{echo $reg_aux['proa_hr_ini']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; ?> >
			</div>
			
			<label class="col-md-1" style="width: 110px">Enzima liquida</label>


			<div class="col-md-2 tiempos">
				<input type="text" class="form-control" id="txtEnzima" name="txtEnzima" required="" placeholder="ENZIMA" value="<?php 
				if($reg_fa['pfg3_enzima'] == ''){
					echo "0"; $str_prop = '';
				}else{
					echo $reg_fa['pfg3_enzima']; $str_prop = '';
				} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; ?> >
			</div>


	
			<div class="col-md-2" style="height: 30px; border-radius: 5px; border:1px solid #e6e6e6; margin-bottom: 3px; font-weight:bold; background-color:#CCCCCC;width: 230px">
						<label>BUSCAR CE A 3.0 MAXIMO</label>
				</div>
		</div>
  <!---->
  
    <div class="row">
	<table border="0" cellspacing="5" cellpadding="5">
  <tr class="etiqueta_tbl">
    <td width="15">&nbsp;</td>
    <td>Lav</td>
    <td>Tipo Agua</td>
    <td>TEMP</td>
    <td>HR ini llenado</td>
    <td>HR term llenado</td>
    <td>HR ini movimiento</td>
    <td>HR term movimiento</td>
    <td>pH</td>
	<td>Ce</td>
	<td>PPM</td>
	<td>Agua a</td>
	<td>Observaciones</td>
  </tr>
  <?php for($i = 1; $i <=10; $i++){ 
  $cad_fad1 = mysqli_query($cnx, "SELECT * FROM procesos_fase_3_d WHERE pro_id = '$reg_pro[pro_id]' and pfd3_ren = '$i' ");
$reg_fad1 = mysqli_fetch_array($cad_fad1);

$fltVal = fnc_parametro_max(5, 'C');

$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 5);
						
if($i <= 3 or $bolVal == 'Si' ){
  ?>
  <tr>
    <td>&nbsp;</td>
  	<td><?php echo $i; ?></td>
    <td><input type="hidden" class="form-control" id="<?php echo "txtRen".$i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen".$i ?>">
							<!--<input type="text" class="form-control" id="<?php /*echo "txtLavTipAgua".$i ?>" name="<?php echo "txtLavTipAgua".$i ?>"  value="<?php if($reg_fad1['pfd3_tipo_ag'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad1['pfd3_tipo_ag']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4*/; ?> size="5">-->
							<select id="cbxTipAg<?php echo $i  ?>" class="form-control" style="width: 140px" name="cbxTipAg<?php echo $i  ?>" <?php if($reg_fad1['tpa_id'] == ''){echo ""; $str_prop = 'disabled';}else{ $str_prop = '';} echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo $strProp4; ?>>
				<option value="">Seleccionar</option>
				<?php 
				$cadena =  mysqli_query($cnx,"SELECT * from tipos_agua");
				$registros =  mysqli_fetch_array($cadena);

				do
				{
					?><option value="<?php echo $registros['tpa_id'] ?>" <?php if($registros['tpa_id'] == $reg_fad1['tpa_id']){ ?>selected="selected"<?php }?>><?php echo $registros['tpa_descripcion'] ?></option><?php
				}while($registros =  mysqli_fetch_array($cadena));

				//mysqli_free_result($registros);

				?>
			</select></td>
    <td><input type="text" class="form-control" id="<?php echo "txtTemp".$i ?>" name="<?php echo "txtTemp".$i ?>" value="<?php if($reg_fad1['pfd3_temp'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad1['pfd3_temp']; $str_prop = '';} ?>" <?php echo $str_prop;  echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> size="5"placeholder="Temp"></td>
    <td><input type="time" class="form-control" id="<?php echo "txtHraIni".$i ?>" name="<?php echo "txtHraIni".$i ?>" value="<?php if($reg_fad1['pfd3_hr_ini'] == ''){echo date("H:i"); $str_prop = 'disabled';}else{echo $reg_fad1['pfd3_hr_ini']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> ></td>
    <td><input type="time" class="form-control" id="<?php echo "txtHraFin".$i ?>" name="<?php echo "txtHraFin".$i ?>" value="<?php if($reg_fad1['pfd3_hr_fin'] == ''){echo date("H:i"); $str_prop = 'disabled';}else{echo $reg_fad1['pfd3_hr_fin']; $str_prop = '';} ?>" <?php echo $str_prop;  echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4;?>></td>
    <td><input type="time" class="form-control" id="<?php echo "txtHraIniMov".$i ?>" name="<?php echo "txtHraIniMov".$i ?>" value="<?php if($reg_fad1['pfd3_hr_ini_mov'] == ''){echo date("H:i"); $str_prop = 'disabled';}else{echo $reg_fad1['pfd3_hr_ini_mov']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?>></td>
    <td><input type="time" class="form-control" id="<?php echo "txtHraFinMov".$i ?>" name="<?php echo "txtHraFinMov".$i ?>" value="<?php if($reg_fad1['pfd3_hr_fin_mov'] == ''){echo date("H:i"); $str_prop = 'disabled';}else{echo $reg_fad1['pfd3_hr_fin_mov']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?>></td>
    <td><input type="text" class="form-control" id="<?php echo "txtPh".$i ?>" name="<?php echo "txtPh".$i ?>" value="<?php if($reg_fad1['pfd3_ph'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad1['pfd3_ph']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?> size="5" placeholder="Ph"></td>
	<td><input type="text" class="form-control" id="<?php echo "txtCe".$i ?>" name="<?php echo "txtCe".$i ?>" value="<?php if($reg_fad1['pfd3_ce'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad1['pfd3_ce']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?> size="5" <?php if($fltVal >= $reg_fad1['pfd3_ce'] and $reg_fad1['pfd3_ce'] != ''){?>style="background-color:#66FF66;"<?php }?> placeholder="Ce"></td>
	<td><input type="text" class="form-control" id="<?php echo "txtPpm".$i ?>" name="<?php echo "txtPpm".$i ?>" value="<?php if($reg_fad1['pfd3_ppm'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad1['pfd3_ppm']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?> size="3" placeholder="PPM"></td>
	<td>
		<select id="cbxAgua<?php echo $i  ?>" class="form-control" style="width: 140px" name="cbxAgua<?php echo $i  ?>" <?php if($reg_fad1['taa_id'] == ''){echo ""; $str_prop = 'disabled';}else{ $str_prop = '';} echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?>>


												<option value="">Seleccionar</option>
												<?php 
												$cadena =  mysqli_query($cnx,"SELECT * from tipos_agua_a");
												$registros =  mysqli_fetch_array($cadena);

												do
												{
													?><option value="<?php echo $registros['taa_id'] ?>" <?php if($registros['taa_id'] == $reg_fad1['taa_id']){ ?>selected="selected"<?php }?>><?php echo $registros['taa_descripcion'] ?></option><?php
												}while($registros =  mysqli_fetch_array($cadena));

				//mysqli_free_result($registros);

												?>
											</select>
	</td>
	<td><input type="text" class="form-control" id="<?php echo "txtObs".$i ?>" name="<?php echo "txtObs".$i ?>" value="<?php if($reg_fad1['pfd3_observaciones'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad1['pfd3_observaciones']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?> size="5" placeholder="Observaciones"></td>
  </tr>
  <?php 
  $valUltRen = $i;
  }//termina if
							
	}// termina for?>
</table>

	</div>

	<?php /*<div class="form-row">
		<div class="form-group col-md-1" style="width: 30px">
			<label for="inputPassword3"></label>
			<label for="inputPassword3" style="margin-bottom: 15px">1er</label>
			<label for="inputPassword3" style="margin-bottom: 15px">2do</label>
			<label for="inputPassword3" style="margin-bottom: 15px">3er</label>
		</div>
	</div>
	
	<div class="form-row">
		<div class="form-group col-md-1" style="width: 130px">
			<label for="inputPassword3">Lav Tipo Agua</label>
			<!--<label class="col-md-1"  style="width: 50px">1er</label>-->
			<input type="text" class="form-control" id="inputPassword3" placeholder="">
			<input type="text" class="form-control" id="inputPassword3" placeholder="">
			<input type="text" class="form-control" id="inputPassword3" placeholder="">
		</div>
	</div>
	
	<div class="form-row">
		<div class="form-group col-md-1">
			<label for="inputPassword4">TEMP</label>
			<!--<label class="col-md-1"  style="width: 50px">1er</label>-->
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
		</div>
	</div>
	
	<div class="form-row">
		<div class="form-group col-md-1" style="width: 150px">
			<label for="inputPassword4">HR inicia llenado </label>
			<!--<label class="col-md-1" style="width: 150px"  style="width: 50px">1er</label>-->
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
		</div>
	</div>
	
	<div class="form-row">
		<div class="form-group col-md-1" style="width: 160px">
			<label for="inputPassword4">HR termina llenado </label>
			<!--<label class="col-md-1" style="width: 160px"  style="width: 50px">1er</label>-->
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
		</div>
	</div>
	
	<div class="form-row">
		<div class="form-group col-md-1" style="width: 180px">
			<label for="inputPassword4">HR inicia movimiento </label>
			<!--<label class="col-md-1" style="width: 180px"  style="width: 50px">1er</label>-->
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
		</div>
	</div>
	
	<div class="form-row">
		<div class="form-group col-md-1" style="width: 190px">
			<label for="inputPassword4">HR termina movimiento </label>
			<!--<label class="col-md-1"  style="width: 50px">1er</label>-->
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
		</div>
	</div>
	
	<div class="form-row">
		<div class="form-group col-md-2">
			<label for="inputPassword4">pH</label>
			<!--<label class="col-md-1"  style="width: 50px">1er</label>-->
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
			<input type="text" class="form-control" id="inputPassword4" placeholder="">
		</div>
	</div>*/?>
	

	<div class="row" id="camposFase3">

	</div>
		 <!--estilo general de estapas-->
		<div class="row">
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Fecha termina</label>
					<input type="date" class="form-control" id="txtFeTerm" placeholder="" name="txtFeTerm" value="<?php if($reg_aux['proa_fe_fin'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_aux['proa_fe_fin']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3." ".$strProp4; ?>>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Hora termina</label>
					<input type="time" class="form-control" id="txtHrTerm" placeholder="" name="txtHrTerm" value="<?php if($reg_aux['proa_hr_fin'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_aux['proa_hr_fin']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3." ".$strProp4; ?>>
				</div>
			</div>
			<!--<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Realizó</label>
					<input type="text" class="form-control" id="inputPassword4" placeholder="">
				</div>
			</div>-->
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">(<?php echo fnc_hora_de(5) ?> a <?php echo fnc_hora_a(5) ?> Horas)</label>
				</div>
			</div>
			<div class="col-md-3 divEtapas3">
				<div class="col-md-1 etapa3">
					<p class="numEtapa">3</p>
				</div>
				<div class="col-md-2 divEtapasInput">
					<label class="etiquetaEtapa">LIBERACION CE A <?php echo fnc_rango_a(5) ?> MAX</label>
					<input  type="text" id="txtCeLib" class="form-control" placeholder="Ce liberacion" name="txtCeLib" value="<?php if($reg_lib['prol_ce'] == ''){ echo ""; $str_prop = 'disabled';}else{echo $reg_lib['prol_ce']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
					<!--<select  type="text" id="cbxColor" class="form-control" placeholder="Colores" name="cbxColor" <?php /*if($reg_lib['prol_color'] == ''){ echo ""; $str_prop = '';}else{echo "<option value='$reg_lib[prol_color]'>$reg_lib[prol_color]</option>"; $str_prop = 'disabled';} ?> <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;*/?>>
						<option value="">Color</option>
						<option value="1-A">1-A</option>
						<option value="1-B">1-B</option>
						<option value="1-C">1-C</option>
						<option value="1-D">1-D</option>
						<option value="2-A">2-A</option>
						<option value="2-B">2-B</option>
						<option value="2-C">2-C</option>
						<option value="2-D">2-D</option>
						<option value="3-A">3-A</option>
						<option value="3-B">3-B</option>
						<option value="3-C">3-C</option>
						<option value="3-D">3-D</option>
						<option value="4-A">4-A</option>
						<option value="4-B">4-B</option>
						<option value="4-C">4-C</option>
						<option value="4-D">4-D</option>
					</select>-->
					<input  type="text" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if($reg_lib['prol_hr_totales'] == ''){ echo ""; $str_prop = 'disabled';}else{echo $reg_lib['prol_hr_totales']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
					<input  type="text" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
					<!--<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
				</div>	
			</div>
		</div>
		<!---->

		<!--textareaobservaciones-->
		<div class="row">
			<div class="col-md-7 textareaObservaciones">
				<label for="inputPassword4">Observaciones</label>
				<!--<label class="col-md-1"  style="width: 50px">1er</label>-->
				<textarea type="textarea" class="form-control" id="" placeholder="Observaciones..." name="txaObservaciones" value="<?php if($reg_aux['proa_observaciones'] == ''){$str_prop = 'disabled';}else{$str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3; echo " ".$strProp5." ".$strProp4;?>><?php echo $reg_aux['proa_observaciones']; ?></textarea>
			</div>
		</div>
		<!---->

		<div class="row" style="margin-bottom: 10px">
			<label style="font-weight:bold; margin-left:20px;">El agua de este proceso se manda a PILA 2 recuperada semilimpia</label>
		</div>

	 <!--barra botones-->
		<div class="footerdivProcesos row" style="margin-bottom: 10px">
		
			<div class="form-group col-md-7">
				<div class="alert alert-info hide" id="alerta-errorFase3EOpe" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px;margin-bottom: -10px">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>

			<?php /*if($_SESSION['privilegio'] == 4){	?>

				<div class="col-md-1">
					<!--Nota: si no ocupa es-->
					<button type="button" class="btn btn-success" id="paleto" onClick="javascript:AbreModalPaleto(<?php echo $reg_pro['pro_id'] ?>, <?php echo $id_l ?>);"> <img src="../iconos/procesos2.png" alt="">Paleto
					</button>
				</div>
				<div class="col-md-1">
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE(<?php echo $reg_pro['pro_id'] ?>, 5);"> <img src="../iconos/edit.png" alt="">Editar
					</button>
				</div>
				<div class="col-md-2" style="width: 110px">
					<button type="button" class="btn btn-success" id="permitir" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 5, <?php echo $valUltRen ?>);"> <img src="../iconos/add.png" alt="">Renglon
					</button>
				</div>
			<?php }*/ ?>
			<div class="col-md-1" style="float: right;margin-right: 80px">
				<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>	
			</div>
			<div class="col-md-1" style="float: right;margin-right: 30px">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();"><img src="../iconos/close.png" alt="">Cerrar</button>	
			</div>
		</div>
		
</form>
			<div class="modal" id="modalRenglon3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
			
			<div class="modal" id="modalPaleto3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

		</div>
	</div>
</div>