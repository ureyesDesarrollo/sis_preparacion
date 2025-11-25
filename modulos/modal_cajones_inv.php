<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../seguridad/user_seguridad.php";
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST);

$consulta =  mysqli_query($cnx, "select * from inventario where inv_id = '$hdd_inv'");
$reg = mysqli_fetch_assoc($consulta);
?>
<style>
    .alert {
        padding: 0.5rem;
        margin-bottom: 0px;
    }
</style>
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> -->
<div class="modal-dialog modal-lg">
    <form id="form_cajones">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>No. viaje/consec</th>
                                <th>Fecha entrada</th>
                                <th>Fecha entrada</th>
                                <th>Proveedor</th>
                                <th>Material</th>
                                <th>Kilos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php do {
                                $material =  mysqli_query($cnx, "select * from materiales where mat_id = " . $reg['mat_id'] . "");
                                $reg_mat = mysqli_fetch_assoc($material);
                            ?>
                                <tr>
                                    <td><?php echo $reg['inv_no_ticket'] ?></td>
                                    <td><?php echo $reg['inv_folio_interno'] ?></td>
                                    <td><?php echo $reg['inv_hora_entrada'] ?></td>
                                    <td><?php echo $reg['inv_hora_salida'] ?></td>
                                    <td><?php echo $reg['prv_id'] ?></td>
                                    <td><?php echo $reg_mat['mat_nombre'] ?></td>
                                    <td><?php echo $reg['inv_kg_totales'] ?></td>
                                </tr>
                            <?php } while ($reg = mysqli_fetch_assoc($consulta)); ?>

                        </tbody>
                    </table>
                </div>

                <!-- Historial de movimiento -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Ubicación anterior</th>
                                <th>Ubicación actual</th>
                                <th>Usuario</th>
                                <th>Fecha entrada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $consulta = mysqli_query($cnx, "SELECT * FROM bitacora_cajones WHERE inv_id = '$hdd_inv'");
                            $reg = mysqli_fetch_assoc($consulta);
                            do {
                                if (mysqli_num_rows($consulta) > 0) {
                                    $cajon_ini =  mysqli_query($cnx, "SELECT * FROM almacen_cajones where ac_id = " . $reg['cajon_inicial'] . "");
                                    "Cajón" . $reg_caj_ini = mysqli_fetch_assoc($cajon_ini);

                                    $cajon_fin =  mysqli_query($cnx, "SELECT * FROM almacen_cajones where ac_id = " . $reg['cajon_final'] . "");
                                    $reg_caj_fin = mysqli_fetch_assoc($cajon_fin);

                                    $usuario =  mysqli_query($cnx, "SELECT * FROM usuarios where usu_id = " . $reg['usu_id'] . "");
                                    $reg_usu = mysqli_fetch_assoc($usuario);
                            ?>
                                    <tr>
                                        <td><?php echo "Cajón " . $reg_caj_ini['ac_descripcion'] ?></td>
                                        <td><?php echo "Cajón " . $reg_caj_fin['ac_descripcion'] ?></td>
                                        <td><?php echo $reg_usu['usu_nombre'] ?></td>
                                        <td><?php echo $reg['bc_fecha_movimiento'] ?></td>
                                    </tr>
                            <?php }
                            } while ($reg = mysqli_fetch_assoc($consulta)); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <!--mensajes-->
                <div class="col-md-6">
                    <div class="alert alert-info" id="alerta-errorMovimientoCajon" role="alert" style="display:none">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Titulo</strong> &nbsp;&nbsp;
                        <span> Mensaje </span>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="location.reload()"><i class="fa-solid fa-rectangle-xmark"></i> Cerrar</button>
                <!--   <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button> -->
            </div>
    </form>
</div>
</div>