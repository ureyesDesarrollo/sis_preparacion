<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
?>
<script>
  $(document).ready(function()
  {
  
     $("#formModalR").submit(function(){
    //alert('editar');
    var formData = $(this).serialize();
    $.ajax({
      url: "renglon_agregar.php",
      type: 'POST',
      data: formData,
      success: function(result) {
        data = JSON.parse(result);
        //alert("Guardo el registro");
        alertas("#alerta-errorAgregarR", 'Listo!', data["mensaje"], 1, true, 5000);
        $('#formModalR').each (function(){this.reset();});  
      }
    });
    return false;
  });
  
 });
</script>
  <!--<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">-->
    <div class="modal-dialog modal-lg" role="document" style="height: 200px">
      <div class="modal-content">
        <form id="formModalR"> 
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Agregar renglon</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">       
            <div class="col-md-2">
              <label for="recipient-name" class="col-form-label">Proceso:</label>
			  <input name="txtPro" type="text" id="txtPro" value="<?php echo $_POST['pro_id'] ?>" readonly="true" class="form-control"/>
          </div>
         <div class="col-md-2">
          <label for="recipient-name" class="col-form-label">Etapa:</label>
          <input type="text" class="form-control" id="txtEtapa" name="txtEtapa"  readonly="true" value="<?php echo $_POST['pe_id'] ?>">
        </div>
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">Renglon:</label>
			<input type="text" name="txtRen" id="txtRen" value="<?php echo $_POST['uren'] + 1; ?>" readonly="true" class="form-control">
         </div>

        <div class="modal-footer" style="margin-top: 8%;">
          <!--mensajes-->
          <div class="alert alert-info hide" id="alerta-errorAgregarR" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
            <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
            <strong>Titulo</strong> &nbsp;&nbsp;
            <span> Mensaje </span>
          </div>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();"><img src="../iconos/close.png" alt="">Cerrar</button>
          <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
        </div>
      </div>
    </form>

  </div>
</div>
<!--</div>-->
