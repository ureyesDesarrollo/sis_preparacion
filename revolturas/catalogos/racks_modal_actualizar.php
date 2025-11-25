<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../seguridad/user_seguridad.php";

include "../../conexion/conexion.php";
$cnx =  Conectarse();
extract($_POST);

try {
    $query = mysqli_query($cnx, "SELECT * FROM rev_racks WHERE rac_id = $id");
    $registro = mysqli_fetch_assoc($query);
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
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Actualizar Rack</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_rac_act" method="POST">
                <div class="form-group row">
                    <div class="col-md-6 d-none">
                        <label for="rac_id">Clave</label>
                        <input type="text" class="form-control" id="rac_id" name="rac_id" required value="<?= $registro['rac_id'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="rac_descripcion">Nombre del Rack</label>
                        <input type="text" class="form-control" id="rac_descripcion" name="rac_descripcion" required value="<?= $registro['rac_descripcion'] ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="rac_color">Color del Rack</label>
                        <input type="color" class="form-control form-control-color" id="rac_color" value="<?= $registro['rac_color'] ?>" title="Escoge un color" name="rac_color">
                    </div>
                    <div class="col-md-4">
                        <label for="rac_zona">Zona del Rack</label>
                        <input type="text" class="form-control" id="rac_zona" name="rac_zona" required value="<?= $registro['rac_zona'] ?>">
                    </div>
                    <div class="col-md-2">
                        <div class="form-check form-switch p-0">
                            <div class="d-flex flex-column-reverse gap-1">
                                <input class="form-check-input ms-0 custom-checkbox" type="checkbox" role="switch" id="chk_estatus" name="chk_estatus" <?php echo ($registro['rac_estatus'] == 'A') ? 'checked' : ''; ?> />
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
                    <div id="alerta-rac-act" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_rac_act" type="submit" class="btn btn-primary ms-2">
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
        $("#form_rac_act").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            console.log(dataForm);
            $.ajax({
                type: 'POST',
                url: 'catalogos/racks_actualizar.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    console.log(res);
                    if (res.success) {
                        alertas_v5("#alerta-rac-act", 'Listo!', res.success, 1, true, 5000);
                        $('#dataTableRacks').DataTable().ajax.reload();
                        console.log(res.success);
                    } else {
                        alertas_v5("#alerta-rac-act", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                },
                error: function(result) {
                    let res = JSON.parse(result);
                    console.log(res);
                }
            });
        });
    });
</script>