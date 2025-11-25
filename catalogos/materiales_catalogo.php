<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../generales/menu.php');
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php')
?>
<link rel="stylesheet" href="../css/estilos_catalogos.css">
<script>
	/*Manipular el formulario*/
	$(document).ready(function() {
		$("#formMat").submit(function() {
			var formData = $(this).serialize();
			$.ajax({
				url: "materiales_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {
					data = JSON.parse(result);
					if (data["mensaje"] === "Exito") {
						alertas("#alerta-errorMatAlta", 'Listo!', 'Registro guardado', 1, true, 5000);
						window.location.hash = '#catalogos/productos_add.php';
					} else {
						alertas("#alerta-errorMatAlta", 'Error!', data["mensaje"], 4, true, 5000);
					}
					$('#formMat').each(function() {
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
				url: 'materiales_baja.php',
				data: 'id=' + id,
				type: 'post',
				success: function(result) {
					data = JSON.parse(result);
					alertas("#alerta-errorMatBaja", 'Listo!', data["mensaje"], 1, true, 5000);
					//setTimeout(location.reload(), 1000);//Revisa esta Ceci
					setTimeout("location.reload()", 2000)
				}
			});
			return false;
		}
	}

	/*Abrir Modal Editar*/
	function fnc_abre_modal(id) {
		$.ajax({
			type: 'post',
			url: 'materiales_editar.php',
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

	/*cargar combos*/
	$(document).ready(function() {

		$("#cbx_tipo").change(function() {
			//alert("entra");
			$("#cbx_tipo option:selected").each(function() {
				est_id = $(this).val();

				$.post("extras/getCiudad.php", {
					est_id: est_id
				}, function(data) {

					$("#cbxCiudad").html(data);
				});
			});
		})
	});

	function refresh() {
		location.reload();
	}
</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container">
	<div class="alert alert-info hide" id="alerta-errorMatBaja" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top:90px;margin-bottom:0px;z-index: 10">
		<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
		<strong>Titulo</strong> &nbsp;&nbsp;
		<span> Mensaje </span>
	</div>
	<div class="col-md-5 col-sm-12">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="submenu_catalogos.php" style="font-size: 14px;color: #000">Catálogos</a></li>
				<li class="breadcrumb-item active" aria-current="page">Materiales</li>
				<!--<li class="breadcrumb-item " aria-current="page">Materiales</li>
				<li class="breadcrumb-item " aria-current="page">Origen materiales</li>-->

			</ol>
		</nav>
	</div>
	<div class="diviconos">
		<div class="col-sm-1 col-md-1">

		</div>
		<div class="col-sm-2 col-md-2">
			<?php if (fnc_permiso($_SESSION['privilegio'], 5, 'upe_listar') == 1) { ?>
				<a class="iconos" href="formatos/listado_materiales_disponible.php" target="_blank"><img src="../iconos/printer.png" alt="">
					Mat. Disponible</a>
		</div>
		<!-- <div class="col-sm-2 col-md-2">
			<a class="iconos" href="formatos/listado_materiales_disponible2.php" target="_blank"><img src="../iconos/printer.png" alt="">
				Mat. Disponible para baja</a>
		</div>-->
		<div class="col-sm-1 col-md-1">
			<a class="iconos" href="formatos/listado_materiales.php" target="_blank"><img src="../iconos/printer.png" alt="">
				Imprimir</a>
		</div>
		<div class="col-sm-1 col-md-1">
			<a class="iconos" href="exportar/materiales.php" target="_blank"><img src="../iconos/excel.png" alt="">
				Exp.excel</a>
			<!--<button type="submit" id="export_data" name="export_data" value="Export to excel" class="btn btn-info">Exportar a Excel</button>-->
		</div>
	<?php } ?>
	<?php if (fnc_permiso($_SESSION['privilegio'], 5, 'upe_agregar') == 1) { ?>
		<div class="col-sm-1 col-md-1">
			<a class="iconos" href="#" data-toggle="modal" data-target="#ModalAlta" data-whatever="@getbootstrap"><img src="../iconos/mat.png" alt="">
				Material</a>
		</div>
	<?php } ?>
	</div>
	<?php include "materiales_listado.php"; ?>

	<div class="modal fade" id="ModalAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<?php include "materiales_alta.php"; ?>
	</div>

	<div class="modal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	</div>

</div>
<?php include "../generales/pie_pagina.php";
?>