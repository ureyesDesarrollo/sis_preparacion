<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
$id_l = $_GET['id_l'];

$cad_pro = mysqli_query($cnx, "SELECT p.*  
							   FROM procesos as p
							   WHERE p.pl_id = '$id_l' AND p.pro_estatus = 1");
$reg_pro = mysqli_fetch_array($cad_pro);
?>
<!--<script src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/alerta.js"></script>
<script src="../js/bootstrap.min.js"></script>-->

<script>
 	   //cargar combos kilos
 	   $(document).ready(function(){
 	   	$("#cbxMaterial1").change(function () {
 	   		$("#cbxMaterial1 option:selected").each(function () {
 	   			mat_id = $(this).val();
 	   			$.post("getInvTot.php", { mat_id: mat_id }, function(data){
 	   				$("#cbxKilosID1").html(data);

 	   			});          
 	   		});
 	   	})
 	   	$("#cbxMaterial2").change(function () {
 	   		$("#cbxMaterial2 option:selected").each(function () {
 	   			mat_id = $(this).val();
 	   			$.post("getInvTot.php", { mat_id: mat_id }, function(data){
 	   				$("#cbxKilosID2").html(data);
 	   			});          
 	   		});
 	   	})
 	   	$("#cbxMaterial3").change(function () {
 	   		$("#cbxMaterial3 option:selected").each(function () {
 	   			mat_id = $(this).val();
 	   			$.post("getInvTot.php", { mat_id: mat_id }, function(data){
 	   				$("#cbxKilosID3").html(data);
 	   			});          
 	   		});
 	   	})
 	   	$("#cbxMaterial4").change(function () {
 	   		$("#cbxMaterial4 option:selected").each(function () {
 	   			mat_id = $(this).val();
 	   			$.post("getInvTot.php", { mat_id: mat_id }, function(data){
 	   				$("#cbxKilosID4").html(data);
 	   			});          
 	   		});
 	   	})
 	   	$("#cbxMaterial5").change(function () {
 	   		$("#cbxMaterial5 option:selected").each(function () {
 	   			mat_id = $(this).val();
 	   			$.post("getInvTot.php", { mat_id: mat_id }, function(data){
 	   				$("#cbxKilosID5").html(data);
 	   			});          
 	   		});
 	   	})
 	   	$("#cbxMaterial6").change(function () {
 	   		$("#cbxMaterial6 option:selected").each(function () {
 	   			mat_id = $(this).val();
 	   			$.post("getInvTot.php", { mat_id: mat_id }, function(data){
 	   				$("#cbxKilosID6").html(data);
 	   			});          
 	   		});
 	   	})
 	   	$("#cbxMaterial7").change(function () {
 	   		$("#cbxMaterial7 option:selected").each(function () {
 	   			mat_id = $(this).val();
 	   			$.post("getInvTot.php", { mat_id: mat_id }, function(data){
 	   				$("#cbxKilosID7").html(data);
 	   			});          
 	   		});
 	   	})
 	   	$("#cbxMaterial8").change(function () {
 	   		$("#cbxMaterial8 option:selected").each(function () {
 	   			mat_id = $(this).val();
 	   			$.post("getInvTot.php", { mat_id: mat_id }, function(data){
 	   				$("#cbxKilosID8").html(data);
 	   			});          
 	   		});
 	   	})
 	   });



 		//cargar combos fecha de entrada
 		$(document).ready(function(){
 			$("#cbxKilosID1").change(function () {
 				$("#cbxKilosID1 option:selected").each(function () {
 					inv_id = $(this).val();
 					$.post("getFecha.php", { inv_id: inv_id }, function(data){
 						$("#txtFecha1").val(data);
 					});          
 				});
 			})
 			$("#cbxKilosID2").change(function () {
 				$("#cbxKilosID2 option:selected").each(function () {
 					inv_id = $(this).val();
 					$.post("getFecha.php", { inv_id: inv_id }, function(data){
 						$("#txtFecha2").val(data);
 					});          
 				});
 			})
 			$("#cbxKilosID3").change(function () {
 				$("#cbxKilosID3 option:selected").each(function () {
 					inv_id = $(this).val();
 					$.post("getFecha.php", { inv_id: inv_id }, function(data){
 						$("#txtFecha3").val(data);
 					});          
 				});
 			})
 			$("#cbxKilosID4").change(function () {
 				$("#cbxKilosID4 option:selected").each(function () {
 					inv_id = $(this).val();
 					$.post("getFecha.php", { inv_id: inv_id }, function(data){
 						$("#txtFecha4").val(data);
 					});          
 				});
 			})
 			$("#cbxKilosID5").change(function () {
 				$("#cbxKilosID5 option:selected").each(function () {
 					inv_id = $(this).val();
 					$.post("getFecha.php", { inv_id: inv_id }, function(data){
 						$("#txtFecha5").val(data);
 					});          
 				});
 			})
 			$("#cbxKilosID6").change(function () {
 				$("#cbxKilosID6 option:selected").each(function () {
 					inv_id = $(this).val();
 					$.post("getFecha.php", { inv_id: inv_id }, function(data){
 						$("#txtFecha6").val(data);
 					});          
 				});
 			})
 			$("#cbxKilosID7").change(function () {
 				$("#cbxKilosID7 option:selected").each(function () {
 					inv_id = $(this).val();
 					$.post("getFecha.php", { inv_id: inv_id }, function(data){
 						$("#txtFecha7").val(data);
 					});          
 				});
 			})
 			$("#cbxKilosID8").change(function () {
 				$("#cbxKilosID8 option:selected").each(function () {
 					inv_id = $(this).val();
 					$.post("getFecha.php", { inv_id: inv_id }, function(data){
 						$("#txtFecha8").val(data);
 					});          
 				});
 			})
 		});


 		function sumCantidad() 
 		{
 			var i;
 			var cantTotal = document.getElementById('txtTotKilos').value = 0;

 			for (i = 1; i <= 5 ; i++) 
 			{ 
		//var val = document.getElementById('cbxKilosID'+ i).value;
		var combo = document.getElementById('cbxKilosID'+i);
		var val = combo.options[combo.selectedIndex].text;
		document.getElementById('txtKilos'+i).value=val;
		
		if (val == 'Seleccionar') 
		{
			var val = 0;
		}

		//alert(val);
		
		cantTotal = parseInt(cantTotal) + parseInt(val);
	}
	document.getElementById('txtTotKilos').value = cantTotal;





	function ShowSelected()
	{
		/* Para obtener el valor */
		var cod = document.getElementById("producto").value;
		alert(cod);

		/* Para obtener el texto */
		var combo = document.getElementById("producto");
		var selected = combo.options[combo.selectedIndex].text;
		alert(selected);
	}
}

$(document).ready(function()
{
	$("#formEncabezado2").submit(function(){

		var formData = $(this).serialize();
			var checksCol = $("input:radio[name=radColador]:checked").length;
			var checksCuero = $("input:radio[name=radCe]:checked").length;
			

			/*var check = $("input[type='checkbox']:checked").length;
	        var radio = $("input[type='radio']:checked").length;
	        var select = $("select option:selected").val();*/
			//var chk = Validar();
			var c1 = document.getElementById('cheMolino1').checked;
			 var c2 = document.getElementById('cheMolino2').checked;
			 var c3 = document.getElementById('cheMolino3').checked;
			 var c4 = document.getElementById('cheMolino4').checked;
			 var c5 = document.getElementById('cheMolino5').checked;
			
			if (c1 == false && c2 == false && c3 == false && c4 == false && c5 == false) {//or c2 == false or c3 == false or c4 == false or c5 == false
				alert('Seleccione una opción para Molino');
				return false;
			}
			else if (checksCol == '') {
				alert('Seleccione una opción para COLADORES LIMPIOS');
				return false;
			}
			 else if(checksCuero == '') {
				alert('Seleccione una opción para CUERO');
				return false;
			}
			else{
			$.ajax({
				url: "encabezado_2_actualizar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorEncabezado2", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formEncabezado2').each (function(){this.reset();});  
					setTimeout("location.reload()", 2000);
				}
			});
			return false;
			}
	});
});

function Validar() {
 var c1 = document.getElementById('cheMolino1').checked;
 var c2 = document.getElementById('cheMolino2').checked;
 var c3 = document.getElementById('cheMolino3').checked;
 var c4 = document.getElementById('cheMolino4').checked;
 var c5 = document.getElementById('cheMolino5').checked;
 
 //document.getElementById('alerta-errorEncabezado2').innerHTML = 'CheckBox 1: '+c1+'\n'+'CheckBox 2: '+c2+'\n'+ 'CheckBox 3: '+c3+'\n'+'CheckBox 4: '+c4;
 if (c1 == true || c2 == true) 
 	{
 	return true;
 	}
else
{
	return false;
}	
 }

/*function AbreModalEditarX(id)
{ 
	$.ajax({
		type : 'post',
		url : 'modal_dividir.php', 
		data : {"hdd_id":id}, //Pass $id
		success : function(result){
			$("#modalDividir").html(result);
			$('#modalDividir').modal('show')
		}
	});
	return false;
};*/


function AbreModalEditar(param){
	//alert('aqui');
 			var datos={
    		"inv_id": $("#cbxKilosID"+param).val(),
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



function refresh()
{
	location.reload();
}

</script>

<form id="formEncabezado2">
<!--operador-->
<input name="hdd_user" type="hidden" value="<?php echo $_SESSION['idUsu']; ?>" id="hdd_user"/>
<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id"/>
	<div class="form-row">
		<div class="form-group col-md-3">
			<label for="inputState">Tipo Preparacion</label>
			<select id="cbxProceso" class="form-control" style="width: 250px" name="cbxProceso" disabled>
				<option value="0">Seleccionar</option>
				<?php 
			
				$cadena =  mysqli_query($cnx,"SELECT * from preparacion_tipo ORDER BY pt_descripcion");
				$registros =  mysqli_fetch_array($cadena);

				do
				{
					?><option value="<?php echo $registros['pt_id'] ?>" <?php if($registros['pt_id'] == $reg_pro['pt_id'] ) {?> selected="selected" <?php }?>><?php echo $registros['pt_descripcion'] ?></option><?php
				}while($registros =  mysqli_fetch_array($cadena));

				mysqli_free_result($registros);

				?>
			</select>
		</div>
		<div class="form-group col-md-3" >
			<label for="inputState">Lavador</label>
			<select id="cbxLavador" class="form-control" style="width: 250px" name="cbxLavador" required disabled="disabled">
				<option selected>Seleccionar</option>
				<?php 
				$cadena =  mysqli_query($cnx,"SELECT * from preparacion_lavadores");
				$registros =  mysqli_fetch_array($cadena);

				do
				{
					?><option value="<?php echo $registros['pl_id'] ?>" <?php if($registros['pl_id'] == $reg_pro['pl_id'] ) {?> selected="selected" <?php }?>><?php echo $registros['pl_descripcion'] ?></option><?php
				}while($registros =  mysqli_fetch_array($cadena));

				mysqli_free_result($registros);

				?>
			</select>
		</div>
		

		<div class="form-group col-md-4">
			<div class="alert alert-info hide" id="alerta-errorEncabezado2" style="height: 40px;width: 300px;text-align: left;z-index: 10;margin-top: 10px;margin-bottom: -60px;position: fixed;">
				<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
				<strong>Titulo</strong> &nbsp;&nbsp;
				<span> Mensaje </span>
			</div>
		</div>
		<div class="form-group col-md-1">
			<?php if($reg_pro['pro_id'] != ''){?>
			<div style="height: 40px;width: 300px;text-align: left;z-index: 10;margin-top:10px;float: right;width: 130px;margin-bottom: 0px">
				<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>		
			</div>
			<?php }else{?>
				<div style="height: 40px;width: 270px;text-align: left;z-index: 10;margin-top:10px;float: right;margin-bottom: 0px;background: #e6e6;border-radius: 5px;padding: 10px;color: #BD223E;font-weight: bold;font-style: italic;">
				 Debe capturar primero el supervisor
				</div>
		
			<?php }?>
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-2">
			<label for="inputPassword4">Tipo material</label>
			<?php
			$sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada 
            FROM materiales as m 
            INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
            WHERE pm.pro_id = '$reg_pro[pro_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regProMat= mysqli_fetch_assoc($sqlProMat);

          do{?>
			<input class="form-control" name="txtMaterial" type="text" id="txtMaterial" value="<?php echo $regProMat['mat_nombre'] ?>" readonly="" size="20" disabled="disabled"/>
			<?php }while($regProMat= mysqli_fetch_assoc($sqlProMat));?>
		</div>

		<div class="form-group col-md-1" style="width: 140px">
			<label for="inputPassword4">Toneladas-[Kg]</label>
			<?php 
			$sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada 
            FROM materiales as m 
            INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
            WHERE pm.pro_id = '$reg_pro[pro_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regProMat= mysqli_fetch_assoc($sqlProMat);

          do{?>
			<input class="form-control" name="txtKilos" type="text" id="txtKilos" value="<?php echo $regProMat['pma_kg'] ?>" readonly="" size="10"  disabled="disabled"/>
			<?php }while($regProMat= mysqli_fetch_assoc($sqlProMat));?>
		</div>
		<!--<div class="form-group col-md-1" style="width: 70px">
			<label for="inputPassword4" style="color: #fff">Toneladas</label>
		</div>-->
		<div class="form-group col-md-1" style="width: 130px">
			<label for="inputPassword4">Fecha entrada</label>
			<?php 
			$sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada 
            FROM materiales as m 
            INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
            WHERE pm.pro_id = '$reg_pro[pro_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regProMat= mysqli_fetch_assoc($sqlProMat);

          do{?>
			<input class="form-control" name="txtFecha" type="text" id="txtFecha" value="<?php echo $regProMat['pma_fe_entrada'] ?>" readonly="" style="width:90px;" disabled="disabled"/>
			<?php }while($regProMat= mysqli_fetch_assoc($sqlProMat));?>
		</div>
		<div class="form-group col-md-2">
			<label for="inputPassword4">F. entrada de maquila</label>
			<?php 
			$sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada, SUBSTRING(pm.pma_fe_entrada_maquila, 1, 10)  AS pma_fe_entrada_maquila
            FROM materiales as m 
            INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
            WHERE pm.pro_id = '$reg_pro[pro_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regProMat= mysqli_fetch_assoc($sqlProMat);

          do{?>
			<input  class="form-control" name="txtFecha_ent_maquila" type="text" id="txtFecha_ent_maquila" value="<?php echo $regProMat['pma_fe_entrada_maquila'] ?>" readonly="" style="width:140px;" disabled="disabled"/>
			<?php }while($regProMat= mysqli_fetch_assoc($sqlProMat));?>
		</div>
	</div>

	<?php 
	include "tabla_1.php";
	include "tabla_2.php";
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group col-md-1">
				<label for="inputPassword4">Total kgs</label>
				<input type="text" class="form-control" id="txtTotKilos" name="txtTotKilos" readonly="" value="<?php echo $reg_pro['pro_total_kg'] ?>">
			</div>
			<div class="form-group col-md-2">
				<label for="inputPassword4">Fecha  carga lavador:</label>
				<input type="date" class="form-control" id="txtFechaCarga" placeholder="Fecha de Carga" name="txtFechaCarga" value="<?php echo date("Y-m-d") ?>">
			</div>
			<div class="form-group col-md-2">
				<label for="inputPassword4">Hora Inicia:</label>
				<input type="time" class="form-control" id="txtHrIni" placeholder="" name="txtHrIni" value="<?php echo date("H:i") ?>">
			</div>
			<div class="form-group col-md-2">
				<label for="inputPassword4">Hora Termina</label>
				<input type="time" class="form-control" id="txtHrFin" placeholder="" name="txtHrFin" value="<?php echo date("H:i") ?>">
			 </div>
			 <div class="form-group col-md-5">
				<label for="inputPassword4">Observaciones</label>
				<textarea type="time" class="form-control" id="textObs" maxlength="200" placeholder="" name="textObs" value="<?php echo date("H:i") ?>"></textarea>
			 </div>
		</div>
	</div>
</form>

<!--<div class="modal" id="modalDividir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>-->