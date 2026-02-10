<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include('../generales/menu.php');
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');

$cnx = Conectarse();
$cadena = mysqli_query($cnx, "SELECT *
             FROM parametros ") or die(mysql_error() . "Error: en consultar el proveedor");
$registros = mysqli_fetch_assoc($cadena);
?>
<link rel="stylesheet" href="../css/estilos_catalogos.css">
<script>
   $(document).ready(function() {
     $("#formParametrosEditar").submit(function() {
       //alert('editar');
       var formData = $(this).serialize();
       $.ajax({
         url: "parametros_actualizar.php",
         type: 'POST',
         data: formData,
         success: function(result) {
           data = JSON.parse(result);
           //alert("Guardo el registro");
           alertas("#alerta-errorPEditar", 'Listo!', data["mensaje"], 1, true, 5000);
           //$('#form').each (function(){this.reset();});  
         }
       });
       return false;
     });
   });


	function refresh() {
		location.reload();
	}


	//validar que solo ingrese numeros  y letras
	function CheckUserName() {
		if (
			(event.keyCode > 47 && event.keyCode < 58) ||
			(event.keyCode > 64 && event.keyCode < 91) ||
			(event.keyCode > 96 && event.keyCode < 123)
		)

			return true;
		return false;
	}
</script>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="formParametrosEditar">

        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar parametros</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Días en rojo:</label>
            <input name="txt_dias_r" type="text" class="form-control" id="txt_dias_r" value="<?php echo $registros['rojo'] ?>" maxlength="50" required placeholder="Nombre">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Días en amarillo:</label>
            <input name="txt_dias_a" type="text" class="form-control" id="txt_dias_a" value="<?php echo $registros['amarillo'] ?>" maxlength="25" required placeholder="Usuario" onkeypress="return CheckUserName(event, this);">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Días en verde:</label>
            <input name="txt_dias_v" type="text" class="form-control" id="txt_dias_v" required value="<?php echo $registros['verde'] ?>">
          </div>
         
          <div class="col-md-3">
            <label for="validationCustom01"><span style="color:#FF0000; font-weight:bold;">*</span> Parametro producción</label>
            <input name="txt_ton" type="text" class="form-control" id="txt_ton" required value="<?php echo $registros['ton_produccion'] ?>">
          </div>
          <div class="modal-footer" style="margin-top: 15%;">
            <!--mensajes-->
            <div class="alert alert-info hide" id="alerta-errorPEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
              <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
              <strong>Titulo</strong> &nbsp;&nbsp;
              <span> Mensaje </span>
            </div>
            <div class="col-md-7">
              <div id="resultadoBusquedaE" style="background: #FCD8EC;color: #D3318A;border-radius: 5px;text-align: center;"></div>
            </div>
            
            <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
          </div>
        </div>
      </form>

    </div>
</div>
<?php include "../generales/pie_pagina.php"; ?>