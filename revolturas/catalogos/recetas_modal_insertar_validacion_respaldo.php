<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../funciones/funciones.php";
?>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="form_receta_agr">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRecetaLabel">Agregar Receta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="cte_id" class="form-label">Cliente</label>
                            <select name="cte_id" id="cte_id" class="form-select" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="rre_descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" name="rre_descripcion" required maxlength="30">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row align-items-center mb-3">
                    <div class="col-md-6">
                        <h5 class="mb-0">Detalle</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="button" id="agregarParametro" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Agregar Parametro
                        </button>
                    </div>
                </div>

                <div id="tarimas">
                </div>
            </div>
        </form>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-receta" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_receta_agr" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        obtenerClientes();

        // Inicialmente cargar un campo dinámico
        const fieldHTML = generarCampoTarima();
        $('#tarimas').append(fieldHTML);

        $('#agregarParametro').click(function() {
            const fieldHTML = generarCampoTarima();
            $('#tarimas').append(fieldHTML);
        });

        $('#form_receta_agr').submit(function(e) {
            e.preventDefault();

            let dataForm = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'catalogos/recetas_insertar.php',
                data: dataForm,
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        alertas_v5("#alerta-receta", 'Listo!', res.success, 1, true, 5000);
                        $('#form_receta_agr')[0].reset();
                        $('#dataTableRecetas').DataTable().ajax.reload();
                        $('#tarimas').empty();
                        $('#tarimas').append(fieldHTML);
                    } else {
                        alertas_v5("#alerta-receta", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                },
            });
        });
    });

    function obtenerClientes() {
        $.ajax({
            type: 'GET',
            url: 'catalogos/clientes_listado.php',
            success: function(data) {
                let clientes = JSON.parse(data);
                let options = '<option value="">Seleccione</option>';
                clientes.forEach(function(cte) {
                    if (cte.cte_estatus == 'A') {
                        options += `<option value="${cte.cte_id}">${cte.cte_nombre}</option>`;
                    }
                });
                $('#cte_id').empty().append(options);
            },
            error: function() {
                alert('Error al cargar los clientes.');
            },
        });
    }


    // Objeto para almacenar parámetros seleccionados por tarima
    const selectedParametersByTarima = {};

    function cargarParametros(selectElement, tarimaNo, callback) {
        $.ajax({
            type: 'GET',
            url: 'catalogos/parametros_listado.php',
            success: function(data) {
                let parametros = JSON.parse(data);

                // Filtrar parámetros ya seleccionados para esta tarima
                const filteredOptions = parametros
                    .filter(param =>
                        !selectedParametersByTarima[tarimaNo]?.includes(param.rp_id)
                    )
                    .map(param =>
                        `<option value="${param.rp_id}" data-tipo="${param.rp_tipo}" data-campo="${param.rp_campo}">${param.rp_parametro}</option>`
                    );

                // Generar opciones HTML
                const options = `<option value="">Seleccione</option>` + filteredOptions.join('');
                selectElement.empty();
                selectElement.html(options);

                if (callback) callback(parametros);
            },
            error: function() {
                alert('Error al cargar los parámetros.');
            },
        });
    }

    function generarCampoTarima() {
        const fieldHTML = `
        <div class="row">
            <div class="col-md-3">
                <label for="" class="form-label">No. Tarima</label>
                <input type="text" name="rrd_no_tarima[]" value="" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label">Parametro</label>
                <select name="rp_id[]" class="form-select" required>
                <option>Ingresa el número de tarima</option></select>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label">Comparación</label>
                <div class="dynamic-field-signo"></div>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label">Valor</label>
                <div class="dynamic-field"></div>
            </div>
        </div>
    `;

        const container = $(fieldHTML);

        // Referencias a elementos
        const selectElement = container.find('select[name="rp_id[]"]');
        const tarimaInput = container.find('input[name="rrd_no_tarima[]"]');
        const dynamicField = container.find('.dynamic-field');
        const dynamicFieldSigno = container.find('.dynamic-field-signo');

        // Evento para cargar parámetros dinámicamente según la tarima
        tarimaInput.change(function() {
            const tarimaNo = $(this).val();
            cargarParametros(selectElement, tarimaNo);
        });

        // Evento para manejar selección de parámetros
        selectElement.change(function() {
            const selectedOption = $(this).find(':selected');
            const id = selectedOption.val();
            const tarimaNo = tarimaInput.val();
            const previousValue = $(this).data('previous');

            // Validar número de tarima
            if (!tarimaNo) {
                alert('Primero debe ingresar el número de tarima.');
                $(this).val('');
                return;
            }

            // Inicializar parámetros seleccionados por tarima si no existen
            if (!selectedParametersByTarima[tarimaNo]) {
                selectedParametersByTarima[tarimaNo] = [];
            }

            // Eliminar valor anterior del registro
            if (previousValue) {
                const index = selectedParametersByTarima[tarimaNo].indexOf(previousValue);
                if (index > -1) selectedParametersByTarima[tarimaNo].splice(index, 1);
            }

            // Validar si el nuevo parámetro ya está seleccionado
            if (id && selectedParametersByTarima[tarimaNo].includes(id)) {
                alert('Este parámetro ya ha sido seleccionado para esta tarima.');
                $(this).val('');
                return;
            }

            // Agregar nuevo parámetro al registro
            if (id) {
                selectedParametersByTarima[tarimaNo].push(id);
            }

            // Guardar el valor actual como previo
            $(this).data('previous', id);

            // Manejar campos dinámicos según el tipo de parámetro
            dynamicField.empty();
            dynamicFieldSigno.empty();

            const tipo = selectedOption.data('tipo');
            const campo = selectedOption.data('campo');

            if (tipo === 'F') {
                dynamicField.append('<input type="text" name="rp_valor[]" class="form-control" required onkeypress="return isNumberKey(event, this);">');
                dynamicFieldSigno.append(`
                <select name="rrd_signo[]" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="=">Igual (=)</option>
                    <option value="!=">Diferente (!=)</option>
                    <option value=">">Mayor (&gt;)</option>
                    <option value="<">Menor (&lt;)</option>
                    <option value=">=">Mayor o igual (&gt;=)</option>
                    <option value="<=">Menor o igual (&lt;=)</option>
                </select>
            `);
            } else if (tipo === 'C') {
                const si = campo === 'tar_fino' ? 'F' : 'R';
                const no = campo === 'tar_rechazado' ? 'N' : 'A';
                dynamicField.append(`
                <select name="rp_valor[]" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="${si}">Sí</option>
                    <option value="${no}">No</option>
                </select>
            `);
                dynamicFieldSigno.append('<input type="text" name="rrd_signo[]" class="form-control" required value="=" readonly>');
            } else if (tipo === 'I') {
                const selectDinamic = $('<select name="rp_valor[]" class="form-select" required></select>');
                dynamicField.append(selectDinamic);
                dynamicFieldSigno.append('<input type="text" name="rrd_signo[]" class="form-control" required value="=" readonly>');

                // Cargar datos desde el backend
                $.ajax({
                    type: 'GET',
                    url: 'catalogos/calidades_listado.php',
                    success: function(data) {
                        let calidades = JSON.parse(data);
                        let options = '<option value="">Seleccione</option>';
                        calidades.forEach(function(c) {
                            options += `<option value="${c.cal_id}">${c.cal_descripcion}</option>`;
                        });
                        selectDinamic.append(options);
                    },
                    error: function() {
                        alert('Error al cargar los valores.');
                    },
                });
            }
        });

        return container;
    }
</script>