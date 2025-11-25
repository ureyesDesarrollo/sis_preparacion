<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
require_once('../../conexion/conexion.php');
require_once('../../funciones/funciones.php');
$cnx = Conectarse();
extract($_POST);

$sql_quimicos = mysqli_query($cnx, "SELECT e.*,q.quimico_descripcion,u.usu_usuario FROM quimicos_etapas  as e
inner join quimicos as q on(e.quimico_id = q.quimico_id)
left join usuarios as u on(e.usu_id =  u.usu_id)
WHERE e.pro_id ='$pro_id_m' order by e.quim_fecha asc") or die(mysqli_error($cnx) . "Error: en consultar el tipo de material");
$reg_quim = mysqli_fetch_assoc($sql_quimicos);

$folio = '';
?>

<!-- <div class="modal" tabindex="-1" role="dialog"> -->
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Químicos de proceso <?php echo $pro_id_m; ?></h5>
            <!--   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> -->
        </div>
        <div class="modal-body">
            <p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Químico</th>
                        <th>Lote</th>
                        <th>Litros</th>
                        <th>Fase</th>
                        <th>Fecha</th>
                        <th>Usuario</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $tot_quim = 0;
                    do {

                        $sql_materiales = mysqli_query($cnx, "SELECT * FROM preparacion_etapas
                        WHERE pe_id ='$reg_quim[pe_id]'") or die(mysqli_error($cnx) . "Error: en consultar el tipo de material");


                        $reg_mat = mysqli_fetch_assoc($sql_materiales);
                    ?>
                        <tr>
                            <td><?php echo $reg_quim['quimico_descripcion']; ?></td>
                            <td><?php echo $reg_quim['quim_lote']; ?></td>
                            <td style="text-align: right;"><?php echo $reg_quim['quim_litros']; ?></td>
                            <td><?php echo $reg_mat['pe_descripcion']; ?></td>
                            <td><?php echo $reg_quim['quim_fecha']; ?></td>
                            <td><?php echo $reg_quim['usu_usuario']; ?></td>
                        </tr>
                    <?php
                        $tot_quim += $reg_quim['quim_litros'];
                    } while ($reg_quim = mysqli_fetch_assoc($sql_quimicos)); ?>
                    <tr>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td></td>
                        <td></td>
                        <td style="text-align:right"><?php echo number_format($tot_quim, 2) ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
                </tr>
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