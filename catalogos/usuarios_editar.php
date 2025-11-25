<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();
$cadena = mysqli_query($cnx, "SELECT *
             FROM usuarios 
             WHERE usu_id = '" . $_POST['hdd_id'] . "'") or die(mysql_error() . "Error: en consultar el proveedor");
$registros = mysqli_fetch_assoc($cadena);
?>
  <script>
    /*     $(document).ready(function() {
      $("#formUusuarioEditar").submit(function() {
        //alert('editar');
        var formData = $(this).serialize();
        $.ajax({
          url: "usuarios_actualizar.php",
          type: 'POST',
          data: formData,
          success: function(result) {
            data = JSON.parse(result);
            //alert("Guardo el registro");
            alertas("#alerta-errorUsuEditar", 'Listo!', data["mensaje"], 1, true, 5000);
            //$('#form').each (function(){this.reset();});  
          }
        });
        return false;
      });
    }); */

    $(document).ready(function() {
      $("#formUusuarioEditar").submit(function() {
        var formData = $(this).serialize();

        var texoUser = $("input#txtUserE").val();
        var usuario_anterior = $("input#txtUserE_hdd").val();

        $.ajax({
          url: "extras/getUsuario.php",
          type: 'POST',
          data: {
            "txtUser": texoUser,
            "usuario_anterior": usuario_anterior
          },
          success: function(result) {
            if (result != '') {
              // Mostrar el resultado
              $("#resultadoBusquedaE").html(result);
              $("#resultadoBusquedaE").show(); // Mostrar el div

              // Ocultar el resultado después de 5 segundos
              setTimeout(function() {
                $("#resultadoBusquedaE").hide(); // Ocultar el div
              }, 5000); // 5000 milisegundos = 5 segundos


              document.getElementById("txtUserE").value = "";
              document.getElementById("txtUserE").focus();
            } else {
              $.ajax({
                url: "usuarios_actualizar.php",
                type: 'POST',
                data: formData,
                success: function(result) {
                  data = JSON.parse(result);
                  //alert("Guardo el registro");
                  alertas("#alerta-errorUsuEditar", 'Listo!', data["mensaje"], 1, true, 5000);
                  $('#formUusuarioEditar').each(function() {
                    this.reset();
                  });
                }
              });
              return false;
            }
          }
        });

        return false;
      });
    });
  </script>

  <div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="formUusuarioEditar">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar usuarios</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Nombre:</label>
            <input name="txtNombre" type="text" class="form-control" id="txtNombre" value="<?php echo $registros['usu_nombre'] ?>" maxlength="50" required placeholder="Nombre">
            <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['usu_id'] ?>" />
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Usuario:</label>
            <input name="txtUserE" type="text" class="form-control" id="txtUserE" value="<?php echo $registros['usu_usuario'] ?>" maxlength="25" required placeholder="Usuario" onkeypress="return CheckUserName(event, this);">
            <input name="txtUserE_hdd" type="hidden" class="form-control" id="txtUserE_hdd" value="<?php echo $registros['usu_usuario'] ?>" maxlength="25" required placeholder="Usuario" onkeypress="return CheckUserName(event, this);">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Contraseña:</label>
            <input name="txtPwr" type="password" class="form-control" id="txtPwr" required value="<?php echo $registros['usu_pwr'] ?>">
            <input name="hddPwr" type="hidden" class="form-control" id="hddPwr" required value="<?php echo $registros['usu_pwr'] ?>">
          </div>
          <div class="col-md-3">
            <label for="validationCustom01"><span style="color:#FF0000; font-weight:bold;">*</span> Perfil</label>
            <select name="cbxperfil" class="form-control is-valid" id="cbxperfil" placeholder="" required>
              <option value="">Selecciona...</option>
              <?php
              $list_perfiles = mysqli_query($cnx, "select * from usuarios_perfiles where up_id <> 1 order by up_nombre asc");
              while ($reg_perfiles =  mysqli_fetch_assoc($list_perfiles)) { ?>
                <option value="<?php echo mb_convert_encoding($reg_perfiles['up_id'], "UTF-8");  ?>" <?php if (mb_convert_encoding($reg_perfiles['up_id'], "UTF-8") == $registros['up_id']) { ?>selected="selected" <?php } ?>><?php echo mb_convert_encoding($reg_perfiles['up_nombre'], "UTF-8");  ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label">Correo:</label>
            <input name="txtEmail" type="email" class="form-control" id="txtEmail" placeholder="Email" value="<?php echo $registros['usu_email'] ?>">
          </div>

          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Estatus:</label>
            <select  name="slc_estatus" type="select" class="form-control" id="slc_estatus" required>
            <?php 
              if ($registros['usu_est'] == 'B') 
              {
                  $var_est = "Baja";
                }
            
            if ($registros['usu_est'] == 'A') 
              {
                  $var_est = "Activo";
                }
            
             ?>
                <option value="<?php echo $registros['usu_est']; ?>"><?php echo mb_convert_encoding($var_est, "UTF-8")?></option>
                <?php 
                  if ($registros['usu_est']=='A') {
                    echo '<option value="B">Baja</option>';
                  }
                  if ($registros['usu_est']=='B') {
                    echo '<option value="A">Activo</option>';
                  }
                 ?>
                
      </select>
          </div>

          <div class="modal-footer" style="margin-top: 15%;">
            <!--mensajes-->
            <div class="alert alert-info hide" id="alerta-errorUsuEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
              <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
              <strong>Titulo</strong> &nbsp;&nbsp;
              <span> Mensaje </span>
            </div>
            <div class="col-md-7">
              <div id="resultadoBusquedaE" style="background: #FCD8EC;color: #D3318A;border-radius: 5px;text-align: center;"></div>
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button>
            <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
          </div>
        </div>
      </form>

    </div>
  </div>