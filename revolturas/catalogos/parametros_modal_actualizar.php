<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

extract($_POST);
try {
    $res = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT * FROM rev_parametros WHERE rp_id = '$id'"));
} catch (Exception $e) {
    echo $e->getMessage();
}

?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Actualizar parámetros de: <?= $res['rp_parametro'] ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_parametros" method="POST">
                <input type="text" name="rp_id" id="rp_id" class="d-none" value="<?= $id ?>">
                <div class="row">
                    <div class="col-md-6">
                        <label for="rp_inicio" class="form-label">Valor inicial</label>
                        <input type="text" class="form-control" name="rp_inicio" id="rp_inicio" onkeypress="return isNumberKey(event, this);" value="<?= $res['rp_inicio'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="rp_fin" class="form-label">Valor Final</label>
                        <input type="text" class="form-control" name="rp_fin" id="rp_fin" onkeypress="return isNumberKey(event, this);" value="<?= $res['rp_fin'] ?>" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-param" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_parametros" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form_parametros').submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            console.log(dataForm);
            $.ajax({
                type: 'POST',
                url: 'catalogos/parametros_actualizar.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        $('#dataTableParametros').DataTable().ajax.reload();
                        alertas_v5("#alerta-param", 'Listo!', res.success, 1, true, 5000);
                    } else {
                        alertas_v5("#alerta-param", 'Error!', res.error, 3, true, 5000);
                    }
                }
            });
        });
    });
</script>