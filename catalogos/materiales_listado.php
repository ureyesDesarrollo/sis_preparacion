<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
//include "../conexion/conexion.php";
//include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
 								FROM materiales") or die(mysqli_error($cnx) . "Error: en consultar el material");
$registros = mysqli_fetch_assoc($cadena);

?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>


<script type="text/javascript">
  $(document).ready(function() {
    $('#tabla_lista_materiales').dataTable({
      "sPaginationType": "full_numbers"
    });
  })

  function costos_materiales(mat_id) {
    $.ajax({
      type: 'post',
      url: 'modal_costos_material.php',
      data: {
        "mat_id": mat_id,
      }, //Pass $id
      success: function(result) {
        $("#modal_costos_materiales").html(result);
        $('#modal_costos_materiales').modal('show')
      }
    });
    return false;
  };
</script>

<div class="container" style="margin-top:60px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_materiales">
    <thead>
      <tr align="center">
        <th>&nbsp;Clave&nbsp;</th>
        <th>&nbsp;Origen material&nbsp;</th>
        <th>&nbsp;Material&nbsp;</th>
        <th>&nbsp;Unidad medida&nbsp;</th>
        <th>&nbsp;Costo&nbsp;</th>
        <th>&nbsp;Stock minimo&nbsp;</th>
        <th>&nbsp;Stock maximo&nbsp;</th>
        <th>&nbsp;Existencia&nbsp;</th>
        <th>&nbsp;Estatus&nbsp;</th>
        <th width="20">Editar</th>
        <th width="20">Baja</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $ren = 1;
      do {

        $cad_matTip = mysqli_query($cnx, "select mt_descripcion from materiales_tipo where mt_id = '$registros[mt_id]'");
        $reg_matTip =  mysqli_fetch_assoc($cad_matTip);

        $cad_Um = mysqli_query($cnx, "select um_descripcion from unidades_medida where um_id = '$registros[um_id]'");
        $reg_um =  mysqli_fetch_assoc($cad_Um);
      ?>

        <tr height="20">
          <td><?php echo $registros['mat_id'] ?></td>
          <td><?php echo $reg_matTip['mt_descripcion'] ?></td>
          <td><?php echo $registros['mat_nombre'] ?></td>
          <td><?php echo $reg_um['um_descripcion'] ?></td>

          <td>
            <?php if (fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1) { ?>
              <a href="javascript:costos_materiales(<?= $registros['mat_id'] ?>);"> <i class="fa-solid fa-dollar-sign"></i></a>
              <!-- echo $registros['mat_costo']; -->
            <?php } ?>
          </td>

          <td align="right"><?php echo $registros['mat_stock_min'] ?></td>
          <td align="right"><?php echo $registros['mat_stock_max'] ?></td>
          <td align="right"><?php echo $registros['mat_existencia'] ?></td>
          <td><?php if ($registros['mat_est'] == 'A') {
                echo "Activo";
              } else {
                echo "Baja";
              } ?></td>
          <td style="padding-left: 0px" align="center"><?php if (fnc_permiso($_SESSION['privilegio'], 5, 'upe_editar') == 1) { ?><a href="#" onClick="javascript:fnc_abre_modal(<?= $registros['mat_id']; ?>)"><img src="../iconos/editar.png"></a><?php } ?></td>
          <td style="padding-left: 0px"><?php if (fnc_permiso($_SESSION['privilegio'], 5, 'upe_borrar') == 1) { ?><a href="javascript:fnc_baja(<?= $registros['mat_id'] ?>);"><img src="../iconos/borrar.png" /></a><?php } ?></td>
        </tr>
      <?php
        $ren += 1;
      } while ($registros = mysqli_fetch_assoc($cadena)); ?>

    </tbody>

    <tfoot>
      <?php for ($i = $ren; $i <= 12; $i++) { ?>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
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
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </tfoot>
  </table>
</div>

<div class="modal" id="modal_costos_materiales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>