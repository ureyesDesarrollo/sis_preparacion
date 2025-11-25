<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
require_once('../conexion/conexion.php');
include('../seguridad/user_seguridad.php');
include('../funciones/funciones.php');
require 'funciones_procesos.php';

$cnx =  Conectarse();



$id_p = $_GET['id_p'];
 
/*$cad_tit = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio   
							   FROM procesos as p
							   LEFT JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
							   WHERE p.pl_id = '$id_l' AND p.pro_estatus = 1 ");*/
							   
$cad_tit = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio, x.prop_id, x.prop_directo, x.pt_id as pt2   
							   FROM procesos as p
							   INNER JOIN procesos_paletos_d As d ON(p.pro_id = d.pro_id)
 							   INNER JOIN procesos_paletos as x ON(d.prop_id = x.prop_id)
							   INNER JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
							    
							   WHERE x.pp_id = '$id_p' AND x.prop_estatus = 1 ");//INNER JOIN preparacion_tipo AS z ON(x.pt_id = z.pt_id) , z.pt_para
$reg_tit = mysqli_fetch_array($cad_tit);

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
	//echo "Aqui";
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Bitacora</title>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../css/estilos_proceso.css">
	<script src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/alerta.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="funciones_js.js"></script>
	<script type="text/javascript">
		window.addEventListener("keypress", function(event)
		{
			if (event.keyCode == 13)
			{
				event.preventDefault();
			}
		}, false);

//Bloquear boton al agregar material
		function confirmEnviar() {

			formModalM.btn.disabled = true; 
			formModalM.btn.value = "Enviando...";

			setTimeout(function(){
				formModalM.btn.disabled = true;
				formModalM.btn.value = "Guardar";
			},2000);

			var statSend = false;
			return false;
		}

//Bloquear boton al dividir material
		function confirmEnviar2() {

			formModal.btn.disabled = true; 
			formModal.btn.value = "Enviando...";

			setTimeout(function(){
				formModal.btn.disabled = true;
				formModal.btn.value = "Guardar";
			},2000);

			var statSend = false;
			return false;
		}

		//Bloquear boton al dividir material
		function confirmEnviar4() {

			formModalR.btn.disabled = true; 
			formModalR.btn.value = "Enviando...";

			setTimeout(function(){
				formModalR.btn.disabled = true;
				formModalR.btn.value = "Guardar";
			},2000);

			var statSend = false;
			return false;
		}
	</script>
</head>
<body>
	
	<div class="container encabezado">
		<?php 
			include "header_procesos.php";
		 ?>
	</div>
	
	<div class="container">	
<?php 


if($_SESSION['privilegio'] == 4 and  $id_super == 0 ){ include "encabezado_1_pal.php"; }else if($_SESSION['privilegio'] == 4){
		if($strPara != 'M')
		{
			include "formatos/encabezado_pal.php";
		}
		else
		{ 
			include "formatos/encabezado_pal_mixto.php";
		}

}//Supervisor

if($_SESSION['privilegio'] == 3 and ($id_oper == 0 or $id_oper == ''))
{ 
	include "encabezado_2_pal.php"; //echo "se mete aca"; 
}else if($_SESSION['privilegio'] == 3)
	{
		if($strPara != 'M')
		{
			include "formatos/encabezado_pal.php";
		}
		else
		{ 
			include "formatos/encabezado_pal_mixto.php";
		}
			
	} //Operador

if($_SESSION['privilegio'] == 6)
{
		if($strPara != 'M')
		{
			include "formatos/encabezado_pal.php";
		}
		else
		{ 
			include "formatos/encabezado_pal_mixto.php";
		}
} //Laboratorio

//Dibuja las preparaciones segun el tipo de proceso que seleccione.
$cad_et = mysqli_query($cnx, "SELECT e.pe_archivo, e.pe_id  
							FROM preparacion_tipo_etapas as t
							INNER JOIN preparacion_etapas As e on (t.pe_id = e.pe_id)
							WHERE pt_id = '$id_tipo'
							ORDER BY pte_orden ASC ");
$reg_et = mysqli_fetch_array($cad_et);
$tot_et = mysqli_num_rows($cad_et);

//Muestra las etapas si el operador ya completo el registro
if($idx_pro > 0)
{
	if($strFech != '')
	{
		if($tot_et != 0)
		{
			do
			{	
				if($reg_et['pe_id'] != 17 and $reg_et['pe_id'] != 20 and $reg_et['pe_id'] != 21 and $reg_et['pe_id'] != 26)
				{
					$val = fnc_valida_etapa($idx_pro, $reg_et['pe_id']);
				}
				else
				{
					$val = fnc_valida_etapa_b($idx_pro, $reg_et['pe_id']);
				}
				
				if($val == 'Si')
				{
					//echo $reg_et['pe_archivo'];
					include ($reg_et['pe_archivo']);
				}
				else
				{
					//echo "formatos/".$reg_et['pe_archivo'];
					include "formatos/".$reg_et['pe_archivo'];
				}
			}while($reg_et = mysqli_fetch_array($cad_et));
		}
	}
	else{?>
		<div style="height: 40px;width: 490px;text-align: left;z-index: 10;margin-top:15px;float: right;margin-bottom: 0px;background: #e6e6;border-radius: 5px;padding: 10px;color: #BD223E;font-weight: bold;font-style: italic;float: left;">
		Debe completar la captura el operador antes de desplegar las etapas <?php echo $strFech; ?>
		</div>
	<?php }
}
?>
		
	</div>
	</body>
	</html>