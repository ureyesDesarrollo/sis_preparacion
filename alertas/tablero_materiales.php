<?php 
/*Desarrollado por: CCA Consultores TI*/
/*Contacto: mc.munoz.rz@gmail.com */
/*19 - Nov - 2021*/

require_once('conexion/conexion.php');
  
//Obtiene los procesos de paletos ejecutandose actualmente

$cad_procesos = mysqli_query($cnx, "SELECT x.prop_id, x.pp_id   
						   FROM procesos_paletos as x 
						   WHERE  x.prop_estatus = 1 and x.pp_id > 2
						   ORDER BY x.pp_id") or die(mysql_error()."Error: en consultar el proces");
$reg_procesos = mysqli_fetch_assoc($cad_procesos);

//Obtiente los procesos de lavadores
$cad_pro_lava = mysqli_query($cnx, "SELECT p.pro_id, p.pl_id    
						   FROM procesos as p
						   WHERE p.pro_estatus = 1 and pl_id <> 0
						   ORDER BY p.pl_id");
$reg_pro_lava = mysqli_fetch_array($cad_pro_lava);
  							   
?>

<div class="col-md-12" style="margin-top: 20px">
 <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Paleto</th>
      <th scope="col">S-proceso</th>
      <th scope="col">Lavador</th>
	  <th scope="col">Proceso</th>
	  <th scope="col">Tipo de Material</th>
      <th scope="col">Kilos</th>
	  <th scope="col">Fe. Entrada</th>
	</tr>
   </thead>
   <tbody>
   <?php 
   $flt_kg = 0;
   do{ 
   
   $cad_pal = mysqli_query($cnx, "SELECT pp_descripcion FROM preparacion_paletos WHERE pp_id = '$reg_procesos[pp_id]' ") or die(mysql_error()."Error: en consultar los paletos");
   $reg_pal = mysqli_fetch_assoc($cad_pal);
   
   //Lavadores asociados a un proceso de paletos
   
   $cad_pro_pal = mysqli_query($cnx, "SELECT pro_id FROM procesos_paletos_d WHERE prop_id = '$reg_procesos[prop_id]' ") or die(mysql_error()."Error: en consultar los procesos asignados");
   $reg_pro_pal = mysqli_fetch_assoc($cad_pro_pal);
   
   ?>
	<tr>
      <th scope="row"><?php echo $reg_pal['pp_descripcion'];?></th>
	  <th scope="row"><?php echo $reg_procesos['prop_id'];?></th>
	  <th scope="row">-</th>
	  <th scope="row">-</th>
	  <th scope="row">-</th>
	  <th scope="row">-</th>
	  <th scope="row">-</th>
	</tr>
	<?php do {
		
		$cad_pro_lav = mysqli_query($cnx, "SELECT pl_id FROM procesos WHERE pro_id = '$reg_pro_pal[pro_id]' ") or die(mysql_error()."Error: en consultar los procesos asignados");
		$reg_pro_lav = mysqli_fetch_assoc($cad_pro_lav);
   
		$cad_lav = mysqli_query($cnx, "SELECT pl_descripcion FROM preparacion_lavadores WHERE pl_id = '$reg_pro_lav[pl_id]' ") or die(mysqli_error($cnx)."Error: en consultar los paletos");
		$reg_lav = mysqli_fetch_assoc($cad_lav);
		
		//Consulta los materiales de los procesos
		$cad_pro_mat = mysqli_query($cnx, "SELECT * FROM procesos_materiales WHERE pro_id = '$reg_pro_pal[pro_id]' ") or die(mysql_error()."Error: en consultar los procesos asignados");
		$reg_pro_mat = mysqli_fetch_assoc($cad_pro_mat);
		?>
		<tr>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		  <th scope="row"><?php echo $reg_lav['pl_descripcion'];?></th>
		  <th scope="row"><?php echo $reg_pro_pal['pro_id'];?></th>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		</tr>
		
		<?php do{ 
		$cad_mat = mysqli_query($cnx, "SELECT mat_nombre FROM materiales WHERE mat_id = '$reg_pro_mat[mat_id]' ") or die(mysqli_error($cnx)."Error: en consultar los paletos");
		$reg_mat = mysqli_fetch_assoc($cad_mat);
		?>
		<tr>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		  <th scope="row"><?php echo $reg_mat['mat_nombre'];?></th>
		  <th scope="row" style="text-align:right"><?php echo $reg_pro_mat['pma_kg'];?></th>
		  <th scope="row"><?php echo $reg_pro_mat['pma_fe_entrada'];?></th>
		</tr>
		<?php 
		$flt_kg += $reg_pro_mat['pma_kg'];
		}while($reg_pro_mat = mysqli_fetch_assoc($cad_pro_mat));?>
		
	   <?php }while($reg_pro_pal = mysqli_fetch_assoc($cad_pro_pal));?>
   <?php }while($reg_procesos = mysqli_fetch_assoc($cad_procesos));?>
   <!-- ***************Lavadores*************** -->
   <?php 
   do{    ?>
	<!--<tr>
      <th scope="row">-</th>
	  <th scope="row">-</th>
	  <th scope="row">-</th>
	  <th scope="row">-</th>
	  <th scope="row">-</th>
	  <th scope="row">-</th>
	  <th scope="row">-</th>
	</tr>-->
	<?php do {
		
		$cad_pro_lav = mysqli_query($cnx, "SELECT pl_id FROM procesos WHERE pro_id = '$reg_pro_lava[pro_id]' ") or die(mysql_error()."Error: en consultar los procesos asignados");
		$reg_pro_lav = mysqli_fetch_assoc($cad_pro_lav);
   
		$cad_lav = mysqli_query($cnx, "SELECT pl_descripcion FROM preparacion_lavadores WHERE pl_id = '$reg_pro_lava[pl_id]' ") or die(mysqli_error($cnx)."Error: en consultar los paletos");
		$reg_lav = mysqli_fetch_assoc($cad_lav);
		
		//Consulta los materiales de los procesos
		$cad_pro_mat = mysqli_query($cnx, "SELECT * FROM procesos_materiales WHERE pro_id = '$reg_pro_lava[pro_id]' ") or die(mysql_error()."Error: en consultar los procesos asignados");
		$reg_pro_mat = mysqli_fetch_assoc($cad_pro_mat);
		?>
		<tr>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		  <th scope="row"><?php echo $reg_lav['pl_descripcion'];?></th>
		  <th scope="row"><?php echo $reg_pro_pal['pro_id'];?></th>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		</tr>
		
		<?php do{ 
		$cad_mat = mysqli_query($cnx, "SELECT mat_nombre FROM materiales WHERE mat_id = '$reg_pro_mat[mat_id]' ") or die(mysqli_error($cnx)."Error: en consultar los paletos");
		$reg_mat = mysqli_fetch_assoc($cad_mat);
		?>
		<tr>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		  <th scope="row">-</th>
		  <th scope="row"><?php echo $reg_mat['mat_nombre'];?></th>
		  <th scope="row" style="text-align:right"><?php echo $reg_pro_mat['pma_kg'];?></th>
		  <th scope="row"><?php echo $reg_pro_mat['pma_fe_entrada'];?></th>
		</tr>
		<?php 
		$flt_kg += $reg_pro_mat['pma_kg'];
		}while($reg_pro_mat = mysqli_fetch_assoc($cad_pro_mat));?>
		
	   <?php }while($reg_pro_pal = mysqli_fetch_assoc($cad_pro_pal));?>
   <?php }while($reg_pro_lava = mysqli_fetch_array($cad_pro_lava));?>
   </tbody>
   <tr>
		  <th scope="row"></th>
		  <th scope="row"></th>
		  <th scope="row"></th>
		  <th scope="row"></th>
		  <th scope="row">TOTAL</th>
		  <th scope="row" style="text-align:right"><?php echo $flt_kg;?></th>
		  <th scope="row"></th>
		</tr>
 </table>
</div>