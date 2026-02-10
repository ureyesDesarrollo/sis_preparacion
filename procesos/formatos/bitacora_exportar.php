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

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=fases_exportar.xls");
header("Pragma: no-cache");
header("Expires: 0");

//$idx_pro = $reg_tit['pro_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Bitacora</title>
	<!--<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../../css/estilos_proceso.css">
	<script src="../../js/jquery.min.js"></script>
	<script type="text/javascript" src="../../js/alerta.js"></script>
	<script src="../../js/bootstrap.min.js"></script>-->
</head>
<body>
	
	<div class="container encabezado">
		<?php 
			include "header_procesos_exportar.php";
		 ?>
	</div>
	
	<div class="container">	
		<?php include "exportar_encabezado.php";
		$cad_et = mysqli_query($cnx, "SELECT e.pe_archivo_exp, e.pe_id  
							FROM preparacion_tipo_etapas as t
							INNER JOIN preparacion_etapas As e on (t.pe_id = e.pe_id)
							WHERE pt_id = '$id_tipo'
							ORDER BY pte_orden ASC ");
		$reg_et = mysqli_fetch_array($cad_et);
		$tot_et = mysqli_num_rows($cad_et);

/*		if($tot_et != 0)
		{
			do
			{
				include ($reg_et['pe_archivo_exp']);
			}while($reg_et = mysqli_fetch_array($cad_et));
		}*/


echo $htmlHeader;
echo $encabezado; 

		if($tot_et != 0)
		{
			do
			{
				include ($reg_et['pe_archivo_exp']);
			}while($reg_et = mysqli_fetch_array($cad_et));
		}

//echo $tbHtml1; 
/*echo $tbHtml2; 
echo $tbHtml2b; 
echo $tbHtml2c; 
echo $tbHtml3;
echo $tbHtml3b;  
echo $tbHtml4; 
echo $tbHtml4b;
echo $tbHtml4c;  
echo $tbHtml4d;  
echo $tbHtml5;
echo $tbHtml5b;
echo $tbHtml5c; 
echo $tbHtml5d;
echo $tbHtml5e;
echo $tbHtml6;
echo $tbHtml6b;
echo $tbHtml6c;
echo $tbHtml6d; 
echo $tbHtml7;
echo $tbHtml7b;
echo $tbHtml7c; 
echo $tbHtml7b; 
echo $tbHtml8;
echo $tbHtml8b;
echo $tbHtml8c;*/
?>	
	</div>
	
</body>
</html>
