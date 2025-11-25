<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../seguridad/user_seguridad.php";
?>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Agregar Parametros <strong>Viscosidad</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_vis_agr" method="POST">
                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="vis_descrip">Descripción</label>
                        <input type="text" class="form-control" id="vis_descrip" name="vis_descrip" required>
                    </div>
                    <div class="col-md-3">
                        <label for="vis_min_val">Valor Minimo</label>
                        <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" id="vis_min_val" name="vis_min_val" required>
                    </div>

                    <div class="col-md-3">
                        <label for="vis_max_val">Valor Maximo</label>
                        <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" id="vis_max_val" name="vis_max_val" required>
                    </div>
                    <div class="col-md-3">
                        <label for="vis_color" class="form-label">Color:</label>
                        <input type="color" class="form-control form-control-color" id="vis_color" value="#000000" title="Escoge un color" name="vis_color">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-vis" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_vis_agr" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#form_vis_agr").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            console.log(dataForm);
            $.ajax({
                type: 'POST',
                url: 'catalogos/parametros_calidad_vis_insertar.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    console.log(res);
                    if (res.success) {
                        alertas_v5("#alerta-vis", 'Listo!', res.success, 1, true, 5000);
                        console.log(res.success);
                        $('#dataTableViscosidad').DataTable().ajax.reload();
                        $('#form_vis_agr')[0].reset();
                    } else {
                        alertas_v5("#alerta-vis", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                }
            });
        });
    });
</script>