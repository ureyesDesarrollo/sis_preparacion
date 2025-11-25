<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx =  Conectarse();
extract($_POST);

$query = mysqli_query($cnx, "SELECT * FROM rev_bloom WHERE blo_id = $id");
$registro = mysqli_fetch_assoc($query);

?>
<style>
    .custom-checkbox {
        width: 40px;
        transform: scale(1.5);
    }
</style>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Actualizar Parametros <strong>Bloom</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_bloom_act" method="POST">
                <div class="form-group row">
                    <div class="col-md-3 d-none">
                        <label for="blo_id">Clave</label>
                        <input type="text" class="form-control" id="blo_id" name="blo_id" value="<?= $registro['blo_id'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="blo_ini">Bloom Inicial</label>
                        <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" id="blo_ini" name="blo_ini" required value="<?= $registro['blo_ini'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="blo_fin">Bloom Final</label>
                        <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" id="blo_fin" name="blo_fin" required value="<?= $registro['blo_fin'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="blo_etiqueta">Etiqueta</label>
                        <input type="text" class="form-control" id="blo_etiqueta" name="blo_etiqueta" required value="<?= $registro['blo_etiqueta'] ?>">
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch p-0">
                            <div class="d-flex flex-column-reverse gap-1">
                                <input class="form-check-input ms-0 custom-checkbox" type="checkbox" role="switch" id="chk_estatus" name="chk_estatus" <?php echo ($registro['blo_estatus'] == 'A') ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="chk_estatus" id="chk_estatus_label">Estatus</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-bloom-act" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_bloom_act" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('chk_estatus_label').addEventListener('click', function(event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado del label
    });

    $(document).ready(function() {
        $("#form_bloom_act").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            console.log(dataForm);
            $.ajax({
                type: 'POST',
                url: 'catalogos/parametros_calidad_bloom_actualizar.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    console.log(res);
                    if (res.success) {
                        alertas_v5("#alerta-bloom-act", 'Listo!', res.success, 1, true, 5000);
                        console.log(res.success);
                        $('#dataTableBloom').DataTable().ajax.reload();
                    } else {
                        alertas_v5("#alerta-bloom-act", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                }
            });
        });
    });
</script>