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
		$("#formUusuarioAlta").submit(function() {
			var formData = $(this).serialize();

			var texoUser = $("input#txtUser").val();

			$.ajax({
				url: "extras/getUsuario.php",
				type: 'POST',
				data: 'txtUser=' + texoUser,
				success: function(result) {
					if (result != '') {

						// Mostrar el resultado
						$("#resultadoBusqueda").html(result);
						$("#resultadoBusqueda").show(); // Mostrar el div

						// Ocultar el resultado después de 5 segundos
						setTimeout(function() {
							$("#resultadoBusqueda").hide(); // Ocultar el div
						}, 5000); // 5000 milisegundos = 5 segundos


						document.getElementById("txtUser").value = "";
						document.getElementById("txtUser").focus();
					} else {
						$.ajax({
							url: "usuarios_insertar.php",
							type: 'POST',
							data: formData,
							success: function(result) {
								data = JSON.parse(result);
								//alert("Guardo el registro");
								alertas("#alerta-errorProvAlta", 'Listo!', data["mensaje"], 1, true, 5000);
								$('#formUusuarioAlta').each(function() {
									this.reset();
								});
							}
						});
						return false;
					}
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
				url: 'usuarios_baja.php',
				data: 'id=' + id,
				type: 'post',
				success: function(result) {
					data = JSON.parse(result);
					alertas("#alerta-errorUsuarioBaja", 'Listo!', data["mensaje"], 1, true, 5000);
					//setTimeout(location.reload(), 1000);//Revisa esta Ceci
					setTimeout("location.reload()", 2000)
				}
			});
			//return false;
		}
	}

	/*Abrir Modal Editar*/
	function fnc_abre_modal(id) {
		$.ajax({
			type: 'post',
			url: 'usuarios_editar.php',
			data: {
				"hdd_id": id
			}, //Pass $id
			success: function(result) {
				$("#modalEditarUsuarios").html(result);
				$('#modalEditarUsuarios').modal('show')
			}
		});
		return false;
	};


	function refresh() {
		location.reload();
	}


	/*function valida(F) 
	{
		if(/^\s+|\s+$/.test(formUusuarioAlta.txtUser.value)) 
		{
			alert("Introduzca un cadena de texto.")
			return false
		} else {
			alert("OK")
			//cambiar la linea siguiente por return true para que ejecute la accion del formulario
			return true;
		}
	}

	function trim(){ 
		var c = document.formUusuarioAlta.txtUser.value;
		c = c.replace(/(^\s+|\s+$)/g,""); 
		document.formUusuarioAlta.txtUser.value = c;
		}*/

	/*function EliminarBlanco(){

	var old = document.getElementById("txtUser").value;
	alert(old);
	var nueva = "";

	nueva = nueva.split(" ").join("");

	return nueva;
	}*/

	/*function CheckUserName(ele) 
	{
		if (/\s/.test(ele.value)) 
		{ 
			alert("No se permiten espacios en blanco"); 
			document.getElementById("txtUser").value = '';
			document.getElementById("txtUser").focus();
		}
	}*/

	//validar que solo ingrese numeros  y letras
	function CheckUserName() {
		if (
			(event.keyCode > 47 && event.keyCode < 58) ||
			(event.keyCode > 64 && event.keyCode < 91) ||
			(event.keyCode > 96 && event.keyCode < 123)
		)

			return true;
		return false;
	}
</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container">
	<div class="alert alert-info hide" id="alerta-errorUsuarioBaja" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top:90px;margin-bottom:0px;z-index: 10">
		<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
		<strong>Titulo</strong> &nbsp;&nbsp;
		<span> Mensaje </span>
	</div>
	<div class="col-md-5 col-sm-12">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Catálogos</li>
				<li class="breadcrumb-item active" aria-current="page">Usuarios</li>
			</ol>
		</nav>
	</div>
	<div class="diviconos">
		<div class="col-sm-1 col-md-3">

		</div>
		<div class="col-sm-1 col-md-1">
			<a class="iconos" href="formatos/listado_usuarios.php" target="_blank"><img src="../iconos/printer.png" alt="">
				Imprimir</a>
		</div>
		<div class="col-sm-1 col-md-1">
			<a class="iconos" href="exportar/usuarios.php" target="_blank"><img src="../iconos/excel.png" alt="">
				Exp.excel</a>
		</div>
		<?php if (fnc_permiso($_SESSION['privilegio'], 10, 'upe_agregar') == 1) { ?>
			<div class="col-sm-1 col-md-1">
				<a class="iconos" href="#" data-toggle="modal" data-target="#ModalAlta" data-whatever="@getbootstrap"><img src="../iconos/user2.png" alt="">
					Usuario</a>
			</div>
		<?php } ?>
	</div>
	<?php include "usuarios_listado.php"; ?>

	<div class="modal fade" id="ModalAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<?php include "usuarios_alta.php"; ?>
	</div>

	<div class="modal" id="modalEditarUsuarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	</div>

</div>
<?php include "../generales/pie_pagina.php"; ?>