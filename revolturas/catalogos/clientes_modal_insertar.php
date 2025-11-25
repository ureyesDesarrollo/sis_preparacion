<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
?>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Agregar Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_cliente_agr" method="POST" autocomplete="off">
                <div class="row pt-3">
                    <div class="col-md-3">
                        <label for="cte_nombre" class="form-label">Nombre</label>
                        <input type="text" name="cte_nombre" id="cte_nombre" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="cte_rfc" class="form-label">RFC</label>
                        <input type="text" name="cte_rfc" id="cte_rfc" class="form-control" maxlength="13" required>
                    </div>
                    <div class="col-md-3">
                        <label for="cte_razon_social" class="form-label">Razón social</label>
                        <input type="text" name="cte_razon_social" id="cte_razon_social" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="cte_ubicacion" class="form-label">Ubicación</label>
                        <input type="text" name="cte_ubicacion" id="cte_ubicacion" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="cte_tipo" class="form-label">Tipo de cliente</label>
                        <select name="cte_tipo" id="cte_tipo" class="form-select" required>
                            <option value="" disabled selected>Seleccione</option>
                            <option value="Comercial">Comercial</option>
                            <option value="Industrial">Industrial</option>
                            <option value="Ambos">Ambos</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="cte_clasificacion" class="form-label">Clasificación</label>
                        <select name="cte_clasificacion" id="cte_clasificacion" class="form-select" required>
                            <option value="" disabled selected>Seleccione</option>
                            <option value="AA">AA</option>
                            <option value="AAA">AAA</option>
                        </select>
                    </div>

                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-cliente" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_cliente_agr" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#form_cliente_agr').submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();

            console.log(dataForm);
            $.ajax({
                type: 'POST',
                url: 'catalogos/clientes_insertar.php',
                data: dataForm,
                success: function(response) {
                    let res = response;
                    if (res.success) {
                        alertas_v5("#alerta-cliente", 'Listo!', res.success, 1, true, 5000);
                        $('#dataTableClientes').DataTable().ajax.reload();
                        $('#form_cliente_agr')[0].reset();
                    } else {
                        alertas_v5("#alerta-cliente", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                }
            })
        })
    })
</script>