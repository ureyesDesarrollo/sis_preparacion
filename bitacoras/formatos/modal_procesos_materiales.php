<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
require_once('../../conexion/conexion.php');
require_once('../../funciones/funciones.php');
include('../../seguridad/user_seguridad.php');
$cnx = Conectarse();
extract($_POST);

$sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada, pm.pma_fe_entrada_maquila,inv_id
FROM materiales as m 
INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
WHERE pm.pro_id ='$pro_id_m' order by pma_fe_entrada asc") or die(mysqli_error($cnx) . "Error: en consultar el tipo de material");
$reg_material = mysqli_fetch_assoc($sqlProMat);

$folio = '';
?>

<!-- <div class="modal" tabindex="-1" role="dialog"> -->
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Material de proceso <?php echo $pro_id_m; ?></h5>
            <!--   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> -->
        </div>
        <div class="modal-body">
            <p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tipo de material</th>
                        <th>Toneladas - kg</th>
                        <th>Fecha entrada</th>
                        <th>Fe. entrada maquila</th>
                        <th>Proveedor</th>
                        <th>Folio interno</th>
                        <th>Extrac</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $tot_material = 0;
                    do {

                        $sql_prov = mysqli_query($cnx, "SELECT p.prv_nombre,i.inv_folio_interno, i.inv_folio_interno2,p.prv_tipo,p.prv_ncorto
                        FROM inventario as i 
                        inner join proveedores as p on(i.prv_id = p.prv_id)
                        WHERE inv_id ='" . $reg_material['inv_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el tipo de material");

                        $reg_prov = mysqli_fetch_assoc($sql_prov);
                        if ($reg_prov['prv_tipo'] == 'L') {
                            $folio = $reg_prov['inv_folio_interno'];
                        }
                        if ($reg_prov['prv_tipo'] == 'E') {
                            $folio = $reg_prov['inv_folio_interno2'];
                        }
                    ?>
                        <tr>
                            <td><?php echo $reg_material['mat_nombre'] ?></td>
                            <td style="text-align:right"><?php echo number_format($reg_material['pma_kg'], 2) ?></td>
                            <td><?php echo fnc_formato_fecha($reg_material['pma_fe_entrada']) ?></td>
                            <td><?php echo fnc_formato_fecha($reg_material['pma_fe_entrada_maquila']) ?></td>
                            <td> <?php if ($reg_autorizado['up_ban'] == 1) {
                                        echo $reg_prov['prv_nombre'];
                                    } else {
                                        echo $reg_prov['prv_ncorto'];
                                    } ?></td>
                            <td><?php echo $folio ?></td>
                            <td></td>
                        </tr>
                    <?php
                        $tot_material += $reg_material['pma_kg'];
                    } while ($reg_material = mysqli_fetch_assoc($sqlProMat)); ?>
                    <tr>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td></td>
                        <td style="text-align:right"><?php echo number_format($tot_material, 2) ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
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