<form id="form_preblanqueo">
    <?php
    #consulta información capturada en fases remojo y pelambre
    $pelambre5 = mysqli_query($cnx, "SELECT * FROM 
			inventario_pelambre_etapas_2 WHERE ipe_ren = '1' and ipe_etapa = '5' and ip_id = " . $reg_pelambre['ip_id'] . "");
    $reg_pelambre5 = mysqli_fetch_assoc($pelambre5);
    ?>
    <div class="row renglones" id="titulos">
        <div class="col">
            <input type="hidden" name="hdd_id_pelambre" value="<?php echo $reg_pelambre['ip_id'] ?>">
            <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "hdd_id1" ?>" name="<?php echo "hdd_id1" ?>" value="<?php echo $reg_pelambre5['ipe_id'] ?>" readonly>
            <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_renglon1" ?>" name="<?php echo "txt_renglon1" ?>" value="1" readonly>

            <?php if ($reg_pelambre5['ipe_fe_inicio'] != '') {
                $res_fe_ini_bla = $reg_pelambre5['ipe_fe_inicio'];
                $readonly = 'readonly';
            } else {
                $res_fe_ini_bla = '';
                $readonly = '';
            }
            ?>
            <label for="formFile" class="form-label">Fecha inicio</label>
            <input type="date" class="form-control" required value="<?php echo $res_fe_ini_bla ?>" <?php echo $readonly ?> id="txt_fe_ini_bla1" name=" txt_fe_ini_bla1">
        </div>
        <div class="col">
            <?php if ($reg_pelambre5['ipe_hr_inicio'] != '') {
                $res_hora_ini_bla = $reg_pelambre5['ipe_hr_inicio'];
                $readonly = 'readonly';
            } else {
                $res_hora_ini_bla = '';
                $readonly = '';
            }
            ?>
            <label for="formFile" class="form-label">Hora inicio</label>
            <input type="time" class="form-control" required value="<?php echo $res_hora_ini_bla ?>" <?php echo $readonly ?> id="txt_hora_ini_bla1" name=" txt_hora_ini_bla1">
        </div>
        <div class="col">
            <?php if ($reg_pelambre5['ipe_hr_fin'] != '') {
                $res_hora_fin_bla = $reg_pelambre5['ipe_hr_fin'];
                $readonly = 'readonly';
            } else {
                $res_hora_fin_bla = '';
                $readonly = '';
            }
            ?>
            <label for="formFile" class="form-label">Hora termino</label>
            <input type="time" class="form-control" required value="<?php echo $res_hora_fin_bla ?>" <?php echo $readonly ?> id="txt_hora_fin_bla1" name=" txt_hora_fin_bla1">
        </div>
        <div class="col-md-1">
            <?php if ($reg_pelambre5['ipe_ph'] != '') {
                $res_ph_bla = $reg_pelambre5['ipe_ph'];
                $readonly = 'readonly';
            } else {
                $res_ph_bla = '';
                $readonly = '';
            }
            ?>
            <label for="formFile" class="form-label">PH</label>
            <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" required value="<?php echo $res_ph_bla ?>" <?php echo $readonly ?> id="txt_ph_bla1" name=" txt_ph_bla1">
        </div>
        <div class="col-md-1">
            <?php if ($reg_pelambre5['ipe_redox'] != '') {
                $res_redox_bla = $reg_pelambre5['ipe_redox'];
                $readonly = 'readonly';
            } else {
                $res_redox_bla = '';
                $readonly = '';
            }
            ?>
            <label for="formFile" class="form-label">REDOX</label>
            <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" required value="<?php echo $res_redox_bla ?>" <?php echo $readonly ?> id="txt_redox_bla1" name=" txt_redox_bla1">
        </div>
        <div class="col-md-1">
            <?php if ($reg_pelambre5['ipe_ce'] != '') {
                $res_ce_bla = $reg_pelambre5['ipe_ce'];
                $readonly = 'readonly';
            } else {
                $res_ce_bla = '';
                $readonly = '';
            }
            ?>
            <label for="formFile" class="form-label">CE</label>
            <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" required value="<?php echo $res_ce_bla ?>" <?php echo $readonly ?> id="txt_ce_bla1" name=" txt_ce_bla1">
        </div>
        <div class="col">
            <?php if ($reg_pelambre['ip_hrs_totales'] != '') {
                $res_hora_tot_bla = $reg_pelambre['ip_hrs_totales'];
                $readonly = 'readonly';
            } else {
                $res_hora_tot_bla = '';
                $readonly = '';
            }
            ?>
            <label for="formFile" class="form-label">Hrs totales proceso</label>
            <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" required value="<?php echo $res_hora_tot_bla ?>" <?php echo $readonly ?> id="txt_horas_tot_bla1" name=" txt_horas_tot_bla1">
        </div>

        <?php if ($_SESSION['privilegio'] == 3) { ?>
            <div class="col" id="boton">
                <button id="btnGuardar_blanco" style="margin-top:2rem;" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xl" style="color: #000;"></i> Guardar</button>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-3">
        <div id="alerta-accion_preblanqueo" class="alert d-none" style="margin-top:2rem;">
            <strong class="alert-heading">¡Error!</strong>
            <span class="alert-body"></span>
        </div>
    </div>
</form>