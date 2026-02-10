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
            <h5 class="modal-title">Agregar Rack</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_rack_agr" method="POST">
                <div class="form-group row">
                    <div class="col-md-5">
                        <label for="rac_zona">Zona del Rack</label>
                        <select class="form-select" id="rac_zona" name="rac_zona" required>
                            <option value="">Seleccione la zona</option>
                            <option value="PRODUCTO TERMINADO">PRODUCTO TERMINADO</option>
                            <option value="PATIO REVOLTURAS">PATIO REVOLTURAS</option>
                            <option value="CUARENTENA">CUARENTENA</option>
                            <option value="EMBARQUE">EMBARQUE</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="rac_descripcion">Nombre del Rack</label>
                        <input type="text" class="form-control" id="rac_descripcion" name="rac_descripcion" required>
                    </div>
                    <div class="col-md-2">
                        <label for="rac_color">Color del Rack</label>
                        <input type="color" class="form-control form-control-color" id="rac_color" value="#000000" title="Escoge un color" name="rac_color">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-rack" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_rack_agr" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#form_rack_agr").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'catalogos/racks_insertar.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    console.log(res);
                    if (res.success) {
                        alertas_v5("#alerta-rack", 'Listo!', res.success, 1, true, 5000);
                        console.log(res.success);
                        $('#dataTableRacks').DataTable().ajax.reload();
                        $('#form_rack_agr')[0].reset();
                    } else {
                        alertas_v5("#alerta-rack", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                }
            });
        });
    });
</script>