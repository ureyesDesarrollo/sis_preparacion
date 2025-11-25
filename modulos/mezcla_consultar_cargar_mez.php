<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

//extract($_POST);
$cad_fase = mysqli_query($cnx, "SELECT x.mez_nombre
FROM mezclas as x
INNER JOIN preparacion_etapas_mezclas as m on (x.mez_id = m.mez_id)
WHERE m.pep_id = '".$_GET['id']."' ") or die(mysql_error()."Error: en consultar las etapas");
$reg_fase = mysqli_fetch_assoc($cad_fase);
$tot_fase = mysqli_num_rows($cad_fase);
?> 

					<div id="main">
						<div class="col-md-5">
						<table border="1">
							<tbody>
								<tr bgcolor="#4AB5B9" style="color:#FFFFFF; font-weight:bold;" height="30">
									<th width="200">Mezcla</th>
								</tr>
							</tbody>
							<?php do{?>
								<tr>
									<td><?php echo $reg_fase['mez_nombre'] ?></td>
								</tr>
							<?php }while($reg_fase = mysqli_fetch_assoc($cad_fase));?>
						</table>
					</div>
					</div>