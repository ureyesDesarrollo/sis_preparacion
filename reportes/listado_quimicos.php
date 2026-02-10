<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT q.*, SUBSTRING(q.quim_fecha, 1, 16) as quim_fecha FROM quimicos_etapas AS q where quim_fecha >= '2024-01-01'") or die(mysql_error()."Error: en consultar quimicos");
$registros = mysqli_fetch_assoc($cadena);
?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>

<script type="text/javascript">
	<!--paginación de tabla-->
	$(document).ready(function()
	{
		$('#tabla_quimicos').dataTable( { 
			"sPaginationType": "full_numbers"
		} );
	})
</script>

<div class="container" style="margin-top:60px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
	<table  cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_quimicos">
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
				$cad_etapa = mysqli_query($cnx, "SELECT * FROM preparacion_etapas WHERE pe_id = '$registros[pe_id]'") or die(mysql_error()."Error: en consultar quimicos");
				$reg_etapa = mysqli_fetch_assoc($cad_etapa);

				$cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_id = ".$registros['quimico_id']."") or die(mysql_error()."Error: en consultar quimicos");
				$reg_quim = mysqli_fetch_assoc($cad_quim);

				?>
				<tr height="20">
					<td><?php echo $registros['pro_id'] ?></td>
					<td><?php echo $reg_etapa['pe_nombre'].' '.$reg_etapa['pe_descripcion'] ?></td>
					<td><?php echo $reg_quim['quimico_descripcion'] ?></td>
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