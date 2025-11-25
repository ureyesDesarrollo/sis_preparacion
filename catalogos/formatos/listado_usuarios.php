<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
/*Ajuste MC 13-09-2023 */
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM usuarios WHERE usu_id <> 1 ORDER BY usu_nombre") or die(mysql_error()."Error: en consultar el Usuario");
$registros = mysqli_fetch_assoc($cadena);
?>
<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<title>Listado de Usuarios</title>
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
			<td><h1>Listado de usuarios</h1></td>
		</tr>
		<tr></tr>
	</table>

</div>

<div class="tablecuerpo">
	

<table class="table table-bordered" style="width: 100%;margin-top:2rem">
	<thead>
	  <tr>
		<th>Clave</th>
		<th>Nombre</th>
		<th>Usuario</th>
		<th>Perfil</th>
		<th>Correo</th>
		<th>Estatus</th>
	  </tr>
	</thead>
	<tbody>
	<?php 
	$ren = 1;
	do{
		$cad_pfl = mysqli_query($cnx,"select up_nombre from usuarios_perfiles where up_id = '$registros[up_id]' ");
    	$reg_pfl =  mysqli_fetch_assoc($cad_pfl);?>
	  <tr>
	   <td><?php echo $registros['usu_id'] ?></td>
	   <td><?php echo $registros['usu_nombre'] ?></td>
	   <td><?php echo $registros['usu_usuario'] ?></td>
	   <td><?php echo $reg_pfl['up_nombre'] ?></td>
	   <td><?php echo $registros['usu_email'] ?></td>
	   <td><?php if($registros['usu_est'] == 'A'){echo "Activo";}else{ echo "Baja";} ?></td>
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
	<br>
	<br>	
	</div>
	
	<?php include "../../generales/pie_pagina_formato.php";?>
</body>
</html>