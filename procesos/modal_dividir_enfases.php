  <?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
  include "../conexion/conexion.php";
  include "../funciones/funciones.php";
  $cnx =  Conectarse();

  $cadena = mysqli_query($cnx, "SELECT *
   FROM inventario 
   WHERE inv_id = '".$_POST['inv_id']."'") or die(mysql_error()."Error: en consultar el inventario");
  $registros = mysqli_fetch_assoc($cadena);

  ?> 

  <script>

    $(document).ready(function()
    {
      $("#formModal").submit(function(){
    //alert('editar');
    var formData = $(this).serialize();
    $.ajax({
      url: "inventario_dividir.php",
      type: 'POST',
      data: formData,
      success: function(result) {
        data = JSON.parse(result);
        //alert("Guardo el registro");
        alertas("#alerta-errorTipoEditar", 'Listo!', data["mensaje"], 1, true, 5000);
        //$('#form').each (function(){this.reset();});  
      }
    });
    return confirmEnviar2();
    return false;
  });
    });
	
	function fnc_restaCantidad()
	{
		if(document.getElementById("txtToma").value < parseInt(document.getElementById("txtKg").value))
		{
			var val = document.getElementById("txtKg").value - document.getElementById("txtToma").value;
			
			document.getElementById("txtSobra").value = val;
      document.getElementById("txtSobraH").value = val;
		}
		else
		{
			alert("La cantidad a tomar debe ser menor");
			document.getElementById("txtToma").value = 0;
		}
		
	}


  //Bloquear boton al dividir material (comente codigo en bitacora y copie a modal_dividir)
    function confirmEnviar2() {

      formModal.btn.disabled = true; 
      formModal.btn.value = "Enviando...";

      setTimeout(function(){
        formModal.btn.disabled = true;
        formModal.btn.value = "Guardar";
      },2000);

      var statSend = false;
      return false;
    }




  </script>


  <div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="formModal" name="formModal"> 
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Dividir kilos en fases</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">       
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">Kilos totales:</label>
            <input name="txtKg" type="text" class="form-control" id="txtKg" required placeholder="" value="<?php echo $registros['inv_kg_totales'] ?>" readonly="true">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label">Cantidad a tomar:</label>
            <input name="txtToma" type="text" class="form-control" id="txtToma" required placeholder="Cantidad" value="" onchange="fnc_restaCantidad();">
            <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['inv_id'] ?>"/>
          </div>
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label">Sobrante:</label>
            <input name="txtSobra" type="text" class="form-control" id="txtSobra" required placeholder="" value="" readonly="true">
            <input name="hdd_param" type="hidden" id="hdd_param" value="<?php echo $_POST['param']; ?>"/>
          </div>
          <div class="modal-footer" style="margin-top: 8%;">
            <!--mensajes-->
            <div class="alert alert-info hide" id="alerta-errorTipoEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
              <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
              <strong>Titulo</strong> &nbsp;&nbsp;
              <span> Mensaje </span>
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="kilos(<?php echo $_POST['param']; ?>);kilos2(<?php echo $_POST['param']; ?>)"><img src="../iconos/close.png" alt="">Cerrar</button>
            <button class="btn btn-primary" type="submit" id="btn" name="btn"><img src="../iconos/guardar.png" alt=""> Guardar</button>
          </div>
        </div>
      </form>

    </div>
  </div>