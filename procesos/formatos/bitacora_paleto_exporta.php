<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
require_once('../../conexion/conexion.php');
include('../../seguridad/user_seguridad.php');
include('../../funciones/funciones.php');
require '../funciones_procesos.php';
$cnx =  Conectarse();



$idx_prop = $_GET['idx_prop'];

$cad_tit = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio, x.prop_id, x.prop_directo, x.pt_id as pt2   
							   FROM procesos as p
							   INNER JOIN procesos_paletos_d As d ON(p.pro_id = d.pro_id)
 							   INNER JOIN procesos_paletos as x ON(d.prop_id = x.prop_id)
							   INNER JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 	    
							   WHERE x.prop_id = '$idx_prop' ");
$reg_tit = mysqli_fetch_array($cad_tit);

$id_oper = $reg_tit['pro_operador'];
$id_super = $reg_tit['pro_supervisor'];
$id_tipo = $reg_tit['pt_id'];

$id_oper = $reg_tit['pro_operador'];
$id_super = $reg_tit['pro_supervisor'];
//Establece el tipo de formato
if($reg_tit['pt2'] == '')
{
	$id_tipo = $reg_tit['pt_id'];
	$strPara = "";
}
else
{
	$id_tipo = $reg_tit['pt2'];
	$cad_para = mysqli_query($cnx, "SELECT pt_para   
							   FROM preparacion_tipo    
							   WHERE pt_id = '$reg_tit[pt2]' ");
	$reg_para = mysqli_fetch_array($cad_para);
	
	$strPara = $reg_para['pt_para'];
}


$strFech = $reg_tit['pro_fe_carga'];
$strHr = $reg_tit['pro_hr_inicio'];

$idx_pro = $reg_tit['pro_id'];
$idx_prop = $reg_tit['prop_id'];
$idx_directo = $reg_tit['prop_directo'];

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
		<?php 
		/*include "exportar_encabezado.php";*/
		if($strPara != 'M')
		{
			include "exportar_encabezado_pal.php";
		}
		else
		{ 
			include "exportar_encabezado_pal_mixto.php";
		}

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

?>	



	</div>
	
</body>
</html>
