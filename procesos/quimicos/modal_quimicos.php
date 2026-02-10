<?php
/*Desarrollado por: Ca & Ce Technologies */
/*16 - Octubre - 2021*/
require '../../conexion/conexion.php';
require '../funciones_procesos.php';
include('../../seguridad/user_seguridad.php');
$cnx =  Conectarse();
header("Content-Type: text/html;charset=utf-8");
extract($_POST);


$cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos_etapas WHERE pro_id = '$pro_id' and pe_id = '$pe_id'");
$reg_quim = mysqli_fetch_array($cad_quim);
$tot = mysqli_num_rows($cad_quim);

$cad_etapas = mysqli_query($cnx, "SELECT * FROM preparacion_etapas WHERE pe_id = '$pe_id'");
$reg_etapas = mysqli_fetch_array($cad_etapas);

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$pro_id' and pe_id = '$pe_id'");
$reg_aux = mysqli_fetch_array($cad_aux);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>


  <script>
    $(document).ready(function() {
      $("#form_quimicos").submit(function() {

        var formData = $(this).serialize();
        $.ajax({
          url: "quimicos/quimicos_insertar.php",
          type: 'POST',
          data: formData,
          success: function(result) {

            data = JSON.parse(result);
            alertas("#alerta", 'Listo!', data["mensaje"], 1, true, 5000);
            //$('#form_quimicos').each (function(){this.reset();}); 
            //setTimeout("location.reload()", 2000); 

            var pe_id = document.getElementById('pe_id').value;
            var pro_id = document.getElementById('pro_id').value;



            cargarQuimicos('#cargar_quimicos', 'quimicos/get_quimicos.php?pro_id=' + pro_id + '&pe_id=' + pe_id + ' ');

          }
        });
        return false;

      });
    });


    function cargarQuimicos(div, desde) {
      $(div).load(desde);
    }
  </script>
  <!--<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">-->
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <form id="form_quimicos">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="text-align: center;font-size: 24px;text-transform: uppercase;"><img src="../iconos/matraz.png" alt="">QUÍMICOS <?php echo $reg_etapas['pe_nombre']; ?> (<?php echo $reg_etapas['pe_descripcion']; ?>) <img src="../iconos/matraz.png" alt=""></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <input type="hidden" value="<?php echo $pe_id ?>" id='pe_id' name="pe_id">
        <input type="hidden" value="<?php echo $pro_id ?>" id='pro_id' name="pro_id">
        <div class="modal-body" id="cargar_quimicos">

          <div class="row">
            <div class="col-md-3">
              <label for="recipient-name" class="col-form-label">Fecha sistema</label>
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
                  <input maxlength="5"  readonly type="text" placeholder="Litro" class="form-control" onKeyPress="return isNumberKey(event, this);" id="txt_litro_quim<?php echo $i ?>" name="txt_litro_quim<?php echo $i ?>" value="<?php echo $reg_quim['quim_litros'] ?>">
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
        </div>
        <div class="modal-footer">
          <div class="form-group col-md-8">
            <div class="alert alert-info hide " id="alerta" style="height: 30px">
              <div style="margin-top: -10px">
                <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                <strong>Titulo</strong> &nbsp;&nbsp;
                <span> Mensaje </span>
              </div>
            </div>
          </div>
          <div class="form-group col-md-4">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();">Cerrar</button>
            <?php
            if ($reg_aux['pro_id'] != '') { ?>
              <button type="submit" class="btn btn-primary">Guardar</button>
            <?php } else {
              echo "N/A Guardar";
            } ?>

          </div>

        </div>
      </form>
    </div>
  </div>
  <!--</div>-->
</body>

</html>