<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST); 

$str_cad =  mysqli_query($cnx, "SELECT l.lote_id, lo.lote_folio,lo.lote_mes, ppd.prop_id,ppd.pro_id,m.mat_id ,m.mat_nombre,pm.pma_kg,pm.pma_fe_entrada, p.prv_nombre 
                      FROM lotes_procesos AS l
                      INNER JOIN procesos_paletos_d AS ppd on(l.prop_id = ppd.prop_id)
                      INNER JOIN procesos_materiales as pm on(ppd.pro_id = pm.pro_id)
                      INNER JOIN materiales as m on(pm.mat_id = m.mat_id) 
                      INNER JOIN inventario as i on(i.inv_id = pm.inv_id)
                      INNER JOIN proveedores as p on(p.prv_id = i.prv_id)
                      INNER JOIN lotes as lo on(lo.lote_id = l.lote_id)
                      where l.lote_id = '$lote' ");
$reg_cad = mysqli_fetch_assoc($str_cad);
$tot_cad = mysqli_num_rows($str_cad);
?>
<!-- Large modal -->
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 style="text-align: center;font-weight: bold;" class="modal-title" id="exampleModalLabel">Consulta de información de material <br> Mes <?php echo $reg_cad['lote_mes'] ?> - Lote <?php echo $reg_cad['lote_folio'] ?> -  Registros <?php echo $tot_cad ?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <table class="table table-striped">
        <thead style="background: #e6e6e6">
          <tr  style="background: #e6e6e6">
            <th  style="background: #e6e6e6" scope="col">Lote</th>
            <th  style="background: #e6e6e6" scope="col">Material</th>
            <th  style="background: #e6e6e6" scope="col">Kilos</th>
            <th  style="background: #e6e6e6" scope="col">Proveedor</th>
            <th  style="background: #e6e6e6" scope="col">Fecha de entrada</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          do {
           ?>
           <tr>
            <td><?php echo $reg_cad['lote_folio']; ?></td>
            <td><?php echo $reg_cad['mat_nombre']; ?></td>
            <td><?php echo $reg_cad['pma_kg']; ?></td>
            <td><?php echo $reg_cad['prv_nombre']; ?></td>
            <td><?php echo $reg_cad['pma_fe_entrada']; ?></td>
          </tr>
        <?php   } while ($reg_cad = mysqli_fetch_assoc($str_cad));
        ?>
      </tbody>
    </table>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
  </div>
</div>
</div>