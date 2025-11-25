<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT p.*, l.le_estatus
 								FROM preparacion_paletos as p
								INNER JOIN listado_estatus as l on (p.le_id = l.le_id)
             WHERE p.pp_id = '".$_POST['hdd_id']."'") or die(mysql_error()."Error: en consultar las etapas");
$registros = mysqli_fetch_assoc($cadena);

?> 
<script>

$(document).ready(function()
{
  $("#formEstEditar").submit(function(){
    //alert('editar');
    var formData = $(this).serialize();
    $.ajax({
      url: "estatus_actualizar_pal.php",
      type: 'POST',
      data: formData,
      success: function(result) {
        data = JSON.parse(result);
        //alert("Guardo el registro");
        alertas("#alerta-errorEtapaEditar", 'Listo!', data["mensaje"], 1, true, 5000);
        $('#formEstEditar').each (function(){this.reset();});  
      }
    });
    return false;
  });
});
</script>


<div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="formEstEditar"> 
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar estatus Paleto y Asignar lote</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    
      <div class="modal-body"> 
	  <div class="row">      
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label">Estatus:</label>
           <select name="cbxEstatus" class="form-control is-valid" id="cbxEstatus" placeholder="" required>
             <option value="">Selecciona...</option>
             <?php 
             $cad_est = mysqli_query($cnx,"select * from listado_estatus WHERE le_aplica = 'P' order by le_estatus asc");
             while($reg_est =  mysqli_fetch_assoc($cad_est)) {
			 ?>
               <option value="<?php echo mb_convert_encoding($reg_est['le_id'], "UTF-8");  ?>" 
                <?php if(mb_convert_encoding($reg_est['le_id'], "UTF-8") == $registros['le_id']){ ?>selected="selected"<?php }?>><?php echo mb_convert_encoding($reg_est['le_estatus'], "UTF-8");  ?></option>
               <?php }?>
           </select>
             <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $_POST['hdd_id'] ?>"/>
			 <input name="hdd_est" type="hidden" id="hdd_est" value="<?php echo $registros['le_id'] ?>"/>
          </div>
		<!--</div>
		 <div class="row">--> 
			  <div class="col-md-4">
				<label for="recipient-name" class="col-form-label">Comentarios:</label>
			   <textarea name="txaComentarios" cols="70" rows="3" ic="txaComentarios" required class="form-control is-valid"></textarea>
			  </div>
		  </div>
		  <hr />
		  <div class="row">      
          	<!--<div class="col-md-2">
		  		<label for="recipient-name" class="col-form-label">Fecha:</label>
				<input name="txt_fecha" id="txt_fecha" type="text" size="10" value="<?php //echo date("d-m-Y") ?>" readonly="true" class="form-control is-valid"/>
			</div>
			<div class="col-md-2">
		  		<label for="recipient-name" class="col-form-label">Hora:</label>
				<input name="txt_hora" id="txt_hora" type="text" size="5" value="<?php //echo date("H:i:s") ?>" readonly="true" class="form-control is-valid"/>
			</div>
			<div class="col-md-2">
		  		<label for="recipient-name" class="col-form-label">Mes:</label>
				<input name="txt_mes" id="txt_mes" type="text" size="10" value="<?php //echo fnc_formato_mes(date("m")) ?>" readonly="true" class="form-control is-valid"/>
			</div>
			<div class="col-md-2">
		  		<label for="recipient-name" class="col-form-label">Turno:</label>
				<select name="slc_turno" id="slc_turno" class="form-control is-valid" required>
					<option value="">Selecciona...</option>
					<option value="D">Dia</option>
					<option value="N">Noche</option>
				</select>
			</div>-->
			<div class="col-md-4">
		  		<label for="recipient-name" class="col-form-label">Lote:</label>
				<!--<input name="txt_lote" id="txt_lote" type="text" size="10" value="<?php //echo fnc_lote(date("m")) ?>" readonly="true" class="form-control is-valid" required/>-->
				<select name="cbxLote" class="form-control is-valid" id="cbxLote" placeholder="" required>
             <option value="">Selecciona...</option>
             <?php 
             $cad_est = mysqli_query($cnx,"select * from lotes order by lote_id DESC LIMIT 5");
             while($reg_est =  mysqli_fetch_assoc($cad_est)) {
			 ?>
               <option value="<?php echo mb_convert_encoding($reg_est['lote_id'], "UTF-8");  ?>" 
                <?php if(mb_convert_encoding($reg_est['lote_id'], "UTF-8") == $registros['lote_id']){ ?>selected="selected"<?php }?>>
				<?php echo mb_convert_encoding($reg_est['lote_folio']." / ".$reg_est['lote_fecha']." / ".$reg_est['lote_mes']." / ".$reg_est['lote_turno'], "UTF-8");  ?>
				</option>
               <?php }?>
           </select>
			</div>
		  </div>
		
      <div class="modal-footer" style="margin-top: 8%;">
          <!--mensajes-->
         <div class="alert alert-info hide" id="alerta-errorEtapaEditar" style="height: 40px;width: 400px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
          <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
          <strong>Titulo</strong> &nbsp;&nbsp;
          <span> Mensaje </span>
        </div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button>
        <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
      </div>
       </div>
    </form>
   
  </div>
</div>    