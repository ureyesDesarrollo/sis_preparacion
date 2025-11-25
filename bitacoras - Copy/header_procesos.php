<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
//$id_l = $_GET['id_l'];

//Obtiene el titulo de la bitacora
if ($id_tipo == '') {
	$cad_tit = mysqli_query($cnx, "SELECT p.pt_id, t.pt_descripcion, t.pt_revision  
							   FROM procesos as p
							   LEFT JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
							   WHERE p.pro_id = '$idx_pro'");
} else {
	$cad_tit = mysqli_query($cnx, "SELECT t.pt_descripcion, t.pt_revision  
							   FROM preparacion_tipo AS t
							   WHERE t.pt_id = '$id_tipo'");
}
$reg_tit = mysqli_fetch_array($cad_tit);

if (isset($reg_tit['pt_descripcion'])) {
	$tipo_proceso = $reg_tit['pt_descripcion'];
	$no_revision = $reg_tit['pt_revision'];
} else {
	$tipo_proceso = '';
	$no_revision = '';
}
?>
<div class="imagen-header">
	<p class="text-header">
		BITÁCORA DE PREPARACIÓN <?php echo $tipo_proceso . " " . $no_revision; ?>
	</p>
</div>