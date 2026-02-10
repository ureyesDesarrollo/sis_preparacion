<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../funciones/funciones.php";
?>

<div class="container-fluid">
    <div class="row mb-3 mt-3">
        <div class="col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Rastreabilidad</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <input type="radio" class="btn-check" name="rastreabilidad" id="revoltura" autocomplete="off" checked>
            <label class="btn btn-outline-primary" for="revoltura">Revoltura</label>

            <input type="radio" class="btn-check" name="rastreabilidad" id="factura" autocomplete="off">
            <label class="btn btn-outline-primary" for="factura">Factura</label>

            <input type="radio" class="btn-check" name="rastreabilidad" id="tarima" autocomplete="off">
            <label class="btn btn-outline-primary" for="tarima">Tarima</label>
        </div>
    </div>
    <form id="form_rastreabilidad" action="funciones/revolturas_detalle.php" method="POST" target="_blank">
        <div class="row mt-4">
            <div class="col-md-2">
                <div class="inputs-dinamicos" id="inputs-dinamicos">
                    <!-- Inputs generados dinámicamente aparecerán aquí -->
                </div>
            </div>
        </div>
    </form>
    <div class="row mt-3">
        <div class="col-md-2">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="buscarBtn">Buscar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('input[name="rastreabilidad"]').on('change', function() {
            let dynamicInputsContainer = $('#inputs-dinamicos');
            dynamicInputsContainer.empty();

            if ($(this).is(':checked')) {
                let selectedOption = $(this).attr('id');

                if (selectedOption === 'tarima') {
                    // Input para el proceso
                    let labelProceso = $('<label>', {
                        for: 'txt_proceso',
                        text: 'Proceso',
                        class: 'form-label'
                    });

                    let inputProceso = $('<input>', {
                        type: 'text',
                        name: 'txt_filtro_proceso',
                        class: 'form-control',
                        id: 'txt_proceso',
                        required: true,
                        keypress: function(event) {
                            let charCode = event.which ? event.which : event.keyCode;
                            if (charCode < 48 || charCode > 57) {
                                event.preventDefault();
                            }
                        }
                    });

                    // Input para la tarima
                    let labelTarima = $('<label>', {
                        for: 'txt_tarima',
                        text: 'Folio Tarima',
                        class: 'form-label mt-2'
                    });

                    let inputTarima = $('<input>', {
                        type: 'text',
                        name: 'txt_filtro_tarima',
                        class: 'form-control',
                        id: 'txt_tarima',
                        required: true,
                        maxLength: 4,
                        minLength: 4,
                        keypress: function(event) {
                            let charCode = event.which ? event.which : event.keyCode;
                            if (charCode < 48 || charCode > 57) {
                                event.preventDefault();
                            }
                        }
                    });

                    dynamicInputsContainer.append(labelProceso).append(inputProceso);
                    dynamicInputsContainer.append(labelTarima).append(inputTarima);

                } else {
                    let label = $('<label>', {
                        for: selectedOption,
                        text: $(this).next('label').text(),
                        class: 'form-label'
                    });

                    let input = $('<input>', {
                        type: 'text',
                        name: 'txt_filtro_' + $(this).next('label').text(),
                        class: 'form-control',
                        id: 'txt_filtro',
                        required: true
                    });

                    dynamicInputsContainer.append(label).append(input);
                }
            }
        });

        // Cambiar la URL y abrir en nueva pestaña al hacer clic en el botón
        $('#buscarBtn').on('click', function() {
            let selectedOption = $('input[name="rastreabilidad"]:checked').attr('id');
            let form = $('#form_rastreabilidad');
            
            if (selectedOption === 'factura') {
                form.attr('action', 'funciones/facturas_empacado_detalle.php');
            } else {
                form.attr('action', 'funciones/revolturas_detalle.php');
            }

            form.submit(); // Enviar el formulario a la URL especificada en `action`
        });

        // Disparar el evento change para la opción seleccionada por defecto
        $('input[name="rastreabilidad"]:checked').trigger('change');
    });
</script>
