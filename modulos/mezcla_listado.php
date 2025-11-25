<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM mezclas ") or die(mysql_error()."Error: en consultar las mezclas");
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
	$('#tabla_inventario').dataTable( { 
	"sPaginationType": "full_numbers"
	} );
})
  </script>

<div class="container" style="border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
   <table  cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_inventario">
            <thead>
              <tr align="center">
			  	<th>&nbsp;Clave&nbsp;</th>
                <th>&nbsp;Nombre&nbsp;</th>
				<th>&nbsp;Consultar&nbsp;</th>
				<th>&nbsp;Editar&nbsp;</th>
              </tr>
            </thead>
            <tbody>
			<?php 
			$ren = 1;
			do{

			?>
              <tr height="20">
               <td align="center"><?php echo $registros['mez_id'] ?></td>
               <td><?php echo $registros['mez_nombre']; ?></td>
               <td style="padding-left: 0px" align="center"><?php if($_SESSION['privilegio'] != 6 and $_SESSION['privilegio'] != 8){?><a href="#" onClick="javascript:AbreModalConsultar(<?=$registros['mez_id']; ?>)"><img src="../iconos/buscar.png"></a><?php }?></td>
			   <td style="padding-left: 0px" align="center"><?php if(fnc_permiso($_SESSION['privilegio'], 9, 'upe_editar' ) == 1){?><a href="#" onClick="javascript:AbreModalEditar(<?=$registros['mez_id']; ?>)"><img src="../iconos/editar.png"></a><?php }?></td>
                
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
              </tr>
            </tfoot>
        </table>
</div>