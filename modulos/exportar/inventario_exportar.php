<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";


$cnx =  Conectarse();
extract($_GET);
$cad_tipo_material = mysqli_query($cnx, "SELECT * FROM materiales_tipo WHERE mt_est = 'A'") or die(mysqli_error($cnx) . "Error: en consultar materiales_tipo");
$reg_tipo_material = mysqli_fetch_assoc($cad_tipo_material);
$tot_carnaza = mysqli_num_rows($cad_tipo_material);

$perfil_autorizado = mysqli_query($cnx, "SELECT * FROM usuarios_perfiles WHERE up_id = '" . $_SESSION['privilegio'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el inventario 1");
$reg_autorizado = mysqli_fetch_assoc($perfil_autorizado);

#1.inventario filtro
#1.inventario filtro
if (isset($fechaIni) || isset($fechaFin)) {
    //inventario filtro por rango de fecha
    if ($fechaIni != '' && $fechaFin != '') {

        $filtro_fecha = "i.inv_fecha >= '" . $fechaIni . "' AND i.inv_fecha <= '" . $fechaFin . "'";

        //fecha de movimiento de maquilas(material en maquila)
        $inv_fe_enviado =  "inv_fe_enviado >= '" . $fechaIni . "' AND inv_fe_enviado <= DATE_ADD('" . $fechaFin . "', INTERVAL 1 DAY)";

        //fecha de movimiento de maquilas(material recibido de maquila)
        $inv_fe_recibe =  "inv_fe_recibe >= '" . $fechaIni . "' AND inv_fe_recibe <= DATE_ADD('" . $fechaFin . "', INTERVAL 1 DAY)";
    }


    //inventario por día
    else if ($fechaIni != '') {
        $filtro_fecha = "i.inv_fecha = '" . $fechaIni . "'";

        //fecha de movimiento de maquilas(material en maquila)
        $inv_fe_enviado =  "substring(i.inv_fe_enviado,1,10) ='" . $fechaIni . "'";

        //fecha de movimiento de maquilas(material en maquila)
        $inv_fe_recibe =  "substring(i.inv_fe_recibe,1,10) ='" . $fechaIni . "'";
        $fechas_exportacion = $fechaIni;
    }

    $fecha_objetivo_dia = "mto_fecha = '" . $fechaIni . "'";
    $fecha_objetivo_sem = "inv_fecha = '" . $fechaIni . "'";

    $fecha = strtotime($fechaIni);
    $monday = date("Y-m-d", strtotime('monday this week', $fecha)) . "<br>";
    $sunday = date("Y-m-d", strtotime('sunday this week', $fecha)) . "<br>";
}

#2.inventario del día
else {
    //fecha de materiales por clasificacion
    $filtro_fecha = "i.inv_fecha >= '" . date("Y-m-d") . "'";

    //fecha de movimiento de maquilas(material en maquila)
    $inv_fe_enviado =  "substring(i.inv_fe_enviado,1,10) ='" . date("Y-m-d") . "'";

    //fecha de movimiento de maquilas(material recibido de maquila)
    $inv_fe_recibe =  "substring(i.inv_fe_recibe,1,10) ='" . date("Y-m-d") . "'";

    $current_day = date("N");
    $days_to_sunday = 7 - $current_day;
    $days_from_monday = $current_day - 1;

    $monday = date("Y-m-d", strtotime("- {$days_from_monday} Days"));
    $sunday = date("Y-m-d", strtotime("+ {$days_to_sunday} Days"));

    $fecha_objetivo_dia = "mto_fecha >= '" . $monday . "' AND mto_fecha <= '" . $sunday . "'";
    $fecha_objetivo_sem = "inv_fecha >= '" . $monday . "' AND inv_fecha <= '" . $sunday . "'";
    $fechas_exportacion = date("Y-m-d");
}

//objetivo de material por dia
$cad_ob_dia = mysqli_query($cnx, "SELECT DISTINCT t.mt_id, t.mt_descripcion, m.mat_nombre
  FROM materiales_tipo AS t
  INNER JOIN materiales as m ON(t.mt_id = m.mt_id)
  INNER JOIN inventario AS i ON ( m.mat_id = i.mat_id ) 
  WHERE $filtro_fecha");
$reg_ob_dia = mysqli_fetch_array($cad_ob_dia);
$tot_ob_dia = mysqli_num_rows($cad_ob_dia);

//objetivo de material por semana
$cad_ob_sem = mysqli_query($cnx, "SELECT DISTINCT t.mt_id, t.mt_descripcion
  FROM materiales_tipo AS t
  INNER JOIN materiales as m ON(t.mt_id = m.mt_id)
  INNER JOIN inventario AS i ON ( m.mat_id = i.mat_id ) 
  WHERE i.inv_fecha >= '$monday' AND i.inv_fecha <= '$sunday' ");
$reg_ob_sem = mysqli_fetch_array($cad_ob_sem);
$tot_ob_sem = mysqli_num_rows($cad_ob_sem);

header('Content-type: application/vnd.ms-excel; charset=UTF-8');
header("Content-Disposition: attachment; filename=inventario_" . $fechas_exportacion . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table tr td {
            border: 1px solid;
        }

        table tr th {
            border: 1px solid;
        }
    </style>
</head>

<body>

    <!-- MATERIALES POR CLASIFICACIÓN Y PROVEEDORES LOCALES-->
    <?php
    do {
        $cad_material = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_ncorto,p.prv_tipo, m.mat_nombre,inv_costo
    FROM inventario as i
    INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
    INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
    WHERE $filtro_fecha AND p.prv_tipo = 'L' AND m.mt_id = '" . $reg_tipo_material['mt_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar el inventario 2");
        $reg_material = mysqli_fetch_assoc($cad_material);
        $tot_material = mysqli_num_rows($cad_material);
        if ($tot_material > 0) {
    ?>
            <table class="table table-bordered" id="tabla_inventario">
                <thead>
                    <tr>
                        <th colspan="18" style="background: #fff;color: #000;text-align: center;text-transform:uppercase "><?php echo $reg_tipo_material['mt_descripcion']; ?></th>
                    </tr>
                    <tr style="background: #e6e6e6;color: #000">
                        <th width="64">No. Ticket</th>
                        <th>No. viaje/consec</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Placas/Camioneta</th>
                        <th width="134">Proveedor</th>
                        <th width="77">Fecha entrada</th>
                        <th width="73">Material</th>
                        <th>Estado</th>
                        <th width="73">Kgs entrada</th>
                        <th width="89">Prueba secador</th>
                        <th width="79">Desc por agua</th>
                        <th width="73">Descuento por descarne</th>
                        <th>Desc por rendimi.</th>
                        <th>Prueba de rendimiento</th>
                        <th width="94">Kg a pagar c/desc</th>
                        <th>Proceso</th>
                        <th>Fecha molienda</th>
                        <th>Costo material</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ren = 1;
                    $cont = 1;
                    $flt_kg_inv = 0;

                    do {
                        if ($tot_material > 0) {
                            //si el material es baja o devolucion
                            if ($reg_material['inv_enviado'] == 3 || $reg_material['inv_enviado'] == 4) {
                                $kilos_totales = 0;
                                $color = "background:#e6e6";
                            } else {
                                $kilos_totales = $reg_material['inv_kg_totales'];
                                $color = '';
                            }

                            $cad_costo = mysqli_query($cnx, "SELECT *
                        FROM materiales_costos
                        WHERE mat_id ='" . $reg_material['mat_id'] . "' and prv_id ='" . $reg_material['prv_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar el inventario 2");
                            $reg_costo = mysqli_fetch_assoc($cad_costo);
                            $tot_costo = mysqli_num_rows($cad_costo);

                            if (isset($reg_material['inv_costo'])) {
                                $costo = $reg_material['inv_costo'];
                            } else {
                                $costo = 0;
                            }

                            $cad_pro_mat = mysqli_query($cnx, "SELECT * FROM procesos_materiales
                        WHERE inv_id='" . $reg_material['inv_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar inventario proceso");
                            $reg_pro_material = mysqli_fetch_assoc($cad_pro_mat);


                            $cad_pro_molienda = mysqli_query($cnx, "SELECT pro_fe_carga FROM procesos
                        WHERE pro_id='" . $reg_pro_material['pro_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar molienda");
                            $reg_pro_molienda = mysqli_fetch_assoc($cad_pro_molienda);
                    ?>
                            <tr style="<?php echo $color . ''; ?>">
                                <td><?php echo $reg_material['inv_no_ticket'] ?></td>
                                <td><?php echo $reg_material['inv_folio_interno'] ?></td>
                                <td><?php echo $reg_material['inv_hora_entrada'] ?></td>
                                <td><?php echo $reg_material['inv_hora_salida'] ?></td>
                                <td><?php echo $reg_material['inv_placas'] . "-" . $reg_material['inv_camioneta'] ?></td>
                                <td> <?php if ($reg_autorizado['up_ban'] == 1) {
                                            echo $reg_material['prv_nombre'];
                                        } else {
                                            echo $reg_material['prv_ncorto'];
                                        } ?></td>
                                <td align="center"><?php echo fnc_formato_fecha($reg_material['inv_fecha']) ?></td>
                                <td><?php echo $reg_material['mat_nombre'] ?></td>
                                <td><?php if ($reg_material['inv_estado'] == 'X') {
                                        echo 'N/A';
                                    } else if ($reg_material['inv_estado'] == 'F') {
                                        echo "Fresco";
                                    } else {
                                        echo "Encalado";
                                    }
                                    ?></td>
                                <td><?php echo $reg_material['inv_kilos'] ?></td>
                                <td><?php echo $reg_material['inv_prueba'] ?></td>
                                <td><?php echo $reg_material['inv_desc_ag'] ?></td>
                                <td><?php echo $reg_material['inv_desc_d'] ?></td>
                                <td><?php echo $reg_material['inv_desc_ren'] ?></td>
                                <td><?php echo $reg_material['inv_prueba_rendimiento'] ?></td>
                                <td align="right"><?php echo number_format($kilos_totales)  ?></td>
                                <td><?php echo $reg_pro_material['pro_id'] ?></td>
                                <td><?php echo $reg_pro_molienda['pro_fe_carga'] ?></td>
                                <td>
                                    <?php if (fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1) {
                                        echo "$" . number_format($costo, 2, '.', ',');
                                    } ?>
                                </td>
                                <td align="right">
                                    <?php if (fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1) {
                                        echo "$" . number_format(($costo * $kilos_totales), 2, '.', ',');
                                    } ?>
                                </td>

                            </tr>
                        <?php
                            $ren += 1;
                            $flt_kg_inv += $kilos_totales;
                        } else { ?>
                            <tr>
                                <td colspan="18">No hay registros</td>
                            </tr>
                    <?php }
                    } while ($reg_material = mysqli_fetch_assoc($cad_material)); ?>
                    <tr>
                        <td colspan="16"></td>
                        <td style="text-align: right;font-weight: bold;font-size: 18px;">Total</td>
                        <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo number_format($flt_kg_inv); ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <?php for ($i = 0; $i <= 40; $i++) { ?>
                    <?php } ?>
                </tfoot>
            </table>

    <?php  }
    } while ($reg_tipo_material = mysqli_fetch_assoc($cad_tipo_material)); ?>


    <?php
    #muestra materiales que regreso de maquila
    #inv_enviado(2:regreso a planta), prv_ban(0:proveedor normal)
    $cad_dep_maq = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_ncorto,p.prv_tipo, m.mat_nombre
FROM inventario as i
INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
WHERE  $inv_fe_recibe and i.inv_enviado = 2 AND p.prv_ban = '0'") or die(mysqli_error($cnx) . "Error: en consultar el inventario 2");
    $reg_dep_maq = mysqli_fetch_assoc($cad_dep_maq);
    $tot_dep_maq = mysqli_num_rows($cad_dep_maq);
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <!-- <th colspan="15" style="background: #fff;color: #000;text-align: center;">PEDACERA AMERICANA DEPILADA POR MAQUILAS</th> -->
                <th colspan="14" style="background: #fff;color: #000;text-align: center;">3 - PEDACERA AMERICANA DEPILADA (POR GRANJA Y/ O MAQUILA)</th>
            </tr>
            <tr style="background: #e6e6e6;color: #000">
                <th>No. Ticket</th>
                <th>No. viaje/consec</th>
                <!-- <th>Fe ingreso</th> -->
                <th>Entrada</th>
                <th>Salida</th>
                <th>Placas/Camioneta</th>
                <th>Proveedor</th>
                <!-- <th>Entrada de maquila</th> -->
                <th>Material</th>
                <th>Kgs de entrada</th>
                <!-- <th width="120">Kgs cargas lavador</th> -->
                <th>Kgs entrada maq.</th>
                <th>Pruebas secador</th>
                <th>Desc agua</th>
                <th>Descuento por descarne</th>
                <th>Desc rendimiento</th>
                <th>Kg a pagar c/desc</th>
                <th>Costo material</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ren = 1;
            $cont = 1;
            $flt_kg_inv3 = 0;

            do {
                if ($tot_dep_maq > 0) {

                    if ($reg_dep_maq['inv_id'] != '') {
                        $cad_inv3 = mysqli_query($cnx, "SELECT inv_kg_totales,prv_id,mat_id FROM `inventario` WHERE inv_id = " . $reg_dep_maq['inv_id'] . "") or die(mysqli_error($cnx));
                        $reg_inv3 = mysqli_fetch_assoc($cad_inv3);

                        $cad_sum3 = mysqli_query($cnx, "SELECT SUM(inv_kg_totales) as inv_kg_totales  FROM `inventario` WHERE  inv_id_key = " . $reg_dep_maq['inv_id'] . "") or die(mysqli_error($cnx));
                        $reg_sum3 = mysqli_fetch_assoc($cad_sum3);

                        $kg_pagar3 = $reg_inv3['inv_kg_totales'] + $reg_sum3['inv_kg_totales'];

                        $cad_costo = mysqli_query($cnx, "SELECT *
                        FROM materiales_costos
                        WHERE mat_id ='" . $reg_inv3['mat_id'] . "' and prv_id ='" . $reg_inv3['prv_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar el inventario 2");
                        $reg_costo = mysqli_fetch_assoc($cad_costo);
                        $tot_costo = mysqli_num_rows($cad_costo);

                        if (isset($reg_costo['mc_costos'])) {
                            $costo = $reg_costo['mc_costos'];
                        } else {
                            $costo = 0;
                        }
                    }
            ?>
                    <tr>
                        <td><?php echo $reg_dep_maq['inv_no_ticket']; ?></td>
                        <td><?php echo $reg_dep_maq['inv_folio_interno2'] ?></td>
                        <td><?php echo $reg_dep_maq['inv_fe_recibe'] ?></td>
                        <td><?php echo $reg_dep_maq['inv_hora_salida2'] ?></td>
                        <td><?php echo $reg_dep_maq['inv_placas'] . "-" . $reg_dep_maq['inv_camioneta'] ?></td>
                        <td> <?php if ($reg_autorizado['up_ban'] == 1) {
                                    echo $reg_dep_maq['prv_nombre'];
                                } else {
                                    echo $reg_dep_maq['prv_ncorto'];
                                } ?></td>
                        <!-- <td align="center"><?php echo fnc_formato_fecha($reg_dep_maq['inv_fe_recibe']); ?></td> -->
                        <td><?php echo $reg_dep_maq['mat_nombre'] ?></td>
                        <td><?php echo $reg_dep_maq['inv_kilos'] ?></td>
                        <!-- <td><?php echo $reg_dep_maq['inv_kg_lavador'] ?></td> -->
                        <td><?php echo $reg_dep_maq['inv_kg_entrada_maq'] ?></td>
                        <td><?php echo $reg_dep_maq['inv_prueba'] ?></td>
                        <td><?php echo $reg_dep_maq['inv_desc_ag'] ?></td>
                        <td><?php echo $reg_dep_maq['inv_desc_d'] ?></td>
                        <td><?php echo $reg_dep_maq['inv_desc_ren'] ?></td>
                        <td><?php echo  $kg_pagar3 ?></td>
                        <td>
                            <?php if (fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1) {
                                echo "$" . number_format($costo);
                            } ?>
                        </td>
                        <td align="right">
                            <?php if (fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1) {
                                echo "$" . number_format($costo * $kg_pagar3);
                            } ?>
                        </td>
                    </tr>
                <?php
                    $ren += 1;
                    $flt_kg_inv3 += $kg_pagar3;
                } else { ?>
                    <tr style="text-align:center">
                        <td colspan="17">No hay registros</td>
                    </tr>
            <?php }
            } while ($reg_dep_maq = mysqli_fetch_assoc($cad_dep_maq)); ?>
            <tr>
                <td colspan="12">
                    <p>&nbsp;</p>
                </td>
                <td valign="top" style="text-align: right;font-weight: bold;font-size: 18px;">Total</td>
                <td valign="top" style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo number_format($flt_kg_inv3); ?></td>
            </tr>
        </tbody>
        <tfoot>
            <?php for ($i = $ren; $i <= 40; $i++) { ?>
            <?php } ?>
        </tfoot>
    </table>


    <?php
    #muestra materiales que estan en bodega
    $cadena_ext_bodega = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_ncorto,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE $filtro_fecha AND prv_tipo = 'E' AND inv_id_key is NULL ") or die(mysqli_error($cnx) . "Error: en consultar el inventario 1");
    $reg_ext_bodega = mysqli_fetch_assoc($cadena_ext_bodega);
    $tot_ext_bodega = mysqli_num_rows($cadena_ext_bodega); ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <!-- <th colspan="15" style="background: #fff;color: #000;text-align: center;">COMPRA PEDACERA AMERICANA(CAMIONES A BODEGA Y/O MAQUILA)</th> -->
                <th colspan="15" style="background: #fff;color: #000;text-align: center;">1 - COMPRA DE PEDACERA AMERICANA (CAMIONES A BODEGA)</th>
            </tr>
            <tr style="background: #e6e6e6;color: #000">
                <th>No. Ticket</th>
                <th>No. viaje/consec</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Placas/Camioneta</th>
                <th>Proveedor</th>
                <th>Material</th>
                <th>No. factura</th>
                <!--             <th width="80">Fe. Ent. Bodega</th>
 -->
                <th>Peso factura</th>
                <th>Kgs de entrada</th>
                <th>Descto. por rendim</th>
                <!-- <th width="80">% Merma max 5.0 %</th> -->
                <th>No. tarimas/sacos</th>
                <!--  <th>Prueba de secador</th> -->
                <!--<th width="100">Kilos a pagar c/desc</th>-->
                <th>Prueba rendimiento</th>
                <th>Costo material</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ren = 1;
            $cont = 1;
            $flt_kg_inv5 = 0;

            do {
                if ($tot_ext_bodega > 0) {
                    $cad_costo = mysqli_query($cnx, "SELECT *
                    FROM materiales_costos
                    WHERE mat_id ='" . $reg_ext_bodega['mat_id'] . "' and prv_id ='" . $reg_ext_bodega['prv_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar el inventario 2");
                    $reg_costo = mysqli_fetch_assoc($cad_costo);
                    $tot_costo = mysqli_num_rows($cad_costo);
                    if (isset($reg_costo['mc_costos'])) {
                        $costo = $reg_costo['mc_costos'];
                    } else {
                        $costo = 0;
                    }


            ?>
                    <tr>
                        <td><?php echo $reg_ext_bodega['inv_no_ticket'] ?></td>
                        <td><?php echo $reg_ext_bodega['inv_folio_interno'] ?></td>
                        <td><?php echo $reg_ext_bodega['inv_hora_entrada'] ?></td>
                        <td><?php echo $reg_ext_bodega['inv_hora_salida'] ?></td>
                        <td><?php echo $reg_ext_bodega['inv_placas'] . "-" . $reg_ext_bodega['inv_camioneta'] ?></td>
                        <td> <?php if ($reg_autorizado['up_ban'] == 1) {
                                    echo $reg_ext_bodega['prv_nombre'];
                                } else {
                                    echo $reg_ext_bodega['prv_ncorto'];
                                } ?></td>
                        <td><?php echo $reg_ext_bodega['mat_nombre'] ?></td>
                        <td><?php echo $reg_ext_bodega['inv_no_factura'] ?></td>
                        <!-- <td width=" 23" align="center"><?php echo fnc_formato_fecha($reg_ext_bodega['inv_fecha']) ?></td> -->
                        <td><?php echo $reg_ext_bodega['inv_peso_factura'] ?></td>
                        <td align="right"><?php echo $reg_ext_bodega['inv_kilos'] ?></td>
                        <td align="right"><?php echo $reg_ext_bodega['inv_desc_ren'] ?></td>
                        <!-- <td align="right"><?php echo $reg_ext_bodega['inv_por_merma'] ?></td> -->
                        <td><?php echo $reg_ext_bodega['inv_no_tarimas'] . " / " . $reg_ext_bodega['inv_no_sacos'] ?></td>
                        <!-- <td><?php echo $reg_ext_bodega['inv_prueba'] ?></td> -->
                        <td>
                            <?php echo $reg_ext_bodega['inv_prueba_rendimiento'] ?></td>
                        <td>
                            <?php if (fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1) {
                                echo "$" . number_format($costo);
                            } ?>
                        </td>
                        <td align="right"><?php if (fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1) {
                                                echo "$" . number_format($costo * $reg_ext_bodega['inv_kilos']);
                                            } ?>
                        </td>
                    </tr>
                <?php
                    $ren += 1;
                    $flt_kg_inv5 += $reg_ext_bodega['inv_kilos'];
                } else { ?>
                    <tr style="text-align:center">
                        <td colspan="12">No hay registros</td>
                    </tr>
            <?php }
            } while ($reg_ext_bodega = mysqli_fetch_assoc($cadena_ext_bodega)); ?>
            <tr>
                <td colspan="8"></td>
                <td style="text-align: right;font-weight: bold;font-size: 18px">Total</td>
                <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo number_format($flt_kg_inv5); ?></td>
            </tr>
        </tbody>
        <tfoot>
            <?php for ($i = $ren; $i <= 40; $i++) { ?>
            <?php } ?>
        </tfoot>
    </table>

    <?php
    #muestra materiales que estan en maquila
    $cad_ext_maquila = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_ncorto,p.prv_tipo, m.mat_nombre
FROM inventario as i
INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
WHERE $inv_fe_enviado AND prv_tipo = 'E' AND inv_enviado = 1") or die(mysqli_error($cnx) . "Error: en consultar el inventario 1");

    $reg_ext_maquila = mysqli_fetch_assoc($cad_ext_maquila);
    $tot_ext_maquila = mysqli_num_rows($cad_ext_maquila);


    ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <!-- <th colspan="15" style="background: #fff;color: #000;text-align: center;">COMPRA PEDACERA AMERICANA(CAMIONES A BODEGA Y/O MAQUILA)</th> -->
                <th colspan="13" style="background: #fff;color: #000;text-align: center;"> 2 - COMPRA DE PEDACERA AMERICANA (CAMIONES A MAQUILA)</th>
            </tr>
            <tr style="background: #e6e6e6;color: #000">
                <th>No. Ticket</th>
                <th>No. viaje/consec</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Placas/Camioneta</th>
                <th>Proveedor</th>
                <th>Material</th>
                <th>No. factura</th>
                <!-- <th width="80">Fe. Ent. Bodega</th> -->
                <th>Peso factura</th>
                <th>Kgs de entrada</th>
                <th>Descto. por rendim</th>
                <!-- <th width="80">% Merma max 5.0 %</th> -->
                <th>No. tarimas/sacos</th>
                <!--  <th>Prueba de secador</th> -->
                <!--<th width="100">Kilos a pagar c/desc</th>-->
                <th>Prueba rendimiento</th>
                <th>Costo material</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ren = 1;
            $cont = 1;
            $flt_kg_inv_maq = 0;

            do {
                if ($tot_ext_maquila > 0) {
                    $cad_costo = mysqli_query($cnx, "SELECT *
                    FROM materiales_costos
                    WHERE mat_id ='" . $reg_ext_maquila['mat_id'] . "' and prv_id ='" . $reg_ext_maquila['prv_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar el inventario 2");
                    $reg_costo = mysqli_fetch_assoc($cad_costo);
                    $tot_costo = mysqli_num_rows($cad_costo);
                    if (isset($reg_costo['mc_costos'])) {
                        $costo = $reg_costo['mc_costos'];
                    } else {
                        $costo = 0;
                    }

            ?>
                    <tr>
                        <td><?php echo $reg_ext_maquila['inv_no_ticket'] ?></td>
                        <td><?php echo $reg_ext_maquila['inv_folio_interno'] ?></td>
                        <td><?php echo $reg_ext_maquila['inv_hora_entrada'] ?></td>
                        <td><?php echo $reg_ext_maquila['inv_fe_enviado'] ?></td>
                        <td><?php echo $reg_ext_maquila['inv_placas'] . "-" . $reg_ext_maquila['inv_camioneta'] ?></td>
                        <td><?php if ($reg_autorizado['up_ban'] == 1) {
                                echo $reg_ext_maquila['prv_nombre'];
                            } else {
                                echo $reg_ext_maquila['prv_ncorto'];
                            } ?></td>
                        <td><?php echo $reg_ext_maquila['mat_nombre'] ?></td>
                        <td><?php echo $reg_ext_maquila['inv_no_factura'] ?></td>
                        <!-- <td width=" 23" align="center"><?php echo fnc_formato_fecha($reg_ext_maquila['inv_fecha']) ?></td> -->
                        <td><?php echo $reg_ext_maquila['inv_peso_factura'] ?></td>
                        <td align="right"><?php echo $reg_ext_maquila['inv_kilos'] ?></td>
                        <td align="right"><?php echo $reg_ext_maquila['inv_desc_ren'] ?></td>
                        <!-- <td align="right"><?php echo $reg_ext_maquila['inv_por_merma'] ?></td> -->
                        <td><?php echo $reg_ext_maquila['inv_no_tarimas'] . " / " . $reg_ext_maquila['inv_no_sacos'] ?></td>
                        <!-- <td><?php echo $reg_ext_maquila['inv_prueba'] ?></td> -->
                        <td><?php echo $reg_ext_maquila['inv_prueba_rendimiento'] ?></td>
                        <td>
                            <?php if (fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1 || fnc_permiso($_SESSION['privilegio'], 29, 'upe_listar') == 1) {
                                echo "$" . number_format($costo);
                            } ?>
                        </td>
                        <td align="right">
                            <?php if (fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1 || fnc_permiso($_SESSION['privilegio'], 29, 'upe_listar') == 1) {
                                echo "$" . number_format($costo * $reg_ext_maquila['inv_kilos']);
                            } ?>
                        </td>
                    </tr>
                <?php
                    $ren += 1;
                    $flt_kg_inv_maq += $reg_ext_maquila['inv_kilos'];
                } else { ?>
                    <tr style="text-align:center">
                        <td colspan="12">No hay registros</td>
                    </tr>
            <?php }
            } while ($reg_ext_maquila = mysqli_fetch_assoc($cad_ext_maquila)); ?>
            <tr>
                <td colspan="8"></td>
                <td style="text-align: right;font-weight: bold;font-size: 18px">Total</td>
                <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo number_format($flt_kg_inv_maq); ?></td>
            </tr>
        </tbody>
        <tfoot>
            <?php for ($i = $ren; $i <= 40; $i++) { ?>
            <?php } ?>
        </tfoot>
    </table>


    <!--TABLA TOTALES POR TIPO DE PRODUCTO-->
    <table class="table">
        <td valign="top" style="border: 0px">
            <table class="table table-bordered">
                <tr>
                    <td colspan="4" align="center" valign="top" style="background: #e6e6e6;color: #000">Entrada cuero del día</td>
                </tr>
                <tr style="background: #e6e6e6;color: #000">
                    <td width="6%">Origen material</td>
                    <td width="11%">Objetivo(Entrada)</td>
                    <td width="6%">Real(A pagar)</td>
                    <td width="7%">Diferencia</td>
                </tr>
                <?php
                $flt_kilos = 0;
                $flt_kilos2 = 0;
                do {
                    if ($tot_ob_dia > 0) {
                        $inv_obj = mysqli_query($cnx, "SELECT mto_kilos as res
              FROM materiales_tipo_obj
              WHERE mt_id = '$reg_ob_dia[mt_id]' and $fecha_objetivo_dia
              ") or die(mysqli_error($cnx) . "Error: en consultar objetivo dia");
                        $reginv_obj = mysqli_fetch_assoc($inv_obj);


                        $inv = mysqli_query($cnx, "SELECT sum(i.inv_kg_totales)as res2 
              FROM inventario as i 
              INNER JOIN materiales as m  ON(i.mat_id = m.mat_id)
              WHERE m.mt_id = '$reg_ob_dia[mt_id]' and  $fecha_objetivo_sem
              ") or die(mysqli_error($cnx) . "Error: en consultar objetivo semana");
                        $reginv = mysqli_fetch_assoc($inv);

                        if (isset($reginv_obj['res'])) {
                            $reginv_obj_res = $reginv_obj['res'];
                        } else {
                            $reginv_obj_res = 0;
                        }
                        if (isset($reginv['res2'])) {
                            $reginv_res = $reginv['res2'];
                        } else {
                            $reginv_res = 0;
                        }
                ?>
                        <tr>
                            <td><?php echo $reg_ob_dia['mt_descripcion']; ?></td>
                            <td><?php echo $reginv_obj_res
                                ?></td>
                            <td><?php echo $reginv_res; ?></td>
                            <td><?php echo $reginv_obj_res - $reginv_res; ?></td>
                        </tr>
                        <?php
                        $flt_kilos += $reginv_obj_res;
                        $flt_kilos2 += $reginv_res;
                        ?>
                    <?php } else { ?>
                        <tr style="text-align:center">
                            <td colspan="4">No hay registros</td>
                        </tr>
                <?php }
                } while ($reg_ob_dia = mysqli_fetch_array($cad_ob_dia)); ?>
                <tr style="font-weight: bold;background: #e6e6e6;color: #000">
                    <td>Totales</td>
                    <td><?php echo number_format($flt_kilos); ?></td>
                    <td><?php echo number_format($flt_kilos2); ?></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
        <td style="border: 0px">
            <table class="table table-bordered">
                <tr>
                    <td colspan="4" align="center" valign="top" style="background: #e6e6e6;color: #000">Entrada cuero de la semana</td>
                </tr>
                <tr style="background: #e6e6e6;color: #000">
                    <td width="6%">Origen material</td>
                    <td width="11%">Objetivo(Entrada)</td>
                    <td width="6%">Real(A pagar)</td>
                    <td width="7%">Diferencia</td>
                </tr>
                <?php
                $flt_kilos3 = 0;
                $flt_kilos4 = 0;

                do {
                    if ($tot_ob_sem > 0) {

                        //Obtiene el objetivo por Origen material por semana
                        //Obtiene el objetivo por Origen material y semana
                        $inv_obj2 = mysqli_query($cnx, "SELECT SUM(mto_kilos) as res
              FROM materiales_tipo_obj
              WHERE mt_id = '$reg_ob_sem[mt_id]' and mto_fecha >= '$monday' AND mto_fecha <= '$sunday'
              ") or die(mysqli_error($cnx) . "Error: en consultar el inventario 3");
                        $reginv_obj2 = mysqli_fetch_assoc($inv_obj2);

                        //obtiene la suma de todos los materiales ingresado a inventario en la semana
                        $inv2 = mysqli_query($cnx, "SELECT sum(i.inv_kg_totales)as res2 
              FROM inventario as i 
              INNER JOIN materiales as m  ON(i.mat_id = m.mat_id)
              WHERE m.mt_id = '$reg_ob_sem[mt_id]' and inv_fecha >= '$monday' AND inv_fecha <= '$sunday'
              ") or die(mysqli_error($cnx) . "Error: en consultar el inventario 3");
                        $reginv2 = mysqli_fetch_assoc($inv2);

                ?>
                        <tr>
                            <td><?php echo $reg_ob_sem['mt_descripcion']; ?></td>
                            <td><?php
                                if ($reginv_obj2['res'] != '') {
                                    echo $reginv_obj2['res'];
                                } else {
                                    echo "0";
                                } ?></td>
                            <td><?php echo $reginv2['res2']; ?></td>
                            <td><?php echo $reginv_obj2['res'] - $reginv2['res2']; ?></td>
                        </tr>
                        <?php
                        $flt_kilos3 += $reginv_obj2['res'];
                        $flt_kilos4 += $reginv2['res2'];
                        ?>
                    <?php } else { ?>
                        <tr style="text-align:center">
                            <td colspan="4">No hay registros</td>
                        </tr>
                <?php }
                } while ($reg_ob_sem = mysqli_fetch_array($cad_ob_sem)); ?>
                <tr style="font-weight: bold;background: #e6e6e6;color: #000">
                    <td>Totales</td>
                    <td><?php echo number_format($flt_kilos3); ?></td>
                    <td><?php echo number_format($flt_kilos4); ?></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
        <td style="border: 0px" valign="top">
            <table class="table table-bordered">
                <tr>
                    <td colspan="6" align="center" valign="top" style="background: #e6e6e6;color: #000">Parametros de secador</td>
                </tr>
                <tr>
                    <td>Material</td>
                    <td>Carnaza</td>
                    <td>Recorte</td>
                    <td>Entero Propio/Reven.</td>
                    <td>Ped. Local </td>
                    <td>Ped. Americana </td>
                </tr>
                <tr>
                    <td>Rendimiento</td>
                    <td>MIN. 28 </td>
                    <td>MIN. 28 </td>
                    <td>MIN. 30 </td>
                    <td>MIN. 27 </td>
                    <td>MIN. 50 </td>
                </tr>
            </table>
        </td>
    </table>

</body>

</html>
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
<script src="../../js/jquery.min.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../../css/estilos_menu_general.css">
<link rel="stylesheet" href="../../assets/css/estilos_generales.css">

<link rel="icon" type="image/png" sizes="32x32" href="imagenes/favicon-32x32.png">
<script src="../../assets/fontawesome/fontawesome.js"></script>
<script src="../../js/jspdf.js"></script>
<script src="../../js/pdfFromHTML.js"></script>