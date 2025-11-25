<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
             FROM materiales_tipo 
             WHERE mt_id = '".$_POST['hdd_id']."'") or die(mysql_error()."Error: en consultar el tipo de material");
$registros = mysqli_fetch_assoc($cadena);
?> 
<script>
$(document).ready(function()
{
  $("#formTipoEditar").submit(function(){
    //alert('editar');
    var formData = $(this).serialize();
    $.ajax({
      url: "mat_tipo_actualizar.php",
      type: 'POST',
      data: formData,
      success: function(result) {
        data = JSON.parse(result);
        //alert("Guardo el registro");
        alertas("#alerta-errorTipoEditar", 'Listo!', data["mensaje"], 1, true, 5000);
        //$('#form').each (function(){this.reset();});  
      }
    });
    return false;
  });
});
</script>

<div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="formTipoEditar"> 
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar Origen material</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    
      <div class="modal-body">       
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label">Origen material:</label>
            <input name="txtTipo" type="text" class="form-control" id="txtTipo" value="<?php echo $registros['mt_descripcion'] ?>" maxlength="17" required placeholder="Nombre">
             <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['mt_id'] ?>"/>
          </div>
           <div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Estatus:</label>
            <select name="cbxEstatus" type="text" class="form-control" id="cbxEstatus" required>
             <?php 
             $list_tipo = mysqli_query($cnx,"select * from materiales_tipo where mt_id= '$registros[mt_id]'");
             while($reg_tipo =  mysqli_fetch_assoc($list_tipo)) {?>
               <option value="<?php echo mb_convert_encoding($reg_tipo['mt_est'], "UTF-8");  ?>" 
                <?php if(mb_convert_encoding($reg_tipo['mt_id'], "UTF-8") == $registros['mt_id']){ ?>selected="selected"<?php }?>>
                <?php 
                if ($reg_tipo['mt_est']=='A') {
                   echo "Activo";
                   echo '<option value="B">Baja</option>';
                 } else{
                   echo "Baja";
                   echo '<option value="A">Activo</option>';
                 }
                 ?>
                <!--<?php echo mb_convert_encoding($reg_tipo['mt_est'], "UTF-8");  ?>-->
                </option>
               <?php }?>
            </select>
          </div>

      <div class="modal-footer" style="margin-top: 8%;">
          <!--mensajes-->
         <div class="alert alert-info hide" id="alerta-errorTipoEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
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