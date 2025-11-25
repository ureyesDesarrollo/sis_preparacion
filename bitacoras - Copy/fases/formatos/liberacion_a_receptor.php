<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
$cnx =  Conectarse();

extract($_POST);
?>
<table style="float:right">
    <tr>
        <td>
            <div class="form-group col-md-4">
                <div class="alert alert-info hide" id="alerta-liberacion" style="height: 40px; width: 270px; text-align: left; z-index: 10; font-size: 10px;margin-top: 3rem;">
                    <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                    <strong>Titulo</strong> &nbsp;&nbsp;
                    <span> Mensaje </span>
                </div>
            </div>
        </td>
        <td>
            <?php
            if ($_SESSION['privilegio'] == 6 && $reg_estatus['le_id'] != 15) {  #liberación de laboratorio
            ?>

                <button <?php echo $oculta_opciones ?> type="button" class="btn btn-success" id="paleto" onClick="javascript:liberar_proceso(<?php echo $id_e ?>);">Liberación laboratorio
                </button>

            <?php
            } ?>
        </td>
        <td>
            <?php if ($_SESSION['privilegio'] == 4 && $tot_fa_7b > 0 && $reg_estatus['le_id'] == 15) { #si hay parametros de laboratorio muestra botón envio a receptores
            ?>

                <button <?php echo $oculta_opciones ?> type="button" class="btn btn-success" id="paleto" onClick="javascript:abre_modal_equipo_receptor(<?php echo $regfg['pro_id'] ?>, <?php echo $id_e ?>);"> <img src="../iconos/procesos2.png" alt="">Equipo
                </button>

            <?php }
            ?>
        </td>
    </tr>
</table>