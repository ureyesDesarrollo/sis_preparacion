<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../seguridad/user_seguridad.php');
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT e.*, l.le_estatus
 								FROM equipos_preparacion as e
								INNER JOIN listado_estatus as l on (e.le_id = l.le_id) where e.estatus = 'A' order by e.estatus asc") or die(mysqli_error($cnx) . "Error: en consultar los lavadores");
$registros = mysqli_fetch_assoc($cadena);

?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#tabla_lista_etapas').dataTable({
      "sPaginationType": "full_numbers"
    });
  })
</script>

<div class="container" style="margin-top:20px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_etapas">
    <thead>
      <tr align="center">
        <th>&nbsp;Clave&nbsp;</th>
        <th>&nbsp;Descripcion&nbsp;</th>
        <th>&nbsp;Estatus equipo&nbsp;</th>
        <th>&nbsp;Estatus proceso&nbsp;</th>
        <th width="20">Editar</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $ren = 1;
      do { ?>
        <tr height="20">
          <td><?php echo $registros['ep_id'] ?></td>
          <td><?php echo $registros['ep_descripcion'] ?></td>
          <td><?php if ($registros['estatus'] == 'A') {
                echo "Activo";
              } else {
                echo "Baja";
              } ?></td>
          <td><?php echo mb_convert_encoding($registros['le_estatus'], "UTF-8") ?></td>
          <td style="padding-left: 0px" align="center">
            <?php
            if (fnc_permiso($_SESSION['privilegio'], 13, 'upe_editar') == 1) {

              if ($registros['ep_tipo'] ==  'X') { ?>
                <a href="#" onClick="javascript:fnc_abre_modal_eq_pelambre(<?= $registros['ep_id']; ?>)"><img src="../iconos/editar.png"></a>
              <?php  } else { ?>
                <a href="#" onClick="javascript:fnc_abre_modal_eq(<?= $registros['ep_id']; ?>)"><img src="../iconos/editar.png"></a>
              <?php } ?>

            <?php } ?>
          </td>
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
        </tr>
      <?php } ?>
      <t>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </t>
    </tfoot>
  </table>
</div>