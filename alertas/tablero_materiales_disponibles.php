<?php 
/*Desarrollado por: CCA Consultores TI*/
/*Contacto: mc.munoz.rz@gmail.com */
/*19 - Nov - 2021*/

require_once('conexion/conexion.php');
  
//Obtiene los materiales
$cad_mat = mysqli_query($cnx, "SELECT * FROM materiales WHERE mat_est = 'A' ") or die(mysql_error()."Error: en consultar los procesos asignados");
$reg_mat = mysqli_fetch_assoc($cad_mat);  							   
?>

<div class="col-md-12" style="margin-top: 20px">
 <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Material</th>
      <th scope="col">Kilos</th>
      <th scope="col">Fecha más antigua</th>
	</tr>
   </thead>
   <tbody>
   <?php 
   $flt_kg = 0;
   do{ 
   
   $cad_kg = mysqli_query($cnx, "SELECT sum(inv_kg_totales) as res
								 FROM inventario 
								 WHERE mat_id = '$reg_mat[mat_id]' and inv_tomado = 0") or die(mysql_error()."Error: en consultar los paletos");
   $reg_kg = mysqli_fetch_assoc($cad_kg);
   
   $cad_fe = mysqli_query($cnx, "SELECT inv_fecha 
								 FROM inventario 
								 WHERE mat_id = '$reg_mat[mat_id]' and inv_tomado = 0
								 ORDER BY inv_fecha ASC") or die(mysql_error()."Error: en consultar los procesos asignados");
   $reg_fe = mysqli_fetch_assoc($cad_fe);
   
   ?>
	<tr>
      <th scope="row"><?php echo $reg_mat['mat_nombre'];?></th>
	  <th scope="row" style="text-align:right"><?php echo $reg_kg['res'];?></th>
	  <th scope="row"><?php echo $reg_fe['inv_fecha'];?></th>
	</tr>

   <?php
	$flt_kg += $reg_kg['res'];
   }while($reg_mat = mysqli_fetch_assoc($cad_mat));?>
   </tbody>
   <tr>
		  <th scope="row">TOTAL</th>
		  <th scope="row" style="text-align:right"><?php echo $flt_kg;?></th>
		  <th scope="row"></th>
		</tr>
 </table>
</div>