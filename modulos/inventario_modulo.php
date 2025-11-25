<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include('../generales/menu.php');
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');
$perfil_autorizado = mysqli_query($cnx, "SELECT * FROM usuarios_perfiles WHERE up_id = '" . $_SESSION['privilegio'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el inventario 1");
$reg_autorizado = mysqli_fetch_assoc($perfil_autorizado);
?>

<link rel="stylesheet" href="../css/estilos_catalogos.css">
<script type="text/javascript" src="../js/alerta.js"></script>
<script>
	/*Manipular el formulario*/
	$(document).ready(function() {
		$("#form").submit(function() {
			var formData = $(this).serialize();
			$.ajax({
				url: "inventario_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {
					data = JSON.parse(result);
					//alert("Guardo el registro");
					alertas("#alerta-errorProvAlta", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#form').each(function() {
						this.reset();
					});
				}
			});
			return false;
		});
	});


	/*Para cambiar el estatus a B*/
	function fnc_baja(id) {
		var respuesta = confirm("¿Deseas dar de baja este registro?");
		if (respuesta) {
			$.ajax({
				url: 'proveedores_baja.php',
				data: 'id=' + id,
				type: 'post',
				success: function(result) {
					data = JSON.parse(result);
					alertas("#alerta-errorProvBaja", 'Listo!', data["mensaje"], 1, true, 5000);
					//$("#main").load("catalogos/proveedores_catalogo.php", 1000);
					//setTimeout(location.reload(), 1000);//Revisa esta Ceci
				}
			});
			return false;
		}
	}

	function fnc_enviar(id) {
		var respuesta = confirm("¿Deseas enviar este registro a maquila?");
		if (respuesta) {
			$.ajax({
				url: 'inventario_enviar.php',
				data: 'id=' + id,
				type: 'post',
				success: function(result) {
					data = JSON.parse(result);
					//alertas("#alerta-errorProvBaja", 'Listo!', data["mensaje"], 1, true, 5000); 
					//$("#main").load("inventario_listado_a_maquila.php", 1000);
					//setTimeout(location.reload(), 1000);//Revisa esta Ceci
					//location.reload("inventario_listado_a_maquila.php");
					//$("#listadoamaquila").load("inventario_listado_a_maquila2.php");
					refresh_envio_maq_ext();
				}
			});
			//return false;
		}
	}

	/*Abrir Modal que recibe en  Ext. en maquila*/
	function AbreModalRecibir(id, prv_recibe) { //alert(id);
		$.ajax({
			type: 'post',
			url: 'inventario_editar.php',
			data: {
				"hdd_id": id,
				"maquila": prv_recibe
			}, //Pass $id
			success: function(result) {
				$("#modalEditar").html(result);
				$('#modalEditar').modal('show');
			}
		});
		return false;
	};

	function AbreModalRecibir2(id) { //alert(id);
		$.ajax({
			type: 'post',
			url: 'inventario_editar_local.php',
			data: {
				"hdd_id": id
			}, //Pass $id
			success: function(result) {
				$("#modalEditar").html(result);
				$('#modalEditar').modal('show')
			}
		});
		return false;
	};


	function refresh_a_maquila() {

		//location.reload('#listadoamaquila','inventario_listado_a_maquila.php');
		//$('#listadoamaquila').load('inventario_listado_a_maquila.php');
		//$("#listadoamaquila").load("inventario_listado_a_maquila.php");
		cargar_inventario('#listadoamaquila', 'inventario_listado_a_maquila.php');

	}

	function cargar_inventario(div, desde) {
		$(div).load(desde);
	}

	function refresh() {
		location.reload();
	}


	//funcion para calcular kilos de porveedores locales
	function fnc_calculaTotal() {
		document.getElementById('txtKgTotales').value = (document.getElementById('txtKg').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDRendimiento').value - document.getElementById('txtDescarne').value;
	}

	//funcion para calcular kilos de porveedores locales
	function fnc_calculaTotalL() {
		document.getElementById('txtKgTotales').value = (document.getElementById('txtKg').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDRendimiento').value - document.getElementById('txtDescarne').value;
	}

	//funcion para calcular kilos de porveedores locales directo a maquila
	function fnc_calculaTotalM() {
		/*document.getElementById('txtKgTotales').value = (document.getElementById('txtKgEntradaMaq').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDRendimiento').value;*/

		document.getElementById('txtKgTotales').value = (document.getElementById('txtKg').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDRendimiento').value;
	}

	//funcion para calcular kilos de porveedores extrajeros
	function fnc_calculaTotalE() {
		document.getElementById('txtKgTotales').value = (document.getElementById('txtKg').value);
	}

	//funcion para calcular kilos de porveedores extrajeros(recibir de maq)
	function fnc_calculaTotalEM() {
		if (document.getElementById('txtKgEntradaMaq').value == '') {
			alert('Ingrese la cantidad de KG entrada Maq');
			document.getElementById('txtDAgua').value = '';
			document.getElementById('txtDescarne').value = '';
			document.getElementById('txtDRendimiento').value = '';
		} else {
			document.getElementById('txtKgTotales').value = (document.getElementById('txtKgEntradaMaq').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDescarne').value - document.getElementById('txtDRendimiento').value;
		}
	}

	//10-11-2021
	//valida KG entrada Maq sea mayor a kg entrada en maquila extranjero
	function valida_entrada() {
		var kg_maquila = parseFloat(document.getElementById('txtKgEntradaMaq').value);
		var kg_kilos = parseInt(document.getElementById('txtKg').value);

		if (kg_maquila < kg_kilos || kg_maquila == kg_kilos) {
			alert('La cantidad de KG entrada Maq debe ser mayor que KG Entrada');
			document.getElementById('txtKgEntradaMaq').value = '';
			document.getElementById('txtKgTotales').value = '';
		}
	}

	//valida KG entrada Maq sea mayor a KG Carga tambor en maquila local
	function valida_entrada_local() {
		var kg_maquila = parseInt(document.getElementById('txtKgEntradaMaq').value);
		var kg_tambor = parseInt(document.getElementById('txtKgLavador').value);
		if (kg_maquila < kg_tambor || kg_maquila == kg_tambor) {
			alert('La cantidad de KG entrada Maq debe ser mayor que KG Carga tambor');
			document.getElementById('txtKgEntradaMaq').value = '';
			document.getElementById('txtKgTotales').value = '';
		}
	}

	function kilos_pendientes_maquila_loc() {
		var kilos = document.getElementById('txtKg').value;
		var kilos_tambor = document.getElementById('txtKgLavador').value;

		document.getElementById('txt_pendientes').value = kilos - kilos_tambor;
	}

	function valida_ticket() {
		var ticket = document.getElementById("txtNoTicket").value;

		$.ajax({
			url: "get_ticket.php",
			type: 'POST',
			data: {
				"ticket": ticket,
			},
			success: function(result) {
				if (result != '') {
					data = JSON.parse(result);
					alertas("#alerta-ticket", '', data["mensaje"], 4, true, 5000);
					document.getElementById('txtNoTicket').value = '';
					document.getElementById('alerta-ticket').style.display = 'block';

				}
			}
		});
		return false;
	}

	function costos_inventario(inv_id, mat_id, prv_id) {
		$.ajax({
			type: 'post',
			url: 'modal_costos_inventario.php',
			data: {
				"inv_id": inv_id,
				"mat_id": mat_id,
				"prv_id": prv_id,
			}, //Pass $id
			success: function(result) {
				$("#modal_costos_inventario").html(result);
				$('#modal_costos_inventario').modal('show')
			}
		});
		return false;
	};
</script>
<style>
	table {
		font-size: 14px;
	}
</style>
<div class="container-fluid" style="margin: 3rem;">
	<div class="">
		<!--LISTADO DE INVENTARIO-->
		<div class="tab-content">
			<div class="row">
				<ul class="nav nav-tabs">
					<?php if ($_SESSION['privilegio'] != 18) { ?>
						<li class="active"><a data-toggle="tab" href="#inventario_modulo">Movimiento inventario día</a></li>
						<li><a data-toggle="tab" href="#listadoamaquila">Inventario extranjero</a></li>
						<li><a data-toggle="tab" href="#listadoenmaquila">Inventario extranjero en maquila</a></li>
					<?php } ?>
					<!-- <li><a data-toggle="tab" href="#list_loc_enmaquila">Loc en maquila</a></li> -->
					<?php if ($_SESSION['privilegio'] != 11) { ?>
						<li><a data-toggle="tab" href="#listadohistorial">Historial inventario</a></li>
						<?php if ($_SESSION['privilegio'] != 18) { ?>
							<li><a data-toggle="tab" href="#historialamaquila">Hist. inventario extranjero</a></li>
						<?php } ?>
						<!-- <li><a data-toggle="tab" href="#historial_loc_maquila">Hist. loc maquila</a></li> -->
					<?php } ?>
				</ul>
			</div>

			<div id="inventario_modulo" class="tab-pane fade in active">
				<?php include "inventario_listado.php"; ?>

				<div class="modal fade" id="ModalAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<?php include "inventario_alta.php"; ?>
				</div>
			</div>

			<!--LISTADO A MAQUILA-->
			<div id="listadoamaquila" class="tab-pane fade">
				<?php include "inventario_listado_a_maquila.php"; ?>
			</div>

			<!--LISTADO EN MAQUILA-->
			<div id="listadoenmaquila" class="tab-pane fade">
				<?php include "inventario_listado_en_maquila.php"; ?>
			</div>

			<!--LISTADO HISTORIAL-->
			<div id="listadohistorial" class="tab-pane fade">
				<?php include "inventario_historial.php"; ?>
			</div>

			<!--LISTADO A MAQUILA HISTORIAL-->
			<div id="historialamaquila" class="tab-pane fade">
				<?php include "inventario_historial_a_maquila.php"; ?>
			</div>

			<!--LISTADO EN MAQUILA LOCAL-->
			<div id="list_loc_enmaquila" class="tab-pane fade">
				<?php include "inventario_list_loc_en_maquila.php"; ?>
			</div>

			<!--LISTADO A MAQUILA HISTORIAL LOCAL-->
			<div id="historial_loc_maquila" class="tab-pane fade">
				<?php include "inventario_hist_loc_a_maquila.php"; ?>
			</div>
			<div class="modal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			</div>

			<div class="modal" id="modal_costos_inventario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			</div>
		</div>
	</div>
</div>
<?php include "../generales/pie_pagina.php"; ?>
<!-- <style>
	.fa-solid {
		padding-left: 3rem;
	}
</style> -->
<script>
	/* 	document.addEventListener("DOMContentLoaded", function() {
		// Verifica si hay un hash en la URL al cargar la página
		if (window.location.hash) {
			var hash = window.location.hash;
			var tabElement = document.querySelector('a[href="' + hash + '"]');
			if (tabElement) {
				tabElement.click(); // Activa la pestaña correspondiente
			}
		}

		// Añade un evento a los enlaces de las pestañas para actualizar la URL
		var tabLinks = document.querySelectorAll('a[data-toggle="tab"]');
		tabLinks.forEach(function(tabLink) {
			tabLink.addEventListener('click', function() {
				history.replaceState(null, null, tabLink.getAttribute('href'));
			});
		});
	});
 */

	document.addEventListener("DOMContentLoaded", function() {
		// Función para activar la pestaña según el hash en la URL
		function activateTabFromHash() {
			if (window.location.hash) {
				var hash = window.location.hash;
				var tabElement = document.querySelector('a[href="' + hash + '"]');
				if (tabElement) {
					tabElement.click(); // Activa la pestaña correspondiente
				}
			}
		}

		// Activar la pestaña al cargar la página si hay un hash en la URL
		activateTabFromHash();

		// Añadir un evento a los enlaces de las pestañas para actualizar la URL
		var tabLinks = document.querySelectorAll('a[data-toggle="tab"]');
		tabLinks.forEach(function(tabLink) {
			tabLink.addEventListener('click', function() {
				history.replaceState(null, null, tabLink.getAttribute('href'));
			});
		});

		// Escuchar cambios en el hash y activar la pestaña correspondiente
		window.addEventListener('hashchange', activateTabFromHash);
	});
</script>