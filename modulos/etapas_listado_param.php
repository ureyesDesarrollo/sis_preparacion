<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
//include('../seguridad/user_seguridad.php');
//include "../conexion/conexion.php";
//include "../funciones/funciones.php";
//$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
 								FROM preparacion_etapas_param") or die(mysql_error()."Error: en consultar las etapas");
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
   $('#tabla_lista_etapas_param').dataTable( { 
     "sPaginationType": "full_numbers"
   } );
 })
</script>

<div class="container" style="margin-top:80px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
 <table  cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_etapas_param">
  <thead>
    <tr align="center">
      <th>&nbsp;Clave&nbsp;</th>
      <th>&nbsp;Descripcion<br>Sistema&nbsp;</th>
	  <th>&nbsp;Nombre&nbsp;</th>
	  <th>&nbsp;Tipo&nbsp;</th>
	  <th>&nbsp;Rango <br>Inicio&nbsp;</th>
	  <th>&nbsp;Rango <br>Fin&nbsp;</th>
	  <th>&nbsp;Control <br>Lib&nbsp;</th>
	  <th>&nbsp;Control <br>Ren&nbsp;</th>
	  <th>&nbsp;Control <br>Mat&nbsp;</th>
	  <th>&nbsp;Enviar<br>Email&nbsp;</th>
      <th width="20">Editar</th>
	  <th width="20">Mezcla</th>
    </tr>
  </thead>
  <tbody>
   <?php 
   $ren = 1;
   do{?>
    <tr height="20">
     <td><?php echo $registros['pe_id'] ?></td>
     <td><?php echo $registros['pep_descripcion'] ?></td>
	 <td><?php echo $registros['pep_nombre'] ?></td>
	 <td><?php echo $registros['pep_tipo'];?></td>
	 <td><?php echo $registros['pep_inicio'] ?></td>
	 <td><?php echo $registros['pep_fin'] ?></td>
	 <td><?php if($registros['pep_control_lib'] == 0){echo "No";}else{echo "Si";} ?></td>
	 <td><?php if($registros['pep_control_renglon'] == 0){echo "No";}else{echo "Si";} ?></td>
	 <td><?php if($registros['pep_control_material'] == 0){echo "No";}else{echo "Si";} ?></td>
	 <td><?php if($registros['pep_enviar_email'] == 0){echo "No";}else{echo "Si";} ?></td>
     <td style="padding-left: 0px" align="center"><?php if(fnc_permiso($_SESSION['privilegio'], 12, 'upe_editar' ) == 1){?><a href="#" onClick="javascript:fnc_abre_modal_param(<?=$registros['pep_id']; ?>)"><img src="../iconos/editar.png"></a><?php }?></td>
	 <td style="padding-left: 0px" align="center"><?php if($registros['pep_control_material'] == 1){ if(fnc_permiso($_SESSION['privilegio'], 12, 'upe_editar' ) == 1){?><a href="#" onClick="javascript:fnc_abre_modal_mat(<?=$registros['pep_id']; ?>)"><img src="../iconos/editar.png"></a><?php }}else{echo "";}?></td>
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
  <th></th>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
</tr>
</tfoot>
</table>
</div>