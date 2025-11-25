<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include('../generales/menu.php');
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php')
?>

<link rel="stylesheet" href="../css/estilos_catalogos.css">
<script>


	/*Manipular el formulario*/
	$(document).ready(function()
	{
		$("#form").submit(function(){
			var formData = $(this).serialize();
			$.ajax({
				url: "inventario_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {
					data = JSON.parse(result);
			  //alert("Guardo el registro");
			  alertas("#alerta-errorProvAlta", 'Listo!', data["mensaje"], 1, true, 5000);
			  $('#form').each (function(){this.reset();});  
			}
		});
			return false;
		});
	});


	/*Para cambiar el estatus a B*/
	function fnc_baja(id){
		var respuesta = confirm("¿Deseas dar de baja este registro?");
		if (respuesta){
			$.ajax({
				url: 'proveedores_baja.php',
				data: 'id=' + id,
				type: 'post',
				success: function(result){
					data = JSON.parse(result);
					alertas("#alerta-errorProvBaja", 'Listo!', data["mensaje"], 1, true, 5000); 
		//$("#main").load("catalogos/proveedores_catalogo.php", 1000);
		//setTimeout(location.reload(), 1000);//Revisa esta Ceci
	}
});
			return false;
		}
	}
	
	function fnc_enviar(id){
		var respuesta = confirm("¿Deseas enviar este registro a maquila?");
		if (respuesta){
			$.ajax({
				url: 'inventario_enviar.php',
				data: 'id=' + id,
				type: 'post',
				success: function(result){
					data = JSON.parse(result);
					//alertas("#alerta-errorProvBaja", 'Listo!', data["mensaje"], 1, true, 5000); 
		//$("#main").load("inventario_listado_a_maquila.php", 1000);
		//setTimeout(location.reload(), 1000);//Revisa esta Ceci
		//location.reload("inventario_listado_a_maquila.php");
		//$("#listadoamaquila").load("inventario_listado_a_maquila2.php");
		refresh();
	}
});
			//return false;
		}
	}

	/*Abrir Modal Editar*/
	function AbreModalRecibir(id)
	{ //alert(id);
		$.ajax({
			type : 'post',
			url : 'inventario_editar.php', 
		data : {"hdd_id":id}, //Pass $id
		success : function(result){
			$("#modalEditar").html(result);
			$('#modalEditar').modal('show')
		}
	});
		return false;
	};
	
	function AbreModalRecibir2(id)
	{ //alert(id);
		$.ajax({
			type : 'post',
			url : 'inventario_editar_local.php', 
		data : {"hdd_id":id}, //Pass $id
		success : function(result){
			$("#modalEditar").html(result);
			$('#modalEditar').modal('show')
		}
	});
		return false;
	};

	function refresh()
	{
		location.reload();
	}

	//funcion para calcular kilos de porveedores locales
	function fnc_calculaTotal()
	{
		document.getElementById('txtKgTotales').value = (document.getElementById('txtKg').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDRendimiento').value - document.getElementById('txtDescarne').value;
	}

	//funcion para calcular kilos de porveedores locales
	function fnc_calculaTotalL()
	{
		document.getElementById('txtKgTotales').value = (document.getElementById('txtKg').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDRendimiento').value - document.getElementById('txtDescarne').value;
	}

	//funcion para calcular kilos de porveedores locales directo a maquila
	function fnc_calculaTotalM()
	{
		/*document.getElementById('txtKgTotales').value = (document.getElementById('txtKgEntradaMaq').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDRendimiento').value;*/

		document.getElementById('txtKgTotales').value = (document.getElementById('txtKg').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDRendimiento').value;
	}


	function fnc_calculaTotalE()
	{
		document.getElementById('txtKgTotales').value = (document.getElementById('txtKgEntradaMaq').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDescarne').value - document.getElementById('txtDRendimiento').value;
	}
</script>
<script type="text/javascript" src="../js/alerta.js"></script>


<div class="container">
	<ul class="nav nav-tabs">
		
		<li class="active"><a data-toggle="tab" href="#inventario_modulo">Inventario</a></li>
		<li><a data-toggle="tab" href="#listadoamaquila" >De extranjero</a></li>
		<li><a data-toggle="tab" href="#listadoenmaquila">Ext. en maquila</a></li>
		<li><a data-toggle="tab" href="#list_loc_enmaquila">Loc en maquila</a></li>
		<?php if ($_SESSION['privilegio'] != 11){?><li><a data-toggle="tab" href="#listadohistorial">Hist. Inventario</a></li>
		<li><a data-toggle="tab" href="#historialamaquila">Hist. extranjero</a></li>
		<li><a data-toggle="tab" href="#historial_loc_maquila">Hist. loc maquila</a></li><?php } ?>
	</ul>

	<!--LISTADO DE INVENTARIO-->
	<div class="tab-content">
		<div id="inventario_modulo" class="tab-pane fade in active">
			<div class="row" style="margin-top: 50px;margin-bottom: -50px">
				<div class="col-md-3 col-sm-12">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">Funciones</li>
							<li class="breadcrumb-item active" aria-current="page">Inventario</li>
						</ol>
					</nav>
				</div>
				<div class="diviconosInve">
					<div class="col-sm-1 col-md-3">
					</div>
					<?php if ($_SESSION['privilegio'] != 11){?>
					<div class="col-sm-1 col-md-1">
						<a class="iconos" href="formatos/listado_inventario.php" target="_blank"><img src="../iconos/printer.png" alt="">
						formato anterior</a>
					</div>
					<div class="col-sm-1 col-md-1">
						<a class="iconos" href="formatos/listado_inventario_nuevo.php" target="_blank"><img src="../iconos/printer.png" alt="">
						Formato nuevo</a>
					</div>
					<div class="col-sm-1 col-md-1">
						<a class="iconos"  href="exportar/inventario.php" target="_blank"><img src="../iconos/excel.png" alt="">
						Exp.excel</a>
						<!--<button type="submit" id="export_data" name="export_data" value="Export to excel" class="btn btn-info">Exportar a Excel</button>-->
					</div>
					<div class="col-sm-1 col-md-1">
						<a class="iconos"  href="exportar/inventario_total.php" target="_blank"><img src="../iconos/excel.png" alt="">
						Exp.todo</a>
						<!--<button type="submit" id="export_data" name="export_data" value="Export to excel" class="btn btn-info">Exportar a Excel</button>-->
					</div> <?php }else{ ?>
					<div class="col-sm-1 col-md-7"></div>
					<?php }?>
					
					<?php if(fnc_permiso($_SESSION['privilegio'], 3, 'upe_agregar' ) == 1){?>
						<div class="col-sm-1 col-md-1">
							<a class="iconos"  href="#"  data-toggle="modal" data-target="#ModalAlta" data-whatever="@getbootstrap"><img src="../iconos/inventario.png" alt=""> 
							Inventario</a>
						</div>
					<?php }?>
				</div>
			</div>
			
			<?php include "inventario_listado.php";?>
			
			<div class="modal fade" id="ModalAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<?php include "inventario_alta.php";?>
			</div>

			<!--<div class="modal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			</div>-->
			<?php include "../generales/pie_pagina.php";?>
		</div>

		<!--LISTADO A MAQUILA-->
		<div id="listadoamaquila" class="tab-pane fade">
			<?php include "inventario_listado_a_maquila.php";?>
		</div>

		<!--LISTADO EN MAQUILA-->
		<div id="listadoenmaquila" class="tab-pane fade">
			<?php include "inventario_listado_en_maquila.php";?>
		</div>

		<!--LISTADO HISTORIAL-->
		<div id="listadohistorial" class="tab-pane fade">
			<?php include "inventario_historial.php";?>
		</div>
		
		<!--LISTADO A MAQUILA HISTORIAL-->
		<div id="historialamaquila" class="tab-pane fade">
			<?php include "inventario_historial_a_maquila.php";?>
		</div>
		
		<!--LISTADO EN MAQUILA LOCAL-->
		<div id="list_loc_enmaquila" class="tab-pane fade">
			<?php include "inventario_list_loc_en_maquila.php";?>
		</div>
		
		<!--LISTADO A MAQUILA HISTORIAL LOCAL-->
		<div id="historial_loc_maquila" class="tab-pane fade">
			<?php include "inventario_hist_loc_a_maquila.php";?>
		</div>


		
		<div class="modal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
		</div>
		
	</div>
</div>

