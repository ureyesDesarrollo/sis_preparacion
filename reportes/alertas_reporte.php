<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../generales/menu.php');
?>

<link rel="stylesheet" href="../css/estilos_catalogos.css">
<script>
	
/*Abrir Modal Editar*/
function AbreModalEditar(id)
{ 
 $.ajax({
  type : 'post',
  url : 'proveedores_editar.php', 
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
				<li class="breadcrumb-item">Reportes</li>
				<li class="breadcrumb-item active" aria-current="page">Bitácora Alertas</li>
				<!--<li class="breadcrumb-item " aria-current="page">Materiales</li>
				<li class="breadcrumb-item " aria-current="page">Tipo materiales</li>-->

			</ol>
		</nav>
	</div>
	<div class="diviconos">
	<div class="col-sm-1 col-md-4" >
		
	</div>
	<div class="col-sm-1 col-md-1">
		<a class="iconos" href="formatos/listado_alertas.php" target="_blank"><img src="../iconos/printer.png" alt="">
		Imprimir</a>
	</div>
	<div class="col-sm-1 col-md-1">
		<a class="iconos"  href="exportar/exp_bitacora_alertas.php" target="_blank"><img src="../iconos/excel.png" alt="">
		Exp.excel</a>
		<!--<button type="submit" id="export_data" name="export_data" value="Export to excel" class="btn btn-info">Exportar a Excel</button>-->
	</div>

</div>
<?php include "alertas_listado.php";?>

<div class="modal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>

</div>
<?php include "../generales/pie_pagina.php";?>