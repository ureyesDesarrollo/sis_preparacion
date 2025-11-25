<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
//include "../conexion/conexion.php";
//include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM materiales_tipo") or die(mysql_error()."Error: en consultar el tipo de material");
$registros = mysqli_fetch_assoc($cadena);

?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>

<script type="text/javascript">
  $(document).ready(function()
  {
   $('#tabla_lista_tipo').dataTable( { 
     "sPaginationType": "full_numbers"
   } );
 })
</script>

<div class="container" style="margin-top:60px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
 <table  cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_tipo">
  <thead>
    <tr align="center">
      <th>&nbsp;Clave&nbsp;</th>
      <th>&nbsp;Origen material&nbsp;</th>
      <th>&nbsp;Estatus&nbsp;</th>
      <th width="20">Editar</th>
      <th width="20">Baja</th>
    </tr>
  </thead>
  <tbody>
   <?php 
   $ren = 1;
   do{?>
    <tr height="20">
     <td align="center"><?php echo $registros['mt_id'] ?></td>
     <td><?php echo $registros['mt_descripcion'] ?></td>
     <td><?php if($registros['mt_est'] == 'A'){echo "Activo";}else{ echo "Baja";} ?></td>
     <td style="padding-left: 0px" align="center"><?php if(fnc_permiso($_SESSION['privilegio'], 8, 'upe_editar' ) == 1){?><a href="#" onClick="javascript:fnc_abre_modal(<?=$registros['mt_id']; ?>)"><img src="../iconos/editar.png"></a><?php }?></td>
     <td style="padding-left: 0px"><?php if(fnc_permiso($_SESSION['privilegio'], 8, 'upe_borrar' ) == 1){?><a href="javascript:fnc_baja(<?=$registros['mt_id']?>);"><img src="../iconos/borrar.png"/></a><?php }?></td>
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
  </tr>
<?php }?>
<tr>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
</tr>
</tfoot>
</table>
</div>