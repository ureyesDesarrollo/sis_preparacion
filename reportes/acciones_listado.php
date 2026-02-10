<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT b.*, m.bm_descripcion from bitacora_acciones as b 
							inner join bitacora_modulos as m on (b.bm_id = m.bm_id) 
							ORDER BY b.ba_id DESC LIMIT 0,500") or die(mysqli_error($cnx)."Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);

?>
<!--formato de tablas -->
  <link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
  <script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
	<link rel="stylesheet" href="../js/jquery-ui.css" />
	<script src="../js/jquery-ui.js"></script>


  <script type="text/javascript">
<!-- paginación de tabla-->
$(document).ready(function()
{
	$('#tabla_inventario').dataTable( { 
	"sPaginationType": "full_numbers"
	} );
})
  </script>

<div class="container" style="margin-top:60px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
   <table  cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_inventario">
            <thead>
              <tr align="center">
			  	<th>&nbsp;Clave&nbsp;</th>
                <th>&nbsp;Usuario&nbsp;</th>
                <th>&nbsp;Fecha&nbsp;</th>
                <th>&nbsp;Modulo&nbsp;</th>
				<th>&nbsp;Acción&nbsp;</th>
                <th>&nbsp;Valor&nbsp;</th>
              </tr>
            </thead>
            <tbody>
			<?php 
			$ren = 1;
			do{?>
              <tr height="20">
               <td align="center"><?php echo $registros['ba_id'] ?></td>
               <td><?php echo fnc_nom_usuario($registros['usu_id']); ?></td>
               <td><?php echo $registros['ba_fecha'] ?></td>
               <td><?php echo $registros['bm_descripcion'] ?></td>
               <td><?php echo fnc_nom_accion($registros['ba_accion']); ?></td>
			   <td><?php echo $registros['ba_valor'] ?></td>
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
			</tr>
			<?php }?>
              <tr>
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