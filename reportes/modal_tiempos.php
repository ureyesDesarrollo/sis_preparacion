<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*27 - Noviembre - 2023*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include "../funciones/funciones_procesos.php";
$cnx =  Conectarse();

$proceso = $_POST['pro_id'];

echo "SELECT a.pro_id,a.pe_id,e.pe_descripcion, a.proa_fe_ini, a.proa_hr_ini, a.proa_fe_fin,a.proa_hr_fin,e.pe_hr_maxima
FROM procesos_auxiliar as a
INNER JOIN preparacion_etapas as e on (a.pe_id = e.pe_id)                   
where pro_id = '" . $proceso . "'";
$cad_auxiliar = mysqli_query($cnx, "SELECT a.pro_id,a.pe_id,e.pe_descripcion, a.proa_fe_ini, a.proa_hr_ini, a.proa_fe_fin,a.proa_hr_fin,e.pe_hr_maxima
FROM procesos_auxiliar as a
INNER JOIN preparacion_etapas as e on (a.pe_id = e.pe_id)                   
where pro_id = '" . $proceso . "'") or die(mysqli_error($cnx) . "Error: en consultar procesos_auxiliar");
$reg_auxiliar = mysqli_fetch_assoc($cad_auxiliar);
$tot_auxiliar = mysqli_num_rows($cad_auxiliar);
?>

<div class="modal-dialog modal-lg" role="document">
  <!--<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">-->
  <div class="modal-content">
    <form id="formTipoEditar">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tiempos proceso <?php echo $proceso ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Fase</th>
              <th>Hora máxima</th>
              <th>Fecha inicio</th>
              <th>Fecha fin</th>
              <th>Tiempo transcurrido</th>
              <th>Terminada</th>
            </tr>
          </thead>
          <tbody>
            <?php do {
            ?>
              <tr>
                <td><?php echo $reg_auxiliar['pe_descripcion'] ?></td>
                <td><?php echo $reg_auxiliar['pe_hr_maxima'] ?></td>
                <td><?php echo $reg_auxiliar['proa_fe_ini'] . ' ' . $reg_auxiliar['proa_hr_ini']; ?></td>
                <td><?php echo $reg_auxiliar['proa_fe_fin'] . ' ' . $reg_auxiliar['proa_hr_fin']; ?></td>
                <td><?php
                    if ($reg_auxiliar['proa_fe_fin'] != NULL) {
                      echo fnc_horas($reg_auxiliar['proa_fe_ini'], $reg_auxiliar['proa_fe_fin'], $reg_auxiliar['proa_hr_ini'], $reg_auxiliar['proa_hr_fin']);
                      /* echo fnc_horas($reg_auxiliar['proa_fe_ini'], date("Y-m-d"), $reg_auxiliar['proa_hr_ini'], date("H:i:s"));  */
                    } else {
                      echo "0";
                    }
                    ?></td>
                <td><?php
                    if ($reg_auxiliar['proa_fe_fin'] == '') {
                      echo "No";
                    } else {
                      echo "Si";
                    }
                    ?>
                </td>
              </tr>
            <?php } while ($reg_auxiliar = mysqli_fetch_assoc($cad_auxiliar)); ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><img src="../iconos/close.png" alt="">Cerrar</button>
      </div>
    </form>

  </div>
</div>