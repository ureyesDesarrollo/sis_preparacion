<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Octubre-2023*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadenaT = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre,prv_recibe,p.prv_ncorto
						 FROM inventario as i
						 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
						 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
						 WHERE inv_no_factura is not null and (inv_enviado = 5 or inv_enviado = 6) and inv_tomado = 0  and p.prv_ban = 0 ORDER BY prv_tipo") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
$registrosT = mysqli_fetch_assoc($cadenaT);

?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#tabla_inventarioT').dataTable({
            "sPaginationType": "full_numbers"
        });
    })
    //nueva funcionalidad / parcialidad al enviar a maquila
    function abre_modal_parcialidad_pelambre(inv_id) {
        //alert('aqui');
        var datos = {
            "inv_id": inv_id,
        }
        //alert($("#cbxKilosID"+param).val());
        $.ajax({
            type: 'post',
            url: 'modals/modal_parcialidad_pelambre.php',
            data: datos,
            //data: {nombre:n},
            success: function(result) {
                $("#modal_recibir_pelambre").html(result);
                $('#modal_recibir_pelambre').modal('show')
            }
        });
        return false;
    }
</script>

<div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Recibir de pelambre</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="" style="border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_inventarioT">
                    <thead>
                        <tr align="center">
                            <th style="width: 70px">Fecha</th>
                            <th>Dia</th>
                            <th>No. Ticket</th>
                            <th>Placas</th>
                            <th>Camioneta</th>
                            <th>Proveedor</th>
                            <th>Tipo</th>
                            <th>Material</th>
                            <th>Kg entrada</th>
                            <th>Prueba<br /> secador</th>
                            <th>Kg totales</th>
                            <th width="20">Recibir</th>
                            <!--<th width="20">Baja</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ren = 1;
                        $flt_kg = 0;
                        $flt_kg_t = 0;

                        do {

                            if (isset($registrosT['inv_dia'])) {
                                /* $registrosT['inv_dia'] = 7; */

                                /* if ($registrosT['inv_enviado'] == '5' || $registrosT['inv_enviado'] == '6') {
                                    $str_resalta = 'background: #CC9838;';
                                } else {
                                    $str_resalta = '';
                                } */

                        ?>
                                <tr height="20" style="<?php echo $str_resalta; ?>">
                                    <td align="center"><?php echo $registrosT['inv_fecha'] ?></td>
                                    <td><?php echo fnc_nom_dia($registrosT['inv_dia']); ?></td>
                                    <td><?php echo $registrosT['inv_no_ticket'] ?></td>
                                    <td><?php echo $registrosT['inv_placas'] ?></td>
                                    <td><?php echo $registrosT['inv_camioneta'] ?></td>
                                    <td><?php if ($reg_autorizado['up_ban'] == 1) {
                                            echo $registrosT['prv_nombre'];
                                        } else {
                                            echo $registrosT['prv_ncorto'];
                                        } ?></td>
                                    <?php
                                    if ($registrosT['prv_tipo']  == 'L') { ?>
                                        <td><?php echo "Local" ?></td>

                                    <?php
                                    } else { ?>
                                        <td><?php echo "Extranjero" ?></td>
                                    <?php } ?>
                                    <td><?php echo $registrosT['mat_nombre'] ?></td>
                                    <td align="right"><?php echo number_format($registrosT['inv_kilos'], 2) ?></td>
                                    <td><?php echo $registrosT['inv_prueba'] ?></td>
                                    <td align="right"><?php echo number_format($registrosT['inv_kg_totales'], 2) ?></td>
                                    <td style="padding-left: 0px" align="center">
                                        <?php if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_editar') == 1) {
                                            if ($registrosT['inv_enviado'] == 1) { ?>
                                                <a href="#listadoenmaquila" onClick="javascript:AbreModalRecibir(<?= $registrosT['inv_id'] ?>,<?= $registrosT['prv_recibe']; ?>)"><img src="../iconos/editar.png"></a>
                                            <?php }
                                            $cad_inv = mysqli_query($cnx, "SELECT i.ip_kg_finales,e.ep_descripcion FROM inventario_pelambre as i
								INNER JOIN equipos_preparacion as e on(i.ep_id = e.ep_id) 
								WHERE i.inv_id = '" . $registrosT['inv_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el inventario 1");
                                            $reg_inv = mysqli_fetch_assoc($cad_inv);
                                            if ($reg_inv['ip_kg_finales'] != '') {
                                            ?>
                                                <a href="#listadoenmaquila" onClick="javascript:abre_modal_parcialidad_pelambre(<?php echo $registrosT['inv_id'] ?>)"><img src="../iconos/editar.png"></a>
                                        <?php /* echo number_format($reg_inv['ip_kg_finales'], 2); */
                                            }
                                            echo $reg_inv['ep_descripcion'];
                                        } else {
                                            echo "";
                                        } ?>
                                    </td>
                                </tr>
                        <?php
                                $ren += 1;

                                $flt_kg += $registrosT['inv_kilos'];
                                $flt_kg_t += $registrosT['inv_kg_totales'];
                            }
                        } while ($registrosT = mysqli_fetch_assoc($cadenaT)); ?>

                    </tbody>

                    <tfoot>
                        <?php for ($i = $ren; $i <= 12; $i++) { ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>

                            <th></th>
                            <th>Total Kg:</th>
                            <th align="right"><?php echo number_format($flt_kg, 2); ?></th>
                            <th></th>
                            <th align="right"><?php echo number_format($flt_kg_t, 2); ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modal_pelambre_parcialidad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>