<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2024*/

include "../../seguridad/user_seguridad.php";
?>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalCerrarProcesosLabel">Cerrar procesos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_cerrar_proceso_admin" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <label for="pro_id" class="form-label">Proceso</label>
                        <select name="pro_id" id="pro_id" class="form-select" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta_cerrar_proceso" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_cerrar_proceso_admin" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        cargarProcesos();

        $("#form_cerrar_proceso_admin").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "administrador/cerrar_procesos.php",
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        alertas_v5("#alerta_cerrar_proceso", 'Listo!', res.success, 1, true, 5000);
                        $('#form_cerrar_proceso_admin')[0].reset();
                    } else {
                        alertas_v5("#alerta_cerrar_proceso", 'Error!', res.error, 3, true, 5000);
                    }
                },
                error: function() {
                    alertas_v5("#alerta_cerrar_proceso", 'Error!', 'Hubo un problema al procesar la solicitud.', 3, true, 5000);
                }
            });
        });
    });

    function cargarProcesos() {
        $.ajax({
            type: 'GET',
            url: 'funciones/tarimas_procesos_listado.php',
            success: function(data) {
                let procesos = JSON.parse(data);
                let options = '<option value="">Seleccione</option>';
                procesos.forEach(function(pro) {
                    options += `<option value="${pro.pro_id}">${pro.pro_id}</option>`;
                });
                $('#pro_id').empty().append(options);
            },
            error: function() {
                alert('Error al cargar los procesos.');
            }
        });
    }
</script>