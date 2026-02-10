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
		$("#form").submit(function() {
			var formData = $(this).serialize();
			$.ajax({
				url: "materiales_obj_insertar.php",
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
	/*function fnc_baja(id){
		var respuesta = confirm("¿Deseas dar de baja este registro?");
		if (respuesta){
		$.ajax({
		  url: 'tipo_proceso_baja.php',
		  data: 'id=' + id,
		  type: 'post',
		  success: function(result){
			data = JSON.parse(result);
			alertas("#alerta-errorProvBaja", 'Listo!', data["mensaje"], 1, true, 5000); 
			//$("#main").load("catalogos/proveedores_catalogo.php", 1000);
			setTimeout(location.reload(), 13000);//Revisa esta Ceci
		  }
		});
		 return false;
		}
	}
	*/
	/*Abrir Modal Editar*/
	function AbreModalEditar(id) {
		$.ajax({
			type: 'post',
			url: 'materiales_obj_editar.php',
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

	/*Abrir Modal Consultar*/
	/*function AbreModalConsultar(id)
	{ 
	 $.ajax({
	  type : 'post',
	  url : 'tipo_proceso_consultar.php', 
			data : {"hdd_id":id}, //Pass $id
			success : function(result){
			  $("#modalEditar").html(result);
			  $('#modalEditar').modal('show')
			}
		  });
	 return false;
	};*/

	function refresh() {
		location.reload();
	}
</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container">
	<div class="row">

		<div class="col-sm-12 col-md-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">Funciones</li>
					<li class="breadcrumb-item active" aria-current="page">Materiales Objetivo</li>
				</ol>
			</nav>
		</div>
		<!-- <div class="col-sm-12 col-md-3">
			<div class="alert alert-info hide" id="alerta-errorProvBaja" style="height: 40px;width: 280px;text-align: left;">
				<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
				<strong>Titulo</strong> &nbsp;&nbsp;
				<span> Mensaje </span>
			</div>
		</div> -->
		<div class="col-sm-6 col-md-2">
			<a class="iconos" href="formatos/listado_materiales_obj.php" target="_blank"><i class="fa-solid fa-print fa-2xl"></i>
				Imprimir</a>
		</div>
		<div class="col-sm-6 col-md-2">
			<a class="iconos" href="exportar/materiales_obj.php" target="_blank"><i class="fa-solid fa-file-excel fa-2xl"></i>
				Exp.excel</a>
			<!--<button type="submit" id="export_data" name="export_data" value="Export to excel" class="btn btn-info">Exportar a Excel</button>-->
		</div>
		<div class="col-sm-6 col-md-2">
			<a class="iconos" href="formatos/listado_entrada_materia_prima.php" target="_blank"><i class="fa-solid fa-file fa-2xl"></i>
				Entrada materia prima</a>
		</div>
		<?php if (fnc_permiso($_SESSION['privilegio'], 17, 'upe_agregar') == 1) { ?>
			<div class="col-sm-6 col-md-2">
				<a class="iconos" href="#" data-toggle="modal" data-target="#ModalAlta" data-whatever="@getbootstrap"><i class="fa-solid fa-square-plus fa-2xl"></i>
					Mat. Objetivo</a>
			</div>
		<?php } ?>
	</div>
	<?php include "materiales_obj_listado.php"; ?>

	<div class="modal fade" id="ModalAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<?php include "materiales_obj_alta.php"; ?>
	</div>

	<div class="modal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	</div>

</div>
<?php include "../generales/pie_pagina.php"; ?>