<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST); 

$str_cad =  mysqli_query($cnx, "SELECT l.lote_folio,l.lote_mes, t.lote_id, t.tarima_id,t.tarima_lim_param, t.tarima_bloom,t.tarima_viscocidad, t.tarima_color,t.tarima_cenizas FROM tarimas as t INNER JOIN lotes as l WHERE t.lote_id = l.lote_id AND t.lote_id = '$lote' ");
$reg_cad = mysqli_fetch_assoc($str_cad);
$tot_cad = mysqli_num_rows($str_cad);
?>
<!-- Large modal -->
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 style="text-align: center;font-weight: bold;" class="modal-title" id="exampleModalLabel">Consulta de información de tarimas <br> Mes <?php echo $reg_cad['lote_mes'] ?> - Lote <?php echo $reg_cad['lote_folio'] ?> -  Registros <?php echo $tot_cad ?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <table class="table table-striped">
        <thead style="background: #e6e6e6">
          <tr  style="background: #e6e6e6">
            <th  style="background: #e6e6e6" scope="col">Lote</th>
            <th  style="background: #e6e6e6" scope="col">Lim parametro</th>
            <th  style="background: #e6e6e6" scope="col">Bloom</th>
            <th  style="background: #e6e6e6" scope="col">Viscosidad</th>
            <th  style="background: #e6e6e6" scope="col">Color</th>
            <th  style="background: #e6e6e6" scope="col">Cenizas</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          do {
           ?>

           <?php if ($reg_cad['lote_id'] == '') { ?>
             <tr>
            <td colspan="2" align="center">No hay información</td>
          </tr>
          <?php }else{ ?>
           <tr>
            <td><?php echo $reg_cad['lote_folio']; ?></td>
            <td><?php echo $reg_cad['tarima_lim_param']; ?></td>
            <td><?php echo $reg_cad['tarima_bloom']; ?></td>
            <td><?php echo $reg_cad['tarima_viscocidad']; ?></td>
            <td><?php echo $reg_cad['tarima_color']; ?></td>
            <td><?php echo $reg_cad['tarima_cenizas']; ?></td>
          </tr>

        <?php  } } while ($reg_cad = mysqli_fetch_assoc($str_cad));
        ?>
      </tbody>
    </table>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
  </div>
</div>
</div>