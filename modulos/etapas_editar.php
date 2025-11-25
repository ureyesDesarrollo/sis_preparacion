<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
             FROM preparacion_etapas 
             WHERE pe_id = '".$_POST['hdd_id']."'") or die(mysql_error()."Error: en consultar las etapas");
$registros = mysqli_fetch_assoc($cadena);

?> 
<script>

$(document).ready(function()
{
  $("#formEtapasEditar").submit(function(){
    //alert('editar');
    var formData = $(this).serialize();
    $.ajax({
      url: "etapas_actualizar.php",
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
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar Etapa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    
      <div class="modal-body">  
	  	  <div id="row">   
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descripción:</label>
            <input name="txtEtapaDesc" type="text" class="form-control" id="txtEtapaDesc" disabled placeholder="" value="<?php echo $registros['pe_descripcion'] ?>">
             <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['pe_id'] ?>"/>
          </div>
		  
		  <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Nombre:</label>
            <input name="txtNombre" type="text" class="form-control" id="txtNombre" required placeholder="" value="<?php echo $registros['pe_nombre'] ?>">
             <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['pe_id'] ?>"/>
          </div>
		  
		  <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Hr Ideal:</label>
            <input name="txtHrIdeal" type="text" class="form-control" id="txtHrIdeal" placeholder="Calle" value="<?php echo $registros['pe_hr_ideal'] ?>"onkeypress="return isNumberKey(event, this);"  required>
          </div>
		  
		  <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Hr Maximo:</label>
            <input name="txtHrMax" type="text" class="form-control" id="txtHrMax" placeholder="Calle" value="<?php echo $registros['pe_hr_maxima'] ?>" onkeypress="return isNumberKey(event, this);" required>
          </div>
		  
		  <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Validacion:</label>
		    <select name="cbxValida" class="form-control" id="cbxValida" required>
                 <?php 
                    if ($registros['pe_hr_validacion'] == 0) 
                    {
                        $var_est = "No";
                      }
                  
                  if ($registros['pe_hr_validacion'] == 1) 
                    {
                        $var_est = "Si";
                      }
                  
                   ?>
                      <option value="<?php echo $registros['pe_hr_validacion']; ?>"><?php echo mb_convert_encoding($var_est, "UTF-8")?></option>
                      <?php 
                        if ($registros['pe_hr_validacion'] == 1) {
                          echo '<option value="0">No</option>';
                        }
                        if ($registros['pe_hr_validacion']== 0) {
                          echo '<option value="1">Si</option>';
                        }
                       ?>
            </select>
	    </div>
		</div>
		
		<div class="row">
			<div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Tipo:</label>
            <select name="cbxTipo" class="form-control" id="cbxTipo" disabled>
					 <?php 
			if ($registros['pe_tipo'] == 'C') 
			{
				$var_est = "Ce";
			  }
		  
		  if ($registros['pe_tipo'] == 'H') 
			{
				$var_est = "Hr";
			  }
		  if ($registros['pe_tipo'] == 'P') 
			{
				$var_est = "Ph";
			  }
		   ?>
              <option value="<?php echo $registros['pe_tipo']; ?>"><?php echo mb_convert_encoding($var_est, "UTF-8");?></option>
              <option value="C">Ce</option>
              <option value="H">Hr</option>
              <option value="P">Ph</option>
            </select>
          </div>
		  
		  <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Rango Inicio:</label>
            <input name="txtInicio" type="text" class="form-control" id="txtInicio" placeholder="Inicio" value="<?php echo $registros['pe_inicio'] ?>"onkeypress="return isNumberKey(event, this);"  required>
          </div>
		  
		  <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Rango Fin:</label>
            <input name="txtFin" type="text" class="form-control" id="txtFin" placeholder="Fin" value="<?php echo $registros['pe_fin'] ?>" onkeypress="return isNumberKey(event, this);" required>
          </div>
		</div>
		
		<!--<div class="row">
			<div class="col-md-3">
				<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Control liberación:</label>
				<select name="slcLiberacion" class="form-control" id="slcLiberacion" disabled>
				<?php 
					/* if ($registros['pe_control_lib'] == '1') 
					{
						$var_lib = "Si";
					}
					else
					{
						$var_lib = "No";
					}

			   ?>
					<option value="<?php echo $registros['pe_control_lib']; ?>" selected><?php echo mb_convert_encoding($var_lib, "UTF-8");?></option>
					<option value="1">Si</option>
					<option value="0">No</option>
				</select>
            </div>
			<div class="col-md-2">
				<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Control renglón:</label>
				<select name="slcRenglon" class="form-control" id="slcRenglon" disabled>
				<?php 
					if ($registros['pe_control_renglon'] == '1') 
					{
						$var_lib = "Si";
					}
					else
					{
						$var_lib = "No";
					}

			   ?>
					<option value="<?php echo $registros['pe_control_renglon']; ?>" selected><?php echo mb_convert_encoding($var_lib, "UTF-8");?></option>
					<option value="1">Si</option>
					<option value="0">No</option>
				</select>
            </div>
			<div class="col-md-3">
				<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Control material:</label>
				<select name="slcMaterial" class="form-control" id="slcMaterial" disabled>
				<?php 
					if ($registros['pe_control_material'] == '1') 
					{
						$var_lib = "Si";
					}
					else
					{
						$var_lib = "No";
					}

			   ?>
					<option value="<?php echo $registros['pe_control_material']; ?>" selected><?php echo mb_convert_encoding($var_lib, "UTF-8");?></option>
					<option value="1">Si</option>
					<option value="0">No</option>
				</select>
            </div>
			<div class="col-md-2">
				<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Envio Email:</label>
				<select name="slcEmail" class="form-control" id="slcEmail" required>
				<?php 
					if ($registros['pe_enviar_email'] == '1') 
					{
						$var_lib = "Si";
					}
					else
					{
						$var_lib = "No";
					}

			   ?>
					<option value="<?php echo $registros['pe_enviar_email']; ?>" selected><?php echo mb_convert_encoding($var_lib, "UTF-8");*/?></option>
					<option value="1">Si</option>
					<option value="0">No</option>
				</select>
            </div>
		</div>-->

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