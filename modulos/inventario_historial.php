<script>
	function filtro() {
    const fechaini = $("#fechaInicio").val();
    const fechafin = $("#fechaFinal").val();
    const ticket = $("#ticket").val();

    if (fechaini || ticket) {
        $.ajax({
            type: 'POST',
            url: 'reporte_inventario.php',
            data: { fechaini, fechafin, ticket },
            success: function(response) {
                console.log(response);
                $("#tab_historial").html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error en la petición AJAX:", error);
            }
        });
    } else {
        alert('Por favor, seleccione una fecha de inicio o ingrese un ticket.');
    }
}



	function Imprimir() {
		event.preventDefault();

		var fchi = document.getElementById('fechaInicio').value;
		var fchf = document.getElementById('fechaFinal').value;
		/*window.location.href = 'formatos/listado_historial.php?fchi='+ encodeURIComponent(fchi) 
		+'&fchf=' + encodeURIComponent(fchf);*/

		if (fchi != '') {
			window.open('formatos/listado_historial.php?fchi=' + encodeURIComponent(fchi) +
				'&fchf=' + encodeURIComponent(fchf));
		} else {
			alert('Seleccione la fecha de inicio');
		}

	}


	function exportar() {
		event.preventDefault();

		var fchi = document.getElementById('fechaInicio').value;
		var fchf = document.getElementById('fechaFinal').value;
		if (fchi != '') {

			window.open('exportar/inventario_historial.php?fchi=' + encodeURIComponent(fchi) +
				'&fchf=' + encodeURIComponent(fchf));
		} else {
			alert('Seleccione la fecha de inicio');
			return true;
		}
		return true;
	}

	function reset() {
		$("#listadohistorial").load("inventario_historial.php");
	}

	/*function modal_proceso(inv_id) {
		$.ajax({
			type: 'post',
			url: 'modal_proceso.php',
			data: {
				"inv_id": inv_id,
			}, //Pass $id
			success: function(result) {
				$("#modal_proceso").html(result);
				$('#modal_proceso').modal('show')
			}
		});
		return false;
	};*/


	function agregar_extractibilidad(inv_id) {
		$.ajax({
			type: 'post',
			url: 'modal_extractibilidad.php',
			data: {
				"inv_id": inv_id,
			}, //Pass $id
			success: function(result) {
				$("#modal_extractibilidad").html(result);
				$('#modal_extractibilidad').modal('show')
			}
		});
		return false;
	};

	function agregar_humedad_origen(inv_id) {
		$.ajax({
			type: 'post',
			url: 'modal_humedad.php',
			data: {
				"inv_id": inv_id,
			}, //Pass $id
			success: function(result) {
				$("#modal_extractibilidad").html(result);
				$('#modal_extractibilidad').modal('show')
			}
		});
	};
</script>


<script type="text/javascript" src="../js/alerta.js"></script>
<div class="">
	<!--LISTADO DE INVENTARIO-->
	<div class="tab-content">
		<div id="inventario_modulo" class="tab-pane fade in active">
			<div class="row" style="margin-top:2rem;">
				<div class="col-sm-12 col-md-2">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">Funciones</li>
							<li class="breadcrumb-item active" aria-current="page">Inventario historial </li>
						</ol>
					</nav>
				</div>

				<div class="col-sm-12 col-md-2" style="text-align:right">
					<a class="iconos" href="#" target="_blank" onclick="Imprimir()"><i class="fa-solid fa-print fa-2xl"></i>
						Imprimir</a>
					<a class="iconos" href="#" onclick="exportar();" target="_blank"><i class="fa-solid fa-file-excel fa-2xl"></i>
						Exp.todo</a>
				</div>

				<div class="col-md-2" style="margin-top: -15px;">
					<label for="recipient-name" class="form-control-label">Ticket</label>
					<input type="text" style="height: 25px" class="form-control" id="ticket">
				</div>

				<div class="col-md-2" style="margin-top: -15px;">
					<label for="recipient-name" class="form-control-label">Fecha Inicio:</label>
					<input type="date" style="height: 25px" class="form-control" id="fechaInicio" value="<?php //echo $date 
																											?>">
				</div>

				<div class="col-md-2" style="margin-top: -15px">
					<label for="recipient-name" class="form-control-label">Fecha Fin:</label>
					<input type="date" style="height: 25px" class="form-control" id="fechaFinal" value="<?php //echo $date 
																										?>">
				</div>
				<div class="col-md-1">
					<button type="button" class="btn btn-primary" onclick="filtro();">Filtrar</button>
				</div>
				<div class="col-md-1">
					<button type="button" class="btn btn-primary" onclick="reset();">Limpiar</button>
				</div>
			</div>
			<div id="tab_historial">
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="inventario">
					<thead>
						<tr align="center">
							<th>&nbsp;No&nbsp;</th>
							<th>&nbsp;Fecha&nbsp;</th>
							<th>&nbsp;Dia&nbsp;</th>
							<th>&nbsp;No. Ticket&nbsp;</th>
							<th>&nbsp;Placas&nbsp;</th>
							<th>&nbsp;Camioneta&nbsp;</th>
							<th>&nbsp;Proveedor&nbsp;</th>
							<th>&nbsp;Tipo&nbsp;</th>
							<th>&nbsp;Material&nbsp;</th>
							<th>&nbsp;Kilos&nbsp;</th>
							<th>&nbsp;Prueba<br /> secador&nbsp;</th>
							<th width="100px">&nbsp;Kilos<br />Entrada&nbsp;</th>
							<th width="20">Editar</th>
						</tr>
					</thead>
					<tbody>
						<!-- <?php
								$ren = 1;
								$cont = 1;
								$flt_kg = 0;
								$flt_kg_t = 0;

								do {

									if (isset($registros['inv_dia'])) {

								?>
								<tr height="20">

									<td><?php echo $cont++ ?></td>
									<td align="center"><?php echo $registros['inv_fecha'] ?></td>
									<td><?php echo fnc_nom_dia($registros['inv_dia']); ?></td>
									<td><?php echo $registros['inv_no_ticket'] ?></td>
									<td><?php echo $registros['inv_placas'] ?></td>
									<td><?php echo $registros['inv_camioneta'] ?></td>
									<td><?php if ($reg_autorizado['up_ban'] == 1) {
											echo $registros['prv_nombre'];
										} else {
											echo $registros['prv_ncorto'];
										} ?></td>
									<?php
										if ($registros['prv_tipo']  == 'L') { ?>
										<td><?php echo "Local" ?></td>

									<?php
										} else { ?>
										<td><?php echo "Extranjero" ?></td>
									<?php } ?>
									<td><?php echo $registros['mat_nombre'] ?></td>
									<td align="right"><?php echo number_format($registros['inv_kilos'],2) ?></td>
									<td><?php echo $registros['inv_prueba'] ?></td>
									<td align="right"><?php echo number_format($registros['inv_kg_totales'],2) ?></td>
									<!--<td style="padding-left: 0px" align="center"><a href="#" onClick="javascript:modal_proceso(<?= $registros['inv_id']; ?>)"><img src="../iconos/editar.png"></a></td>-->
						<td style="padding-left: 0px"><a href="javascript:fnc_baja(<?= $registros['prv_id'] ?>);"><img src="../iconos/borrar.png" /></a></td>
						</tr>
				<?php
										$ren += 1;

										$flt_kg += $registros['inv_kilos'];
										$flt_kg_t += $registros['inv_kg_totales'];
									}
								} while ($registros = mysqli_fetch_assoc($cadena)); ?> -->

					</tbody>

					<tfoot>
						<?php for ($i = $ren; $i <= 12; $i++) { ?>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						<?php } ?>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th>Total Kg:</th>
							<th align="right"><?php echo number_format($flt_kg,2); ?></th>
							<th></th>
							<th></th>
							<th></th>
							<th align="right"><?php echo number_format($flt_kg_t,2); ?></th>
						</tr>
					</tfoot>
				</table>
			</div>



		</div>

	</div>
</div>
<div class="modal" id="modal_proceso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>
<div class="modal" id="modal_extractibilidad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>




<!-- these js files are used for making PDF -->
<script src="../js/jspdf.js"></script>
<script src="../js/pdfFromHTML.js"></script>