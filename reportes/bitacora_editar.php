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
      url: "bitacora_actualizar.php",
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
            <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Activar captura "laboratorio"</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">       
            <div class="col-md-2">
              <label for="recipient-name" class="col-form-label">Proceso:</label>
			  <input name="txtPro" type="text" id="txtPro" value="<?php echo $_POST['pro_id'] ?>" readonly="true" class="form-control"/>
          </div>
         <div class="col-md-5">
            <label for="recipient-name" class="col-form-label">Tipo Preparaci&oacute;n:</label>
			<select id="cbxProceso" class="form-control"  name="cbxProceso" required>
				<!--<option value="">Seleccionar</option>-->
				<?php 
				$html = '';
				
				$cadena =  mysqli_query($cnx,"SELECT * from preparacion_tipo WHERE pt_id = '".$_POST['hdd_tipo']."' ");
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
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">Etapa:</label>
			<select id="cbxEtapa" class="form-control"  name="cbxEtapa" required>
				<option value="">Seleccionar</option>
				<?php 
			
				$cadena =  mysqli_query($cnx,"SELECT * from preparacion_tipo_etapas as t INNER JOIN preparacion_etapas AS e ON(t.pe_id = e.pe_id) where pt_id = '".$_POST['hdd_tipo']."'");
				$registros =  mysqli_fetch_array($cadena);

				do
				{
					?><option value="<?php echo $registros['pe_id'] ?>" ><?php echo $registros['pe_descripcion'] ?></option>
				<?php }while($registros =  mysqli_fetch_array($cadena));


				mysqli_free_result($registros);

				?>
			</select>
         </div>

        <div class="modal-footer" style="margin-top: 8%;">
		<?php 
		//Estatus de proceso
		$cadPro = mysqli_query($cnx, "SELECT pro_estatus FROM procesos WHERE pro_id = '".$_POST['pro_id']."'");
	    $regPro = mysqli_fetch_array($cadPro);
	
		if ($regPro['pro_estatus'] == 2){
			?>
			<div class="col-md-5" style="background: #FDCE2C; color:#E97C09;border:1px solid#F5C72C;border-radius: 5px;text-align: justify;font-weight: bold;">
				Nota: El proceso ya fue terminado 
			</div>
		<?php }?>
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
