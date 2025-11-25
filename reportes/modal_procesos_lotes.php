<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
require_once('../conexion/conexion.php');
require_once('../funciones/funciones.php');
$cnx = Conectarse();
extract($_POST);


$sql_pro = mysqli_query($cnx, "SELECT a.*,l.lote_folio FROM procesos_agrupados as a
inner join lotes_anio as l on(a.lote_id = l.lote_id)
WHERE a.lote_id ='$lote_id' order by pro_id") or die(mysqli_error($cnx) . "Error: en consultar");
$reg_pro = mysqli_fetch_assoc($sql_pro);

$folio = '';
?>

<!-- <div class="modal" tabindex="-1" role="dialog"> -->
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Procesos del lote <?php echo $reg_pro['lote_folio']; ?></h5>
            <!--   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> -->
        </div>
        <div class="modal-body">
            <p>
            <table class="table table-bordered" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Procesos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    do {
                    ?>
                        <tr>
                            <td><?php echo $reg_pro['pro_id'] ?></td>
                        </tr>
                    <?php

                    } while ($reg_pro = mysqli_fetch_assoc($sql_pro)); ?>

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