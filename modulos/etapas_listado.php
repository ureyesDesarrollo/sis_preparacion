<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../seguridad/user_seguridad.php');
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
 								FROM preparacion_etapas") or die(mysql_error()."Error: en consultar las etapas");
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
   $('#tabla_lista_etapas').dataTable( { 
     "sPaginationType": "full_numbers"
   } );
 })
</script>

<div class="container" style="margin-top:80px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
 <table  cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_etapas">
  <thead>
    <tr align="center">
      <th>&nbsp;Clave&nbsp;</th>
      <th>&nbsp;Descripcion Sistema&nbsp;</th>
	  <th>&nbsp;Nombre&nbsp;</th>
	  <th>&nbsp;Hr Ideal&nbsp;</th>
	  <th>&nbsp;Hr Maxima&nbsp;</th>
	  <th>&nbsp;Validación&nbsp;</th>
	  <th>&nbsp;Tipo&nbsp;</th>
	  <th>&nbsp;Rango Inicio&nbsp;</th>
	  <th>&nbsp;Rango Fin&nbsp;</th>
	  <!--<th>&nbsp;Control Lib&nbsp;</th>
	  <th>&nbsp;Control Ren&nbsp;</th>
	  <th>&nbsp;Control Mat&nbsp;</th>-->
      <th width="20">Editar</th>
	  <!--<th width="20">Mezcla</th>-->
    </tr>
  </thead>
  <tbody>
   <?php 
   $ren = 1;
   do{?>
    <tr height="20">
     <td><?php echo $registros['pe_id'] ?></td>
     <td><?php echo $registros['pe_descripcion'] ?></td>
	 <td><?php echo $registros['pe_nombre'] ?></td>
	 <td><?php echo $registros['pe_hr_ideal'] ?></td>
	 <td><?php echo $registros['pe_hr_maxima'] ?></td>
	 <td><?php if($registros['pe_hr_validacion'] == 0){echo "No";}else{echo "Si";} ?></td>
	 <td><?php if($registros['pe_tipo'] == 'P'){echo "Ph";} if($registros['pe_tipo'] == 'H'){echo "Hr";} if($registros['pe_tipo'] == 'C'){echo "Ce";} ?></td>
	 <td><?php echo $registros['pe_inicio'] ?></td>
	 <td><?php echo $registros['pe_fin'] ?></td>
	 <!--<td><?php if($registros['pe_control_lib'] == 0){echo "No";}else{echo "Si";} ?></td>
	 <td><?php if($registros['pe_control_renglon'] == 0){echo "No";}else{echo "Si";} ?></td>
	 <td><?php if($registros['pe_control_material'] == 0){echo "No";}else{echo "Si";} ?></td>-->
     <td style="padding-left: 0px" align="center"><?php if(fnc_permiso($_SESSION['privilegio'], 12, 'upe_editar' ) == 1){?><a href="#" onClick="javascript:fnc_abre_modal(<?=$registros['pe_id']; ?>)"><img src="../iconos/editar.png"></a><?php }?></td>
	 <!--<td style="padding-left: 0px" align="center"><?php if($registros['pe_control_material'] == 1){ if(fnc_permiso($_SESSION['privilegio'], 12, 'upe_editar' ) == 1){?><a href="#" onClick="javascript:fnc_abre_modal_mat(<?=$registros['pe_id']; ?>)"><img src="../iconos/editar.png"></a><?php }}else{echo "";}?></td>-->
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
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<!--<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>-->
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
  <th></th>
  <th></th>
  <th></th>
  <!--<th></th>
  <th></th>
  <th></th>
  <th></th>-->
</tr>
</tfoot>
</table>
</div>