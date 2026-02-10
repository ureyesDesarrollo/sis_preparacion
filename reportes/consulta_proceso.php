<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST); 

$str_cad =  mysqli_query($cnx, "SELECT * FROM lotes  WHERE lote_id = '$lote' ");
$reg_cad = mysqli_fetch_assoc($str_cad);
$tot_cad = mysqli_num_rows($str_cad);
?>
<script>

$(document).ready(function()
{
  $("#formProceso").submit(function(){
    //alert('editar');
    var formData = $(this).serialize();
    $.ajax({
      url: "actualizar_proceso.php",
      type: 'POST',
      data: formData,
      success: function(result) {
        data = JSON.parse(result);
        //alert("Guardo el registro");
        alertas("#alerta-errorEtapaEditar", 'Listo!', data["mensaje"], 1, true, 5000);
        $('#formProceso').each (function(){this.reset();});  
      }
    });
    return false;
  });
});
</script>
<form name="formProceso" id="formProceso">
<!-- Large modal -->
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 style="text-align: center;font-weight: bold;" class="modal-title" id="exampleModalLabel">Consulta de información de tarimas <br> Mes <?php echo $reg_cad['lote_mes'] ?> - Lote <?php echo $reg_cad['lote_folio'] ?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
	<div class="modal-body">
		Lote <input name="txtLote" type="text" required="required" id="txtLote" autocomplete="off" value="<?php echo $reg_cad['lote_id']?>" size="5" readonly="readonly">
		
		<label for="txtProActual">Proceso Actual</label>
        <input name="txtProActual" type="text" required="required" id="txtProActual" value="<?php echo $pro;?>" size="5"  readonly="readonly">

<label for="txtProNew">Nuevo proceso </label>
<input name="txtProNew" type="text" autofocus="autofocus" required="required" id="txtProNew" size="5">
	</div>
  <div class="modal-footer">
	  <div class="alert alert-info hide" id="alerta-errorEtapaEditar" style="height: 40px;width: 400px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
          <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
          <strong>Titulo</strong> &nbsp;&nbsp;
          <span> Mensaje </span>
        </div>
    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()">Cerrar</button>
	  <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
  </div>
</div>
</div>
</form>