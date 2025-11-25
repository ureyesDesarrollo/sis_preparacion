<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);
$cad_fase = mysqli_query($cnx, "SELECT p.*, e.pe_descripcion
FROM preparacion_tipo_etapas as p
INNER JOIN preparacion_etapas as e on(p.pe_id = e.pe_id)
WHERE p.pt_id = " . $_GET['id'] . " ORDER BY p.pte_orden asc ") or die(mysqli_error($cnx) . "Error: en consultar las etapas");
$reg_fase = mysqli_fetch_assoc($cad_fase);
$tot_fase = mysqli_num_rows($cad_fase);

?>

 <div id="main">
 	<div class="col-md-5">
 		<table border="1">
 			<tbody>
 				<tr bgcolor="#4AB5B9" style="color:#FFFFFF; font-weight:bold;" height="30">
 					<th width="200">Fase</th>
 					<th width="60">Orden</th>
 					<th width="60">Quitar</th>
 					<th width="60">Arriba</th>
 					<th width="60">Abajo</th>
 				</tr>
 			</tbody>
 			<?php do {
					if (isset($reg_fase['pe_id'])) { ?>
 					<tr>
 						<td><?php echo $reg_fase['pe_descripcion'] ?></td>
 						<td align="center"><?php echo $reg_fase['pte_orden'] ?></td>
 						<td align="center"><a href="javascript:fnc_baja_fase(<?= $reg_fase['pte_id'] ?>);"><img src="../iconos/quitar.png" alt="Quitar" /></a></td>
 						<td align="center"><?php if ($reg_fase['pte_orden'] != 1) { ?><a href="javascript:fnc_sube_fase(<?= $reg_fase['pte_id'] ?>);"><img src="../iconos/arriba.png" alt="Arriba" /></a><?php } ?></td>
 						<td align="center"><?php if ($reg_fase['pte_orden'] != $tot_fase) { ?><a href="javascript:fnc_bajar_fase(<?= $reg_fase['pte_id'] ?>);"><img src="../iconos/abajo.png" alt="Abajo" /></a><?php } ?></td>
 					</tr>
 			<?php }
				} while ($reg_fase = mysqli_fetch_assoc($cad_fase)); ?>
 		</table>
 	</div>
 </div>