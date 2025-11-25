<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
?>
<script src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/alerta.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script>

	function obtenerInv(param){
		var datos={

			"mat_id": $("#cbxMaterial"+param).val(),

			"inv_id1": $("#cbxKilosID1").val(),
			"inv_id2": $("#cbxKilosID2").val(),
			"inv_id3": $("#cbxKilosID3").val(),
			"inv_id4": $("#cbxKilosID4").val(),
			"inv_id5": $("#cbxKilosID5").val(),
			"inv_id6": $("#cbxKilosID6").val(),
			"inv_id7": $("#cbxKilosID7").val(),
			"inv_id8": $("#cbxKilosID8").val(),
			"inv_id9": $("#cbxKilosID9").val(),
			"inv_id10": $("#cbxKilosID10").val(),
			"inv_id11": $("#cbxKilosID11").val(),
			"inv_id12": $("#cbxKilosID12").val(),
		}
		$.ajax({
			type:'post',
			url: 'getInvTot.php',
			data: datos,
			//data: {nombre:n},
			success: function(d){
				$("#cbxKilosID"+param).html(d);
				document.getElementById('txtFecha'+param).value = '';
				document.getElementById('txtFecha_ent_maquila'+param).value = '';
			  //alert(d);
			}	
		});
		return false;
	}

//PONE EL CONTADOR A 0
var count_click = 0;

//AÑADE UN CLICK AL EJECUTAR LA FUNCIÓN
function count_click_add() {
	count_click += 1;
}
$( document ).ready(function(){
	$("button[name='count_click']").click(function(){
		count_click_add();
		if (count_click == 7) {
			formEncabezado1.count_click.disabled = true; 
		}
	});
});
function obtenerFecha(param){
	var datos={
		
		"inv_id": $("#cbxKilosID"+param).val(),
	}
	$.ajax({
		type:'post',
		url: 'getFecha.php',
		data: datos,
			//data: {nombre:n},
			success: function(d){
				$("#txtFecha"+param).val(d);
			  //alert(d);
			}	
		});
	return false;
}
function obtenerFecha_entrada_maquila(param){
	var datos={
		
		"inv_id": $("#cbxKilosID"+param).val(),
	}
	$.ajax({
		type:'post',
		url: 'getFecha_maquila.php',
		data: datos,
			//data: {nombre:n},
			success: function(d){
				$("#txtFecha_ent_maquila"+param).val(d);
			  //alert(d);
			}	
		});
	return false;
}



function sumCantidad(param) 
{
	var cantTotal = document.getElementById('txtTotKilos').value = 0;
	for (i = 1; i <= param ; i++) 
	{ 
		var combo = document.getElementById('cbxKilosID'+i);
		var val = combo.options[combo.selectedIndex].text;
		document.getElementById('txtKilos'+i).value = val;

		if (val == 'Seleccionar') 
		{
			var val = 0;
		}

        		//alert(val);
        		
        		cantTotal = parseFloat(cantTotal) + parseFloat(val);
        	}

        	var x = document.getElementById('txtTotKilos').value = parseFloat(cantTotal);


        }



        function sumCantidadD(param) 
        {
        	var cantTotal = document.getElementById('txtTotKilos').value = 0;
        	for (i = 1; i <= param ; i++) 
        	{ 
        		var combo = document.getElementById('cbxKilosID'+i);
        		var val = combo.options[combo.selectedIndex].text;

        		var kilos = val.split(' ');
        		//document.getElementById('txtKilos'+i).value = val;
        		document.getElementById('txtKilos'+i).value = kilos[0];
        		
        		if (val == 'Seleccionar') 
        		{
        			var val = 0;
        		}
        		
        		//alert(val);
        		
        		cantTotal = parseFloat(cantTotal) + parseFloat(val);
        	}

        	var x = document.getElementById('txtTotKilos').value = parseFloat(cantTotal);


        }

        $(document).ready(function()
        {
        	$("#formEncabezado1").submit(function(){
	/*if (document.formEncabezado1.cbxMaterial1.value == "") 
	{
		alert("Seleccona al menos un material");
	}
	else{*/

		var material = document.getElementById('cbxMaterial1').value;
		var kilos = document.getElementById('cbxKilosID1').value;
		if(material == 0 || kilos == 0){
			alert('Seleccione al menos un material y kilos a procesar');
			document.getElementById("cbxMaterial1").focus();
			return false;
		}else{

			var formData = $(this).serialize();
			$.ajax({
				url: "encabezado_1_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorEncabezado1", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formEncabezado1').each (function(){this.reset();}); 
					setTimeout("location.reload()", 2000); 
				}
			});
			return confirmEnviar();
			return false;
		//}
	}
});
        });


        function AbreModalEditar(param){
	//alert('aqui');
	var datos={
		"inv_id": $("#cbxKilosID"+param).val(), 
		"param": param,
	}
		//alert($("#cbxKilosID"+param).val());
		$.ajax({
			type:'post',
			url: 'modal_dividir.php',
			data: datos,
			//data: {nombre:n},
			success: function(result){
				$("#modalDividir").html(result);
				$('#modalDividir').modal('show')
			}	
		});
		return false;
	}

	function kilos(par)
	{
		var datos={ 
	      // "hdd_id": $("#cbxKilosID1").val(),
	  }

	  //var par = $("#cbxKilosID1").val()
	  //alert(par);
	  $.ajax({
	  	type:'post',
	  	url: "getKilos.php",
	  	data: datos,
        //data: {nombre:n},
        success: function(d){
        	$("#cbxKilosID" + par).html(d); 
        	sumCantidad(par);

        } 
    });      
	}


	function refresh()
	{
		location.reload();
	}


	function confirmEnviar() {
		setTimeout("location.reload()", 1000); 
		formEncabezado1.btnEnviar.disabled = true; 
		formEncabezado1.btnEnviar.value = "Enviando...";

		setTimeout(function(){
			formEncabezado1.btnEnviar.disabled = true;
			formEncabezado1.btnEnviar.value = "Guardar";
		},2000);

		var statSend = false;
		return false;
	}

		//agregado por CC 26-01-202
		a = 5;

		function agregarRenglon(){
			a++;
			var div = document.createElement('div');
			div.setAttribute('class', 'row col-md-12');
			div.setAttribute('style', 'margin-top:-15px;margin-left:-15px');

			div.innerHTML = 
			'<div class="col-sm-3 col-md-2">'+
			'<select class="form-control" id="cbxMaterial'+a+'" name="cbxMaterial'+a+'" style="width:155px;" onchange="obtenerInv('+a+')">'+
			'<option value="">Seleccionar</option>'+
			<?php
			$query =  $cadena_mat =  mysqli_query($cnx,"SELECT * from materiales WHERE mat_est = 'A' ") ;
			while($registros = mysqli_fetch_array($query)) { ?>
				'<option value="<?php echo $registros['mat_id']?>" > <?php echo $registros['mat_nombre']?> </option>'+
			<?php } 
			?>
			'</select></div>'+

			'<div class="col-sm-3 col-md-2" style="width:140px;margin-left:5px">'+
			'<select class="form-control" id="cbxKilosID'+a+'" name="cbxKilosID'+a+'" onchange="sumCantidadD('+a+');obtenerFecha('+a+');obtenerFecha_entrada_maquila('+a+')">'+
			'<option value="">Seleccionar</option>'+
			'</select>'+
			'<input type="hidden" id="txtKilos'+a+'" name="txtKilos'+a+'">'+
			'</div>'+



			'<div class="form-group col-md-1" style="width: 70px">'+
			'<a id="btn'+a+'" href="#" onClick="javascript:AbreModalEditar('+a+')"><img src="../iconos/division.png" style="padding-bottom: 5px"></a>'+
			'</div>'+


			'<div class="form-group col-md-1" style="width: 130px">'+
			'<input type="text" class="form-control" id="txtFecha'+a+'" style="width: 100px" name="txtFecha'+a+'"  readonly="true" size="10">'+
			'</div>'+

			'<div class="form-group col-md-1" style="width: 130px">'+
			'<input type="text" class="form-control" id="txtFecha_ent_maquila'+a+'" style="width: 100px" name="txtFecha_ent_maquila'+a+'"  readonly="true" size="10">'+
			'</div>';

			document.getElementById('campos').appendChild(div);document.getElementById('campos').appendChild(div);
		}

	</script>

	<form id="formEncabezado1">
		<!--supervisor-->
		<input name="hdd_user" type="hidden" value="<?php echo $_SESSION['idUsu']; ?>" id="hdd_user"/>
		<div class="form-row">
			<div class="form-group col-md-4">
				<label for="inputState">Tipo Preparacion</label>
				<select id="cbxProceso" class="form-control"  name="cbxProceso" required>
					<option value="">Seleccionar</option>
					<?php 
					$html = '';

					$cadena =  mysqli_query($cnx,"SELECT * from preparacion_tipo WHERE pt_estatus = 'A' AND pt_para = 'L' ORDER BY pt_descripcion");
					$registros =  mysqli_fetch_array($cadena);

					do
					{
						$html.= "<option value='".$registros['pt_id']."'>".$registros['pt_descripcion']."</option>";
					}while($registros =  mysqli_fetch_array($cadena));

					echo $html;

					mysqli_free_result($registros);

					?>
				</select>
			</div>
			<div class="form-group col-md-3" >
				<label for="inputState">Lavador</label>
		
				<select id="cbxLavador" class="form-control" style="width: 250px" name="cbxLavador">
					<option value="">Seleccionar</option>
					<?php 
					$cad =  mysqli_query($cnx,"SELECT * from preparacion_lavadores where le_id = 6 and pl_id =  ".$_GET['id_l']." ");
					$reg =  mysqli_fetch_array($cad);

					$cadena =  mysqli_query($cnx,"SELECT * from preparacion_lavadores where le_id = 6");
					$registros =  mysqli_fetch_array($cadena);

					do
					{
						?><option value="<?php echo $registros['pl_id'] ?>" <?php if($registros['pl_id'] == $reg['pl_id']){ ?>selected="selected"<?php }?>><?php echo $registros['pl_descripcion'] ?></option><?php
					}while($registros =  mysqli_fetch_array($cadena));

				//mysqli_free_result($registros);

					?>
				</select>

			</div>


			<div class="form-group col-md-4">
				<div class="alert alert-info hide" id="alerta-errorEncabezado1" style="height: 40px;width: 300px;text-align: left;z-index: 10;margin-top: 10px;margin-bottom: -60px;position: fixed;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>
			<div class="form-group col-md-1">
				<div style="height: 40px;width: 300px;text-align: left;z-index: 10;margin-top:10px;float: right;width: 130px;margin-bottom: 0px">
					<button type="submit" class="btn btn-primary" id="btnEnviar" name="btnEnviar"><img src="../iconos/guardar.png" alt="">Guardar</button>		
				</div>
			</div>
		</div>

		<div class="form-row col-md-12">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Tipo material</label>
				<?php for($i = 1; $i <= 5; $i++){ ?>
					<select id="<?php echo "cbxMaterial".$i ?>" class="form-control"  name="<?php echo "cbxMaterial".$i ?>"; onchange="obtenerInv(<?php echo $i; ?>)">
						<option value="">Seleccionar</option>
						<?php
						$html = ''; 
						$cadena_mat =  mysqli_query($cnx,"SELECT * from materiales WHERE mat_est = 'A' ");
						$registros =  mysqli_fetch_array($cadena_mat);
						do
						{
							$html.= "<option value='".$registros['mat_id']."'>".$registros['mat_nombre']."</option>";
						}while($registros =  mysqli_fetch_array($cadena_mat));
						echo $html;
						?>
					</select>
				<?php }?>
			</div>

			<div class="form-group col-md-1" style="width: 140px">
				<label for="inputPassword4">Toneladas-[Kg]</label>
				<?php for($i = 1; $i <= 5; $i++){ ?>
					<select id="<?php echo "cbxKilosID".$i ?>" class="form-control" name="<?php echo "cbxKilosID".$i ?>" onchange="sumCantidadD(<?php echo $i ?>);obtenerFecha(<?php echo $i; ?>);obtenerFecha_entrada_maquila(<?php echo $i; ?>)" >
						<option value="">Seleccionar</option>
					</select>
					<input type="hidden" id="<?php echo "txtKilos".$i ?>" name="<?php echo "txtKilos".$i ?>">
				<?php }?>
				<!--<input type="hidden" id="txtSobraH" name="txtSobraH">-->
			</div>
			<div class="form-group col-md-1" style="width: 70px">
				<label for="inputPassword4" style="color: #fff">Toneladas</label>

				<?php for($i = 1; $i <= 5; $i++){ ?>
					<a id="<?php echo "btn".$i ?>" href="#" onClick="javascript:AbreModalEditar(<?php echo $i ?>)"><img src="../iconos/division.png" style="padding-bottom: 5px"></a>
				<?php }?>
			</div>
			<div class="form-group col-md-1" style="width: 130px">
				<label for="inputPassword4">Fecha entrada</label>
				<?php for($i = 1; $i <= 5; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtFecha".$i ?>" style="width: 100px" name="<?php echo "txtFecha".$i ?>"  readonly="true" size="10">
				<?php }?>
			</div>
			<div class="form-group col-md-2">
				<label for="inputPassword4">F. entrada de maquila</label>
				<?php for($i = 1; $i <= 5; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtFecha_ent_maquila".$i ?>" style="width:140px;" name="<?php echo "txtFecha_ent_maquila".$i ?>"  readonly="true" size="10">
				<?php }?>
			</div>


		</div>
		<div class="form-row col-md-12">
			<div id="campos"></div>
		</div>
		<?php 
	//include "tabla_1.php";
	//include "tabla_2.php";
		?>
		<div class="row">
			<div class="col-md-12">
				
				<div class="form-group col-md-2">
					<label for="inputPassword4" style="color: #fff">N/A</label>
					<button type="button" class="btn btn-primary" style="padding: 2px;margin-top: -3px"  name="count_click" onclick="agregarRenglon()" ><img src="../iconos/add.png" alt=""> Agregar renglón </button>
				</div>
				<div class="form-group col-md-2">
					<label for="inputPassword4">Total kgs:</label>
					<input style="width:105px" type="text" class="form-control" id="txtTotKilos" name="txtTotKilos" readonly="">
					<input type="hidden" class="form-control" id="txtAux" name="txtAux" readonly="">
				</div>
			<!--<div class="form-group col-md-3">
				<label for="inputPassword4">Fecha que carga lavador:</label>
				<input type="date" class="form-control" id="txtFechaCarga" placeholder="Fecha de Carga" name="txtFechaCarga" value="<?php echo date("Y-m-d") ?>">
			</div>
			<div class="form-group col-md-2">
				<label for="inputPassword4">Hora Inicia:</label>
				<input type="time" class="form-control" id="txtHrIni" placeholder="" name="txtHrIni" value="<?php echo date("H:i")?>">
			</div>
			<div class="form-group col-md-2">
				<label for="inputPassword4">Hora Termina</label>
				<input type="time" class="form-control" id="txtHrFin" placeholder="" name="txtHrFin" value="<?php echo date("H:i")?>">
			</div>-->
		</div>
	</div>
</form>

<div class="modal" id="modalDividir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>