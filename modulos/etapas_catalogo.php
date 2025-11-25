<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../generales/menu.php');
?>

<link rel="stylesheet" href="../css/estilos_catalogos.css">
<script>
	/*Abrir Modal Editar*/
	function fnc_abre_modal(id) {
		$.ajax({
			type: 'post',
			url: 'etapas_editar.php',
			data: {
				"hdd_id": id
			}, //Pass $id
			success: function(result) {
				$("#modalEtapaEditar").html(result);
				$('#modalEtapaEditar').modal('show')
			}
		});
		return false;
	};

	function fnc_abre_modal_param(id) {
		$.ajax({
			type: 'post',
			url: 'etapas_editar_param.php',
			data: {
				"hdd_id": id
			}, //Pass $id
			success: function(result) {
				$("#modalEtapaEditar").html(result);
				$('#modalEtapaEditar').modal('show')
			}
		});
		return false;
	};

	function fnc_abre_modal_mat(id) {
		$.ajax({
			type: 'post',
			url: 'etapas_editar_mat.php',
			data: {
				"hdd_id": id
			}, //Pass $id
			success: function(result) {
				$("#modalEtapaEditar").html(result);
				$('#modalEtapaEditar').modal('show')
			}
		});
		return false;
	};

	function refresh() {
		location.reload();
	}
</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#parametros_base">Parametros fases</a></li>
		<li><a data-toggle="tab" href="#parametros_material">Parametros alertas </a></li>
	</ul>
	<div class="alert alert-info hide" id="alerta-errorTipoBaja" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top:90px;margin-bottom:0px;z-index: 10">
		<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
		<strong>Titulo</strong> &nbsp;&nbsp;
		<span> Mensaje </span>
	</div>
	<div class="row" style="margin-top: 50px;margin-bottom: -60px">
		<div class="col-md-5 col-sm-12">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="submenu_funciones.php" style="font-size: 14px;color: #000">Funciones</a></li>
					<li class="breadcrumb-item active" aria-current="page">Preparación fases</li>
					<!--<li class="breadcrumb-item " aria-current="page">mat_tipo</li>
				<li class="breadcrumb-item " aria-current="page">Tipo mat_tipo</li>-->

				</ol>
			</nav>
		</div>
	</div>

	<div class="tab-content">
		<div id="parametros_base" class="tab-pane fade in active">
			<?php include "etapas_listado.php"; ?>
		</div>
		<div id="parametros_material" class="tab-pane fade">
			<?php include "etapas_listado_param.php"; ?>
		</div>

		<div class="modal fade" id="ModalAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<?php //include "mat_tipo_alta.php";
			?>
		</div>

		<div class="modal" id="modalEtapaEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
		</div>

	</div>
	<?php include "../generales/pie_pagina.php"; ?>