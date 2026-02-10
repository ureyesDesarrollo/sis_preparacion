<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

 require_once('../conexion/conexion.php');
 $cnx = Conectarse();
?>
  <script>
    $(document).ready(function() {
      $('#formUusuarioAlta').each(function() {
        this.reset();
      });
    });
  </script>
  <div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="formUusuarioAlta">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Alta usuarios</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Nombre:</label>
            <input name="txtNombre" type="text" class="form-control" id="txtNombre" maxlength="50" required placeholder="Nombre">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Usuario:</label>
            <input  autocomplete="off" name="txtUser" type="text" class="form-control" id="txtUser" maxlength="25" required placeholder="Usuario" onkeypress="return CheckUserName(event, this);">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Contraseña:</label>
            <input  autocomplete="off" name="txtPwr" type="password" class="form-control" id="txtPwr" required placeholder="Contraseña">
          </div>
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Perfil:</label>
            <select name="cbxperfil" type="email" class="form-control" id="cbxperfil" required>
              <option value="">Seleccionar</option>
              <?php
              $query =  mysqli_query($cnx, "SELECT up_id, up_nombre FROM usuarios_perfiles WHERE up_id <> 1 order by up_nombre asc");
              while ($row = mysqli_fetch_array($query)) { ?>
                <option value="<?php echo mb_convert_encoding($row['up_id'], "UTF-8");  ?>"><?php echo mb_convert_encoding($row['up_nombre'], "UTF-8");  ?></option>
              <?php }
              ?>
            </select>
          </div>
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label">Correo:</label>
            <input name="txtEmail" type="email" class="form-control" id="txtEmail" placeholder="Email">
          </div>

          <div class="modal-footer" style="margin-top: 15%;">
            <!--mensajes-->
            <div class="alert alert-info hide" id="alerta-errorProvAlta" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
              <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
              <strong>Titulo</strong> &nbsp;&nbsp;
              <span> Mensaje </span>
            </div>
            <div class="col-md-7">
              <div id="resultadoBusqueda" style="background: #FCD8EC;color: #D3318A;border-radius: 5px;text-align: center;"></div>
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button>
            <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
          </div>
        </div>
      </form>

    </div>
  </div>