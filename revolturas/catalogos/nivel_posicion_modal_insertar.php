<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";

?>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Agregar Nivel - Posición</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_nivel_pos_agr" method="POST">
                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="rac_zona" class="form-label">Zona del Rack</label>
                        <select name="rac_zona" id="rac_zona_sel" class="form-select" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rac_id_n" class="form-label">Rack</label>
                        <select name="rac_id" id="rac_id_n" class="form-select" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="niv_nivel" class="form-label">Filas (Ejemplo: A,B,C,D):</label>
                        <input type="text" class="form-control" name="filas" id="filas" required>
                    </div>
                    <div class="col-md-3">
                        <label for="niv_posicion" class="form-label">Cantidad de Niveles:</label>
                        <input type="text" class="form-control" id="niveles" name="niveles" min="1" required onkeypress="return isNumberKey(event, this);" maxlength="2">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-nivel-pos" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_nivel_pos_agr" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        cargarRacksZonas();

        $('#rac_zona_sel').on('change', function(e) {
            cargarRacks($(this).val());
        });

        $("#form_nivel_pos_agr").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'catalogos/nivel_posicion_insertar.php',
                data: dataForm,
                success: function(result) {
                    console.log(result);
                    let res = JSON.parse(result);
                    console.log(res);
                    if (res.success) {
                        alertas_v5("#alerta-nivel-pos", 'Listo!', res.success, 1, true, 5000);
                        $('#dataTableNivelPos').DataTable().ajax.reload();
                        $('#form_nivel_pos_agr')[0].reset();
                    } else {
                        alertas_v5("#alerta-nivel-pos", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                }
            });
        });
    });


    function cargarRacksZonas() {
        $.ajax({
            type: 'GET',
            url: 'catalogos/racks_listado.php',
            success: function(data) {
                let racks = JSON.parse(data);
                let zonasSet = new Set();
                let options = '';

                racks.forEach(function(rack) {
                    if (rack.rac_estatus === 'A') {
                        zonasSet.add(rack.rac_zona);
                    }
                });
                zonasSet.forEach(function(zona) {
                    options += `<option value="${zona}">${zona}</option>`;
                });

                $('#rac_zona_sel').append(options);
            },
            error: function() {
                alert('Error al cargar los racks.');
            }
        });
    }

    function cargarRacks(rac_zona) {
        $.ajax({
            type: 'GET',
            url: 'catalogos/racks_listado.php',
            success: function(data) {
                let racks = JSON.parse(data);
                let options = '<option value="">Seleccione</option>';
                racks.forEach(function(rack) {
                    if (rack.rac_estatus === 'A' && rack.rac_zona === rac_zona) {
                        options += `<option value="${rack.rac_id}">${rack.rac_descripcion}</option>`;
                    }
                });
                $('#rac_id_n').empty().append(options);
            },
            error: function() {
                alert('Error al cargar los racks.');
            }
        });
    }
</script>