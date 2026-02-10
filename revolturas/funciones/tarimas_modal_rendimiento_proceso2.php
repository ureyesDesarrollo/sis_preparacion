<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Julio-2024 */

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

extract($_POST);

// Ejecutamos la consulta para obtener las tarimas
$result = mysqli_query($cnx, "SELECT tar_id, tar_folio, tar_kilos FROM rev_tarimas WHERE pro_id = '$pro_id'");

$totalTarimas = mysqli_num_rows($result); // Obtenemos el número total de tarimas

$totalKgTarimas = 0;
// Recorremos todas las filas para sumar los kilos de las tarimas
while ($res = mysqli_fetch_assoc($result)) {
    $totalKgTarimas += (float)$res['tar_kilos'];
}

// Obtenemos el total de kg del proceso
/*$resultProceso = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, t.mt_descripcion, inv.inv_kilos
    FROM procesos_materiales AS pm
    INNER JOIN inventario AS inv ON inv.inv_id = pm.inv_id 
    INNER JOIN materiales AS m ON m.mat_id = pm.mat_id
    INNER JOIN materiales_tipo AS t ON (m.mt_id = t.mt_id)
    WHERE pm.pro_id = '$pro_id'");*/

$resultProceso = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, t.mt_descripcion, m.mat_ingreso, inv.inv_kilos
    FROM procesos_materiales AS pm
    INNER JOIN inventario AS inv ON inv.inv_id = pm.inv_id 
    INNER JOIN materiales AS m ON m.mat_id = pm.mat_id
    INNER JOIN materiales_tipo AS t ON (m.mt_id = t.mt_id)
    WHERE pm.pro_id = '$pro_id'");

$kgProceso = 0;

// Recorremos todas las filas para sumar los kilos del proceso
while ($resProceso = mysqli_fetch_assoc($resultProceso)) {
    //if ($resProceso['mt_descripcion'] == 'LOCAL' || $resProceso['mt_descripcion'] == 'NACIONAL') {
    if ($resProceso['mat_ingreso'] == 'N') {
        $kgProceso += (float)$resProceso['pma_kg'];
    } else {
        $kgProceso += (float)$resProceso['inv_kilos'];
    }
}


$resultProceso2 = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, t.mt_descripcion, m.mat_ingreso, inv.inv_kilos
    FROM procesos_materiales AS pm
    INNER JOIN inventario AS inv ON inv.inv_id = pm.inv_id 
    INNER JOIN materiales AS m ON m.mat_id = pm.mat_id
    INNER JOIN materiales_tipo AS t ON (m.mt_id = t.mt_id)
    WHERE pm.pro_id = '$pro_id_2'");

$kgProceso2 = 0;

// Recorremos todas las filas para sumar los kilos del proceso
while ($resProceso2 = mysqli_fetch_assoc($resultProceso2)) {
    //if ($resProceso['mt_descripcion'] == 'LOCAL' || $resProceso['mt_descripcion'] == 'NACIONAL') {
    if ($resProceso2['mat_ingreso'] == 'N') {
        $kgProceso2 += (float)$resProceso2['pma_kg'];
    } else {
        $kgProceso2 += (float)$resProceso2['inv_kilos'];
    }
}

// Sumamos los kilos de ambos procesos
$totalKg = $kgProceso + $kgProceso2;

// Calculamos el rendimiento
$rendimiento = $totalKg ? ($totalKgTarimas / $totalKg) : 0;
$rendimiento = number_format($rendimiento, 4);
?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Calcular Rendimiento Proceso: <?= htmlspecialchars($pro_id) ?>/<?= htmlspecialchars($pro_id_2) ?> Tarima: <?= htmlspecialchars($tar_folio) ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <button id="btn-show" class="btn btn-sm btn-primary mb-3">Mostrar/Ocultar Tabla</button>
            <form id="form_tarima_rendi" method="POST">
                <input type="hidden" value="<?= htmlspecialchars($tar_id) ?>" name="tar_id" id="tar_id">
                <input type="hidden" value="<?= htmlspecialchars($pro_id) ?>" name="pro_id" id="pro_id">
                <div class="row">
                    <div class="col-md-12" id="table_procesos">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Origen</th>
                                    <th>Kilos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Volvemos a ejecutar la consulta para mostrar los datos en la tabla
                                mysqli_data_seek($resultProceso, 0);
                                while ($resProceso = mysqli_fetch_assoc($resultProceso)) {
                                    echo "<tr>";
                                    echo "<td>{$resProceso['mat_nombre']}</td>";
                                    echo "<td>{$resProceso['mt_descripcion']}</td>";
                                    //if ($resProceso['mt_descripcion'] == 'LOCAL') {
                                    if ($resProceso['mat_ingreso'] == 'N') {
                                        echo "<td>{$resProceso['pma_kg']}</td>";
                                    } else {
                                        echo "<td>{$resProceso['inv_kilos']}</td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Origen</th>
                                    <th>Kilos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Volvemos a ejecutar la consulta para mostrar los datos en la tabla
                                mysqli_data_seek($resultProceso2, 0);
                                while ($resProceso2 = mysqli_fetch_assoc($resultProceso2)) {
                                    echo "<tr>";
                                    echo "<td>{$resProceso2['mat_nombre']}</td>";
                                    echo "<td>{$resProceso2['mt_descripcion']}</td>";
                                    //if ($resProceso['mt_descripcion'] == 'LOCAL') {
                                    if ($resProceso2['mat_ingreso'] == 'N') {
                                        echo "<td>{$resProceso2['pma_kg']}</td>";
                                    } else {
                                        echo "<td>{$resProceso2['inv_kilos']}</td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="row mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="form-label">Total KG Proceso <?= htmlspecialchars($pro_id) ?> </label>
                                    <input type="text" class="form-control" readonly required value="<?= htmlspecialchars($kgProceso) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Total KG Proceso <?= htmlspecialchars($pro_id_2) ?> </label>
                                    <input type="text" class="form-control" readonly required value="<?= htmlspecialchars($kgProceso2) ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="" class="form-label">Kilos Totales</label>
                                    <input type="text" class="form-control" readonly required value="<?= htmlspecialchars($totalKg) ?>">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="" class="form-label">Total Tarimas</label>
                                    <input type="text" class="form-control" readonly required value="<?= htmlspecialchars($totalTarimas) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">Total KG Tarimas</label>
                                    <input type="text" class="form-control" readonly required value="<?= htmlspecialchars($totalKgTarimas) ?>">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label for="tar_rendimiento" class="form-label">Rendimiento</label>
                                    <input type="text" name="tar_rendimiento" id="tar_rendimiento" class="form-control" readonly required value="<?= htmlspecialchars($rendimiento) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <table class="table table-bordered" id="table_tarimas">
                            <thead>
                                <tr>
                                    <th colspan="4" style="text-align:center">Tarimas</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Clave</th>
                                    <th>Folio</th>
                                    <th>Kilos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $total = 0;
                                // Recorremos todas las filas obtenidas
                                mysqli_data_seek($result, 0); // Reiniciamos el puntero del resultado
                                while ($res = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td>" . htmlspecialchars($res['tar_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($res['tar_folio']) . "</td>";
                                    echo "<td>" . htmlspecialchars($res['tar_kilos']) . "</td>";
                                    echo "</tr>";
                                    $total += (float)$res['tar_kilos'];
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Total kilos</td>
                                    <td><?= number_format($total) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-tarima-rendi" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_tarima_rendi" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table_tarimas').addClass('d-none');
        $("#form_tarima_rendi").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'funciones/tarimas_insertar_rendimiento.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        alertas_v5("#alerta-tarima-rendi", 'Listo!', res.success, 1, true, 5000);
                        $('#dataTableTarimas').DataTable().ajax.reload();
                    } else {
                        alertas_v5("#alerta-tarima-rendi", 'Error!', res.error, 3, true, 5000);
                    }
                }
            });
        });

        $('#btn-show').on('click', function() {
            $('#table_tarimas').toggleClass('d-none');

            if ($('#table_tarimas').hasClass('d-none')) {
                $('#table_procesos').removeClass('col-md-6').addClass('col-md-12');
            } else {
                $('#table_procesos').removeClass('col-md-12').addClass('col-md-6');
            }
        });
    });
</script>