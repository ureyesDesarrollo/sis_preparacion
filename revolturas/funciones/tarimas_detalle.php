<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../conexion/conexion.php";
include "../../funciones/funciones_procesos.php";
include "../../funciones/funciones.php";

session_start();
if (!isset($_SESSION['autentificado']) || $_SESSION['autentificado'] !== "SI") {
    // Si no está autenticado, redirigir al login y pasar el tar_id en la URL
    $tar_id = $_GET['tar_id'];
    header("Location: ./login_qr.php?tar_id=" . $tar_id);
    exit();
}

$cnx = Conectarse();

date_default_timezone_set('America/Mexico_City');
$hora_actual = date('Y:m-d H:i:s');

try {

    //extract($_GET);
    $query = "SELECT t.*, c.cal_descripcion, c.cal_color, t.niv_id
    FROM rev_tarimas t 
    LEFT JOIN rev_calidad c ON c.cal_id = t.cal_id 
    WHERE t.tar_id = '" . $_GET['tar_id'] . "'";

    $result = mysqli_fetch_assoc(mysqli_query($cnx, $query));

    $sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada, pm.pma_fe_entrada_maquila,inv_id
    FROM materiales as m 
    INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
    WHERE pm.pro_id ='" . $result['pro_id'] . "' order by pma_fe_entrada asc");
    $reg_material = mysqli_fetch_assoc($sqlProMat);

    $sqlPosicion = "SELECT r.rac_descripcion,r.rac_zona, np.* 
    FROM rev_nivel_posicion np 
    INNER JOIN rev_racks r ON r.rac_id = np.rac_id
    INNER JOIN rev_tarimas t ON t.niv_id = np.niv_id 
    WHERE t.tar_id = '" . $_GET['tar_id'] . "'";


    $pos = mysqli_fetch_assoc(mysqli_query($cnx, $sqlPosicion));
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    //mysqli_close($cnx);
    echo "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Tarimas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .table-light {
            background-color: #f8f9fa;
        }

        h3 {
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-4">
        <nav class="navbar bg-body-tertiary" style="box-shadow: 10px 5px 5px #e6e6e6;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="../../imagenes/logo_progel_v5.png" alt="Progel Mexicana">
                </a>
                DETALLE DE TARIMA
                <ul class="nav justify-content-center">
                    <li>
                        <span><?= $hora_actual ?></span>
                    </li>
                </ul>
            </div>
        </nav>

        <div>
            <h3 class="mb-3 mt-3">Parámetros de la tarima</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th></th>
                            <th>Bloom</th>
                            <th>Visc.</th>
                            <th>pH Final</th>
                            <th>Trans.</th>
                            <th>%T(620)</th>
                            <th>NTU</th>
                            <th>Humedad</th>
                            <th>Cenizas</th>
                            <th>Conduct.</th>
                            <th>Redox</th>
                            <th>Color</th>
                            <th>Fino</th>
                            <th>Olor</th>
                            <th>P.E en 1 kg</th>
                            <th>Part. Extrañas</th>
                            <th>Part. Ind. 6.66%</th>
                            <th>Hidratación</th>
                            <th>Aceptado</th>
                            <th>Cuarentena</th>
                            <th>Calidad</th>
                        </tr>
                    </thead>
                    <tbody id="tarimas">
                        <?php if (!empty($result)) : ?>
                            <tr>
                                <td><?= $result['tar_fecha'] ?></td>
                                <td>P<?= $result['pro_id'] ?>T<?= $result['tar_folio'] ?></td>
                                <td><?= $result['tar_bloom'] ?></td>
                                <td><?= $result['tar_viscosidad'] ?></td>
                                <td><?= $result['tar_ph'] ?></td>
                                <td><?= $result['tar_trans'] ?></td>
                                <td><?= $result['tar_porcentaje_t'] ?></td>
                                <td><?= $result['tar_ntu'] ?></td>
                                <td><?= $result['tar_humedad'] ?></td>
                                <td><?= $result['tar_cenizas'] ?></td>
                                <td><?= $result['tar_ce'] ?></td>
                                <td><?= $result['tar_redox'] ?></td>
                                <td><?= $result['tar_color'] ?></td>
                                <td><?= $result['tar_fino'] ?></td>
                                <td><?= $result['tar_olor'] ?></td>
                                <td><?= $result['tar_pe_1kg'] ?></td>
                                <td><?= $result['tar_par_extr'] ?></td>
                                <td><?= $result['tar_par_ind'] ?></td>
                                <td><?= $result['tar_hidratacion'] ?></td>
                                <td><?= ($result['tar_rechazado'] === 'A') ? 'X' : '' ?></td>
                                <td><?= ($result['tar_rechazado'] === 'C') ? 'X' : '' ?></td>
                                <td><?= $result['cal_descripcion'] ?></td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <td colspan="20">No se encontraron resultados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="row">
            <div class="col-md-4">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th colspan="3">Posición Inicial</th>
                        </tr>
                        <tr>
                            <th>Zona</th>
                            <th>Rack</th>
                            <th>Nivel - Posición</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= isset($pos['rac_zona']) ? $pos['rac_zona'] : 'Sin posición' ?></td>
                            <td><?= isset($pos['rac_descripcion']) ? $pos['rac_descripcion'] : 'Sin posición' ?></td>
                            <td><?= isset($pos) ? ($pos['niv_codigo'] ?? 'Sin posición') : 'Sin posición' ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <h3 class="mb-3">Información del proceso</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Proceso</th>
                            <th>Tipo preparación</th>
                            <th>Operador</th>
                            <th>Supervisor</th>
                            <th>Kilos</th>
                            <th>Fecha carga</th>
                            <th>Hora inicio</th>
                            <th>Hora fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $cad_g_procesos = mysqli_query($cnx, "SELECT * FROM procesos WHERE pro_id  = '" . $result['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
                        $reg_g_procesos = mysqli_fetch_assoc($cad_g_procesos);

                        //selecciona el tipo de prepracion  de los procesos
                        $sqlPreTipo = mysqli_query($cnx, "SELECT pt_descripcion FROM preparacion_tipo where pt_id ='$reg_g_procesos[pt_id]'") or die(mysqli_error($cnx) . "Error: en consultar el tipo de material");
                        $regPreTip = mysqli_fetch_assoc($sqlPreTipo);

                        ?>
                        <tr>
                            <td><?php echo $reg_g_procesos['pro_id']; ?></td>
                            <td><?php echo $regPreTip['pt_descripcion']; ?></td>
                            <td><?php echo fnc_nom_usu($reg_g_procesos['pro_operador']);; ?></td>
                            <td><?php echo fnc_nom_usu($reg_g_procesos['pro_supervisor']);; ?></td>
                            <td style="text-align: right;"><?php echo number_format($reg_g_procesos['pro_total_kg'], 2); ?></td>
                            <td><?php echo fnc_formato_fecha($reg_g_procesos['pro_fe_carga']) ?></td>
                            <td><?php echo $reg_g_procesos['pro_hr_inicio'] ?></td>
                            <td><?php echo $reg_g_procesos['pro_hr_fin'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <h3 class="mb-3">Información de materiales</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tipo de material</th>
                            <th>Toneladas - kg</th>
                            <th>Fecha entrada</th>
                            <th>Fe. entrada maquila</th>
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
                        WHERE inv_id ='" . $reg_material['inv_id'] . "'");

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
                                <td><?php echo fnc_formato_fecha($reg_material['pma_fe_entrada_maquila'] ?? ''); ?></td>
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

                        </tr>
                    </tfoot>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>