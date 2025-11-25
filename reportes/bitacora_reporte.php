<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../generales/menu.php');
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include('../seguridad/user_seguridad.php');
?>

<link rel="stylesheet" href="../css/estilos_catalogos.css">
<script>
	
/*Manipular el formulario*/
/*$(document).ready(function()
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
});*/


/*Para cambiar el estatus a B*/
/*function fnc_baja(id){
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
}*/

/*Abrir Modal Editar*/
function AbreModalEditar(id, tipo)
{ 
 $.ajax({
  type : 'post',
  url : 'bitacora_editar.php', 
		data : {"pro_id":id, "hdd_tipo": tipo}, //Pass $id
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



/*Abrir Modal info parametros*/
function AbreModalInfo(id, tipo)
{ 
 $.ajax({
  type : 'post',
  url : 'desglose_parametros.php', 
		data : {"pro_id":id, "hdd_tipo": tipo}, //Pass $id
		success : function(result){
		  $("#modalInfoParam").html(result);
		  $('#modalInfoParam').modal('show')
		}
	  });
 return false;
};


function AbreModalInfopal(id, tipo)
{ 
 $.ajax({
  type : 'post',
  url : 'desglose_parametros_pal.php', 
		data : {"prop_id":id, "hdd_tipo": tipo}, //Pass $id
		success : function(result){
		  $("#modalInfoParampal").html(result);
		  $('#modalInfoParampal').modal('show')
		}
	  });
 return false;
};

/*Para cambiar el estatus a B*/
function fnc_cerrar(id){
	var respuesta = confirm("¿Deseas cerrar este proceso?");
	if (respuesta == true){
	$.ajax({
	  url: 'bitacora_cerrar.php',
	  data: 'id=' + id,
	  type: 'post',
	  success: function(result){
		data = JSON.parse(result);
		setTimeout("location.reload()", 1000)
	  }
	});
	 return false;
	}
}

/*Para cambiar eliminar el proceso*/
function fnc_quitar(id){
	var respuesta = confirm("¿Deseas eliminar este proceso?");
	if (respuesta == true){
	$.ajax({
	  url: 'bitacora_eliminar.php',
	  data: 'id=' + id,
	  type: 'post',
	  success: function(result){
		data = JSON.parse(result);
		setTimeout("location.reload()", 2000)
	  }
	});
	 return false;
	}
}
/* function fnc_calculaTotal()
 {
 	document.getElementById('txtKgTotales').value = (document.getElementById('txtKg').value - document.getElementById('txtDAgua').value) - document.getElementById('txtDRendimiento').value;
 }*/
</script>
<script type="text/javascript" src="../js/alerta.js"></script>

<div class="container">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#listado_bitacora">Listado bitácora</a></li>
		<li><a data-toggle="tab" href="#listado_bitacora_pal">Listado bitácora paleto</a></li>
	</ul>
	<div class="alert alert-info hide" id="alerta-errorProvBaja" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top:90px;margin-bottom:0px;z-index: 10">
	  <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
	  <strong>Titulo</strong> &nbsp;&nbsp;
	  <span> Mensaje </span>
	</div>
	<div class="row" style="margin-top: 50px;margin-bottom: -60px">
	<div class="col-md-5 col-sm-12">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Reportes</li>
				<li class="breadcrumb-item active" aria-current="page">Listado de Bitácoras</li>
				<!--<li class="breadcrumb-item " aria-current="page">Materiales</li>
				<li class="breadcrumb-item " aria-current="page">Tipo materiales</li>-->

			</ol>
		</nav>
	</div>
</div>

	<div class="diviconos">
	<div class="col-sm-1 col-md-4" >
		
	</div>
	<!--<div class="col-sm-1 col-md-1">
		<a class="iconos" href="formatos/listado_bitacora.php" target="_blank"><img src="../iconos/printer.png" alt="">
		Imprimir</a>
	</div>
	<div class="col-sm-1 col-md-1">
		<a class="iconos"  href="exportar/bitacora.php" target="_blank"><img src="../iconos/excel.png" alt="">
		Exp.excel</a>
	</div>-->
	<!--<div class="col-sm-1 col-md-1">
		<a class="iconos"  href="#"  data-toggle="modal" data-target="#ModalAlta" data-whatever="@getbootstrap"><img src="../iconos/inventario.png" alt=""> 
		Inventario</a>
	</div>-->
</div>
<div class="tab-content">
	<div id="listado_bitacora" class="tab-pane fade in active">
<?php include "bitacora_listado.php";?>
</div>

<div id="listado_bitacora_pal" class="tab-pane fade">
	<?php include "bitacora_listado_pal.php";?>
</div>
</div>

<!--<div class="modal fade" id="ModalAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<?php //include "inventario_alta.php";?>
</div>-->

<div class="modal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>

<div class="modal" id="modalInfoParam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>

<div class="modal" id="modalInfoParampal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>
</div>
<?php include "../generales/pie_pagina.php";?>