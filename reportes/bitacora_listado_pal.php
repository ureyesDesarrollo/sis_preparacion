<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
require 'conexion2.php';
?>
<html lang="es">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-theme.css" rel="stylesheet">
		<link href="css/jquery.dataTables.min.css" rel="stylesheet">
		<script src="js/jquery-3.1.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>	
		<script src="js/jquery.dataTables.min.js"></script>
		
		<script>
			$(document).ready(function(){
				$('#mitabla3').DataTable({
					"order": [[1, "asc"]],
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
					"sAjaxSource": "server_process_pal.php"
				});	
			});
			
		</script>
		
	</head>
	
	<body>
		
		<div class="container" style="margin-top:60px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
			<!--<div class="row">
				<h2 style="text-align:center">Curso de PHP y MySQL</h2>
			</div>-->
			
			<!--<div class="row">
				<a href="nuevo.php" class="btn btn-primary">Nuevo Registro</a>
			</div>-->
			
			<br>
			
			<div class="table-responsive">
				<table class="display" id="mitabla3" width="100%">
					 <thead>
					  <tr align="center">
						<th>&nbsp;Clave&nbsp;</th>
						<th>&nbsp;Paleto&nbsp;</th>
						<th>&nbsp;Lavador&nbsp;</th>
						<th>&nbsp;Material&nbsp;</th>
						<th>&nbsp;Kilos&nbsp;</th>
						<th>&nbsp;Preparacion&nbsp;</th>
						<th>&nbsp;Directo&nbsp;</th>
						<th>&nbsp;Estatus&nbsp;</th>
						<th>&nbsp;Imprimir&nbsp;</th>
						<th>&nbsp;Exportar&nbsp;</th>
						<th>&nbsp;Info&nbsp;</th>
					  </tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
		
		<!-- Modal -->
		<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Eliminar Registro</h4>
					</div>
					
					<div class="modal-body">
						¿Desea eliminar este registro?
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<a class="btn btn-danger btn-ok">Delete</a>
					</div>
				</div>
			</div>
		</div>
		<?php 
		//include "desglose_parametros.php";?>
		<script>
			$('#confirm-delete').on('show.bs.modal', function(e) {
				$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
				
				$('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
			});
			
		/*	$('#Info').on('show.bs.modal', function(e) {
				$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
				
				$('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
			});*/
		</script>	
		
	</body>
</html>	