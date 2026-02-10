<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../conexion/conexion.php";
include('../funciones/funciones.php');
$cnx =  Conectarse();


$cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre,p.prv_ncorto
	FROM inventario as i
	INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
	INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
	WHERE inv_no_factura is not null and inv_enviado = 0 and p.prv_tipo = 'E'  and inv_tomado = 0  ORDER BY prv_tipo") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);

?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/alerta.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#tabla_inventarioD').dataTable({
			"sPaginationType": "full_numbers"
		});
	})

	//nueva funcionalidad / parcialidad al enviar a maquila
	function abre_modal_parcialidad(inv_id) {
		//alert('aqui');
		var datos = {
			"inv_id": inv_id,
		}
		//alert($("#cbxKilosID"+param).val());
		$.ajax({
			type: 'post',
			url: 'parcialidad/modal_dividir.php',
			data: datos,
			//data: {nombre:n},
			success: function(result) {
				$("#modal_dividir").html(result);
				$('#modal_dividir').modal('show')
			}
		});
		return false;
	}

	//nueva funcionalidad / ya no se manda a maquila, se pelambra en planta 10/02/22
	function abre_modal_pelambre(inv_id) {
		//alert('aqui');
		var datos = {
			"inv_id": inv_id,
		}
		//alert($("#cbxKilosID"+param).val());
		$.ajax({
			type: 'post',
			url: 'pelambrado/modal_pelambrado.php',
			data: datos,
			//data: {nombre:n},
			success: function(result) {
				$("#modal_pelambrado").html(result);
				$('#modal_pelambrado').modal('show');
			}
		});
		return false;
	}
</script>

<div class="row" style="margin-top:2rem;">
	<div class="col-sm-12 col-md-4 ">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Funciones</li>
				<li class="breadcrumb-item active" aria-current="page">Inventario Extranjero en Bodega</li>
			</ol>
		</nav>
	</div>

	<div class="col-sm-12 col-md-8" style="text-align:right">
		<a class="iconos" href="formatos/listado_extranjero.php" target="_blank"><i class="fa-solid fa-print fa-2xl"></i>
			Imprimir</a>
		<a class="iconos" href="exportar/extranjero.php" target="_blank"><i class="fa-solid fa-file-excel fa-2xl"></i>
			Exp.excel</a>
	</div>
</div>
<div class="container" style="border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_inventarioD">
		<thead>
			<tr align="center">
				<th width="5%">Fecha</th>
				<th>Dia</th>
				<th>No. Ticket</th>
				<th>Placas</th>
				<th>Camioneta</th>
				<th>Proveedor</th>
				<th>Tipo</th>
				<th>Material</th>
				<th>Kg entrada</th>
				<th>Prueba<br /> secador</th>
				<th>Kg totales</th>
				<th>Maquila</th>
				<!-- <th>Pelambrado</th> -->
				<!--<th width="20">Baja</th>-->
			</tr>
		</thead>
		<tbody>
			<?php
			$ren = 1;
			$flt_kg = 0;
			$flt_kg_t = 0;

			do {

				if (isset($registros['inv_dia'])) {
					/* $registros['inv_dia'] = 7; */

			?>
					<tr height="20">
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
						<td align="right"><?php echo number_format($registros['inv_kilos'], 2) ?></td>
						<td><?php echo $registros['inv_prueba'] ?></td>
						<td align="right"><?php echo number_format($registros['inv_kg_totales'], 2) ?></td>
						<!--<td style="padding-left: 0px" align="center"><a href="#" onClick="javascript:AbreModalEditar(<?= $registros['prv_id']; ?>)"><img src="../iconos/editar.png"></a></td>-->
						<td style="padding-left: 0px">
							<?php
							if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_editar') == 1) { ?>
								<a href="#" onClick="javascript:abre_modal_parcialidad(<?php echo $registros['inv_id']; ?>)"><img src="../iconos/disp.png" /></a>
							<?php } ?>
						</td>
						<!-- <td style="padding-left: 0px">
							<?php if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_editar') == 1) { ?>
								<a href="#listadoamaquila" onClick="javascript:abre_modal_pelambre(<?php echo $registros['inv_id']; ?>)"><img src="../iconos/disp.png" /></a>
							<?php } ?>
						</td> -->
					</tr>
			<?php
					$ren += 1;

					$flt_kg += $registros['inv_kilos'];
					$flt_kg_t += $registros['inv_kg_totales'];
				}
			} while ($registros = mysqli_fetch_assoc($cadena)); ?>

		</tbody>

		<tfoot>
			<?php for ($i = $ren; $i <= 12; $i++) { ?>
				<tr>
					
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			<?php } ?>
			<tr>
				
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th>Total Kg:</th>
				<th align="right"><?php echo number_format($flt_kg, 2); ?></th>
				<th></th>
				<th align="right"><?php echo number_format($flt_kg_t, 2); ?></th>
				<th></th>
				<th></th>

			</tr>
		</tfoot>
	</table>
</div>

<div class="modal" id="modal_dividir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">

</div>

<div class="modal" id="modal_pelambrado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>