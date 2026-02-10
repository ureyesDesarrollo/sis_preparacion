<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM lotes") or die(mysqli_error()."Error: en consultar los lotes");
$registros = mysqli_fetch_assoc($cadena);

?>


<div class="container" style="border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
   <table class="display" id="tabla_inventario">
            <thead>
              <tr align="center">
			  	<!--<th>&nbsp;ID Lote&nbsp;</th>-->
				<th style="width: 50px">&nbsp;Folio Lote&nbsp;</th>
                <th>&nbsp;Fecha&nbsp;</th>
				<th>&nbsp;Hora&nbsp;</th>
                <th>&nbsp;Mes&nbsp;</th>
				<th>&nbsp;Turno&nbsp;</th>
				<th>&nbsp;Usuario&nbsp;</th>
				<th>Editar</th>
              </tr>
            </thead>
            <tbody>
			
			  
          </tbody>

        </table>
</div>