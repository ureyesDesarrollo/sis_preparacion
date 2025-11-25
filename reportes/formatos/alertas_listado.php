<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "conexion2.php";
include "../funciones/funciones.php";
?>

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
			$('#tabla_alertas').DataTable({
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
				"sAjaxSource": "server_process_alerta.php"
			});	
		});

	</script>

</head>

<div class="container" style="margin-top:60px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
	<table  cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_alertas">
		<thead>
			<tr align="center">
				<th>&nbsp;Clave&nbsp;</th>
				<th>&nbsp;Usuario&nbsp;</th>
				<th>&nbsp;Fecha&nbsp;</th>
				<th>&nbsp;Parametro&nbsp;</th>
				<th>&nbsp;Valor&nbsp;</th>
				<th>&nbsp;Proceso&nbsp;</th>
				<th>&nbsp;Etapa&nbsp;</th>
				<!--<th>&nbsp;-&nbsp;</th>-->
			</tr>
		</thead>
		<tbody>

		</tbody>

		<tfoot>

		</tfoot>
	</table>
</div>