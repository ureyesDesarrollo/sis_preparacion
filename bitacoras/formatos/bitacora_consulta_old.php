<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*21 - Agosto - 2018*/
require_once('../../conexion/conexion.php');
include('../../seguridad/user_seguridad.php');
include('../../funciones/funciones.php');
require '../funciones_procesos.php';
$cnx =  Conectarse();
$id_e = $_GET['id_e'];
//si no encuenta el proceso 
if (!isset($idx_pro)) {
	//desde listado bicoras
	if (isset($_GET['idx_pro'])) {
		//toma proceso seleccionado
		$idx_pro = $_GET['idx_pro'];

		$sql_procesos_g = mysqli_query($cnx, "SELECT pa_id
		FROM procesos_agrupados 
			  WHERE pro_id = '" . $idx_pro . "'
			  ") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
		$reg_procesos_g = mysqli_fetch_assoc($sql_procesos_g);

		//seleccina todos los procesos con el mismo datos agrupador que el proceso seleccionado
		$sql_procesos_a = mysqli_query($cnx, "SELECT pro_id
			FROM procesos_agrupados 
				  WHERE pa_id = '" . $reg_procesos_g['pa_id'] . "'
				  ORDER BY pro_id DESC ") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
		$reg_procesos_a = mysqli_fetch_assoc($sql_procesos_a);
		$pro_activo = '';
		do {
			$sql_procesos_eq = mysqli_query($cnx, "SELECT  pro_id
			FROM procesos_equipos as p
				  inner join equipos_preparacion as eq on (p.ep_id = eq.ep_id)
				  WHERE p.pro_id = '" . $reg_procesos_a['pro_id'] . "'  and p.pe_ban_activo = 1
				  ORDER BY  p.pro_id DESC ") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
			$reg_procesos_eq = mysqli_fetch_assoc($sql_procesos_eq);

			$pro_activo .= $reg_procesos_eq['pro_id'];
		} while ($reg_procesos_a = mysqli_fetch_assoc($sql_procesos_a));

		$idx_pro = $pro_activo;

		$cad_tit = mysqli_query($cnx, "SELECT *
							   FROM procesos as p
							   INNER JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
							   WHERE p.pro_id = '$pro_activo' ");
	} else {
		//Obtiene el proceso desde equipos tipo almacen

		$cad_pro = mysqli_query($cnx, "SELECT p.pro_id,p.pro_estatus   
		FROM procesos as p
		inner join procesos_equipos as e ON(p.pro_id = e.pro_id)
		WHERE e.ep_id = '" . $_GET['id_e'] . "' and e.pe_ban_activo = 1
		 ORDER BY e.ped_id DESC LIMIT 1");
		$reg_pro = mysqli_fetch_array($cad_pro);

		$cad_tipo_equipo = mysqli_query($cnx, "SELECT le_id FROM equipos_preparacion 
              WHERE ep_id = '$id_e'");
		$reg_tipo_equipo = mysqli_fetch_array($cad_tipo_equipo);

		/* if ($reg_tipo_equipo['ban_almacena'] == 'S') {
			$oculta_opciones = 'style="display:none"';
		} else {
			$oculta_opciones = '';
		} */

		//si el proceso esta como terminado y el equipo esta libre no muestres bitacoras
		if ($reg_pro['pro_estatus'] == 2 && $reg_tipo_equipo['le_id'] == 9) {
			$idx_pro = '';
			$oculta_bitacoras = 'style="display:none"';
		} else {
			$idx_pro = $reg_pro['pro_id'];
			$oculta_bitacoras = 'style="display:block"';
		}


		$cad_tit = mysqli_query($cnx, "SELECT *
		FROM procesos as p
		INNER JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
		WHERE p.pro_id = '" . $reg_pro['pro_id'] . "' ");
	}
}

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
	<script>
		//modal materiales
		function abre_modal_material(proceso) {
			var datos = {
				"pro_id_m": proceso,
			}
			$.ajax({
				type: 'post',
				//url: 'modal_procesos_materiales.php',
				url: "modal_procesos_materiales.php",
				data: datos,
				//data: {nombre:n},
				success: function(result) {
					$("#modal_procesos_materiales").html(result);
					$('#modal_procesos_materiales').modal('show')
				}
			});
			return false;
		}

		//modal equipos
		function abre_modal_equipos_bit(proceso) {
			var datos = {
				"pro_id_m": proceso,
			}
			$.ajax({
				type: 'post',
				url: 'modal_procesos_equipos.php',
				data: datos,
				//data: {nombre:n},
				success: function(result) {
					$("#modal_procesos_equipos").html(result);
					$('#modal_procesos_equipos').modal('show')
				}
			});
			return false;
		}

		//modal quimicos 
		function abre_modal_quimicos(proceso) {
			var datos = {
				"pro_id_m": proceso,
			}
			$.ajax({
				type: 'post',
				url: 'modal_procesos_quimicos.php',
				data: datos,
				//data: {nombre:n},
				success: function(result) {
					$("#modal_procesos_quimicos").html(result);
					$('#modal_procesos_quimicos').modal('show')
				}
			});
			return false;
		}
	</script>
</head>

<body>

	<div class="container encabezado">
		<?php
		include "../header_procesos.php";
		?>
	</div>

	<div class="container">

		<?php
		include "encabezado.php";
		/* } */
		$cad_et = mysqli_query($cnx, "SELECT e.pe_archivo, e.pe_id  
							FROM preparacion_tipo_etapas as t
							INNER JOIN preparacion_etapas As e on (t.pe_id = e.pe_id)
							WHERE pt_id = '$id_tipo'
							ORDER BY pte_orden ASC ");
		$reg_et = mysqli_fetch_array($cad_et);
		$tot_et = mysqli_num_rows($cad_et);
		echo '<div ' . $oculta_bitacoras . '>';
		if ($tot_et != 0) {
			do {
				include("../fases/formatos/" . $reg_et['pe_archivo']);
			} while ($reg_et = mysqli_fetch_array($cad_et));
		}
		echo '</div>';
		?>
	</div>

</body>

</html>