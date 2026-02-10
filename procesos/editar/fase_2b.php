<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*20 - Abril - 2019*/
require '../../conexion/conexion.php';
require '../funciones_procesos.php';
include ('../../seguridad/user_seguridad.php');
$cnx =  Conectarse();

$reg_pro['pro_id'] = $_POST['pro_id'];

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 3");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_g WHERE pro_id = '$reg_pro[pro_id]'");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 3");
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
	a = 6;
	function agregarCampo(){
		a++;
		var div = document.createElement('div');
		div.setAttribute('class', 'form-row');
		div.setAttribute('style', 'margin-top:-20px');

		div.innerHTML = 
		'<div  class="form-group col-md-1" style="width: 30px"><label>'+a+'o</label></div>'+
		'<div class="form-group col-md-1" style="width: 130px"><input class="form-control" name="txtLavTipAgua'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1"><input class="form-control" name="txtTemp'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 150px"><input class="form-control" name="txtHraIni'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 160px"><input class="form-control" name="txtHraFin'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 180px"><input class="form-control" name="txtHraIniMov'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 190px"><input class="form-control" name="txtHraFinMov'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1"><input class="form-control" name="txtPh'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1"><input class="form-control" name="txtCe'+a+'" type="text"/></div>';
		document.getElementById('campos').appendChild(div);document.getElementById('campos').appendChild(div);
	}
	
	$(document).ready(function()
	{
		$("#formFase2bE").submit(function(){

			var formData = $(this).serialize();
			$.ajax({
				url: "editar/fase_2b_actualizar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase2bEOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase2bE').each (function(){this.reset();});  
					//setTimeout(location.reload(), 23000);
					setTimeout("location.reload()", 2000);	 
				}
			});
			return confirmEnviar5();
			return false;
			
		});
	});
	
	function AbreModalE2b(proceso, etapa){
	var datos={
		"pro_id": proceso,
		"pe_id": etapa
	}
		$.ajax({
			type:'post',
			url: 'editar/fase_2b.php',
			data: datos,
			success: function(result){
				$("#modalEditar2b").html(result);
				$('#modalEditar2b').modal('show')
			}	
		});
		return false;
	}

	//Bloquear boton guardar
		function confirmEnviar5() {

			formFase2bE.btn.disabled = true; 
			formFase2bE.btn.value = "Enviando...";

			setTimeout(function(){
				formFase2bE.btn.disabled = true;
				formFase2bE.btn.value = "Guardar";
			},2000);

			var statSend = false;
			return false;
		}
</script>
<div class="modal-dialog modal-lg" role="document" style="width: 1100px">
	<div class="modal-content">
		<div class="divProcesos1">
<form id="formFase2bE" name="formFase2bE">
	<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id"/>
	<input name="hdd_pe_id" type="hidden" value="3" id="hdd_pe_id"/>
	<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>"/>
	<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg2_id']; ?>"/>

		<div class="headerdivProcesos">
			<div class="col-md-2" >ENZIMA</div>
			<div class="col-md-4" >Este proceso es de 10 horas en movimiento continuo</div>
			<div class="col-md-5" ></div>
		</div>

		<!--tiempos-->
		<div class="row" style="margin-bottom: 20px">
			<label class="col-md-1">Enzima</label>
			<div class="col-md-1 tiempos">
				<input type="text" class="form-control" id="txtEnzima" placeholder="Kg" name="txtEnzima" value="<?php if($reg_fa['pfg2_enzima'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fa['pfg2_enzima']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; ?> required>
			</div>
			<label class="col-md-1">Kg</label>

			<label class="col-md-2" style="width: 120px">Fecha inicio</label>
			<div class="col-md-2 tiempos">
				<input type="date" class="form-control" id="txtFeIni" placeholder="" name="txtFeIni" value="<?php if($reg_aux['proa_fe_ini'] == ''){ echo date("Y-m-d"); $str_prop = 'disabled';}else{echo $reg_aux['proa_fe_ini']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 110px">Hora inicio</label>
			<div class="col-md-2 tiempos" style="width: 140px">
				<input type="time" class="form-control" id="txtHrIni" placeholder="" name="txtHrIni" value="<?php if($reg_aux['proa_hr_ini'] == ''){echo date("H:i"); $str_prop = 'disabled';}else{echo $reg_aux['proa_hr_ini']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 165px">Temp agua utilizada</label>
			<div class="col-md-1 tiempos">
				<input type="text" class="form-control" id="txtTemp" placeholder="" name="txtTemp" value="<?php if($reg_fa['pfg2_temp_ag'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fa['pfg2_temp_ag']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; ?> required>
			</div>
		</div>
		<!---->

		<div class="row">
			<div class="col-md-5">
				<table border="0" cellspacing="5" cellpadding="5">
					<tr class="etiqueta_tbl">
						<td>No</td>
						<td>Hora</td>
						<td>Ph</td>
						<td>Sosa</td>
						<td>Acido</td>
					</tr>
					<?php for($i = 1; $i <=6; $i++){ 
						if($i > 2){$i+=1;}

						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_d WHERE pfg2_id = '$reg_fa[pfg2_id]' and pfd2_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);
						?>
						<tr>
							<td><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen".$i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen".$i ?>"></td>
							<td><input type="time" class="form-control" id="<?php echo "txtHoraD".$i ?>" name="<?php echo "txtHoraD".$i ?>" value="<?php if($reg_fad['pfd2_hr'] == ''){echo date("H:i"); $str_prop = 'disabled';}else{echo $reg_fad['pfd2_hr']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?>></td>
							<td><input style="width: 70px" type="text" class="form-control" id="<?php echo "txtPhD".$i ?>" name="<?php echo "txtPhD".$i ?>" value="<?php if($reg_fad['pfd2_ph'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad['pfd2_ph']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> placeholder="pH"></td>
							<td><input type="text" class="form-control" id="<?php echo "txtSosaD".$i ?>" name="<?php echo "txtSosaD".$i ?>" value="<?php if($reg_fad['pfd2_sosa'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad['pfd2_sosa']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> size="5" placeholder="Sosa"></td>
							<td><input type="text" class="form-control" id="txtAcidoD<?php echo $i; ?>"  name="txtAcidoD<?php echo $i; ?>" size="5" value="<?php if($reg_fad['pfd2_acido'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad['pfd2_acido']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> placeholder="Acido"></td>
							<td>Lts</td>
						</tr>
					<?php }?>
				</table>
			</div>

			
			<div class="col-md-7">
				<div  style="height: 30px; border-radius: 5px; border:1px solid #e6e6e6; margin-bottom: 3px; font-weight:bold; background-color:#CCCCCC;width: 320px;text-align: center;width: 610px">
					<label>CP CHEQUEOS DE PH 10.0 - 10.8</label>
				</div>
				<div class="form-group col-md-1" style="width: 110px">
					<label for="inputPassword3" style="margin-bottom: 10px">Ph solucion</label>
					<label for="inputPassword3" style="margin-bottom: 10px">Ph solucion</label>
				</div>
				<div class="form-group col-md-2">	
					<input type="text" class="form-control" id="txtPhS1" name="txtPhS1" value="<?php if($reg_fa['pfg2_ph1'] == ''){ echo ""; $str_prop = 'disabled';}else{echo $reg_fa['pfg2_ph1']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
					<input type="text" class="form-control" id="txtPhS2" name="txtPhS2" value="<?php if($reg_fa['pfg2_ph2'] == ''){ echo ""; $str_prop = 'disabled';}else{echo $reg_fa['pfg2_ph2']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
				</div>
				<div class="form-group col-md-1" style="width: 30px">
					<label for="inputPassword3" style="margin-bottom: 6px">Hrs</label>
					<label for="inputPassword3" style="margin-bottom: 10px">Hrs</label>
				</div>
				<div class="form-group col-md-2">
					<input type="text" class="form-control" style="margin-bottom: 5px" id="txtHoraS1" name="txtHoraS1" value="<?php if($reg_fa['pfg2_hr1'] == ''){ echo ""; $str_prop = 'disabled';}else{echo $reg_fa['pfg2_hr1']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
					<input type="text" class="form-control" style="margin-bottom: 10px" id="txtHoraS2" name="txtHoraS2" value="<?php if($reg_fa['pfg2_hr2'] == ''){ echo ""; $str_prop = 'disabled';}else{echo $reg_fa['pfg2_hr2']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
				</div>
                <div class="form-group col-md-2" style="width: 120px">
					<label for="inputPassword3" style="margin-bottom: 10px">Nombre LCP</label>
					<label for="inputPassword3" style="margin-bottom: 10px">Nombre LCP</label>
				</div>
				<div class="form-group col-md-3">	
					<input  type="text" id="txtUsu" class="form-control" placeholder="Nombre LCP"  value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
					<input  type="text" id="txtUsu" class="form-control" placeholder="Nombre LCP"  value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">

				</div>
			</div>
			
		</div>
		<div class="col-md-5" style="margin-top: 20px;">
				Este proceso es de 15 a 30 minutos  de movimiento por cada 2 o 3 horas de reposo
			</div>
			<div class="col-md-7" style="margin-top: 20px;margin-bottom: 20px;text-align: justify;">
				Nota: Seguir anotando los chequeos después de que cumpla su tiempo (32-36) o cuando se inicia abara normalidad.
			</div>

<!--	<div class="row">
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 80px">
				<label for="inputPassword3" style="margin-bottom: 10px">1 Hora</label>
				<label for="inputPassword3" style="margin-bottom: 10px">2 Hora</label>
				<label for="inputPassword3" style="margin-bottom: 10px">4 Hora</label>
				<label for="inputPassword3" style="margin-bottom: 10px">6 Hora</label>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 130px">
				<?php for($i = 1; $i <=4; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtHora".$i ?>">
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 30px">
				<label for="inputPassword3" style="margin-bottom: 10px">Ph</label>
				<label for="inputPassword3" style="margin-bottom: 10px">Ph</label>
				<label for="inputPassword3" style="margin-bottom: 10px">Ph</label>
				<label for="inputPassword3" style="margin-bottom: 10px">Ph</label>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 130px">
				<?php for($i = 1; $i <=4; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtph".$i ?>">
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 50px">
				<label for="inputPassword3" style="margin-bottom: 10px">Sosa</label>
				<label for="inputPassword3" style="margin-bottom: 10px">Sosa</label>
				<label for="inputPassword3" style="margin-bottom: 10px">Sosa</label>
				<label for="inputPassword3" style="margin-bottom: 10px">Sosa</label>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 130px">
				<?php for($i = 1; $i <=4; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtSosa".$i ?>">
				<?php }?>
			</div>
		</div>
		<div class="form-group col-md-1" style="width: 50px">
			<label for="inputPassword3" style="margin-bottom: 10px">Lts</label>
			<label for="inputPassword3" style="margin-bottom: 10px">Lts</label>
			<label for="inputPassword3" style="margin-bottom: 10px">Lts</label>
			<label for="inputPassword3" style="margin-bottom: 10px">Lts</label>
		</div>
		<div class="form-row">
			<div class="form-group col-md-2" style="width: 170px">
				<label for="inputPassword3" style="margin-bottom: 10px">Realizo este proceso</label>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-3">
				<select type="text" class="form-control" id="txtRealizo"> 
					<option value=""></option>
				</select>
			</div>
		</div>
	</div>-->

	<div class="row">
		<div class="col-md-6">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>No</td>
					<td>Hora</td>
					<td>Min. Mon</td>
					<td>Reposo</td>
					<td>Ph</td>
					<td>Temp</td>
					<td>Sosa</td>
					<td>Acido</td>
				</tr>
				<?php for($i = 7; $i <=44; $i +=4){ 
					if($i == 31){$i += 1;}

					if($i == 40){$i = 38;}

					if($i == 42){$i = 40;}

					if($i == 44){$i = 42;}

					$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_d2 WHERE pfg2_id = '$reg_fa[pfg2_id]' and pfd22_ren = '$i' ");
					$reg_fad = mysqli_fetch_array($cad_fad);
					?>
					<tr>
						<td><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen2".$i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen2".$i ?>"></td>
						<td><input type="time" class="form-control" id="<?php echo "txtHora2".$i ?>" name="<?php echo "txtHora2".$i ?>" value="<?php if($reg_fad['pfd22_hr'] == ''){echo date("H:i"); $str_prop = 'disabled';}else{echo $reg_fad['pfd22_hr']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?>></td>
						<td><input type="text" class="form-control" id="<?php echo "txtMinMov".$i ?>" name="<?php echo "txtMinMov".$i ?>" size="7" value="<?php if($reg_fad['pfd22_min'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad['pfd22_min']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> placeholder="Mov"></td>
						<td><input type="text" class="form-control" id="<?php echo "txtReposo".$i ?>" name="<?php echo "txtReposo".$i ?>" size="7" value="<?php if($reg_fad['pfd22_reposo'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad['pfd22_reposo']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> placeholder="Reposo"></td>
						<td><input type="text" class="form-control" id="<?php echo "txtPh2".$i ?>" name="<?php echo "txtPh2".$i ?>" size="7" value="<?php if($reg_fad['pfd22_ph'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad['pfd22_ph']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> placeholder="pH"></td>
						<td><input type="text" class="form-control" id="<?php echo "txtTemp2".$i ?>" name="<?php echo "txtTemp2".$i ?>" size="7" value="<?php if($reg_fad['pfd22_temp'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad['pfd22_temp']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> placeholder="Temp"></td>
						<td><input type="text" class="form-control" id="<?php echo "txtSosaT".$i ?>" name="<?php echo "txtSosaT".$i ?>" value="<?php if($reg_fad['pfd22_sosa'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad['pfd22_sosa']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> size="5" placeholder="Sosa"></td>
							<td><input type="text" class="form-control" id="txtAcidoT<?php echo $i; ?>" name="txtAcidoT<?php echo $i; ?>" size="5" value="<?php if($reg_fad['pfd22_acido'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fad['pfd22_acido']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> placeholder="Acido"></td>
							<td>Lts</td>
					</tr>
				<?php  }?>
			</table>
		</div>
		<div class="col-md-3 divEtapas" style="height: 112px">
			<div class="col-md-1 etapa" style="height: 112px">
				<p class="numEtapa">2b</p>
			</div>
			<div class="col-md-2 divEtapasInput">
				<label class="etiquetaEtapa">LIBERACION <?php echo fnc_rango_de(3) ?> - <?php echo fnc_rango_a(3) ?> Horas</label>
				<input  type="text" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if($reg_lib['prol_hr_totales'] == ''){ echo ""; $str_prop = 'disabled';}else{echo $reg_lib['prol_hr_totales']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
				<input  type="text" id="inputPassword" class="form-control" placeholder="Nombre LCP"  value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
				<!--<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
			</div>	
		</div>
	</div>

	<div class="row">
<!--		<div class="form-row">
			<div class="form-group col-md-1" style="width:90px ">
				<label for="inputPassword3" style="color: #fff">...</label>
				<label for="inputPassword3" style="margin-bottom: 10px">7 Horas</label>
				<label for="inputPassword3" style="margin-bottom: 10px">11 Horas</label>
				<label for="inputPassword3" style="margin-bottom: 10px">15 Horas</label>
				<label for="inputPassword3" style="margin-bottom: 10px">19 Horas</label>
				<label for="inputPassword3" style="margin-bottom: 10px">23 Horas</label>
				<label for="inputPassword3" style="margin-bottom: 10px">27 Horas</label>
				<label for="inputPassword3" style="margin-bottom: 10px">32 Horas</label>
				<label for="inputPassword3" style="margin-bottom: 10px">36 Horas</label>
				<label for="inputPassword3" style="margin-bottom: 10px">38 Horas</label>
				<label for="inputPassword3" style="margin-bottom: 10px">40 Horas</label>
				<label for="inputPassword3" style="margin-bottom: 10px">42 Horas</label>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1">
				<label for="inputPassword3" style="color: #fff">...</label>
				<?php for($i = 1; $i <=11; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtHora".$i ?>">
				<?php }?>
			</div>
		</div>
		
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 100px">
				<label for="inputPassword3">Hr</label>
				<?php for($i = 1; $i <=11; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtHr".$i ?>">
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 130px">
				<label for="inputPassword3">Min. Mon</label>
				<?php for($i = 1; $i <=11; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtMinMov".$i ?>">
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 130px">
				<label for="inputPassword3">Reposo</label>
				<?php for($i = 1; $i <=11; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtReposo".$i ?>">
				<?php }?>
			</div>
		</div>
		
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 100px">
				<label for="inputPassword3">Ph</label>
				<?php for($i = 1; $i <=11; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtPh".$i ?>">
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 100px">
				<label for="inputPassword3">Temp</label>
				<?php for($i = 1; $i <=11; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtTemp".$i ?>">
				<?php }?>
			</div>
		</div>-->

	</div>

	<div class="row" id="campos"></div>


	<!--estilo general de estapas-->
	<div class="row">
		<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Fecha termina enzima</label>
				<input type="date" class="form-control" id="txtFeTerm" placeholder="" name="txtFeTerm" value="<?php if($reg_aux['proa_fe_fin'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_aux['proa_fe_fin']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3." ".$strProp4; ?>>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Hora termina enzima</label>
				<input type="time" class="form-control" id="txtHrTerm" placeholder="" name="txtHrTerm" value="<?php if($reg_aux['proa_hr_fin'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_aux['proa_hr_fin']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3." ".$strProp4; ?>>
			</div>
		</div>
		<!--<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Realizo proceso</label>
				<select type="text" class="form-control" id="inputPassword4" placeholder=""><option value=""></option></select> 
			</div>
		</div>-->

		<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Hrs totales del proceso</label>
				<input type="text" class="form-control" id="txtHrasTot" name="txtHrasTot"  placeholder="" value="<?php if($reg_fa['pfg2_hr_totales'] == ''){echo ""; $str_prop = 'disabled';}else{echo $reg_fa['pfg2_hr_totales']; $str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3." ".$strProp4; ?>>
			</div>
		</div>
		<!--<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Revisó</label>
				<select type="text" class="form-control" id="inputPassword4" placeholder=""><option value=""></option></select>
			</div>
		</div>-->
		<div class="form-group col-md-1">
			<label for="inputPassword4">(<?php echo fnc_hora_de(3) ?> a <?php echo fnc_hora_a(3) ?> Horas)</label>
			<label for="inputPassword4">42 a 53 Horas)</label>
		</div>


		<!--textareaobservaciones-->

		<div class="col-md-5">
			<label for="inputPassword4">Observaciones</label>
			<!--<label class="col-md-1"  style="width: 50px">1er</label>-->
			<textarea type="textarea" class="form-control" id="" placeholder="Observaciones..." name="txaObservaciones" value="<?php if($reg_aux['proa_observaciones'] == ''){$str_prop = 'disabled';}else{$str_prop = '';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3; echo " ".$strProp5." ".$strProp4; ?>><?php echo $reg_aux['proa_observaciones']; ?></textarea>
		</div>
	</div>

<!--barra botones-->
	<div class="row footerdivProcesos" style="margin-bottom: 10px">	

		<div class="col-md-5">
			El agua de este proceso se manda a agua recuperada semilimpia (Pila 2)
		</div>

		<div class="form-group col-md-4">
			<div class="alert alert-info hide" id="alerta-errorFase2bEOpe" style="height: 40px;width: 300px;text-align: left;z-index: 10;font-size: 10px;">
				<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
				<strong>Titulo</strong> &nbsp;&nbsp;
				<span> Mensaje </span>
			</div>
		</div>	

		<?php /*if($_SESSION['privilegio'] == 4){	?>
		<div class="col-md-1">
			<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE2b(<?php echo $reg_pro['pro_id'] ?>, 3);"> 
				<img src="../iconos/edit.png" alt="">Editar
			</button>
		</div>
		<?php }*/?>
		<div class="col-md-1">
			<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();"><img src="../iconos/close.png" alt="">Cerrar</button>	
		</div>
		<div class="col-md-1" >
				<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>	
		</div>
		
	</div>

</form>

<!--<div class="modal" id="modalEditar2b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>-->

		</div>
	</div>
</div>