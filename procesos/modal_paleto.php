<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
require_once('../conexion/conexion.php');
$cnx = Conectarse();
?>
<script>
  $(document).ready(function()
  {
  
     $("#formModalP").submit(function(){
    //alert('editar');
    var formData = $(this).serialize();
    $.ajax({
      url: "modal_paleto_agregar.php",
      type: 'POST',
      data: formData,
      success: function(result) {
        data = JSON.parse(result);
        //alert("Guardo el registro");
        alertas("#alerta-errorAgregarR", 'Listo!', data["mensaje"], 1, true, 5000);
        $('#formModalP').each (function(){this.reset();});  
      }
    });
    confirmEnviar3();
    return false;
  });
  
 });
</script>
  <!--<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">-->
    <div class="modal-dialog modal-lg" role="document" style="height: 200px">
      <div class="modal-content">
        <form id="formModalP" name="formModalP"> 
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Agregar Paleto "Libre - Ocupado"</h5>
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
          <label for="recipient-name" class="col-form-label">Lavador:</label>
          <input type="text" class="form-control" id="txtLavador" name="txtLavador"  readonly="true" value="<?php echo $_POST['lavador'] ?>">
        </div>
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">Paleto:</label>
			<select id="cbxPaleto" class="form-control"  name="cbxPaleto" required>
				<option value="">Seleccionar</option>
				<?php 
			
				//$cadena =  mysqli_query($cnx,"SELECT * from preparacion_paletos where le_id IN (2,1) and pp_id > 2");//Invalidador en correo de Andrea "Detalle con el boton.."
				$cadena =  mysqli_query($cnx,"SELECT * from preparacion_paletos where le_id IN (2,1)");
				$registros =  mysqli_fetch_array($cadena);

				do
				{
					?><option value="<?php echo $registros['pp_id'] ?>" <?php if($registros['le_id'] == 1){?>style="background:#F7FEA0"<?php }?> ><?php echo $registros['pp_descripcion'] ?></option>
				<?php }while($registros =  mysqli_fetch_array($cadena));


				mysqli_free_result($registros);

				?>
			</select>
         </div>
		 
		  <div class="col-md-4">
            <label for="recipient-name" class="col-form-label">Tipo Preparaci&oacute;n:</label>
			<select id="cbxProceso" class="form-control"  name="cbxProceso" required>
				<option value="">Seleccionar</option>
				<?php 
				$html = '';
				
				$cadena =  mysqli_query($cnx,"SELECT * from preparacion_tipo WHERE pt_estatus = 'A' AND pt_id IN (6) ORDER BY pt_descripcion");
				$registros =  mysqli_fetch_array($cadena);

				do
				{
					$html.= "<option value='".$registros['pt_id']."'>".$registros['pt_descripcion']."</option>";
				}while($registros =  mysqli_fetch_array($cadena));

				echo $html;

				mysqli_free_result($registros);

				?>
			</select>
         </div>

        <div class="modal-footer" style="margin-top: 8%;">
          <!--mensajes-->
          <div class="alert alert-info hide" id="alerta-errorAgregarR" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
            <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
            <strong>Titulo</strong> &nbsp;&nbsp;
            <span> Mensaje </span>
          </div>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();"><img src="../iconos/close.png" alt="">Cerrar</button>
          <button class="btn btn-primary" type="submit" id="btn" name="btn"><img src="../iconos/guardar.png" alt=""> Guardar</button>
        </div>
      </div>
    </form>

  </div>
</div>
<!--</div>-->
