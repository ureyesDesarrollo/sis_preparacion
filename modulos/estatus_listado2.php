<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

$cadena = mysqli_query($cnx, "SELECT p.*, l.le_estatus
 								FROM preparacion_paletos as p
								INNER JOIN listado_estatus as l on (p.le_id = l.le_id)") or die(mysql_error()."Error: en consultar los lavadores");
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
   $('#tabla_lista_etapas2').dataTable( { 
     "sPaginationType": "full_numbers"
   } );
 })
</script>

<div class="container" style="margin-top:20px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
 <table  cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_etapas2">
  <thead>
    <tr align="center">
      <th>&nbsp;Clave&nbsp;</th>
      <th>&nbsp;Descripcion&nbsp;</th>
	  <th>&nbsp;Estatus&nbsp;</th>
      <th width="20">Editar</th>
	  <!--<th width="20">Asignar</th>-->
    </tr>
  </thead>
  <tbody>
   <?php 
   $ren = 1;
   do{?>
    <tr height="20">
     <td><?php echo $registros['pp_id'] ?></td>
     <td><?php echo $registros['pp_descripcion'] ?></td>
	 <td><?php echo mb_convert_encoding($registros['le_estatus'],"UTF-8") ?></td>
     <td style="padding-left: 0px" align="center"><?php 
	 
	 if(fnc_permiso($_SESSION['privilegio'], 13, 'upe_editar' ) == 1)
	 {?>
	 <?php if($registros['pp_id'] == 1 or $registros['pp_id'] == 2)
			{ 
				if($registros['le_id'] == 1 and $_SESSION['privilegio'] == 6)
				{
	 ?>
					<a href="#" onClick="javascript:fnc_abre_modal3(<?=$registros['pp_id']; ?>)"><img src="../iconos/editar.png"></a>
	 <?php 
					//}
					//else{echo "-";}
				}
				else
				{?>
					<a href="#" onClick="javascript:fnc_abre_modal2(<?=$registros['pp_id']; ?>)"><img src="../iconos/editar.png"></a>
				<?php 
				}
			}
			else
			{?>
				<a href="#" onClick="javascript:fnc_abre_modal2(<?=$registros['pp_id']; ?>)"><img src="../iconos/editar.png"></a>
	 <?php  }	
	 }?></td>
	 <!--<td>
	 <?php
	 /*if($registros['le_id'] == 1 and $_SESSION['privilegio'] == 6 and ($registros['pp_id'] == 1 or $registros['pp_id'] == 2))
	 {
	 ?>
		<a href="#" onClick="javascript:fnc_abre_modal3(<?=$registros['pp_id']; ?>)"><img src="../iconos/disp.png"></a>
	 <?php 
	 }
	 else
	 {
		 echo "-";
	 }*/
	 ?>
	 </td>-->
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
	<!--<td>&nbsp;</td>-->
  </tr>
<?php }?>
<tr>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
  <!--<th></th>-->
</tr>
</tfoot>
</table>
</div>