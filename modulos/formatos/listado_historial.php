<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST);


$fchi = $_GET['fchi'];
$fchf = $_GET['fchf'];

if ($fchf == '') {
  $cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_ncorto,p.prv_tipo, m.mat_nombre
             FROM inventario as i
             INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
             INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
             WHERE inv_fecha = '$fchi'   and inv_tomado = 0 ORDER BY inv_fecha") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
  $registros = mysqli_fetch_assoc($cadena);
} else {
  $cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_ncorto,p.prv_tipo, m.mat_nombre
             FROM inventario as i
             INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
             INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
             WHERE inv_fecha >= '$fchi' AND inv_fecha <= '$fchf'  and inv_tomado = 0 ORDER BY inv_fecha") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
  $registros = mysqli_fetch_assoc($cadena);
}

$tot_reg = mysqli_num_rows($cadena);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Listado de Inventario <?php echo date("d-m-Y"); ?></title>
    <link rel="stylesheet" href="../../css/estilos_formatos.css">
    <style type="text/css">
    td {
        border: 1px solid #000
    }
    </style>
</head>

<body>
    <div class="container">
        <center>
            <div class="tablehead" style="width: 1200px">
                <table style="border: 1px solid #fff">
                    <tr style="border: 1px solid #fff">
                        <td style="border: 1px solid #fff"><img src="../../imagenes/logo_progel_v3.png"></td>
                        <td style="border: 1px solid #fff">
                            <h1>Historial Inventario del <?php echo $fchi . " a " . $fchf ?></h1>
                        </td>
                    </tr>
                    <tr></tr>
                </table>
            </div>


            <!--TABLA EXTRANJERO-->
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="inventario" style="width: 1200px">
                <thead>
                    <tr align="center">
                        <th>&nbsp;No&nbsp;</th>
                        <th>&nbsp;Fecha&nbsp;</th>
                        <th>&nbsp;Dia&nbsp;</th>
                        <th>&nbsp;No. Ticket&nbsp;</th>
                        <th>&nbsp;Placas&nbsp;</th>
                        <th>&nbsp;Camioneta&nbsp;</th>
                        <th>&nbsp;Proveedor&nbsp;</th>
                        <th>&nbsp;Tipo&nbsp;</th>
                        <th>&nbsp;Material&nbsp;</th>
                        <th>&nbsp;Kilos&nbsp;</th>
                        <th>&nbsp;Prueba<br /> secador&nbsp;</th>
                        <th width="100px">&nbsp;Kilos<br />Entrada&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
          $ren = 1;
          $cont = 1;
          $flt_kg = 0;
          $flt_kg_t = 0;

          if ($tot_reg > 0) {
            do {

              if ($registros['inv_dia'] == '') {
                $registros['inv_dia'] = 7;
              }
          ?>
                    <tr height="20">

                        <td><?php echo $cont++ ?></td>
                        <td align="center"><?php echo $registros['inv_fecha'] ?></td>
                        <td><?php echo fnc_nom_dia($registros['inv_dia']); ?></td>
                        <td><?php echo $registros['inv_no_ticket'] ?></td>
                        <td><?php echo $registros['inv_placas'] ?></td>
                        <td><?php echo $registros['inv_camioneta'] ?></td>
                        <td> <?php if ($reg_autorizado['up_ban'] == 1) {
                        echo $registros['prv_nombre'];
                      } else {
                        echo $registros['prv_ncorto'];
                      } ?></td>
                        <?php
                if ($registros['prv_tipo']  == 'L') { ?>
                        <td><?php echo "Local" ?></td>

                        <?php
                } else { ?>
                        <td><?php echo "Extranjero" ?></td>
                        <?php } ?>
                        <td><?php echo $registros['mat_nombre'] ?></td>
                        <td align="right"><?php echo $registros['inv_kilos'] ?></td>
                        <td><?php echo $registros['inv_prueba'] ?></td>
                        <td align="right"><?php echo $registros['inv_kg_totales'] ?></td>
                    </tr>
                    <?php
              $ren += 1;

              $flt_kg += $registros['inv_kilos'];
              $flt_kg_t += $registros['inv_kg_totales'];
            } while ($registros = mysqli_fetch_assoc($cadena));
          } ?>

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
                        <th>Total Kg:</th>
                        <th align="right"><?php echo $flt_kg; ?></th>
                        <th></th>
                        <th align="right"><?php echo $flt_kg_t; ?></th>
                    </tr>
                </tfoot>
            </table>
            <!--TABLA LOCAL-->
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </center>
    </div>
    <p>&nbsp;</p>

    <?php include "../../generales/pie_pagina_formato.php"; ?>
</body>

</html>