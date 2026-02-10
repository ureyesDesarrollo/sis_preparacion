<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
?>

<style>
    .inputs-dinamicos .row {
        margin-bottom: 15px;
        /* Espacio entre filas de inputs */
    }
</style>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Crear Filtro</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_revolturas_filtro" method="POST" action="filtrar.php">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="checkbox" class="btn-check" id="bloom" name="parametros[]" value="bloom">
                            <label class="btn btn-outline-primary d-block mb-2" for="bloom">Bloom</label>

                            <input type="checkbox" class="btn-check" id="viscosidad" name="parametros[]" value="viscosidad">
                            <label class="btn btn-outline-primary d-block mb-2" for="viscosidad">Viscosidad</label>

                            <input type="checkbox" class="btn-check" id="ph" name="parametros[]" value="ph">
                            <label class="btn btn-outline-primary d-block mb-2" for="ph">pH</label>

                            <input type="checkbox" class="btn-check" id="trans" name="parametros[]" value="trans">
                            <label class="btn btn-outline-primary d-block mb-2" for="trans">Trans</label>

                            <input type="checkbox" class="btn-check" id="malla_30" name="parametros[]" value="malla_30">
                            <label class="btn btn-outline-primary d-block mb-2" for="malla_30">Malla 30</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" class="btn-check" id="porcentaje_t" name="parametros[]" value="porcentaje_t">
                            <label class="btn btn-outline-primary d-block mb-2" for="porcentaje_t">%T</label>

                            <input type="checkbox" class="btn-check" id="ntu" name="parametros[]" value="ntu">
                            <label class="btn btn-outline-primary d-block mb-2" for="ntu">NTU</label>

                            <input type="checkbox" class="btn-check" id="humedad" name="parametros[]" value="humedad">
                            <label class="btn btn-outline-primary d-block mb-2" for="humedad">Humedad</label>

                            <input type="checkbox" class="btn-check" id="cenizas" name="parametros[]" value="cenizas">
                            <label class="btn btn-outline-primary d-block mb-2" for="cenizas">Cenizas</label>

                            <input type="checkbox" class="btn-check" id="malla_45" name="parametros[]" value="malla_45">
                            <label class="btn btn-outline-primary d-block mb-2" for="malla_45">Malla 45</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" class="btn-check" id="ce" name="parametros[]" value="ce">
                            <label class="btn btn-outline-primary d-block mb-2" for="ce">Conductividad</label>

                            <input type="checkbox" class="btn-check" id="redox" name="parametros[]" value="redox">
                            <label class="btn btn-outline-primary d-block mb-2" for="redox">Redox</label>

                            <input type="checkbox" class="btn-check" id="color" name="parametros[]" value="color">
                            <label class="btn btn-outline-primary d-block mb-2" for="color">Color</label>

                            <input type="checkbox" class="btn-check" id="olor" name="parametros[]" value="olor">
                            <label class="btn btn-outline-primary d-block mb-2" for="olor">Olor</label>

                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" class="btn-check" id="pe_1kg" name="parametros[]" value="pe_1kg">
                            <label class="btn btn-outline-primary d-block mb-2" for="pe_1kg">PE 1kg</label>

                            <input type="checkbox" class="btn-check" id="par_extr" name="parametros[]" value="par_extr">
                            <label class="btn btn-outline-primary d-block mb-2" for="par_extr">Par Extr</label>

                            <input type="checkbox" class="btn-check" id="par_ind" name="parametros[]" value="par_ind">
                            <label class="btn btn-outline-primary d-block mb-2" for="par_ind">Par Ind</label>

                            <input type="checkbox" class="btn-check" id="hidratacion" name="parametros[]" value="hidratacion">
                            <label class="btn btn-outline-primary d-block mb-2" for="hidratacion">Hidratación</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="orden_direccion" class="form-label">Ordenar</label>
                        <select name="orden_direccion" id="" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="ASC">Menor a mayor</option>
                            <option value="DESC">Mayor a menor</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="calidad" class="form-label">Calidad</label>
                        <select name="calidad" id="calidad" class="form-select">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tipo" class="form-label">Tipo de tarima</label>
                        <select name="tipo" id="" class="form-select">
                            <option value="">Seleccionar tipo</option>
                            <option value="F">Fino</option>
                            <option value="N">Normal</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="ordenColumna">
                        <label for="orden_columna" class="form-label">Parametro a tomar en cuenta para ordenar</label>
                        <select name="orden_columna" id="select_param" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="tar_bloom">Bloom</option>
                            <option value="tar_viscosidad">Viscosidad</option>
                            <option value="tar_ph">pH</option>
                            <option value="tar_trans">Trans</option>
                            <option value="tar_malla_30">Malla 30</option>
                            <option value="tar_porcentaje_t">%T</option>
                            <option value="tar_ntu">NTU</option>
                            <option value="tar_humedad">Humedad</option>
                            <option value="tar_cenizas">Cenizas</option>
                            <option value="tar_malla_45">Malla 45</option>
                            <option value="tar_ce">Conductividad</option>
                            <option value="tar_redox">Redox</option>
                            <option value="tar_color">Color</option>
                            <option value="tar_olor">Olor</option>
                            <option value="tar_pe_1kg">PE 1KG</option>
                            <option value="tar_par_extr">Par Extr</option>
                            <option value="tar_par_ind">Par Ind</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row inputs-dinamicos" id="inputs-dinamicos">
                            <!-- Inputs generados dinámicamente aparecerán aquí -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row w-100 align-items-center">
                        <div class="col-md-8 mb-3">
                            <div id="alerta-revoltura-filtro" class="alert alert-success m-0 d-none">
                                <strong class="alert-heading"></strong>
                                <span class="alert-body"></span>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <img src="../iconos/close.png" alt=""> Cerrar
                            </button>
                            <button type="submit" class="btn btn-primary ms-2">
                                <i class="fa-solid fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        cargarCalidades();
        $('#ordenColumna').hide();
        // Manejador de eventos para cambios en las casillas de verificación
        $('input[type="checkbox"]').on('change', function() {
            // Selecciona el contenedor donde se agregarán o eliminarán los inputs dinámicos
            let dynamicInputsContainer = $('#inputs-dinamicos');
            // Verifica si la casilla de verificación está marcada
            if ($(this).is(':checked')) {
                // Crea una etiqueta <label> para el campo de valor mínimo
                let minLabel = $('<label>', {
                    for: $(this).val() + '_min', // Asocia la etiqueta con el id del campo de entrada
                    text: $(this).next('label').text() + ' - Valor Mínimo', // Texto para la etiqueta
                    class: 'form-label' // Clase de Bootstrap para etiquetas
                });

                // Crea un campo de entrada <input> para el valor mínimo
                let minInput = $('<input>', {
                    type: 'text', // Tipo de campo de entrada
                    id: $(this).val() + '_min', // Asigna el id a partir del valor de la casilla de verificación
                    name: $(this).val() + '_min', // Asigna el nombre a partir del valor de la casilla de verificación
                    class: 'form-control mb-2', // Clase de Bootstrap para estilos de entrada
                    onkeypress: "return isNumberKey(event, this);" // Llama a una función personalizada para permitir solo números
                });

                // Crea una etiqueta <label> para el campo de valor máximo
                let maxLabel = $('<label>', {
                    for: $(this).val() + '_max', // Asocia la etiqueta con el id del campo de entrada
                    text: $(this).next('label').text() + ' - Valor Máximo', // Texto para la etiqueta
                    class: 'form-label' // Clase de Bootstrap para etiquetas
                });

                // Crea un campo de entrada <input> para el valor máximo
                let maxInput = $('<input>', {
                    type: 'text', // Tipo de campo de entrada
                    id: $(this).val() + '_max', // Asigna el id a partir del valor de la casilla de verificación
                    name: $(this).val() + '_max', // Asigna el nombre a partir del valor de la casilla de verificación
                    class: 'form-control mb-2', // Clase de Bootstrap para estilos de entrada
                    onkeypress: "return isNumberKey(event, this);" // Llama a una función personalizada para permitir solo números
                });

                // Crea un grupo de entrada que contiene las etiquetas y los campos de entrada
                let inputGroup = $('<div>', {
                    class: 'col-md-4 mb-2' // Clase de Bootstrap para colocar en columnas de 4 espacios en pantallas medianas
                }).append(
                    $('<div>', {
                        class: 'form-group' // Clase para agrupar etiquetas y campos de entrada
                    }).append(minLabel, minInput, maxLabel, maxInput) // Agrega las etiquetas y los campos de entrada al grupo
                );

                // Agrega el nuevo grupo de entrada al contenedor
                dynamicInputsContainer.append(inputGroup);
                if ($('#inputs-dinamicos .col-md-4').length > 1) {
                    $('#ordenColumna').show();
                    $('#ordenColumna').attr('required', true);
                }
            } else {
                // Elimina el grupo de entrada correspondiente si la casilla de verificación se desmarca
                $('#inputs-dinamicos').find('#' + $(this).val() + '_min').closest('.col-md-4').remove();
                if ($('#inputs-dinamicos .col-md-4').length === 0) {
                    $('#ordenColumna').hide();
                    $('#ordenColumna').removeAttr('required');
                }
            }
        });

        $('#form_revolturas_filtro').on('submit', function(e) {
            e.preventDefault();
            let ordenDireccion = $('select[name="orden_direccion"]').val();
            let ordenColumna = $('select[name="orden_columna"]').val();
            let calidad = $('select[name="calidad"]').val();
            let tipo = $('select[name="tipo"]').val();
            let parametros = [];
            let valores = {};

            // Recoger los parámetros seleccionados
            $('input[name="parametros[]"]:checked').each(function() {
                parametros.push($(this).val());
            });

            // Recoger los valores de los inputs dinámicos
            $('#inputs-dinamicos input').each(function() {
                let id = $(this).attr('id');
                let valor = $(this).val();
                valores[id] = valor;
            });

            // Cambiar la columna de ordenación si hay un solo parámetro
            if (parametros.length == 1) {
                ordenColumna = `tar_${parametros[0]}`;
            }

            // Realizar la solicitud AJAX
            $.ajax({
                url: 'funciones/revolturas_filtrar.php',
                type: 'POST',
                data: {
                    'parametros': parametros,
                    'valores': valores,
                    'orden_direccion': ordenDireccion,
                    'orden_columna': ordenColumna,
                    'calidad': calidad,
                    'tipo': tipo
                },
                success: function(response) {
                    // Intentar parsear la respuesta JSON
                    try {
                        var data = JSON.parse(response); // Convertir la respuesta en objeto JSON
                    } catch (error) {
                        console.error('Error al parsear la respuesta:', error);
                        alert('Error al procesar la respuesta del servidor');
                        return;
                    }

                    // Verificar el estado de la respuesta
                    if (data.status === 'error') {
                        alert(data.mensaje); // Mostrar el mensaje de error si es necesario
                    } else {
                        // Crear un formulario oculto y enviarlo si no hay errores
                        let form = $('<form action="funciones/revolturas_orden_sugerido.php" method="POST">' +
                            '<input type="hidden" name="data" value=\'' + JSON.stringify(data) + '\' />' +
                            '</form>');
                        $('body').append(form);
                        form.submit(); // Enviar el formulario
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error); // Manejar cualquier error de la solicitud AJAX
                    alert('Hubo un error en la solicitud.');
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
                $('#calidad').append(options);
            },
            error: function() {
                alert('Error al cargar las calidades.');
            }
        });
    }
</script>