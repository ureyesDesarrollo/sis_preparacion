<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();
$fechaActual = date("Y-m-d");
extract($_POST);

if (isset($_POST['action']) && $_POST['action'] == 'obtener_tarimas') {
    $listado_tarimas = mysqli_query($cnx, "SELECT t.pro_id,t.tar_folio
    FROM rev_tarimas t
    JOIN rev_revolturas_tarimas rt ON t.tar_id = rt.tar_id
    JOIN rev_revolturas r ON rt.rev_id = r.rev_id
    WHERE t.tar_estatus = 3 AND r.rev_id = '$rev_id'");
    try {
        $datos_tarimas = array();

        while ($fila = mysqli_fetch_assoc($listado_tarimas)) {
            $datos_tarimas[] = $fila;
        }

        $json_tarimas = json_encode($datos_tarimas);

        echo $json_tarimas;
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}


try {
    $sql = "SELECT rev_hora_ini,rev_imanes_limpios,rev_sacos_limpios,rev_libre_sobrantes,rev_mezcladora FROM rev_revolturas WHERE rev_id = '$rev_id'";
    $res = mysqli_fetch_assoc(mysqli_query($cnx, $sql));
} catch (Exception $e) {
    echo $e->getMessage();
}

try {
    $sql = "SELECT e_estatus FROM rev_equipos";
    $res_e = mysqli_query($cnx, $sql);
    $res_equipos = [];
    while ($fila = mysqli_fetch_assoc($res_e)) {
        $res_equipos[] = $fila['e_estatus'];
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>


<script type="text/javascript" src="../js/alerta.js"></script>
<style>
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.6);
        cursor: not-allowed;
        border-radius: 5px;
    }

    .radio-wrapper {
        position: relative;
    }
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Revoltura: <?= $rev_folio ?> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_revolturas" method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-3 d-none">
                                <label for="rev_id" class="form-label">Clave Revoltura</label>
                                <input type="text" class="form-control" name="rev_id" id="rev_id" readonly required value="<?= $rev_id ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="form-label">Fecha</label>
                                <input type="text" name="" id="" class="form-control" readonly required value="<?= $fechaActual ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="form-label">Responsable Procesar</label>
                                <input type="text" name="" id="" class="form-control" readonly required value="<?= $_SESSION['user'] ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="rev_hora_ini" class="form-label">Hora inicio</label>
                                <input type="time" name="rev_hora_ini" id="rev_hora_ini" class="form-control" required value="<?= $res['rev_hora_ini'] ?>" <?= ($res['rev_hora_ini'] == null) ? '' : 'readonly' ?>>
                            </div>
                            <div class=" col-md-3">
                                <label for="rev_hora_fin" class="form-label">Hora fin</label>
                                <input type="time" name="rev_hora_fin" id="rev_hora_fin" class="form-control">
                            </div>

                        </div>
                        <div class="row mb-3">

                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-7">
                        <div class="form-group d-flex align-items-center mb-3">
                            <label class="mr-3 mb-0 form-label" style="width: 250px;">¿Los imanes se encuentran limpios?</label>
                            <div class="d-flex justify-content-start">
                                <input type="radio" class="btn-check" name="rev_imanes_limpios" id="imanes_limpios_si" autocomplete="off" value="S" required <?= ($res['rev_imanes_limpios'] == 'S') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary mr-2" for="imanes_limpios_si" style="width: 50px;">Sí</label>

                                <input type="radio" class="btn-check" name="rev_imanes_limpios" id="imanes_limpios_no" autocomplete="off" value="N" required <?= ($res['rev_imanes_limpios'] == 'N') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="imanes_limpios_no" style="width: 50px;">No</label>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center mb-3">
                            <label class="mr-3 mb-0 form-label" style="width: 250px;">¿La base para los sacos se encuentra limpia?</label>
                            <div class="d-flex justify-content-start">
                                <input type="radio" class="btn-check" name="rev_sacos_limpios" id="sacos_limpios_si" autocomplete="off" value="S" required <?= ($res['rev_sacos_limpios'] == 'S') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary mr-2" for="sacos_limpios_si" style="width: 50px;">Sí</label>

                                <input type="radio" class="btn-check" name="rev_sacos_limpios" id="sacos_limpios_no" autocomplete="off" value="N" required <?= ($res['rev_sacos_limpios'] == 'N') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="sacos_limpios_no" style="width: 50px;">No</label>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center mb-3">
                            <label class="mr-3 mb-0 form-label" style="width: 250px;">¿La helicoidad está libre de sobrantes?</label>
                            <div class="d-flex justify-content-start">
                                <input type="radio" class="btn-check" name="rev_libre_sobrantes" id="libre_sobrantes_si" autocomplete="off" value="S" required <?= ($res['rev_libre_sobrantes'] == 'S') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary mr-2" for="libre_sobrantes_si" style="width: 50px;">Sí</label>

                                <input type="radio" class="btn-check" name="rev_libre_sobrantes" id="libre_sobrantes_no" autocomplete="off" value="N" required <?= ($res['rev_libre_sobrantes'] == 'N') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="libre_sobrantes_no" style="width: 50px;">No</label>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center mb-3">
                            <label class="mr-3 mb-0 form-label" style="width: 250px;">Número de mezcladora</label>
                            <div class="d-flex justify-content-start">
                                <!-- Mezcladora 1 -->
                                <div class="radio-wrapper position-relative">
                                    <input type="radio" class="btn-check" name="rev_mezcladora" id="mezcladora_1"
                                        value="1" required <?= ($res['rev_mezcladora'] == '1') ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-primary mr-2 radio-label" for="mezcladora_1" style="width: 50px;">1</label>
                                    <?php if ($res_equipos[0] == '2'): ?>
                                        <div class="overlay"></div> <!-- Bloqueo visual -->
                                    <?php endif; ?>
                                </div>

                                <!-- Mezcladora 2 -->
                                <div class="radio-wrapper position-relative">
                                    <input type="radio" class="btn-check" name="rev_mezcladora" id="mezcladora_2"
                                        value="2" required <?= ($res['rev_mezcladora'] == '2') ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-primary radio-label" for="mezcladora_2" style="width: 50px;">2</label>
                                    <?php if ($res_equipos[1] == '2'): ?>
                                        <div class="overlay"></div> <!-- Bloqueo visual -->
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-5">
                        <label for="tarimas" class="form_label">Tarimas</label>
                        <table class="table table-bordered" id="tarimas">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Folio</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-revolturas" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_revolturas" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        obtenerTarimasTabla();

        $('#form_revolturas').submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            console.log(dataForm);
            $.ajax({
                type: 'POST',
                url: 'funciones/revolturas_revolver.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        $('#dataTableRevolturas').DataTable().ajax.reload();
                        obtenerTarimasTabla();
                        alertas_v5("#alerta-revolturas", 'Listo!', res.success, 1, true, 5000);
                    } else {
                        alertas_v5("#alerta-revolturas", 'Error!', res.error, 3, true, 5000);
                    }
                }
            });
        });
    })

    function obtenerTarimasTabla() {
        $.ajax({
            type: 'POST',
            url: 'funciones/revolturas_modal_insertar.php',
            data: {
                rev_id: $('#rev_id').val(),
                action: 'obtener_tarimas'
            },
            success: function(data) {
                console.log("Respuesta del servidor:", data); // Añadido para debug
                try {
                    let res = JSON.parse(data);
                    let tbody = $('#tarimas tbody');
                    tbody.empty(); // Limpia el contenido del tbody
                    if (res.length > 0) {
                        $.each(res, function(index, item) {
                            var row = `<tr><td>${index + 1}</td><td>P${item.pro_id}T${item.tar_folio}</td></tr>`
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="4">No hay datos disponibles</td></tr>');
                    }
                } catch (e) {
                    console.error('Error al parsear JSON:', e);
                }
            },
            error: function() {
                alert('Error al obtener tarimas.');
            }
        });
    }
</script>