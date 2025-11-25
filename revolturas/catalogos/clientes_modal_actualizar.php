<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();
try {
    $cnx = Conectarse();
    extract($_POST);
    $res = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT * FROM rev_clientes WHERE cte_id = '$id'"));
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<style>
    .custom-checkbox {
        width: 40px;
        transform: scale(1.5);
    }
</style>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Actualizar Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_cliente_act" method="POST">
                <input type="text" name="cte_id" value="<?= $res['cte_id'] ?>" class="d-none">
                <div class="row pt-3 gx-3 align-items-end">
                    <div class="col-md-3">
                        <label for="cte_nombre" class="form-label">Nombre</label>
                        <input type="text" name="cte_nombre" id="cte_nombre" class="form-control" required value="<?= $res['cte_nombre'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="cte_rfc" class="form-label">RFC</label>
                        <input type="text" name="cte_rfc" id="cte_rfc" class="form-control" maxlength="13" required value="<?= $res['cte_rfc'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="cte_razon_social" class="form-label">Razón social</label>
                        <input type="text" name="cte_razon_social" id="cte_razon_social" class="form-control" required value="<?= $res['cte_razon_social'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="cte_ubicacion" class="form-label">Ubicación</label>
                        <input type="text" name="cte_ubicacion" id="cte_ubicacion" class="form-control" value="<?= $res['cte_ubicacion'] ?> ">
                    </div>

                    <div class="col-md-3">
                        <label for="cte_tipo" class="form-label">Tipo de cliente</label>
                        <select name="cte_tipo" id="cte_tipo" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option value="Comercial" <?= $res['cte_tipo'] == 'Comercial' ? 'selected' : '' ?>>Comercial</option>
                            <option value="Industrial" <?= $res['cte_tipo'] == 'Industrial' ? 'selected' : '' ?>>Industrial</option>
                            <option value="Ambos" <?= $res['cte_tipo'] == 'Ambos' ? 'selected' : '' ?>>Ambos</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="cte_clasificacion" class="form-label">Clasificación</label>
                        <select name="cte_clasificacion" id="cte_clasificacion" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option value="AA" <?= $res['cte_clasificacion'] == 'AA'  ? 'selected' : '' ?>>AA</option>
                            <option value="AAA" <?= $res['cte_clasificacion'] == 'AAA' ? 'selected' : '' ?>>AAA</option>
                        </select>
                    </div>
                    <div class="form-group ps-3">
                        <div class="col-md-3">
                            <div class="form-check form-switch p-0">
                                <div class="d-flex flex-column-reverse gap-1">
                                    <input class="form-check-input ms-0 custom-checkbox" type="checkbox" role="switch" id="chk_estatus" name="chk_estatus" <?php echo ($res['cte_estatus'] == 'A') ? 'checked' : ''; ?> />
                                    <label class="form-check-label" for="chk_estatus" id="chk_estatus_label">Estatus</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-cliente-act" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_cliente_act" type="submit" class="btn btn-primary ms-2">
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
        $('#form_cliente_act').submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'catalogos/clientes_actualizar.php',
                data: dataForm,
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        alertas_v5("#alerta-cliente-act", 'Listo!', res.success, 1, true, 5000);
                        console.log(res.success);
                        $('#dataTableClientes').DataTable().ajax.reload();
                    } else {
                        alertas_v5("#alerta-cliente-act", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                }
            })
        })
    })
</script>