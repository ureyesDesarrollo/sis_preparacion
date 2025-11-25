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
$(document).ready(function()
{
	$("#form").submit(function(){
		var formData = $(this).serialize();
		$.ajax({
			url: "mezcla_insertar.php",
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
/*function fnc_baja(id){
	var respuesta = confirm("¿Deseas dar de baja este registro xx?");
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
	// return false;
	}
}*/

/*Abrir Modal Editar*/
function AbreModalEditar(id)
{ 
 $.ajax({
  type : 'post',
  url : 'mezcla_editar.php', 
		data : {"hdd_id":id}, //Pass $id
		success : function(result){
		  $("#modalEditar").html(result);
		  $('#modalEditar').modal('show')
		}
	  });
 return false;
};

/*Abrir Modal Consultar*/
function AbreModalConsultar(id)
{ 
 $.ajax({
  type : 'post',
  url : 'mezcla_consultar.php', 
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

</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container">
	<div class="alert alert-info hide" id="alerta-errorProvBaja" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top:90px;margin-bottom:0px;z-index: 10">
	  <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
	  <strong>Titulo</strong> &nbsp;&nbsp;
	  <span> Mensaje </span>
	</div>
	<div class="col-md-5 col-sm-12">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Funciones</li>
				<li class="breadcrumb-item active" aria-current="page">Mezclas</li>
			</ol>
		</nav>
	</div>
	<div class="diviconos">
	<div class="col-sm-1 col-md-3" >
		
	</div>
	<div class="col-sm-1 col-md-1">
		<!--<a class="iconos" href="formatos/listado_tipos_proceso.php" target="_blank"><img src="../iconos/printer.png" alt="">
		Imprimir</a>-->
	</div>
	<div class="col-sm-1 col-md-1">
		<!--<a class="iconos"  href="exportar/tipos_proceso.php" target="_blank"><img src="../iconos/excel.png" alt="">
		Exp.excel</a>-->
		<!--<button type="submit" id="export_data" name="export_data" value="Export to excel" class="btn btn-info">Exportar a Excel</button>-->
	</div>
	<?php if(fnc_permiso($_SESSION['privilegio'], 9, 'upe_agregar' ) == 1){?>
	<div class="col-sm-1 col-md-1">
		<a class="iconos"  href="#"  data-toggle="modal" data-target="#ModalAlta" data-whatever="@getbootstrap"><img src="../iconos/mezcla.PNG" alt=""> 
		Mezclas</a>
	</div>
	<?php }?>
</div>
<?php include "mezcla_listado.php";?>

<div class="modal fade" id="ModalAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<?php include "mezcla_alta.php";?>
</div>

<div class="modal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>

</div>
<?php include "../generales/pie_pagina.php";?>