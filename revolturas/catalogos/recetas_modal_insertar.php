<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../funciones/funciones.php";
?>
<style>
     /* Ajustes para la modal fullscreen */
     .modal-receta .modal-content {
        height: 100vh;
        /* Toda la altura de la pantalla */
        overflow-y: auto;
        /* Scroll si el contenido excede */
    }

    .modal-receta .modal-body {
        max-height: calc(100vh - 120px);
        /* Ajuste para body */
        overflow-y: auto;
    }

    .modal-receta .modal-fullscreen .modal-dialog {
        margin: 0;
        max-width: 100%;
        max-height: 100vh;
    }
</style>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-fullscreen modal-receta">

    <div class="modal-content">
        <form id="form_receta_agr">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRecetaLabel">Agregar Receta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="form-group">
                            <label for="cte_id" class="form-label">Cliente</label>
                            <select name="cte_id" id="cte_id" class="form-select" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-5">
                        <div class="form-group">
                            <label for="rre_descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" name="rre_descripcion" required maxlength="30">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <img src="../iconos/close.png" alt=""> Cerrar
                        </button>
                        <button form="form_receta_agr" type="submit" class="btn btn-primary ms-2">
                            <img src="../iconos/guardar.png" alt=""> Guardar
                        </button>
                    </div>
                </div>
                <div class="row w-100 align-items-center mt-3">
                    <div class="col-md-8 mb-3">
                        <div id="alerta-receta" class="alert alert-success m-0 d-none">
                            <strong class="alert-heading"></strong>
                            <span class="alert-body"></span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="container-fluid">
                    <button type="button" class="btn btn-success mb-3" id="agregar-tarima-vacia">Agregar Tarima Vacía</button>

                    <div id="tarimas">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-10" id="tarima-1">
                                        <div class="row">
                                            <div class="col-2" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                                <h5>No. Tarima 1</h5>
                                                <input type="hidden" name="tarima_numero[]" value="1">
                                            </div>
                                            <div class="col form-group">
                                                <label for="">Parámetros 1</label>
                                                <button type="button" class="btn btn-danger mb-1 btn-xs btn-remove-param" style="font-size: 12px; padding: 0.2rem 0.5rem; line-height: 1;">
                                                    X
                                                </button>
                                                <select name="rp_id[]" class="form-select mb-3" required>
                                                    <option>Seleccionar parámetros</option>
                                                </select>
                                                <div class="dynamic-field-signo mb-3"></div>
                                                <div class="dynamic-field"></div>
                                            </div>
                                            <div class="col form-group">
                                                <label for="">Parámetro 2</label>
                                                <button type="button" class="btn btn-danger mb-1 btn-xs btn-remove-param" style="font-size: 12px; padding: 0.2rem 0.5rem; line-height: 1;">
                                                    X
                                                </button>
                                                <select name="rp_id[]" class="form-select mb-3" required>
                                                    <option>Seleccionar parámetros</option>
                                                </select>
                                                <div class="dynamic-field-signo mb-3"></div>
                                                <div class="dynamic-field"></div>
                                            </div>
                                            <div class="col form-group">
                                                <label for="">Parámetro 3</label>
                                                <button type="button" class="btn btn-danger mb-1 btn-xs btn-remove-param" style="font-size: 12px; padding: 0.2rem 0.5rem; line-height: 1;">
                                                    X
                                                </button>
                                                <select name="rp_id[]" class="form-select mb-3" required>
                                                    <option>Seleccionar parámetros</option>
                                                </select>
                                                <div class="dynamic-field-signo mb-3"></div>
                                                <div class="dynamic-field"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="col">
                                            <div class="row">
                                                <button type="button" class="btn btn-primary mb-3 btn-agregar-parametro">Agregar parámetro</button>
                                                <button type="button" class="btn btn-info mb-3 btn-duplicar-tarima">Duplicar tarima</button>
                                                <button type="button" class="btn btn-danger btn-quitar-tarima">Quitar tarima</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        obtenerClientes();
        cargarParametros();
        $('#modalReceta').on('hidden.bs.modal', function() {
            $(this).find('.modal-content').css('height', '');
        });



        $(document).on('change', 'select[name="rp_id[]"]', function() {
            let selectedOption = $(this).find('option:selected');
            let tipo = selectedOption.data('tipo');
            let campo = selectedOption.data('campo');
            cargarCamposAdicionales($(this), tipo, campo);
        });


        $(document).off('click', '.btn-agregar-parametro').on('click', '.btn-agregar-parametro', function() {
            const container = $(this).closest('.card-body').find('.col-10 .row').first();

            // Obtener el número actual de parámetros en el contenedor
            const parametrosActuales = container.find('.form-group').length;

            // Establecer el contador inicial basado en los parámetros ya en pantalla
            let containerCounter = parametrosActuales + 1;

            // Generar y agregar el parámetro con el número actualizado
            const newParametro = agregarParametro(containerCounter);
            container.append(newParametro);

            cargarParametros();
        });


        $(document).off('click', '.btn-duplicar-tarima').on('click', '.btn-duplicar-tarima', function() {
            const container = $(this).closest('.card-body').find('.col-10 .row').first();

            // Llamamos a la función duplicarTarima
            duplicarTarima(container);
        });

        $(document).off('click', '.btn-quitar-tarima').on('click', '.btn-quitar-tarima', function() {
            // Eliminamos la tarjeta actual
            $(this).closest('.card').remove();

            actualizarNumeracionTarimas();
        });

        $(document).off('click', '#agregar-tarima-vacia').on('click', '#agregar-tarima-vacia', function() {
            // Obtener el contenedor donde se agregan las tarimas
            const container = $('#tarimas');

            // Crear una nueva tarima vacía
            const nextTarima = crearTarimaVacia();

            // Agregar la nueva tarima vacía al contenedor de tarimas
            container.append(nextTarima);
            cargarParametros();
        });


        $(document).off('click', '.btn-remove-param').on('click', '.btn-remove-param', function() {
            const container = $(this).closest('.row');

            // Eliminar el parámetro
            $(this).closest('.col.form-group').remove();

            // Actualizar numeración de los parámetros
            actualizarNumeracionParametros(container);
        });


        $('#form_receta_agr').submit(function(e) {
            e.preventDefault();

            const receta = procesarReceta(this);

            if (receta) {
                $.ajax({
                    type: 'POST',
                    url: 'catalogos/recetas_insertar.php',
                    data: JSON.stringify(receta),
                    contentType: 'application/json',
                    success: function(response) {
                        let res = JSON.parse(response);
                        console.log(res);
                        if (res.status === 'success') {
                            alertas_v5("#alerta-receta", 'Listo!', res.message, 1, true, 5000);
                            $('#form_receta_agr')[0].reset();
                            $('#dataTableRecetas').DataTable().ajax.reload();
                            $('#tarimas').empty();
                        } else {
                            alertas_v5("#alerta-receta", 'Error!', res.message, 3, true, 5000);
                            console.log(res.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            //console.log(receta);
        });

    });

    function procesarReceta(form) {
        // Inicializamos la receta con datos vacíos
        const receta = {
            descripcion: "",
            cliente_id: "",
            tarimas: []
        };

        // Obtenemos los datos del formulario serializados
        const formArray = $(form).serializeArray();
        let currentTarima = null;

        // Iteramos sobre los campos del formulario
        formArray.forEach(field => {
            switch (field.name) {
                case "rre_descripcion":
                    receta.descripcion = field.value || "";
                    break;

                case "cte_id":
                    receta.cliente_id = field.value || "";
                    break;

                case "tarima_numero[]":
                    // Comprobamos si hay una tarima existente y si no la hay, iniciamos una nueva
                    currentTarima = {
                        numero: field.value,
                        parametros: [],
                        signo: [],
                        valor: []
                    };
                    receta.tarimas.push(currentTarima); // Agregamos la tarima a la receta
                    break;

                case "rp_id[]":
                    // Agregamos un parámetro a la tarima actual
                    if (currentTarima) currentTarima.parametros.push(field.value);
                    break;

                case "rp_valor[]":
                    // Agregamos un valor a la tarima actual
                    if (currentTarima) currentTarima.valor.push(field.value);
                    break;

                case "rrd_signo[]":
                    // Agregamos un signo a la tarima actual
                    if (currentTarima) currentTarima.signo.push(field.value);
                    break;

                default:
                    console.warn(`Campo inesperado: ${field.name}`);
            }
        });

        // Validamos que la receta tenga al menos una descripción, cliente y tarimas
        if (!receta.descripcion || !receta.cliente_id || receta.tarimas.length === 0) {
            console.error("Faltan datos necesarios para la receta.");
            alert("Por favor, completa todos los campos obligatorios.");
            return null; // Retornamos null si falta algún dato
        }

        return receta; // Retornamos el objeto receta si todo está correcto
    }


    function duplicarTarima(container) {
        // Crear una nueva tarjeta clonada
        let nextTarima = container.closest('.card').clone();

        // Obtener los valores de los campos de la tarima actual
        const parametros = container.find('.form-select');
        const valoresInputs = container.find('.dynamic-field input');
        const valoresSelects = container.find('.dynamic-field select');

        // Asignar los valores copiados a la nueva tarima
        nextTarima.find('.form-select').each(function(index) {
            const valorParametro = parametros.eq(index).val();
            $(this).val(valorParametro); // Copiar el valor del parámetro
        });

        // Asignar valores a los campos de tipo input
        nextTarima.find('.dynamic-field input').each(function(index) {
            const valorInput = valoresInputs.eq(index).val();
            $(this).val(valorInput); // Copiar el valor del input
        });

        // Asignar valores a los campos de tipo select dentro de dynamic-field
        nextTarima.find('.dynamic-field select').each(function(index) {
            const valorSelect = valoresSelects.eq(index).val();
            $(this).val(valorSelect); // Copiar el valor del select
        });

        // Actualizar el título de la tarima en la nueva tarjeta
        let tarimaCount = $('.card').length + 1;
        nextTarima.find('h5').text(`No. Tarima ${tarimaCount}`);
        nextTarima.find('.card-body .col-10').attr('id', `tarima-${tarimaCount}`);
        nextTarima.find('input[name="tarima_numero[]"]').val(tarimaCount);
        // Agregar la nueva tarima clonada al contenedor de tarimas
        $('#tarimas').append(nextTarima);
    }

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

    function cargarParametros() {
        $.ajax({
            type: 'GET',
            url: 'catalogos/parametros_listado.php',
            success: function(data) {
                const parametros = JSON.parse(data);
                const filteredOptions = parametros.map(param =>
                    `<option value="${param.rp_id}" data-tipo="${param.rp_tipo}" data-campo="${param.rp_campo}">${param.rp_parametro}</option>`
                );

                let options = `<option value="">Seleccione</option>` + filteredOptions.join('');
                $('select[name="rp_id[]"]').each(function() {
                    if ($(this).children('option').length === 1) { // Solo actualizar si está vacío
                        $(this).empty().append(options);
                    }
                });
            },
            error: function() {
                alert('Error al cargar los parámetros.');
            },
        });
    }


    function cargarCamposAdicionales(selectElement, tipo, campo) {
        let dynamicFieldSigno = selectElement.closest('.form-group').find('.dynamic-field-signo');
        let dynamicField = selectElement.closest('.form-group').find('.dynamic-field');

        dynamicFieldSigno.empty();
        dynamicField.empty();

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
            const si = campo === 'tar_fino' ? 'F' : 'C';
            const no = campo === 'tar_fino' ? 'N' : 'A';
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
    }

    function agregarParametro(containerCounter) {
        return `
        <div class="col form-group">
            <label for="">Parámetro ${containerCounter}</label>
            <button type="button" class="btn btn-danger mb-1 btn-xs btn-remove-param" style="font-size: 12px; padding: 0.2rem 0.5rem; line-height: 1;">
                X
            </button>
            <select name="rp_id[]" class="form-select mb-3" required>
                <option>Seleccionar parametro</option>
            </select>
            <div class="dynamic-field-signo mb-3"></div>
            <div class="dynamic-field"></div>
        </div>
    `;
    }

    function crearTarimaVacia() {
        // Crear una nueva tarjeta vacía (sin parámetros y sin valores)
        const tarimaCount = $('.card').length + 1; // Consecutivo de la tarima
        const tarimaVacia = $(`
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-10" id="tarima-${tarimaCount}">
                        <div class="row">
                            <div class="col-2" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <h5>No. Tarima ${tarimaCount}</h5>
                                <input type="hidden" name="tarima_numero[]" value="${tarimaCount}">
                            </div>
                            <div class="col form-group">
                                <label for="">Parámetros 1</label>
                                <button type="button" class="btn btn-danger mb-1 btn-xs btn-remove-param" style="font-size: 12px; padding: 0.2rem 0.5rem; line-height: 1;">
                                    X
                                </button>
                                <select name="rp_id[]" class="form-select mb-3" required>
                                    <option>Seleccionar parámetros</option>
                                </select>
                                <div class="dynamic-field-signo mb-3"></div>
                                <div class="dynamic-field"></div>
                            </div>
                            <div class="col form-group">
                                <label for="">Parámetro 2</label>
                                <button type="button" class="btn btn-danger mb-1 btn-xs btn-remove-param" style="font-size: 12px; padding: 0.2rem 0.5rem; line-height: 1;">
                                    X
                                </button>
                                <select name="rp_id[]" class="form-select mb-3" required>
                                    <option>Seleccionar parámetros</option>
                                </select>
                                <div class="dynamic-field-signo mb-3"></div>
                                <div class="dynamic-field"></div>
                            </div>
                            <div class="col form-group">
                                <label for="">Parámetro 3</label>
                                <button type="button" class="btn btn-danger mb-1 btn-xs btn-remove-param" style="font-size: 12px; padding: 0.2rem 0.5rem; line-height: 1;">
                                    X
                                </button>
                                <select name="rp_id[]" class="form-select mb-3" required>
                                    <option>Seleccionar parámetros</option>
                                </select>
                                <div class="dynamic-field-signo mb-3"></div>
                                <div class="dynamic-field"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="col">
                            <div class="row">
                                <button type="button" class="btn btn-primary mb-3 btn-agregar-parametro">Agregar parámetro</button>
                                <button type="button" class="btn btn-info mb-3 btn-duplicar-tarima">Duplicar tarima</button>
                                <button type="button" class="btn btn-danger btn-quitar-tarima">Quitar tarima</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `);

        // Retornar la nueva tarima vacía
        return tarimaVacia;
    }

    function actualizarNumeracionParametros(container) {
        container.find('.col.form-group').each(function(index) {
            const numero = index + 1;
            $(this).find('label').text(`Parámetro ${numero}`);
        });
    }

    function actualizarNumeracionTarimas() {
        let index = 1;
        $('.card').each(function() {
            $(this).attr('id', `tarima-${index}`);
            $(this).find('h5').text(`No. Tarima ${index}`);
            $(this).find('input[name="tarima_numero[]"]').val(index);
            index++;
        });
        tarimaCount = index - 1;
    }
</script>