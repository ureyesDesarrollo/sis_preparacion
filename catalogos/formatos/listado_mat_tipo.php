<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM materiales_tipo order by mt_id asc") or die(mysql_error()."Error: en consultar el tipo de material");
$registros = mysqli_fetch_assoc($cadena);
?>
<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<title>Listado tipo de material</title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<center>
		<div class="tablehead">
			<table>
				<tr>
				<td><img src="../../imagenes/logo_progel_v3.png" style="width: 80px"></td>
					<td><h1>Listado origen de material</h1></td>
				</tr>
				<tr></tr>
			</table>
		</div>

		<div class="tablecuerpo">
		<table class="table table-bordered" style="width: 100%;margin-top:2rem">
			 <thead>
			    <tr>
			      <th>Clave</th>
			      <th>Origen material</th>
			      <th>Estatus</th>
			    </tr>
			  </thead>
			<tbody>
			<?php 
			$ren = 1;
			do{
			    ?>
				 <td style="padding-left: 5px"><?php echo $registros['mt_id'] ?></td>
			     <td><?php echo $registros['mt_descripcion'] ?></td>
			     <td><?php if($registros['mt_est'] == 'A'){echo "Activo";}else{ echo "Baja";} ?></td>
			   </tr>
			  <?php 
			  $ren += 1;
			  }while($registros = mysqli_fetch_assoc($cadena));?>	  
		  </tbody>
		   <tfoot>
		   <?php for($i=$ren; $i <= 40; $i++){?>

			<?php }?>
			</tfoot>
		</table>
		</div>
	</center>	
</div>
		
		<?php include "../../generales/pie_pagina_formato.php";?>
	</body>
	</html>