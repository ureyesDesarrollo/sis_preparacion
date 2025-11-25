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
    JOIN rev_mezclas_tarimas rt ON t.tar_id = rt.tar_id
    JOIN rev_mezcla m ON rt.mez_id = m.mez_id
    WHERE t.tar_estatus = 5 AND m.mez_id = '$mez_id'");
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
    $sql = "SELECT 
    mez_hora_ini,
    mez_imanes_limpios,
    mez_sacos_limpios,
    mez_libre_sobrantes,
    mez_mezcladora FROM rev_mezcla WHERE mez_id = '$mez_id'";
    $res = mysqli_fetch_assoc(mysqli_query($cnx, $sql));
} catch (Exception $e) {
    echo $e->getMessage();
}
?>


<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Mezcla: <?= $mez_folio ?> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_mezclas" method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-3 d-none">
                                <label for="mez_id" class="form-label">Clave Mezcla</label>
                                <input type="text" class="form-control" name="mez_id" id="mez_id" readonly required value="<?= $mez_id ?>">
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
                                <label for="mez_hora_ini" class="form-label">Hora inicio</label>
                                <input type="time" name="mez_hora_ini" id="mez_hora_ini" class="form-control" required value="<?= $res['mez_hora_ini'] ?>" <?= ($res['mez_hora_ini'] == null) ? '' : 'readonly' ?>>
                            </div>
                            <div class=" col-md-3">
                                <label for="mez_hora_fin" class="form-label">Hora fin</label>
                                <input type="time" name="mez_hora_fin" id="mez_hora_fin" class="form-control">
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
                                <input type="radio" class="btn-check" name="mez_imanes_limpios" id="imanes_limpios_si" autocomplete="off" value="S" required <?= ($res['mez_imanes_limpios'] == 'S') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary mr-2" for="imanes_limpios_si" style="width: 50px;">Sí</label>

                                <input type="radio" class="btn-check" name="mez_imanes_limpios" id="imanes_limpios_no" autocomplete="off" value="N" required <?= ($res['mez_imanes_limpios'] == 'N') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="imanes_limpios_no" style="width: 50px;">No</label>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center mb-3">
                            <label class="mr-3 mb-0 form-label" style="width: 250px;">¿La base para los sacos se encuentra limpia?</label>
                            <div class="d-flex justify-content-start">
                                <input type="radio" class="btn-check" name="mez_sacos_limpios" id="sacos_limpios_si" autocomplete="off" value="S" required <?= ($res['mez_sacos_limpios'] == 'S') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary mr-2" for="sacos_limpios_si" style="width: 50px;">Sí</label>

                                <input type="radio" class="btn-check" name="mez_sacos_limpios" id="sacos_limpios_no" autocomplete="off" value="N" required <?= ($res['mez_sacos_limpios'] == 'N') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="sacos_limpios_no" style="width: 50px;">No</label>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center mb-3">
                            <label class="mr-3 mb-0 form-label" style="width: 250px;">¿La helicoidad está libre de sobrantes?</label>
                            <div class="d-flex justify-content-start">
                                <input type="radio" class="btn-check" name="mez_libre_sobrantes" id="libre_sobrantes_si" autocomplete="off" value="S" required <?= ($res['mez_libre_sobrantes'] == 'S') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary mr-2" for="libre_sobrantes_si" style="width: 50px;">Sí</label>

                                <input type="radio" class="btn-check" name="mez_libre_sobrantes" id="libre_sobrantes_no" autocomplete="off" value="N" required <?= ($res['mez_libre_sobrantes'] == 'N') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="libre_sobrantes_no" style="width: 50px;">No</label>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center mb-3">
                            <label class="mr-3 mb-0 form-label" style="width: 250px;">Número de mezcladora</label>
                            <div class="d-flex justify-content-start">
                                <input type="radio" class="btn-check" name="mez_mezcladora" id="mezcladora_1" autocomplete="off" value="1" required <?= ($res['mez_mezcladora'] == '1') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary mr-2" for="mezcladora_1" style="width: 50px;">1</label>

                                <input type="radio" class="btn-check" name="mez_mezcladora" id="mezcladora_2" autocomplete="off" value="2" required <?= ($res['mez_mezcladora'] == '2') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="mezcladora_2" style="width: 50px;">2</label>
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
                    <div id="alerta-mezclas" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_mezclas" type="submit" class="btn btn-primary ms-2">
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

        $('#form_mezclas').submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            console.log(dataForm);
            $.ajax({
                type: 'POST',
                url: 'funciones/mezclas_mezclar.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        $('#dataTableMezclas').DataTable().ajax.reload();
                        alertas_v5("#alerta-mezclas", 'Listo!', res.success, 1, true, 5000);
                    } else {
                        alertas_v5("#alerta-mezclas", 'Error!', res.error, 3, true, 5000);
                    }
                }
            });
        });
    })

    function obtenerTarimasTabla() {
        $.ajax({
            type: 'POST',
            url: 'funciones/mezclas_modal_insertar.php',
            data: {
                mez_id: $('#mez_id').val(),
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