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
  $("#formEtapasEditar").submit(function(){
    //alert('editar');
    var formData = $(this).serialize();
    $.ajax({
      url: "estatus_actualizar2.php",
      type: 'POST',
      data: formData,
      success: function(result) {
        data = JSON.parse(result);
        //alert("Guardo el registro");
        alertas("#alerta-errorEtapaEditar", 'Listo!', data["mensaje"], 1, true, 5000);
        //$('#form').each (function(){this.reset();});  
      }
    });
    return false;
  });
});
</script>


<div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="formEtapasEditar"> 
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar Estatus Paleto</h5>
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
		</div>
		 <div class="row"> 
			  <div class="col-md-4">
				<label for="recipient-name" class="col-form-label">Comentarios:</label>
			   <textarea name="txaComentarios" cols="70" rows="3" ic="txaComentarios" required></textarea>
			  </div>
		  </div>
		
      <div class="modal-footer" style="margin-top: 8%;">
          <!--mensajes-->
         <div class="alert alert-info hide" id="alerta-errorEtapaEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
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