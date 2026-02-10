<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
require_once('../../conexion/conexion.php');
require_once('../../funciones/funciones.php');
$cnx = Conectarse();
extract($_POST);
?>

<!-- <div class="modal" tabindex="-1" role="dialog"> -->
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Historial movimiento proceso <?php echo $pro_id_m; ?></h5>
            <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> -->
        </div>
        <div class="modal-body">
            <p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Equipo anterior</th>
                        <th>Equipo nuevo / último</th>
                        <th>Comentarios</th>
                        <th>Fecha movimiento</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $sqlPros = mysqli_query($cnx, "SELECT * FROM movimiento_equipos
                                WHERE pro_id = '$pro_id_m' order by be_fecha desc") or die(mysqli_error($cnx) . "Error: en consultar el proceso");

                    $regPros = mysqli_fetch_assoc($sqlPros);
                    $totPros = mysqli_num_rows($sqlPros);
                    do {
                    ?>
                        <tr>
                            <td><?php echo $regPros['equipo_anterior'] ?></td>
                            <td><?php echo $regPros['equipo_nuevo'] ?></td>
                            <td><?php echo $regPros['be_comentarios'] ?></td>
                            <td><?php echo $regPros['be_fecha'] ?></td>
                            <td><?php echo $regPros['usu_usuario'] ?></td>
                        </tr>
                    <?php
                    } while ($regPros = mysqli_fetch_assoc($sqlPros));
                    ?>

                </tbody>
            </table>
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
<!-- </div> -->