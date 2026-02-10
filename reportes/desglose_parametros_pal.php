<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*10 - Mayo - 2019*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$proceso = $_POST['prop_id'];

/*if(!$_REQUEST)
{
	include "../conexion/conexion.php";
	$cnx =  Conectarse();
}*/

$cadena = mysqli_query(Conectarse(), "SELECT * FROM procesos_liberacion  as pl inner join procesos_paletos_d as ppd on(pl.pro_id=ppd.pro_id) AND   ppd.prop_id = '$proceso'") or die(mysql_error()."Error: en consultar el procesos");
$registros = mysqli_fetch_assoc($cadena);


echo "SELECT * FROM procesos_liberacion_b as pl inner join procesos_paletos_d as ppd on(pl.pro_id=ppd.pro_id) and ppd.prop_id = '$proceso'";
$cadenaD = mysqli_query(Conectarse(), "SELECT * FROM procesos_liberacion_b as pl inner join procesos_paletos_d as ppd on(pl.pro_id=ppd.pro_id) and ppd.prop_id = '$proceso' ") or die(mysql_error()."Error: en consultar el procesos");
$registrosD = mysqli_fetch_assoc($cadenaD);
?>

<div class="modal-dialog modal-lg" role="document" style="height: 200px;width: 70%;">
<!--<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">-->
  <div class="modal-content">
    <form id="formTipoEditar"> 
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Parametros (Proceso <?php echo $proceso ?>)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">       


       <table width="740" border="1" class="table table-striped" style="border:1px solid#e6e6e6">

        <!--Table head-->
        <thead style="background: linear-gradient(#838383, #999999, #838383, #cccccc);color: #fff">
          <tr>
            <th width="72">Etapa</th>
			<th width="72">Nombre</th>
            <th width="100">Horas totales</th>
            <th width="51">Ph</th>
            <th width="49">Ce</th>
            <th width="48">Color</th>
            <th width="105" style="padding-left: 0px;padding-right: -10px">Adelgazamiento</th>
            <th width="49">L1 Cocido</th>
            <th width="51">Ce</th>
            <th width="46">L2 Cocido</th>
            <th width="49">Ce</th>
            <th width="50">Cocido Lib% Ext</th>
            <th>Color</th>
            <th>% de Solides</th>
          </tr>
        </thead>
        <!--Table head-->

        <!--Table body-->
        <tbody>
          <?php do { 

            $etapa = mysqli_query(Conectarse(), "SELECT pe_descripcion FROM preparacion_etapas WHERE pe_id = '$registros[pe_id]'") or die(mysql_error()."Error: en consultar el procesos");
            $regEtapa = mysqli_fetch_assoc($etapa);
            ?>
            <tr>
              <th><?php echo $regEtapa['pe_descripcion']?></th>
			  <td><?php echo fnc_nombre_etapa($registros['pe_id']);?></td>
              <td style="padding-left: 50px"><?php echo $registros['prol_hr_totales']?></td>
              <td style="padding-left: 20px"><?php echo $registros['prol_ph']?></td>
              <td style="padding-left: 20px"><?php echo $registros['prol_ce']?></td>
              <td style="padding-left: 20px"><?php echo $registros['prol_color']?></td>
              <td style="padding-left: 20px"><?php echo $registros['prol_adelgasamiento']?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>             
            </tr>
          <?php } while ($registros = mysqli_fetch_assoc($cadena)); ?>


           <?php do { 

            $etapaD = mysqli_query(Conectarse(), "SELECT pe_descripcion FROM preparacion_etapas WHERE pe_id = '$registrosD[pe_id]' OR pe_id = '$registrosD[pe_id]' ") or die(mysql_error()."Error: en consultar el procesos");
            $regEtapaD = mysqli_fetch_assoc($etapaD);
            ?>
            <tr>
              <th><?php echo $regEtapaD['pe_descripcion']?></th>
			  <td><?php echo fnc_nombre_etapa($registrosD['pe_id']);?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>

              <td style="padding-left: 20px"><?php echo $registrosD['prol_cocido_ph1']?></td>
              <td><?php echo $registrosD['prol_ce1']?></td>
              <td><?php echo $registrosD['prol_cocido_ph2']?></td>
              <td><?php echo $registrosD['prol_ce2']?></td>
              <td><?php echo $registrosD['prol_cocido_lib']?></td>
              <td><?php echo $registrosD['prol_color']?></td>
              <td><?php echo $registrosD['prol_solides']?></td>
            </tr>
          <?php } while ($registrosD = mysqli_fetch_assoc($cadenaD)); ?>

        </tbody>
        <!--Table body-->


      </table>

      <div class="modal-footer" style="margin-top: 8%;">
        <!--mensajes-->
        <div class="alert alert-info hide" id="alerta-errorTipoEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
          <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
          <strong>Titulo</strong> &nbsp;&nbsp;
          <span> Mensaje </span>
        </div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><img src="../iconos/close.png" alt="">Cerrar</button>
      </div>
    </div>
  </form>

</div>
</div>  