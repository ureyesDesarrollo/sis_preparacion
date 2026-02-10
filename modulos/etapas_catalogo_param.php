<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../generales/menu.php');
?>

<link rel="stylesheet" href="../css/estilos_catalogos.css">
<script>
	
/*Abrir Modal Editar*/
function fnc_abre_modal(id)
{ 
 $.ajax({
  type : 'post',
  url : 'etapas_editar_param.php', 
		data : {"hdd_id":id}, //Pass $id
		success : function(result){
		  $("#modalEtapaEditar").html(result);
		  $('#modalEtapaEditar').modal('show')
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
	<div class="alert alert-info hide" id="alerta-errorTipoBaja" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top:90px;margin-bottom:0px;z-index: 10">
	  <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
	  <strong>Titulo</strong> &nbsp;&nbsp;
	  <span> Mensaje </span>
	</div>
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
<?php include "etapas_listado_param.php";?>

<div class="modal fade" id="ModalAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<?php //include "mat_tipo_alta.php";?>
</div>

<div class="modal" id="modalEtapaEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>

</div>
<?php include "../generales/pie_pagina.php";?>