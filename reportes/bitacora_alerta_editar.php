<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
require_once('../conexion/conexion.php');
$cnx = Conectarse();

//Estatus de proceso
$cadAle = mysqli_query($cnx, "SELECT * FROM bitacora_alertas WHERE ba_id = '".$_POST['ba_id']."'");
$regAle = mysqli_fetch_array($cadAle);
?>
<script>
  $(document).ready(function()
  {

   $("#formModalP").submit(function(){
    //alert('editar');
    var formData = $(this).serialize();
    $.ajax({
      url: "bitacora_alerta_actualizar.php",
      type: 'POST',
      data: formData,
      success: function(result) {
        data = JSON.parse(result);
        //alert("Guardo el registro");
        alertas("#alerta-errorAgregarR", 'Listo!', data["mensaje"], 1, true, 5000);
        $('#formModalP').each (function(){this.reset();});  
      }
    });
    return false;
  });

 });
</script>
<!--<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">-->
  <div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="formModalP"> 
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Completar datos de alerta</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">       
          <div class="row">          
            <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">Alerta:</label>
            <input name="txtId" type="text" id="txtId" value="<?php echo $_POST['ba_id'] ?>" readonly="true" class="form-control" required/>
          </div>
          <div class="col-md-5">
            <label for="recipient-name" class="col-form-label">Comentarios:</label>
            <textarea class="form-control" style="width: 600px" name="txaComentarios"  rows="2" id="txaComentarios" required><?php echo $regAle['ba_comentarios'];?></textarea>
         </div>
       </div>
       </div>
       <div class="modal-footer">

        <!--mensajes-->
        <div class="alert alert-info hide" id="alerta-errorAgregarR" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
          <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
          <strong>Titulo</strong> &nbsp;&nbsp;
          <span> Mensaje </span>
        </div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();"><img src="../iconos/close.png" alt="">Cerrar</button>
        <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
      </div>
    </form>

  </div>
</div>
<!--</div>-->
