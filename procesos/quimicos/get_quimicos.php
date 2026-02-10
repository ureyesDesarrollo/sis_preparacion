<?php
include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();

extract($_GET);
$cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos_etapas WHERE pro_id = '$pro_id' and pe_id = '$pe_id'");
$reg_quim = mysqli_fetch_array($cad_quim);
$tot = mysqli_num_rows($cad_quim);

?>

<div class="row">
  <div class="col-md-3">
    <label for="recipient-name" class="col-form-label">Fecha sistema:</label>
  </div>
  <div class="col-md-4">
    <label for="recipient-name" class="col-form-label">Tipo químico:</label>
  </div>
  <div class="col-md-3">
    <label for="recipient-name" class="col-form-label">Lote:</label>
  </div>
  <div class="col-md-2">
    <label for="recipient-name" class="col-form-label">Litros:</label>
  </div>
</div>
<?php
if ($tot != 0) {


  do { ?>
    <div class="row">
      <div class="col-md-3">
        <input readonly type="text" placeholder="" class="form-control" name="txt_fecha<?php echo $i ?>" value="<?php echo $reg_quim['quim_fecha'] ?>">
      </div>
      <div class="col-md-4">
        <select readonly id="cbx_quimico<?php echo $i  ?>" class="form-control" name="cbx_quimico<?php echo $i; ?>">
          <option value="">Seleccionar</option>
          <?php
          $cadena =  mysqli_query($cnx, "SELECT * from quimicos WHERE quimico_est = 'A' ORDER BY quimico_descripcion");
          $registros =  mysqli_fetch_array($cadena);



          do {
          ?><option value="<?php echo $registros['quimico_id'] ?>" <?php if ($registros['quimico_id'] == $reg_quim['quimico_id']) { ?>selected="selected" <?php } ?>>
              <?php echo $registros['quimico_descripcion'] ?>
            </option><?php
                    } while ($registros =  mysqli_fetch_array($cadena));

                      ?>
        </select>
      </div>
      <div class="col-md-3">
        <input maxlength="20" readonly type="text" placeholder="Lote" class="form-control" id="txt_lote_quim<?php echo $i ?>" name="txt_lote_quim<?php echo $i ?>" value="<?php echo $reg_quim['quim_lote'] ?>">
      </div>

      <div class="col-md-2">
        <input maxlength="5" readonly type="text" placeholder="Litro" class="form-control" onKeyPress="return isNumberKey(event, this);" id="txt_litro_quim<?php echo $i ?>" name="txt_litro_quim<?php echo $i ?>" value="<?php echo $reg_quim['quim_litros'] ?>">
      </div>
    </div>
  <?php
  } while ($reg_quim = mysqli_fetch_array($cad_quim));
}
$res = 7 - $tot;
for ($i = 1; $i <= $res; $i++) { ?>
  <div class="row">
    <div class="col-md-3">
      <input readonly type="text" placeholder="" class="form-control" name="txt_fecha<?php echo $i ?>" value="<?php echo date("Y-m-d") ?>">
    </div>
    <div class="col-md-4">
      <select type="text" placeholder="Lote" class="form-control" id="cbx_quimico<?php echo $i ?>" name="cbx_quimico<?php echo $i ?>">
        <option value="">Selecciona</option>
        <?php
        $cadena =  mysqli_query($cnx, "SELECT * from quimicos WHERE quimico_est = 'A' ORDER BY quimico_descripcion");
        $registros =  mysqli_fetch_array($cadena);
        do {
        ?><option value="<?php echo $registros['quimico_id'] ?>"><?php echo $registros['quimico_descripcion'] ?></option><?php
                                                                                                                        } while ($registros =  mysqli_fetch_array($cadena));

                                                                                                                          ?>
      </select>
    </div>
    <div class="col-md-3">
      <input maxlength="20" type="text" placeholder="Lote" class="form-control" id="txt_lote_quim<?php echo $i ?>" name="txt_lote_quim<?php echo $i ?>" value="<?php echo $reg_quim['quim_lote'] ?>">
    </div>

    <div class="col-md-2">
      <input maxlength="5" type="text" placeholder="Litro" class="form-control" onKeyPress="return isNumberKey(event, this);" id="txt_litro_quim<?php echo $i ?>" name="txt_litro_quim<?php echo $i ?>" value="<?php echo $reg_quim['quim_litros'] ?>">
    </div>
  </div>
<?php }  ?>