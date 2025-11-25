<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
require_once('../../conexion/conexion.php');
include('../../seguridad/user_seguridad.php');
include('../../funciones/funciones.php');
require '../funciones_procesos.php';
$cnx =  Conectarse();



$idx_pro = $_GET['idx_pro'];

$cad_tit = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor   
							   FROM procesos as p
							   LEFT JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
							   WHERE p.pro_id = '$idx_pro' ");
$reg_tit = mysqli_fetch_array($cad_tit);

$id_oper = $reg_tit['pro_operador'];
$id_super = $reg_tit['pro_supervisor'];
$id_tipo = $reg_tit['pt_id'];

//$idx_pro = $reg_tit['pro_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Bitacora</title>
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../../css/estilos_proceso.css">
	<script src="../../js/jquery.min.js"></script>
	<script type="text/javascript" src="../../js/alerta.js"></script>
	<script src="../../js/bootstrap.min.js"></script>
	<style>
		@page {
		    size: A4;
		}
	</style>
</head>
<body>
	
	<div class="container encabezado">
		<?php 
			include "../header_procesos.php";
		 ?>
	</div>
	
	<div class="container">	
		<?php include "encabezado.php";
		$cad_et = mysqli_query($cnx, "SELECT e.pe_archivo, e.pe_id  
							FROM preparacion_tipo_etapas as t
							INNER JOIN preparacion_etapas As e on (t.pe_id = e.pe_id)
							WHERE pt_id = '$id_tipo'
							ORDER BY pte_orden ASC ");
		$reg_et = mysqli_fetch_array($cad_et);
		$tot_et = mysqli_num_rows($cad_et);

		if($tot_et != 0)
		{
			do
			{
				include ($reg_et['pe_archivo']);
			}while($reg_et = mysqli_fetch_array($cad_et));
		}
		
		?>	
	</div>
	
</body>
</html>
