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
<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="../reportes/css/bootstrap.min.css" rel="stylesheet">
		<link href="../reportes/css/bootstrap-theme.css" rel="stylesheet">
		<link href="../reportes/css/jquery.dataTables.min.css" rel="stylesheet">
		<script src="../reportes/js/jquery-3.1.1.min.js"></script>
		<script src="../reportes/js/bootstrap.min.js"></script>	
		<script src="../reportes/js/jquery.dataTables.min.js"></script>
		
		<script>
			$(document).ready(function(){
				$('#tabla_inventario').DataTable({
					"order": [[1, "desc"]],
					"language":{
					"lengthMenu": "Mostrar _MENU_ registros por pagina",
					"info": "Mostrando pagina _PAGE_ de _PAGES_",
						"infoEmpty": "No hay registros disponibles",
						"infoFiltered": "(filtrada de _MAX_ registros)",
						"loadingRecords": "Cargando...",
						"processing":     "Procesando...",
						"search": "Buscar:",
						"zeroRecords":    "No se encontraron registros coincidentes",
						"paginate": {
							"next":       "Siguiente",
							"previous":   "Anterior"
						},					
					},
					"bProcessing": true,
					"bServerSide": true,
					"sAjaxSource": "server_process_lotes.php"
				});	
			});
			
		</script>
<script>
/*Abrir Modal Editar*/
function AbreModalEditar(hdd_id,folio,mes)
{ 
 $.ajax({
  type : 'post',
  url : 'lotes_editar.php', 
		data : {"hdd_id":hdd_id,"folio":folio,"mes":mes}, //Pass $id
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
				<li class="breadcrumb-item active" aria-current="page">Lotes</li>
			</ol>
		</nav>
	</div>
	<div class="diviconos">
	<div class="col-sm-1 col-md-5" >
		
	</div>
	<div class="col-sm-1 col-md-1">
		<a class="iconos" href="formatos/listado_lotes.php" target="_blank"><img src="../iconos/printer.png" alt="">
		Imprimir</a>
	</div>
	<!--<div class="col-sm-1 col-md-1">
		<a class="iconos"  href="exportar/lotes_exportar.php" target="_blank"><img src="../iconos/excel.png" alt="">
		Exp.excel</a>

	</div>-->
	<?php if(fnc_permiso($_SESSION['privilegio'], 18, 'upe_agregar' ) == 1){?>
	<div class="col-sm-1 col-md-1">
		<a class="iconos"  href="#"  data-toggle="modal" data-target="#ModalAlta" data-whatever="@getbootstrap"><img src="../iconos/lotes.png" alt=""> 
		Lote</a>
	</div>
	<?php }?>
</div>
<?php include "lotes_listado.php";?>

<div class="modal fade" id="ModalAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<?php include "lotes_alta.php";?>
</div>

<div class="modal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>

</div>
<?php include "../generales/pie_pagina.php";?>