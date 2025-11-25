<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../funciones/funciones.php";
include "../../conexion/conexion.php";

if (isset($_POST['action'])  && $_POST['action'] == 'obtener_calidad') {
    $cnx = Conectarse();
    $calidad = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT cal_descripcion FROM rev_calidad WHERE cal_id = {$_POST['cal_id']}"))['cal_descripcion'];

    echo json_encode($calidad);
    exit;
}
?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <form id="form_receta_act">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRecetaLabel">Actualizar Receta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" value="<?= $_POST['id_receta'] ?>" id="id_receta_act" class="d-none" name="id_receta">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="cte_id" class="form-label">Cliente</label>
                            <input name="cte_id" id="cte_id_act" class="form-control d-none" required readonly />
                            <input name="" id="cte_nombre" class="form-control" required readonly />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="rre_descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" name="rre_descripcion" id="rre_descripcion_act" required maxlength="30">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row align-items-center mb-3">
                    <div class="col-md-6">
                        <h5 class="mb-0">Detalle</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="button" id="agregarParametro_act" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Agregar Parametro
                        </button>
                    </div>
                </div>

                <div id="tarimas_act">
                </div>
            </div>
        </form>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-receta-act" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_receta_act" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        cargarTarimasGuardadas();

        $(document).on('click', '.eliminar-tarima', function() {
            const tarimaRow = $(this).closest('.tarima-add, .tarima-readonly');
            tarimaRow.remove();
        });


        $('#agregarParametro_act').click(function() {
            const fieldHTML = generarCampoTarima();
            $('#tarimas_act').append(fieldHTML);
        });

        $('#form_receta_act').submit(function(e) {
            e.preventDefault();

            let dataForm = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'catalogos/recetas_actualizar.php',
                data: dataForm,
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        alertas_v5("#alerta-receta-act", 'Listo!', res.success, 1, true, 5000);
                        $('#dataTableRecetas').DataTable().ajax.reload();
                        $('#tarimas_act').empty();
                        cargarTarimasGuardadas();
                    } else {
                        alertas_v5("#alerta-receta-act", 'Error!', res.error, 3, true, 5000);
                        console.log(res.error);
                    }
                },
            });
        });
    });
    //const selectedParametersByTarima = {};

    /* function cargarParametros(selectElement, tarimaNo, selectedParamId = null, callback = null) {
        $.ajax({
            type: 'GET',
            url: 'catalogos/parametros_listado.php',
            success: function(data) {
                let parametros = JSON.parse(data);

                // Filtrar parámetros ya seleccionados para esta tarima
                const filteredOptions = parametros
                    .filter(param =>
                        !selectedParametersByTarima[tarimaNo]?.includes(param.rp_id) || param.rp_id === selectedParamId
                    )
                    .map(param =>
                        `<option value="${param.rp_id}" data-tipo="${param.rp_tipo}" data-campo="${param.rp_campo}" 
                        ${param.rp_id == selectedParamId ? 'selected' : ''}>
                        ${param.rp_parametro}
                    </option>`
                    );

                // Generar opciones HTML
                let options = `<option value="">Seleccione</option>` + filteredOptions.join('');
                selectElement.empty();
                selectElement.html(options);

                // Ejecutar callback si está definido
                if (callback) callback(parametros);
            },
            error: function() {
                alert('Error al cargar los parámetros.');
            },
        });
    } */


    function generarCampoTarima() {
        const fieldHTML = `
        <div class="row mb-3 tarima-add">
            <div class="col-md-3">
                <label for="" class="form-label">No. Tarima</label>
                <input type="text" name="rrd_no_tarima[]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label">Parametro</label>
                <select name="rp_id[]" class="form-select" required>
                    <option>Ingresa el número de tarima</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label">Comparación</label>
                <div class="dynamic-field-signo"></div>
            </div>
            <div class="col-md-2">
                <label for="" class="form-label">Valor</label>
                <div class="dynamic-field"></div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm eliminar-tarima">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
    `;

        const container = $(fieldHTML);

        const selectElement = container.find('select[name="rp_id[]"]');
        const tarimaInput = container.find('input[name="rrd_no_tarima[]"]');
        const dynamicField = container.find('.dynamic-field');
        const dynamicFieldSigno = container.find('.dynamic-field-signo');

        tarimaInput.change(function() {
            const tarimaNo = $(this).val();
            cargarParametros(selectElement, tarimaNo);
        });

        selectElement.change(function() {
            const selectedOption = $(this).find(':selected');
            const tipo = selectedOption.data('tipo');
            const campo = selectedOption.data('campo');

            dynamicField.empty();
            dynamicFieldSigno.empty();

            if (tipo === 'F') {
                dynamicField.append(`<input type="text" name="rp_valor[]" class="form-control" required>`);
                dynamicFieldSigno.append(`
                <select name="rrd_signo[]" class="form-select" required>
                    <option value="=">Igual (=)</option>
                    <option value="!=">Diferente (!=)</option>
                    <option value=">">Mayor (&gt;)</option>
                    <option value="<">Menor (&lt;)</option>
                    <option value=">=">Mayor o igual (&gt;=)</option>
                    <option value="<=">Menor o igual (&lt;=)</option>
                </select>
            `);
            } else if (tipo === 'C') {
                const valor = campo === 'tar_fino' ? 'F' : 'R';
                dynamicField.append(`
                <select name="rp_valor[]" class="form-select" required>
                    <option value="${valor}">Sí</option>
                    <option value="${valor === 'F' ? 'R' : 'F'}">No</option>
                </select>
            `);
                dynamicFieldSigno.append('<input type="text" name="rrd_signo[]" class="form-control" required value="=" readonly>');
            } else if (tipo === 'I') {
                const selectDinamic = $('<select name="rp_valor[]" class="form-select" required></select>');
                dynamicField.append(selectDinamic);
                dynamicFieldSigno.append('<input type="text" name="rrd_signo[]" class="form-control" required value="=" readonly>');

                $.ajax({
                    type: 'GET',
                    url: 'catalogos/calidades_listado.php',
                    success: function(data) {
                        const calidades = JSON.parse(data);
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



    // Función para cargar tarimas guardadas y mostrarlas como readonly
    async function cargarTarimasGuardadas() {
        const recetaId = $('#id_receta_act').val();
        console.log(recetaId);

        try {
            const data = await $.ajax({
                type: 'POST',
                url: 'catalogos/recetas_detalle_listado.php',
                data: {
                    id_receta: recetaId
                },
            });

            let tarimas = JSON.parse(data);
            $('#cte_id_act').val(tarimas[0].ID_Cliente);
            $('#cte_nombre').val(tarimas[0].Cliente);
            $('#rre_descripcion_act').val(tarimas[0].Descripcion_Receta);

            for (const tarima of tarimas) {
                const fieldHTML = await generarCampoTarimaGuardada(tarima);
                $('#tarimas_act').append(fieldHTML);
            }
        } catch (error) {
            console.error('Error al cargar las tarimas guardadas:', error);
        }
    }

    async function validarTarimaValor(tarima) {
        if (tarima.Parametro === 'tar_fino') {
            tarima.Nombre_valor = (tarima.Valor === 'F') ? 'Sí' : 'No';
        } else if (tarima.Parametro === 'cal_id') {
            try {
                tarima.Nombre_valor = await obtener_calidad(tarima.Valor);
            } catch (error) {
                console.error('Error al obtener la calidad:', error);
                tarima.Nombre_valor = 'Error al obtener la calidad';
            }
        } else if (tarima.Parametro === 'tar_rechazado') {
            tarima.Nombre_valor = (tarima.Valor === 'R') ? 'Sí' : 'No';
        } else {
            tarima.Nombre_valor = tarima.Valor;
        }

        return tarima;
    }

    async function generarCampoTarimaGuardada(datosGuardados = null) {
        const fieldHTML = `
        <div class="row mb-3 tarima-add">
            <div class="col-md-3">
                <label for="" class="form-label">No. Tarima</label>
                <input type="text" name="rrd_no_tarima[]" value="${datosGuardados ? datosGuardados.No_Tarima : ''}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label">Parametro</label>
                <select name="rp_id[]" class="form-select" required>
                    <option>Ingresa el número de tarima</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label">Comparación</label>
                <div class="dynamic-field-signo"></div>
            </div>
            <div class="col-md-2">
                <label for="" class="form-label">Valor</label>
                <div class="dynamic-field"></div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm eliminar-tarima">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
    `;

        const container = $(fieldHTML);

        const selectElement = container.find('select[name="rp_id[]"]');
        const tarimaInput = container.find('input[name="rrd_no_tarima[]"]');
        const dynamicField = container.find('.dynamic-field');
        const dynamicFieldSigno = container.find('.dynamic-field-signo');

        if (datosGuardados) {
            cargarParametros(selectElement, datosGuardados.No_Tarima, datosGuardados.ID_Parametro).then(() => {
                selectElement.trigger('change'); // Forzar evento change si hay datos guardados
            });
        }

        tarimaInput.change(function() {
            const tarimaNo = $(this).val();
            cargarParametros(selectElement, tarimaNo).then(() => {
                selectElement.trigger('change'); // Forzar evento change después de cargar opciones
            });
        });

        selectElement.on('change', function() {
            const selectedOption = $(this).find(':selected');
            const id = selectedOption.val();
            const tipo = selectedOption.data('tipo');
            const campo = selectedOption.data('campo');

            dynamicField.empty();
            dynamicFieldSigno.empty();

            if (tipo === 'F') {
                dynamicField.append(`
                <input type="text" name="rp_valor[]" class="form-control" required value="${datosGuardados?.Valor || ''}">
            `);
                dynamicFieldSigno.append(`
                <select name="rrd_signo[]" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="=" ${datosGuardados?.Comparacion === '=' ? 'selected' : ''}>Igual (=)</option>
                    <option value="!=" ${datosGuardados?.Comparacion === '!=' ? 'selected' : ''}>Diferente (!=)</option>
                    <option value=">" ${datosGuardados?.Comparacion === '>' ? 'selected' : ''}>Mayor (&gt;)</option>
                    <option value="<" ${datosGuardados?.Comparacion === '<' ? 'selected' : ''}>Menor (&lt;)</option>
                    <option value=">="${datosGuardados?.Comparacion === '>=' ? 'selected' : ''}>Mayor o igual (&gt;=)</option>
                    <option value="<="${datosGuardados?.Comparacion === '<=' ? 'selected' : ''}>Menor o igual (&lt;=)</option>
                </select>
            `);
            } else if (tipo === 'C') {
                const si = campo === 'tar_fino' ? 'F' : 'C';
                const no = campo === 'tar_fino' ? 'N' : 'A';
                dynamicField.append(`
                <select name="rp_valor[]" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="${si}" ${datosGuardados?.Valor === si ? 'selected' : ''}>Sí</option>
                    <option value="${no}" ${datosGuardados?.Valor === no ? 'selected' : ''}>No</option>
                </select>
            `);
                dynamicFieldSigno.append('<input type="text" name="rrd_signo[]" class="form-control" required value="=" readonly>');

            } else if (tipo === 'I') {
                const selectDinamic = $('<select name="rp_valor[]" class="form-select" required></select>');
                dynamicField.append(selectDinamic);
                dynamicFieldSigno.append('<input type="text" name="rrd_signo[]" class="form-control" required value="=" readonly>');

                $.ajax({
                    type: 'GET',
                    url: 'catalogos/calidades_listado.php',
                    success: function(data) {
                        try {
                            // Parsear la respuesta JSON
                            let calidades = JSON.parse(data);
                            let options = '<option value="">Seleccione</option>';

                            // Agregar opciones al select dinámico
                            calidades.forEach(function(c) {
                                options += `<option value="${c.cal_id}" ${(datosGuardados.Valor == c.cal_id) ? 'selected' : ''}>${c.cal_descripcion}</option>`;
                            });

                            // Agregar las opciones al selectDinamic
                            selectDinamic.append(options);
                        } catch (error) {
                            console.error('Error al procesar los datos de calidades:', error);
                            alert('Error al cargar los valores de calidades.');
                        }
                    },
                    error: function() {
                        console.error('Error al realizar la solicitud AJAX');
                        alert('Error al cargar los valores de calidades.');
                    },
                });
            }
        });

        return container;
    }

    function cargarParametros(selectElement, tarimaNo = null, parametroSeleccionado = null) {
        return new Promise((resolve) => {
            $.ajax({
                type: 'GET',
                url: `catalogos/parametros_listado.php?tarimaNo=${tarimaNo || ''}`,
                success: function(data) {
                    selectElement.empty(); // Limpia el select completamente
                    selectElement.append('<option value="">Seleccione un parámetro</option>'); // Opción por defecto

                    const parametros = JSON.parse(data);
                    parametros.forEach((param) => {
                        selectElement.append(`
                        <option value="${param.rp_id}" data-tipo="${param.rp_tipo}" data-campo="${param.rp_campo}" ${parametroSeleccionado === param.rp_id ? 'selected' : ''}>
                            ${param.rp_parametro}
                        </option>
                    `);
                    });

                    resolve(); // Resuelve la promesa cuando se hayan cargado las opciones
                },
                error: function() {
                    alert('Error al cargar parámetros.');
                    selectElement.empty(); // Asegurarse de que el select esté vacío si ocurre un error
                    selectElement.append('<option value="">Error al cargar parámetros</option>');
                    resolve(); // Resuelve incluso en caso de error
                },
            });
        });
    }


    function obtener_calidad(cal_id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: 'catalogos/recetas_actualizar_modal.php',
                data: {
                    action: 'obtener_calidad',
                    cal_id: cal_id
                },
                success: function(response) {
                    try {
                        let calidad = JSON.parse(response);
                        resolve(calidad); // Devuelve la calidad obtenida
                    } catch (error) {
                        reject('Error al parsear la respuesta del servidor.');
                    }
                },
                error: function() {
                    reject('Error al obtener la calidad desde el servidor.');
                }
            });
        });
    }
</script>