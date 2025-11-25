<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../conexion/conexion.php";
$cnx =  Conectarse();

extract($_GET);
?>
<style>
    /* Estilos para el campo de observaciones */
    .observaciones {
        transition: height 0.3s ease, width 0.3s ease;
    }

    .observaciones:focus {
        width: calc(100% + 60px);
    }
</style>
<form id="form_pelambre">
    <div class="row renglones" id="titulos">
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
            <label for="formFile" class="form-label">Lote</label>
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
        <div class="col-md-1">
            <label for="formFile" class="form-label">Obs</label>
        </div>
    </div>
    <?php

    for ($i = $renglon; $i <= 10; $i++) {

        /* PORCENTAJES */
        if ($i == 4) {
            $porcentaje = "50";
            $cantidad = $inventario['inv_kilos'] * 0.50;
        }
        if ($i == 5) {
            $porcentaje = "0.8";
            $cantidad = $inventario['inv_kilos'] * 0.008;
        }
        if ($i == 6 || $i == 9 || $i == 10 || $i == 11) {
            $porcentaje = "1";
            $cantidad = $inventario['inv_kilos'] * 0.01;
        }
        if ($i == 7 || $i == 8) {
            $porcentaje = "0.5";
            $cantidad = $inventario['inv_kilos'] * 0.005;
        }


        /* ETIQUETAS */
        if ($i == 4) {
            $etiqueta = "Litros";
        } else {
            $etiqueta = "Kilos";
        }

        /* MATERIAL */
        if ($i == 4) {
            $consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'agua'");
            $reg = mysqli_fetch_assoc($consulta);
            $material = $reg['quimico_descripcion'];
        }

        if ($i == 5) {
            $consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'Sulfhidrato'");
            $reg = mysqli_fetch_assoc($consulta);
            $material = $reg['quimico_descripcion'];
        }
        if ($i == 6 || $i == 9 || $i == 10) {
            $consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'cal'");
            $reg = mysqli_fetch_assoc($consulta);
            $material = $reg['quimico_descripcion'];
        }
        if ($i == 7 || $i == 8) {
            $consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'Sulfuro'");
            $reg = mysqli_fetch_assoc($consulta);
            $material = $reg['quimico_descripcion'];
        }


        /* ETIQUETA TIEMPOS */
        if ($i == 4) {
            $horas = "";
            $minutos = "";
        }
        if ($i == 5 || $i == 8 || $i == 9) {
            $horas = "";
            $minutos = "90 Minutos";
            $valor_minutos = "90";
        }
        if ($i == 6 || $i == 7) {
            $horas = "";
            $minutos = "60 Minutos";
            $valor_minutos = "60";
        }
        if ($i == 10) {
            $horas = "3 Horas";
            $minutos = "";
            $valor_horas = "3";
        }
        if ($i == 11) {
            $horas = "2 Horas";
            $minutos = "";
            $valor_horas = "2";
        }

        #consulta información capturada en fases remojo y pelambre
        $pelambre1 = mysqli_query($cnx, "SELECT * FROM 
				inventario_pelambre_etapas_1 WHERE ipe_ren = '$i' and ipe_etapa = '2' and ip_id = " . $reg_pelambre['ip_id'] . "");
        $pelambre1 = mysqli_fetch_assoc($pelambre1);

        if ($i == 4) { ?>
            <div class="row g-3 align-items-center renglones">
                <input type="hidden" name="hdd_id_equipo" value="<?php echo $id_e ?>">
                <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "hdd_id" . $i ?>" name="<?php echo "hdd_id" . $i ?>" value="<?php echo $pelambre1['ipe_id'] ?>" readonly>
                <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_renglon" . $i ?>" name="<?php echo "txt_renglon" . $i ?>" value="<?php echo $i ?>" readonly> <!-- <div class="col-md-1">
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
                <div class="col-md-1">
                    <?php
                    if (!empty($pelambre1['ipe_lote'])) {
                        $res_lote = $pelambre1['ipe_lote'];
                        $readonly = 'readonly';
                    } else {
                        $res_lote = '';
                        $readonly = '';
                    } ?>
                    <input type="text" class="form-control"  id="<?php echo "txt_lote" . $i ?>" name="<?php echo "txt_lote" . $i ?>" value="<?php echo $res_lote ?>" <?php echo $readonly ?>>
                </div>
                <div class="col-md-1" style="text-align: center;">
                    <?php
                    if ($pelambre1['ipe_horas'] != '') {
                        $res_horas = $pelambre1['ipe_horas'];
                    } else {
                        $res_horas = $valor_horas;
                    }
                    ?>
                    <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_horas" . $i ?>" name="<?php echo "txt_horas" . $i ?>" value="<?php echo $res_horas ?>">
                    <label for="inputPassword6" class="col-form-label"> <label for="inputPassword6" class="col-form-label"><?php echo $horas ?></label>
                    </label>
                </div>
                <div class="col-md-1" style="text-align: center;">
                    <?php
                    if ($pelambre1['ipe_minutos'] != '') {
                        $res_minutos = $pelambre1['ipe_minutos'];
                    } else {
                        $res_minutos = $valor_minutos;
                    }
                    ?>
                    <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_minutos" . $i ?>" name="<?php echo "txt_minutos" . $i ?>" value="<?php echo $res_minutos ?>">
                    <label for="inputPassword6" class="col-form-label"><?php echo $minutos ?></label>
                </div>
                <div class="col-md-2">
                    <?php if ($pelambre1['ipe_fe_hr_inicio'] != '') {
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
                <div class="col-md-1">
                    <?php
                    if (!empty($pelambre1['ipe_observaciones'])) {
                        $res_obs = $pelambre1['ipe_observaciones'];
                        $readonly = 'readonly';
                    } else {
                        $res_obs = '';
                        $readonly = '';
                    } ?>
                    <label for="formFile" class="form-label" id="etiqueta_niveles">BAJAR NIVEL DE AGUA LO MAS POSIBLE</label>
                </div>
            </div>

        <?php } else { ?>
            <div class="row g-3 align-items-center renglones">
                <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "hdd_id" . $i ?>" name="<?php echo "hdd_id" . $i ?>" value="<?php echo $pelambre1['ipe_id'] ?>" readonly>
                <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_renglon" . $i ?>" name="<?php echo "txt_renglon" . $i ?>" value="<?php echo $i ?>" readonly> <!-- <div class="col-md-1">
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
                <div class="col-md-1">
                    <?php
                    if (!empty($pelambre1['ipe_lote'])) {
                        $res_lote = $pelambre1['ipe_lote'];
                        $readonly = 'readonly';
                    } else {
                        $res_lote = '';
                        $readonly = '';
                    } ?>
                    <input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_lote" . $i ?>" name="<?php echo "txt_lote" . $i ?>" value="<?php echo $res_lote ?>" <?php echo $readonly ?>>
                </div>
                <div class="col-md-1" style="text-align: center;">
                    <?php
                    if ($pelambre1['ipe_horas'] != '') {
                        $res_horas = $pelambre1['ipe_horas'];
                    } else {
                        $res_horas = $valor_horas;
                    }
                    ?>
                    <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_horas" . $i ?>" name="<?php echo "txt_horas" . $i ?>" value="<?php echo $res_horas ?>">
                    <label for="inputPassword6" class="col-form-label"> <label for="inputPassword6" class="col-form-label"><?php echo $horas ?></label>
                    </label>
                </div>
                <div class="col-md-1" style="text-align: center;">
                    <?php
                    if ($pelambre1['ipe_minutos'] != '') {
                        $res_minutos = $pelambre1['ipe_minutos'];
                    } else {
                        $res_minutos = $valor_minutos;
                    }
                    ?>
                    <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_minutos" . $i ?>" name="<?php echo "txt_minutos" . $i ?>" value="<?php echo $res_minutos ?>">
                    <label for="inputPassword6" class="col-form-label"><?php echo $minutos ?></label>
                </div>
                <div class="col-md-2">
                    <?php if ($pelambre1['ipe_fe_hr_inicio'] != '') {
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
                <div class="col-md-1">
                    <?php
                    if (!empty($pelambre1['ipe_observaciones'])) {
                        $res_obs = $pelambre1['ipe_observaciones'];
                        $readonly = 'readonly';
                    } else {
                        $res_obs = '';
                        $readonly = '';
                    } ?>
                    <input type="text" class="form-control observaciones" id="<?php echo "txt_obs" . $i ?>" name="<?php echo "txt_obs" . $i ?>" value="<?php echo $res_obs ?>" <?php echo $readonly ?>>
                </div>
            </div>

    <?php }
    }
    $renglon = $i; ?>

    <div class="row g-3 align-items-center renglones">
        <div class="col-md-10"></div>

        <div class="col-md-2">
            <label for="formFile" class="form-label" id="etiqueta_niveles">CHECAR LIMPIEZA DE PELO</label>
        </div>
    </div>
    <?php
    for ($i = $renglon; $i <= 11; $i++) {
        /* ETIQUETA TIEMPOS */
        if ($i == 11) {
            $horas = "2 Horas";
        }

        #consulta información capturada en fases remojo y pelambre
        $pelambre1 = mysqli_query($cnx, "SELECT * FROM 
				inventario_pelambre_etapas_1 WHERE ipe_ren = '$i' and ipe_etapa = '2' and ip_id = " . $reg_pelambre['ip_id'] . "");
        $pelambre1 = mysqli_fetch_assoc($pelambre1);
    ?>
        <div class="row g-3 align-items-center renglones">
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
            <div class="col-md-1">
                <?php

                if (!empty($pelambre1['ipe_lote'])) {
                    $res_lote = $pelambre1['ipe_lote'];
                    $readonly = 'readonly';
                } else {
                    $res_lote = '';
                    $readonly = '';
                } ?>
                <input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_lote" . $i ?>" name="<?php echo "txt_lote" . $i ?>" value="<?php echo $res_lote ?>" <?php echo $readonly ?>>
            </div>
            <div class="col-md-1" style="text-align: center;">
                <?php
                if ($pelambre1['ipe_horas'] != '') {
                    $res_horas = $pelambre1['ipe_horas'];
                } else {
                    $res_horas = $valor_horas;
                }
                ?>
                <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_horas" . $i ?>" name="<?php echo "txt_horas" . $i ?>" value="<?php echo $res_horas ?>">
                <label for="inputPassword6" class="col-form-label"> <label for="inputPassword6" class="col-form-label"><?php echo $horas ?></label>
                </label>
            </div>
            <div class="col-md-1" style="text-align: center;">
                <?php
                if ($pelambre1['ipe_minutos'] != '') {
                    $res_minutos = $pelambre1['ipe_minutos'];
                } else {
                    $res_minutos = $valor_minutos;
                }
                ?>
                <input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_minutos" . $i ?>" name="<?php echo "txt_minutos" . $i ?>" value="<?php echo $res_minutos ?>">
                <label for="inputPassword6" class="col-form-label"><?php echo $minutos ?></label>
            </div>
            <div class="col-md-2">
                <?php if ($pelambre1['ipe_fe_hr_inicio'] != '') {
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
            <div class="col-md-1">
                <?php
                if (!empty($pelambre1['ipe_observaciones'])) {
                    $res_obs = $pelambre1['ipe_observaciones'];
                    $readonly = 'readonly';
                } else {
                    $res_obs = '';
                    $readonly = '';
                } ?>
                <input type="text" class="form-control observaciones" id="<?php echo "txt_obs" . $i ?>" name="<?php echo "txt_obs" . $i ?>" value="<?php echo $res_obs ?>" <?php echo $readonly ?>>
            </div>
        </div>
    <?php } ?>

    <div class="row renglones" style="margin-top: 2rem;">
        <div class="col-md-5">
            <label for="formFile" class="form-label" style="font-weight: bold;">ENCALADO</label>
        </div>
        <div class="col-md-4">
            <label for="formFile" class="form-label" id="etiqueta_niveles">ADICIONAR AGUA HASTA CUBRIR LOS CUEROS</label>
        </div>
    </div>
    <div class="row renglones">
        <div class="col-md-2">
            <?php
            if (!empty($reg_pelambre['ip_fe_hr_ter_encalado'])) {
                $res_fe_encalado = $reg_pelambre['ip_fe_hr_ter_encalado'];
                $readonly = 'readonly';
            } else {
                $res_fe_encalado = '';
                $readonly = '';
            } ?>
            <label for="formFile" class="form-label">Fecha/hora termina encalado</label>
            <input type="datetime-local" class="form-control" name="txt_fe_ter_encalado" id="txt_fe_ter_encalado" value="<?php echo $res_fe_encalado ?>" <?php echo $readonly ?>>
        </div>
        <!-- <div class="col-md-2">
            <label for="formFile" class="form-label">Hora termina encalado</label>
            <input type="time" class="form-control" name="txt_hora_ter_encalado" id="inputPassword2">
        </div> -->
        <div class="col-md-1">
            <label for="formFile" class="form-label" style="font-weight: bold;">10 horas</label>
        </div>
        <div class="col-md-1">
            <?php
            if (!empty($reg_pelambre['ip_ph_encalado'])) {
                $res_ph_encalado = $reg_pelambre['ip_ph_encalado'];
                $readonly = 'readonly';
            } else {
                $res_ph_encalado = '';
                $readonly = '';
            } ?>
            <label for="formFile" class="form-label">PH</label>
            <input onKeyPress="return isNumberKeyFloat(event, this);" type="text" class="form-control" name="txt_ph" id="txt_ph" value="<?php echo $res_ph_encalado ?>" <?php echo $readonly ?>>
        </div>
        <div class="col-md-1">
            <?php
            if (!empty($reg_pelambre['ip_lavado_encalado'])) {
                $res_lav_encalado = $reg_pelambre['ip_lavado_encalado'];
                $readonly = 'readonly';
            } else {
                $res_lav_encalado = '';
                $readonly = '';
            } ?>
            <label for="formFile" class="form-label">Lavado</label>
            <input onKeyPress="return isNumberKeyFloat(event, this);" type="text" class="form-control" name="txt_lav" id="txt_lav" value="<?php echo $res_lav_encalado ?>" <?php echo $readonly ?>>
        </div>
        <!--mensajes-->
        <div class="col">
            <div id="alerta-accion_pelambre" class="alert d-none" style="margin-top:2rem;">
                <strong class="alert-heading">¡Error!</strong>
                <span class="alert-body"></span>
            </div>
        </div>
        <?php if ($_SESSION['privilegio'] == 3) { ?>
            <div class="col" id="boton">
                <button id="btnGuardar" style="margin-top:2rem;" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xl" style="color: #000;"></i> Guardar</button>
            </div>
        <?php } ?>
    </div>
</form>