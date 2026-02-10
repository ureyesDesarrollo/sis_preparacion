<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
//$id_l = $_GET['id_l'];
$cnx =  Conectarse();
//Obtiene el titulo de la bitacora
if($id_tipo == '')
{
$cad_tit = mysqli_query($cnx, "SELECT p.pt_id, t.pt_descripcion, t.pt_revision  
							   FROM procesos as p
							   LEFT JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
							   WHERE p.pro_id = '$idx_pro'");
}
else
{
$cad_tit = mysqli_query($cnx, "SELECT t.pt_descripcion, t.pt_revision  
							   FROM preparacion_tipo AS t
							   WHERE t.pt_id = '$id_tipo'");
}
$reg_tit = mysqli_fetch_array($cad_tit);




$htmlHeader = '<table style="background:#D61107; height:50px;color:#fff;width:100%;text-align:center">
				<tr>
				<td colspan="9">BITÁCORA DE PREPARACIÓN'.$reg_tit['pt_descripcion'].' '.$reg_tit['pt_revision'].'</td>
				</tr>
			</table>';

 ?>

