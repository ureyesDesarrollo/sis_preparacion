<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../../conexion/conexion.php";
$cnx =  Conectarse();

extract($_GET);
?>
<form id="form_remojo">
    <div class="row renglones" id="titulos">
        <input type="hidden" name="hdd_id_equipo" value="<?php echo $id_e ?>">
        <input type="hidden" name="hdd_id_pelambre" value="<?php echo $reg_pelambre['ip_id'] ?>">
        <!-- <div class="col-md-1">
					<label for="formFile" class="form-label">Bultos</label>
				</div> -->
        <div class="col-md-1">
            <label for="formFile" class="form-label">%</label>
        </div>
        <div class="col-md-1">
            <label for="formFile" class="form-label">Cantidad</label>
        </div>
        <div class="col-md-1" style="text-align: center;">
            <label for="formFile" class="form-label"></label>
        </div>
        <div class="col-md-1">
            <label for="formFile" class="form-label">Material</label>
        </div>
        <div class="col-md-1">
            <label for="formFile" class="form-label">Horas</label>
        </div>
        <div class="col-md-1">
            <label for="formFile" class="form-label">Minutos</label>
        </div>
        <div class="col-md-2">
            <label for="formFile" class="form-label">Fecha/hora inicio</label>
        </div>
        <div class="col-md-2">
            <label for="formFile" class="form-label">Fecha/hora final</label>
        </div>
        <div class="col-md-2">
            <label for="formFile" class="form-label">Obs</label>
        </div>
    </div>
    <?php
    $etiqueta = 0;
    $material = 0;

    for ($i = 1; $i <= 3; $i++) {

        /* PORCENTAJES */
        if ($i == 1) {
            $porcentaje = "100";
            $cantidad = $inventario['inv_kilos'];
        }
        if ($i == 2) {
            $porcentaje = "0.4";
            $cantidad = $inventario['inv_kilos'] * 0.004;
        }
        if ($i == 3) {
            $porcentaje = "0.2";
            $cantidad = $inventario['inv_kilos'] * 0.002;
        }


        /* ETIQUETAS */
        if ($i == 1) {
            $etiqueta = "Litros";
        } else {
            $etiqueta = "Kilos";
        }

        /* MATERIAL */
        if ($i == 1) {
            $consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'agua'");
            $reg = mysqli_fetch_assoc($consulta);
            $material = $reg['quimico_descripcion'];
        }
        if ($i == 2) {
            $consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'carbonato'");
            $reg = mysqli_fetch_assoc($consulta);
            $material = $reg['quimico_descripcion'];
        }
        if ($i == 3) {
            $consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'Sulfhidrato'");
            $reg = mysqli_fetch_assoc($consulta);
            $material = $reg['quimico_descripcion'];
        }


        /* ETIQUETA TIEMPOS */
        if ($i == 3) {
            $horas = "16 horas";
			$valor_horas = "16";
        }
        if ($i == 3) {
            $minutos = "";
        }

        #consulta información capturada en fases remojo y pelambre
        $pelambre1 = mysqli_query($cnx, "SELECT * FROM 
				inventario_pelambre_etapas_1 WHERE ipe_ren = '$i' and ipe_etapa = '1' and ip_id = " . $reg_pelambre['ip_id'] . "");
        $pelambre1 = mysqli_fetch_assoc($pelambre1);
    ?>

        <div class="row g-3 align-items-center renglones" id="<?php echo "renglon" . $i ?>">
            <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "hdd_id" . $i ?>" name="<?php echo "hdd_id" . $i ?>" value="<?php echo $pelambre1['ipe_id'] ?>" readonly>
            <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_renglon" . $i ?>" name="<?php echo "txt_renglon" . $i ?>" value="<?php echo $i ?>" readonly>
            <!-- <div class="col-md-1">
						<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_bultos" . $i ?>" name="<?php echo "txt_bultos" . $i ?>">
					</div> -->
            <div class="col-md-1">
                <input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_porcentaje" . $i ?>" name="<?php echo "txt_porcentaje" . $i ?>" value="<?php echo $porcentaje ?>" readonly>
            </div>
            <div class="col-md-1">
                <?php
                if ($pelambre1['ipe_cantidad'] != '') {
                    $res_cantidad = $pelambre1['ipe_cantidad'];
                } else {
                    $res_cantidad = $cantidad;
                }
                ?>
                <input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_cantidad" . $i ?>" name="<?php echo "txt_cantidad" . $i ?>" value="<?php echo $res_cantidad ?>" readonly>
            </div>
            <div class="col-md-1" style="text-align: center;">
                <label for="inputPassword6" class="col-form-label"><?php echo $etiqueta ?></label>
            </div>
            <div class="col-md-1">
                <input type="hidden" class="form-control" value="<?php echo $reg['quimico_id'] ?>" onkeypress="return isNumberKey(event, this);" id="<?php echo "hdd_id_mat" . $i ?>" name="<?php echo "hdd_id_mat" . $i ?>" readonly>
                <input type="text" class="form-control" value="<?php echo $material ?>" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_quimico" . $i ?>" name="<?php echo "txt_quimico" . $i ?>" readonly>
            </div>
            <div class="col-md-1" style="text-align: center;">
                <?php
                if ($pelambre1['ipe_horas'] != '') {
                    $res_horas = $pelambre1['ipe_horas'];
                } else {
                    $res_horas = $horas;
                }
                ?>
                <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_horas" . $i ?>" name="<?php echo "txt_horas" . $i ?>" value="<?php echo $valor_horas ?>">
                <label for="inputPassword6" class="col-form-label"><?php echo $res_horas ?></label>
            </div>
            <div class="col-md-1" style="text-align: center;">
                <?php
                if ($pelambre1['ipe_minutos'] != '') {
                    $res_minutos = $pelambre1['ipe_minutos'];
                } else {
                    $res_minutos = $minutos;
                }
                ?>
                <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_minutos" . $i ?>" name="<?php echo "txt_minutos" . $i ?>" value="<?php echo $res_minutos ?>">
                <label for="inputPassword6" class="col-form-label"><?php echo $res_minutos ?></label>
            </div>
            <div class="col-md-2">
                <?php
                /*  echo "aqui ->" . $con  = $i - 1; */
                if ($pelambre1['ipe_fe_hr_inicio'] != '') {
                    $res_hora_ini = $pelambre1['ipe_fe_hr_inicio'];
                    $readonly = 'readonly';
                } else {
                    $res_hora_ini = '';
                    $readonly = '';
                }
                ?>
                <input type="datetime-local" class="form-control" id="<?php echo "txt_fe_inicio" . $i ?>" name="<?php echo "txt_fe_inicio" . $i ?>" value="<?php echo $res_hora_ini ?>" <?php echo $readonly ?>>
            </div>
            <div class="col-md-2">
                <?php
                if (!empty($pelambre1['ipe_fe_hr_fin'])) {
                    $res_hora_fin = $pelambre1['ipe_fe_hr_fin'];
                    $readonly = 'readonly';
                } else {
                    $res_hora_fin = '';
                    $readonly = '';
                }
                ?>
                <input type="datetime-local" class="form-control" id="<?php echo "txt_fe_final" . $i ?>" name="<?php echo "txt_fe_final" . $i ?>" value="<?php echo $res_hora_fin ?>" <?php echo $readonly ?>>
            </div>
            <div class="col-md-2">
                <?php
                if (!empty($pelambre1['ipe_observaciones'])) {
                    $res_obs = $pelambre1['ipe_observaciones'];
                    $readonly = 'readonly';
                } else {
                    $res_obs = '';
                    $readonly = '';
                } ?>
                <input type="text" class="form-control" id="<?php echo "txt_obs" . $i ?>" name="<?php echo "txt_obs" . $i ?>" value="<?php echo $res_obs ?>" <?php echo $readonly ?>>
            </div>
        </div>
    <?php }

    $renglon += $i;
    ?>
    <div class="row renglones">
        <div class="col-md-4">
            <?php
            if (!empty($reg_pelambre['ip_fe_hr_ter_remojo']) and $reg_pelambre['ip_fe_hr_ter_remojo'] != '0000-00-00 00:00:00') {
                $res_term_rem = $reg_pelambre['ip_fe_hr_ter_remojo'];
                $readonly = 'readonly';
            } else {
                $res_term_rem = '';
                $readonly = '';
            } ?>
            <label for="formFile" class="form-label">Hora termina remojo</label>
            <input type="datetime-local" class="form-control" id="txt_hora_termina_remojo" name="<?php echo "txt_hora_termina_remojo" ?>" value="<?php echo $res_term_rem ?>" <?php echo $readonly ?>>
        </div>
        <!--mensajes-->
        <div class="col-md-6">
            <div id="alerta-accion_remojo" class="alert d-none" style="margin-top:2rem;">
                <strong class="alert-heading">¡Error!</strong>
                <span class="alert-body"></span>
            </div>
        </div>
        <?php if ($_SESSION['privilegio'] == 3) { ?>
            <div class="col-md-2" id="boton">
                <button style="margin-top:2rem;" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xl" style="color: #000;"></i> Guardar</button>
            </div>
        <?php } ?>

    </div>
</form>

<script>
    $(document).ready(function() {
        /*   var fe_ini_primero = $("#txt_fe_inicio1").val();
          var fe_fin_primero = $("#txt_fe_final1").val();
          var obs_primero = $("#txt_obs1").val();

          var fe_ini_segundo = $("#txt_fe_inicio2").val();
          var fe_fin_segundo = $("#txt_fe_final2").val();
          var obs_segundo = $("#txt_obs2").val();

          var fe_ini_tercero = $("#txt_fe_inicio3").val();
          var fe_fin_tercero = $("#txt_fe_final3").val();
          var obs_tercero = $("#txt_obs3").val();

          if (fe_ini_primero != "" && fe_fin_primero != "" && obs_primero !== "") {
              // Itera sobre los elementos y establece el atributo "readonly" en true
              $("#txt_fe_inicio2").removeAttr('readonly');
              $("#txt_fe_final2").removeAttr('readonly');
              $("#txt_obs2").removeAttr('readonly');

              if (fe_ini_segundo != "") {
                  $("#txt_fe_inicio2").prop('readonly', true);
              }
              if (fe_fin_segundo != "") {
                  $("#txt_fe_final2").prop('readonly', true);
              }
              if (obs_segundo != "") {
                  $("#txt_obs2").prop('readonly', true);
              }
          } else {
              $("#txt_fe_inicio2").prop('readonly', true);
              $("#txt_fe_final2").prop('readonly', true);
              $("#txt_obs2").prop('readonly', true);
          }

          if (fe_ini_segundo != "" && fe_fin_segundo != "" && obs_segundo !== "") {
              // Itera sobre los elementos y establece el atributo "readonly" en true
              $("#txt_fe_inicio3").removeAttr('readonly');
              $("#txt_fe_final3").removeAttr('readonly');
              $("#txt_obs3").removeAttr('readonly');

              if (fe_ini_tercero != "") {
                  $("#txt_fe_inicio3").prop('readonly', true);
              }
              if (fe_fin_tercero != "") {
                  $("#txt_fe_final3").prop('readonly', true);
              }
              if (obs_tercero != "") {
                  $("#txt_obs3").prop('readonly', true);
              }
          } else {
              $("#txt_fe_inicio3").prop('readonly', true);
              $("#txt_fe_final3").prop('readonly', true);
              $("#txt_obs3").prop('readonly', true);
          } */
    });
</script>