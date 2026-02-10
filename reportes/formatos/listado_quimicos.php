<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT q.*, SUBSTRING(q.quim_fecha, 1, 16) as quim_fecha FROM quimicos_etapas as q") or die(mysqli_error($cnx)."Error: en consultar quimicos");
$registros = mysqli_fetch_assoc($cadena);
?>
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
<script src="../../js/jquery.min.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../js/alerta.js"></script>
<script src="../../js/jquery.min.js"></script>

<script>
	/*
	function filtro(){
		var datos={
			"fechaIni": $("#fechaInicio").val(),
			"fechaFin": $("#fechaFinal").val(),
		}

		var fechaIni = document.getElementById('fechaInicio').value;
		var fechaFin = document.getElementById('fechaFinal').value;

		if (fechaIni != '' && fechaFin != '') {
			$.ajax({
				type:'post',
				url: 'quimicos_filtro_rango.php',
				data: datos,
      //data: {nombre:n},
      success: function(d){
      	$("#tab2").html(d);
      } 
  });

		}else{
			$.ajax({
				type:'post',
				url: 'quimicos_filtro.php',
				data: datos,
      //data: {nombre:n},
      success: function(d){
      	$("#tab2").html(d);
      } 
  });
    //return false;
}
}*/

function proceso(){
	var datos={
		"fechaIni": $("#fechaInicio").val(),
		"fechaFin": $("#fechaFinal").val(),
		"proceso": $("#txt_proceso").val(),
		"quimico": $("#cbx_quimico").val(),
	}
	$.ajax({
		type:'post',
		url: 'quimicos_filtro.php',
		data: datos,
      //data: {nombre:n},
      success: function(d){
      	$("#tab2").html(d);
      } 
  });
    //return false;
}


function exportar(){
	var fechaIni = document.getElementById('fechaInicio').value;
	var fechaFin = document.getElementById('fechaFinal').value;
	var proceso = document.getElementById('txt_proceso').value;
	var quimico = document.getElementById('cbx_quimico').value;

	/*if (fechaIni != '' && fechaFin != '') {
		window.open('../exportar/listado_exportar_rango.php?fechaIni='+ encodeURIComponent(fechaIni) 
			+'&fechaFin=' + encodeURIComponent(fechaFin));  
	}

	if (fechaIni != '' || proceso != '') {
		window.open('../exportar/listado_quimicos_exportar_proceso.php?fechaIni='+ encodeURIComponent(fechaIni) 
			+'&proceso=' + encodeURIComponent(proceso));  
	}*/
window.open('../exportar/listado_quimicos_exportar.php?fechaIni='+ encodeURIComponent(fechaIni) 
			+'&fechaFin=' + encodeURIComponent(fechaFin)
			+'&proceso=' + encodeURIComponent(proceso)
			+'&quimico=' + encodeURIComponent(quimico));  
	
}

function reset(){
	location.href = "listado_quimicos.php";
      //var fchi = document.getElementById('fechaInicio').value = '';
      //var fechaFin = document.getElementById('fechaFinal').value = '';

  }

  function imprimir(){
  	window.print();
      //var fchi = document.getElementById('fechaInicio').value = '';
      //var fechaFin = document.getElementById('fechaFinal').value = '';

  }


  function resetea_campos_fecha(){
  	var fechaIni = document.getElementById('fechaInicio').value;
  	var fechaFin = document.getElementById('fechaFinal').value;
  	var proceso = document.getElementById('txt_proceso').value;
  	var quimico = document.getElementById('cbx_quimico').value;

  	if (fechaIni != '' || fechaFin != '') {
  		var proceso = document.getElementById('txt_proceso').value = '';
  		document.getElementById("cbx_quimico").selectedIndex = "0"; 
  	}
  }

    function resetea_campos_proceso(){
  	var fechaIni = document.getElementById('fechaInicio').value;
  	var fechaFin = document.getElementById('fechaFinal').value;
  	var proceso = document.getElementById('txt_proceso').value;
  	var quimico = document.getElementById('cbx_quimico').value;

  	if (proceso != '' ) {
  		var fechaIni = document.getElementById('fechaInicio').value = '';
  		var fechaFin = document.getElementById('fechaFinal').value = '';
  		document.getElementById("cbx_quimico").selectedIndex = "0"; 
  	}
  }

   function resetea_campos_quimicos(){
  	var fechaIni = document.getElementById('fechaInicio').value;
  	var fechaFin = document.getElementById('fechaFinal').value;
  	var proceso = document.getElementById('txt_proceso').value;
  	var quimico = document.getElementById('cbx_quimico').value;
  	if (quimico != '' ) {
  		var fechaIni = document.getElementById('fechaInicio').value = '';
  		var fechaFin = document.getElementById('fechaFinal').value = '';
  		var proceso = document.getElementById('txt_proceso').value = '';
  	}
  }
</script>
<style>

	@media print{
		.ocultar{
			display: none !important;
		}
	}
</style>

<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<title>Listado químicos</title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">
	<style>
		.table th, .table td { 
			border-top: none !important;
			border-bottom: none !important;
			border-left: none !important;
		}
	</style>

</head>

<body>
	<div class="container-fluid">
		<center>
			<table border="0" class="ocultar table"  style="width:90%;margin-bottom: 10px;"> 
				<tr style="border: none;">

					<th colspan="4">Filtrar por fecha</th>
					<th width="3%"></th>
					<th>Filtrar por proceso</th>
					<th></th>
					<th width="3%">Filtrar por químico</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
				<tr style="border: none;">
					<td>De: </td>
					<td><input type="date" style="width: 160px" class="form-control" id="fechaInicio" onchange="proceso();resetea_campos_fecha();"></td>
					<td>a:</td>
					<td><input type="date" style="width: 160px" class="form-control" id="fechaFinal" onchange="proceso();resetea_campos_fecha();"></td>
					<td></td>
					<td><input class="form-control" style="width: 150px" type="text" onKeyPress="return isNumberKey(event, this);" id="txt_proceso" name="txt_proceso" onkeyup="resetea_campos_proceso();proceso();"></td>
					<td></td>
					<td>
						<select class="form-control" style="width: 150px" type="text" onKeyPress="return isNumberKey(event, this);" id="cbx_quimico" name="cbx_quimico" onchange="resetea_campos_quimicos();proceso();" >
							<option value="">Selecciona</option>
							<?php 
							$cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos") or die(mysqli_error($cnx)."Error: en consultar quimicos");
							$reg_quim = mysqli_fetch_assoc($cad_quim);

							do { ?>
								<option value="<?php echo $reg_quim['quimico_id'] ?>"><?php echo $reg_quim['quimico_descripcion'] ?></option>
							<?php } while ($reg_quim = mysqli_fetch_assoc($cad_quim));
							?>
						</select>
					</td>
					<td><button class="btn btn-primary" onclick="imprimir()" type="button">Imprimir	</button></td>
					<td><button class="btn btn-primary" onclick="exportar()" type="button">Exportar	</button></td>
					<td  style="font-size: 18px;font-weight: bold;" width="92" align="left"><button class="btn btn-primary" onclick="reset()" type="button">Limpiar</button></td>
				</tr>
			</table>

			<div class="tablehead">
				<table>
					<tr>
						<td width="350"><img src="../../imagenes/logo_progel_v3.png"></td>
						<td align="center"><h1>Listado químicos</h1></td>
						<td style="text-align: right;vertical-align: top;font-family: New Times Roman"><h6>CONTROL DE CONSUMO DE INSUMOS           COM F 007-REV 003</h6></td>
					</tr>
					<tr></tr>
				</table>

			</div>

			<div class="tablecuerpo" id="tab2">
				<table  cellpadding="0" style="width: 90%;" cellspacing="0" border="0" class="display" id="tabla_quimicos">
					<thead>
						<tr align="center">
							<th>&nbsp;Proceso&nbsp;</th>
							<th>&nbsp;Etapa&nbsp;</th>
							<th>&nbsp;Tipo químico&nbsp;</th>
							<th>&nbsp;Lote&nbsp;</th>
							<th>&nbsp;Litros&nbsp;</th>
							<th>Usuario</th>
							<th width="15%">Fecha y Hora</th>

						</tr>
					</thead>
					<tbody>
						<?php 
						$ren = 1;
						do{
							$cad_etapa = mysqli_query($cnx, "SELECT * FROM preparacion_etapas WHERE pe_id = '$registros[pe_id]'") or die(mysqli_error($cnx)."Error: en consultar quimicos");
							$reg_etapa = mysqli_fetch_assoc($cad_etapa);

							$cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_id = '$registros[quimico_id]'") or die(mysqli_error($cnx)."Error: en consultar quimicos");
							$reg_quim = mysqli_fetch_assoc($cad_quim);

							?>
							<tr height="20">
								<td><?php echo $registros['pro_id'] ?></td>
								<td><?php echo $reg_etapa['pe_nombre'].' ('.$reg_etapa['pe_descripcion'].")"; ?></td>
								<td>
									<?php echo $reg_quim['quimico_descripcion'] ?>
								</td>
								<td><?php echo $registros['quim_lote'] ?></td>
								<td><?php echo $registros['quim_litros'] ?></td>
								<td><?php echo fnc_nom_usuario($registros['usu_id']); ?></td>
								<td><?php echo $registros['quim_fecha'] ?></td>
							</tr>
							<?php 
							$ren += 1;

						}while($registros = mysqli_fetch_assoc($cadena));?>

					</tbody>

					<tfoot>
						<?php for($i=$ren; $i <= 12; $i++){?>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						<?php }?>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</center>	
	</div>

	<?php include "../../generales/pie_pagina_formato.php";?>
</body>
</html>