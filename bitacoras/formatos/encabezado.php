<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
//include "../conexion/conexion.php";
//include "../funciones/funciones.php";
$cnx =  Conectarse();
$sqlG = mysqli_query($cnx, "SELECT * FROM procesos WHERE pro_id = '$idx_pro'") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
$regG = mysqli_fetch_assoc($sqlG);

$nombre_equipo = mysqli_query($cnx, "SELECT  eq.ep_descripcion,eq.ep_carga_max
FROM procesos_equipos as p
      inner join equipos_preparacion as eq on (p.ep_id = eq.ep_id)
      WHERE p.pro_id = '$idx_pro' and p.pe_ban_activo = 1") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
$reg_equipo = mysqli_fetch_assoc($nombre_equipo);

//5.selecciona todos los procesos que tengan el mismo dato agrupador que el proceso actual
$sqlPa = mysqli_query($cnx, "SELECT pa_id FROM procesos_agrupados WHERE pro_id = '$idx_pro' ") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
$reg_proc_agrupados = mysqli_fetch_assoc($sqlPa);
$dato_agrupador = $reg_proc_agrupados['pa_id'];


// Ruta completa de la página actual
$url_actual = $_SERVER['REQUEST_URI'];

// Ruta desde /sis_preparacion/ hasta la página actual
$ruta_base = str_replace('/sis_preparacion', '', dirname($url_actual));

// Contar la cantidad de directorios en la ruta relativa
$levelCount = substr_count($ruta_base, '/');

if ($levelCount > 0) {
  //$directorio = substr_count($ruta_base, '/');
  $directorio = str_repeat('..' . DIRECTORY_SEPARATOR, $levelCount);
  $ruta_base_conexion = str_repeat('..' . DIRECTORY_SEPARATOR, $levelCount);
  $ruta_base_seguridad = str_repeat('..' . DIRECTORY_SEPARATOR, $levelCount);
  $ruta_base_funciones = str_repeat('..' . DIRECTORY_SEPARATOR, $levelCount);
} else {
  $directorio = '';
  $ruta_base_conexion = '';
  $ruta_base_seguridad = '';
  $ruta_base_funciones = '';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Encabezado</title>
  <script src=<?php echo $directorio . "assets/fontawesome/fontawesome.js" ?>></script>

</head>

<body>
  <!--  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.css"> -->
  <style>
    @media print {
      .encabezado {
        width: 430px;
      }

      .tipoCorte {
        width: 200px;
      }

      .coladores {
        width: 170px;
      }

      #ocultar_boton_encabezado {
        display: none;
      }
    }

    body {
      font-size: 12px;
    }

    .table>thead>tr>th,
    .table>tbody>tr>th,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>tbody>tr>td,
    .table>tfoot>tr>td {
      padding: 5px;
      line-height: 1.42857143;
      vertical-align: top;
      border-top: 1px solid #ddd;
      font-size: 12px;
    }
  </style>
  <p></p>
  <?php if ($regG['pro_id'] != '') {

    $tot_kilos = 0; ?>

    <!-- INFORMACION GENERAL DE PROCESOS -->
    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered">
          <tr style="background-color: #e6e6e6;font-weight:bold">
            <td>Proceso</td>
            <td>Tipo preparación</td>
            <td>Horas</td>
            <td>Equipo actual/último</td>
            <td>Capacidad Máxima</td>
            <td>Total kg</td>
            <td>% Cargado</td>
            <td>Operador</td>
            <td>Supervisor</td>
            <td id="ocultar_boton_encabezado">Materiales</td>
            <td id="ocultar_boton_encabezado">Equipos</td>
            <td id="ocultar_boton_encabezado">Quimicos</td>
          </tr>

          <!-- Datos de procesos con el mismo dato agrupador que el equipo activo -->
          <?php
          do {

            //selecciona procesos que contengan el mismo datos agrupador que el proceso principal

            $sql_procesos_a = mysqli_query($cnx, "SELECT pro_id
                                    FROM procesos_agrupados 
                                          WHERE pa_id = '" . $reg_proc_agrupados['pa_id'] . "'
                                          ORDER BY pro_id DESC ") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
            $reg_procesos_a = mysqli_fetch_assoc($sql_procesos_a);

            do {
              if (isset($reg_procesos_a['pro_id'])) {
                //consulta tabla general de procesos
                $cad_g_procesos = mysqli_query($cnx, "SELECT * FROM procesos
                WHERE pro_id  = '" . $reg_procesos_a['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
                $reg_g_procesos = mysqli_fetch_assoc($cad_g_procesos);

                //selecciona el tipo de prepracion  de los procesos
                $sqlPreTipo = mysqli_query($cnx, "SELECT pt_descripcion FROM preparacion_tipo where pt_id ='$reg_g_procesos[pt_id]'") or die(mysqli_error($cnx) . "Error: en consultar el tipo de material");
                $regPreTip = mysqli_fetch_assoc($sqlPreTipo);


                $cad_horas_pro = mysqli_query($cnx, "select sum(pe_hr_maxima) as res from preparacion_tipo_etapas as t 
            inner join preparacion_etapas as e on(t.pe_id = e.pe_id) where pt_id = '$reg_g_procesos[pt_id]' ") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
                $reg_horas_pro = mysqli_fetch_assoc($cad_horas_pro);

                $sqlE = mysqli_query($cnx, "SELECT x.ep_descripcion, x.ep_carga_min, x.ep_carga_max
                      FROM equipos_preparacion AS x
                      INNER JOIN procesos_equipos AS e ON x.ep_id = e.ep_id
                      WHERE e.pro_id = '$reg_g_procesos[pro_id]'") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
                $regE = mysqli_fetch_assoc($sqlE);
          ?>
                <tr>
                  <td><?php echo $reg_procesos_a['pro_id']; ?></td>
                  <td><?php echo $regPreTip['pt_descripcion']; ?></td>
                  <td><?php echo $reg_horas_pro['res']; ?></td>
                  <td>
                    <?php
                    echo $reg_equipo['ep_descripcion'];
                    ?>
                  </td>
                  <td style="text-align: right;"><?php echo $reg_equipo['ep_carga_max']; ?></td>
                  <td style="text-align: right;"><?php echo number_format($reg_g_procesos['pro_total_kg'], 2); ?></td>
                  <td style="text-align: right;"><?php echo number_format((($reg_g_procesos['pro_total_kg'] * 100) / $regE['ep_carga_max']), 2);; ?></td>
                  <td><?php echo fnc_nom_usu($reg_g_procesos['pro_operador']);; ?></td>
                  <td><?php echo fnc_nom_usu($reg_g_procesos['pro_supervisor']);; ?></td>
                  <td id="ocultar_boton_encabezado" style="text-align: center;"><a href="#" onClick="javascript:abre_modal_material(<?php echo $reg_g_procesos['pro_id'] ?>)" style="opacity:0.5"><img src="<?php echo $directorio . "iconos/cuero.png" ?>" alt=""></a></td>
                  <td id="ocultar_boton_encabezado" style="text-align: center;"><a href="#" onClick="javascript:abre_modal_equipos_bit(<?php echo $reg_g_procesos['pro_id'] ?>)"><i class=" fa-solid fa-shapes"></i></a></td>
                  <td id="ocultar_boton_encabezado" style="text-align: center;"><a href="#" onClick="javascript:abre_modal_quimicos(<?php echo $reg_g_procesos['pro_id'] ?>)"><i class="fa-solid fa-flask"></i></a></td>
                </tr>
          <?php
              }
              $tot_kilos += $reg_g_procesos['pro_total_kg'];
            } while ($reg_procesos_a = mysqli_fetch_assoc($sql_procesos_a));
          } while ($reg_proc_agrupados = mysqli_fetch_assoc($sqlPa)); ?>

          <tr>
            <td colspan="6" style="text-align: right;font-weight:bold">
              <?php echo number_format(($tot_kilos), 2) ?>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <!-- INFORMACION DE FECHA DE CARGA DE LOS PROCESOS -->
    <?php
    //selecciona todos los procesos que tengan el mismo dato agrupador que el proceso actual
    $sql_procesos_a_c = mysqli_query($cnx, "SELECT * FROM procesos_agrupados 
    WHERE pa_id = '$dato_agrupador'
     ORDER BY pro_id DESC ") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
    $reg_procesos_a_c = mysqli_fetch_assoc($sql_procesos_a_c);
    do {

      $sql_procesos_eq_c = mysqli_query($cnx, "SELECT * FROM procesos_equipos
      WHERE pro_id = '$reg_procesos_a_c[pro_id]'
       ORDER BY pro_id DESC ") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
      $reg_procesos_eq_c = mysqli_fetch_assoc($sql_procesos_eq_c);


      //5.selecciona todos los procesos que tengan el mismo dato agrupador que el proceso actual
      $slq_carga_lav = mysqli_query($cnx, "SELECT * FROM procesos WHERE pro_id = '$reg_procesos_a_c[pro_id]' order by pro_id desc") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
      $reg_proc_a_carga = mysqli_fetch_assoc($slq_carga_lav); ?>

      <div class="row">
        <!-- tabla 1 -->
        <div class="col-md-4">
          <table class="table table-bordered" style="margin-bottom:0.5rem">
            <tr style="font-weight: bold;background: #e6e6e6">
              <td>Proceso</td>
              <td>Fecha carga</td>
              <td>Hora Inicia</td>
              <td>Hora Termina</td>
            </tr>
            <tr>
              <td><?php echo $reg_proc_a_carga['pro_id'] ?></td>
              <td><?php echo fnc_formato_fecha($reg_proc_a_carga['pro_fe_carga']) ?></td>
              <td><?php echo $reg_proc_a_carga['pro_hr_inicio'] ?></td>
              <td><?php echo $reg_proc_a_carga['pro_hr_fin'] ?></td>
            </tr>
          </table>
        </div>

        <!-- tabla 2 -->
        <div class="col-md-2">
          <table class="table table-bordered" style="margin-bottom:0.5rem">
            <tr>
              <thead>
                <th colspan="5" style="font-weight: bold;background: #e6e6e6;text-align: center;">Tipo de corte</th>
              </thead>
            </tr>
            <tr align="center">
              <td><?php if ($reg_proc_a_carga['pro_molino1'] == 1) {
                    echo "Molino 1,";
                  } ?>
                <?php if ($reg_proc_a_carga['pro_molino2'] == 1) {
                  echo "Molino 2,";
                } ?>
                <?php if ($reg_proc_a_carga['pro_molino3'] == 1) {
                  echo "Molino 3,";
                } ?>
                <?php if ($reg_proc_a_carga['pro_molino4'] == 1) {
                  echo "Molino 4,";
                } ?>
                <?php if ($reg_proc_a_carga['pro_molino5'] == 1) {
                  echo "Molino 5";
                } ?></td>
            </tr>
          </table>
        </div>

        <!-- tabla 3 -->
        <div class="col-md-2">
          <table class="table table-bordered" style="margin-bottom:0.5rem">
            <tr>
              <td style="font-weight: bold;background: #e6e6e6">Coladores limpios</td>
            </tr>
            <tr>
              <td width="11">
                <?php if ($reg_proc_a_carga['pro_col_limp'] == 1) {
                  echo "SI";
                }
                if ($reg_proc_a_carga['pro_col_limp'] == '0') {
                  echo "NO";
                } ?></td>
            </tr>
          </table>
        </div>

        <!-- tabla 4 -->
        <div class="col-md-2">
          <table class="table table-bordered" style="margin-bottom:0.5rem">
            <tr style="font-weight: bold;background: #e6e6e6">
              <td>Pila</td>
              <td>Ph</td>
              <td>Temp</td>
              <td>Ce</td>
            </tr>
            <tr align="center">
              <td><?php if ($reg_proc_a_carga['pro_pila'] == 3) {
                    echo "Limpia";
                  } else if ($reg_proc_a_carga['pro_pila'] == 4) {
                    echo "Tratada";
                  } else {
                    echo $reg_proc_a_carga['pro_pila'];
                  } ?></td>
              <td><?php echo $reg_proc_a_carga['pro_ph'] ?></td>
              <td><?php echo $reg_proc_a_carga['pro_temp'] ?></td>
              <td><?php echo $reg_proc_a_carga['pro_ce'] ?></td>
            </tr>

            <?php
            if ($reg_proc_a_carga['pro_pila2'] != 0) {
            ?>
              <tr align="center">
                <td><?php if ($reg_proc_a_carga['pro_pila2'] == 3) {
                      echo "Limpia";
                    } else if ($reg_proc_a_carga['pro_pila2'] == 4) {
                      echo "Tratada";
                    } else {
                      echo $reg_proc_a_carga['pro_pila2'];
                    } ?></td>
                <td><?php echo $reg_proc_a_carga['pro_ph2'] ?></td>
                <td><?php echo $reg_proc_a_carga['pro_temp2'] ?></td>
                <td><?php echo $reg_proc_a_carga['pro_ce2'] ?></td>
              </tr>
            <?php } ?>

          </table>
        </div>
        <!-- tabla 5 -->
        <div class="col-md-2">
          <table class="table table-bordered" style="margin-bottom:0.5rem">

            <?php if ($reg_proc_a_carga['pro_cuero'] == 'S') {
              $ce = 'CE + 10';
              $estado = 'SUCIO';
            }
            if ($reg_proc_a_carga['pro_cuero'] == 'N') {
              $ce = 'CE  8 - 9';
              $estado = 'NORMAL';
            }
            if ($reg_proc_a_carga['pro_cuero'] == 'L') {
              $ce = 'CE  -8';
              $estado = 'LIMPIO';
            } ?>
            <tr style="font-weight: bold;background: #e6e6e6;">
              <td>Cuero <span style="font-size: 11px;font-weight:bold"><?php echo $ce; ?></span></td>
              <td>Tamaño</td>
            </tr>
            <tr>
              <td><?php if ($reg_proc_a_carga['pro_cuero'] == 'S') {
                    echo $estado;
                  }
                  if ($reg_proc_a_carga['pro_cuero'] == 'N') {
                    echo $estado;
                  }
                  if ($reg_proc_a_carga['pro_cuero'] == 'L') {
                    echo $estado;
                  } ?>
              </td>
              <td>
                <?php if ($reg_proc_a_carga['pro_tam_cuero'] == 'C') {
                  echo "Chico";
                }
                if ($reg_proc_a_carga['pro_tam_cuero'] == 'M') {
                  echo "Mediano";
                }
                if ($reg_proc_a_carga['pro_tam_cuero'] == 'G') {
                  echo "Grande";
                } ?>
              </td>
            </tr>
          </table>
        </div>
      </div>
    <?php  } while ($reg_procesos_a_c = mysqli_fetch_assoc($sql_procesos_a_c)) ?>

    <!-- OBSERVACIONES DE PROCESO ACTIVO -->
    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered">
          <tr>
            <td width="124" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
            <td width="715" valign="top"><?php echo $regG['pro_observaciones'] ?></td>
          </tr>
        </table>
      </div>
    </div>

  <?php } else { ?>
    <div style="height: 40px;width: 350px;text-align: left;z-index: 10;margin-top:10px;float: right;margin-bottom: 0px;background: #e6e6;border-radius: 5px;padding: 10px;color: #BD223E;font-weight: bold;font-style: italic;float: left;">
      No se ha capturado ningun tipo de preparaci贸n
    </div>
  <?php } ?>

  <!-- Modales-->
  <div class="modal fade" id="modal_procesos_materiales" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  </div>
  <div class="modal fade" id="modal_procesos_equipos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  </div>
  <div class="modal fade" id="modal_procesos_quimicos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  </div>
</body>

</html>