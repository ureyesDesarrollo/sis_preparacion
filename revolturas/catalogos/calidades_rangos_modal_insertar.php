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
            <h5 class="modal-title">Agregar Calidad - Rango </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_calidad_rango_agr" method="POST">
                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="blo_ini">Bloom inicial</label>
                        <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" id="blo_ini" name="blo_ini" required>
                    </div>
                    <div class="col-md-3">
                        <label for="blo_fin">Bloom final</label>
                        <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" id="blo_fin" name="blo_fin" required>
                    </div>
                    <div class="col-md-3">
                        <label for="vis_ini">Viscosidad inicial</label>
                        <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" id="vis_ini" name="vis_ini" required>
                    </div>

                    <div class="col-md-3">
                        <label for="vis_fin">Viscosidad final</label>
                        <input type="text" onkeypress="return isNumberKey(event, this);" class="form-control" id="vis_fin" name="vis_fin" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="cal_id">Calidad</label>
                        <select name="cal_id" id="cal_id" class="form-select" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-calidad-rango" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_calidad_rango_agr" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        cargarCalidades();
        $("#form_calidad_rango_agr").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'catalogos/calidades_rangos_insertar.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    console.log(res);
                    if (res.success) {
                        alertas_v5("#alerta-calidad-rango", 'Listo!', res.success, 1, true, 5000);
                        console.log(res.success);
                        $('#dataTableCalidadesRangos').DataTable().ajax.reload();
                        $('#form_calidad_rango_agr')[0].reset();
                    } else {
                        alertas_v5("#alerta-calidad-rango", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                }
            });
        });
    });

    function cargarCalidades() {

        $.ajax({
            type: 'GET',
            url: 'catalogos/calidades_listado.php',
            success: function(data) {
                let calidades = JSON.parse(data);
                let options = '';
                calidades.forEach(function(cal) {
                    options += `<option value="${cal.cal_id}">${cal.cal_descripcion}</option>`;
                });
                $('#cal_id').append(options);
            },
            error: function() {
                alert('Error al cargar las calidades.');
            }
        });
    }
</script>