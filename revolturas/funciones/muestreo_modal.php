<?php
// Desarrollado por: CCA Consultores TI 
// Contacto: contacto@ccaconsultoresti.com 
// Actualizado: Agosto-2024
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";

$cnx = Conectarse();

$listado = mysqli_query($cnx, "SELECT * FROM rev_revolturas_pt_muestreo WHERE rev_id = '" . mysqli_real_escape_string($cnx, $_POST['rev_id']) . "'");

$datos = array();
$pres_kg = array();
$pres_id = array();
while ($fila = mysqli_fetch_assoc($listado)) {
    $datos[] = $fila;
    $pres_id[] = $fila['pres_id'];
}

foreach ($pres_id as $id) {
    $consulta = "SELECT pres_kg FROM rev_presentacion WHERE pres_id = '$id'";
    $resultado = mysqli_query($cnx, $consulta);

    if ($resultado && $fila = mysqli_fetch_assoc($resultado)) {
        $pres_kg[] = $fila['pres_kg'];
    }
}
if (isset($_POST['action']) && $_POST['action'] == 'presentaciones') {
    $listado_presentaciones = mysqli_query($cnx, "SELECT DISTINCT p.pres_descrip, pt.pres_id, p.pres_kg
    FROM rev_revolturas_pt pt 
    INNER JOIN rev_presentacion p ON p.pres_id = pt.pres_id
    WHERE pt.rev_id = '" . mysqli_real_escape_string($cnx, $_POST['rev_id']) . "'");

    if (!$listado_presentaciones) {
        echo json_encode(["error" => "Error al obtener presentaciones: " . mysqli_error($cnx)]);
        exit;
    }

    $presentaciones = array();

    while ($fila = mysqli_fetch_assoc($listado_presentaciones)) {
        $presentaciones[] = $fila;
    }

    echo json_encode($presentaciones);
    exit;
}

mysqli_close($cnx);
?>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Rovoltura: <?= $_POST['rev_folio'] ?> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <form id="form_muestreo" method="POST">
                <input type="text" name="rev_id" value="<?= $_POST['rev_id']  ?>" class="d-none" id="rev_id">
                <div class="row">
                    <div class="col-md-2">
                        <span>#</span>
                    </div>
                    <div class="col-md-3">
                        <span>Presentaciones</span>
                    </div>
                    <div class="col-md-5">
                        <span>Kilos</span>
                    </div>
                </div>
                <?php for ($i = 1; $i <= 10; $i++): ?>

                    <?php
                    $rm_kilos = isset($datos[$i - 1]['rm_kilos']) ? (float)$datos[$i - 1]['rm_kilos'] : null;
                    $pres_id = isset($datos[$i - 1]['pres_id']) ? htmlspecialchars($datos[$i - 1]['pres_id']) : '';
                    $isInvalid = ($rm_kilos !== null && ($rm_kilos < (float)$pres_kg[$i - 1] || $rm_kilos > (float)$pres_kg[$i - 1])) ? 'is-invalid' : '';
                    ?>
                    <div class="form-group row mt-2">
                        <div class="col-md-1">
                            <sapan class="form-label"><?= $i ?></sapan>
                        </div>
                        <div class="col-md-4">
                            <select name="pres_id_<?= $i ?>" class="form-select" id="presentacion_<?= $i ?>">
                            </select>
                            <input type="text" name="pres_id_hidden_<?= $i ?>" id="pres_id_hidden_<?= $i ?>" value="<?= $pres_id ?>" class="d-none">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="campo_<?= $i ?>" class="form-control <?= $isInvalid ?>"
                                id="campo_<?= $i ?>" value="<?= isset($datos[$i - 1]['rm_kilos']) ? htmlspecialchars($datos[$i - 1]['rm_kilos']) : '' ?>" onkeypress="return isNumberKey(event, this);">
                        </div>


                    </div>
                <?php endfor; ?>
            </form>

        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-muestreo" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>

                    <button form="form_muestreo" type="submit" class="btn btn-primary ms-2" id="btn_form">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        cargarPresentaciones();

        $('#form_muestreo').submit(function(e) {
            e.preventDefault();
            let formData = {
                rev_id: $('#rev_id').val(),
                muestra: [],
                pres_id: []
            };

            $('#form_muestreo').find('input[id^="campo_"]').each(function() {
                let valor = $(this).val();
                if (valor.trim() !== "") {
                    formData.muestra.push(valor);
                }
            });

            $('#form_muestreo').find('select[id^="presentacion_"]').each(function() {
                let valor = $(this).val();
                if (valor.trim() !== "") {
                    formData.pres_id.push(valor);
                }
            });
            console.log(formData);
            $.ajax({
                url: 'funciones/muestreo_insertar.php',
                type: 'POST',
                data: {
                    data: JSON.stringify(formData)
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.fallidos.length > 0) {
                        res.fallidos.forEach(function(param) {
                            $('#campo_' + param).addClass('is-invalid');
                        });
                        alertas_v5("#alerta-muestreo", 'Listo!', res.success, 1, true, 5000);
                    } else {
                        alertas_v5("#alerta-muestreo", 'Listo!', res.success, 1, true, 5000);
                        $('#form_muestreo .is-invalid').removeClass('is-invalid');
                    }
                },
                error: function(xhr, status, error) {
                    alertas_v5("#alerta-muestreo", 'Error', 'No se pudo completar la solicitud.', 3, true, 5000);
                }
            });
        });
    });

    function cargarPresentaciones() {
        $.ajax({
            type: 'POST',
            url: 'funciones/muestreo_modal.php',
            data: {
                rev_id: $('#rev_id').val(),
                action: 'presentaciones'
            },
            success: function(data) {
                try {
                    let presentaciones = JSON.parse(data);
                    let options = '<option value="">Seleccione</option>';
                    presentaciones.forEach(function(presentacion) {
                        options += `<option value="${presentacion.pres_id}">${presentacion.pres_descrip} - ${presentacion.pres_kg}</option>`;
                    });

                    // Asignar las opciones a cada select correspondiente
                    $('#form_muestreo').find('select[id^="presentacion_"]').each(function(index) {
                        let selectId = $(this).attr('id');
                        $(this).html(options);

                        // Establecer el valor predeterminado
                        let hiddenPresId = $('#pres_id_hidden_' + (index + 1)).val();
                        if (hiddenPresId) {
                            $(this).val(hiddenPresId);
                        }
                    });
                } catch (e) {
                    console.error('Error al procesar los datos:', e);
                    alert('Error al procesar los datos de presentaciones.');
                }
            },
            error: function() {
                alert('Error al cargar las presentaciones.');
            }
        });
    }
</script>