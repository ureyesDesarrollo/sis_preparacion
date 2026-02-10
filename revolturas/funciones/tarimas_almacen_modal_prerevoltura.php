<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
?>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Pre revoltura</h5>
            <!-- Botón de cerrar -->
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label">Cliente - Descripción receta</label>
                    <select class="form-select" id="recetas" name="rre_id">
                        <!-- Opciones dinámicas -->
                    </select>
                </div>
                <div class="col-2 d-flex align-items-end">
                    <!-- Botón de guardar -->
                    <button type="submit" class="btn btn-primary w-100" id="guardarModalPrerevoltura" form="form-prerevoltura">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>

            <hr>
            <form id="form-prerevoltura">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tabla-detalle-receta" class="table table-bordered d-none">
                            <thead>
                                <tr>
                                    <th>No. Tarima</th>
                                    <!-- Las columnas de parámetros se generarán dinámicamente -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Las filas con parámetros se llenarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <div class="col-md-12">
                <table id="tabla-detalle-tarimas" class="table table-bordered d-none">
                    <thead>
                        <tr>
                            <th>Tarima</th>
                            <th>Bloom</th>
                            <th>Viscocidad</th>
                            <th>Tran</th>
                            <th>Malla 45</th>
                            <th>Calidad</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        let detalle_receta = [];
        let tarimasSeleccionadas = [];
        obtenerRecetas();

        $('#recetas').on('change', async function() {
            $('#tabla-detalle-receta').removeClass('d-none');
            const receta_id = $(this).val()
            detalle_receta = await obtenerDetalleReceta(receta_id);
            generarTablaDetalleReceta(detalle_receta);
        });


        $('#form-prerevoltura').submit(function(e) {
            e.preventDefault();
            const dataForm = $(this).serialize();
            const tarimas = $('.tarima-select').map((_, el) => $(el).val()).get();
            if (tarimas.length < 5) {
                return Swal.fire({
                    title: "Seleccionar al menos 5 tarimas",
                    icon: "warning"
                });
            }
            crear_prerevoltura(dataForm);

        });


        $(document).on('change', '.tarima-select', function() {
            let seleccionados = [];

            // Recorre todos los selects y guarda las opciones seleccionadas
            $('.tarima-select').each(function() {
                let valorSeleccionado = $(this).val();
                if (valorSeleccionado) {
                    seleccionados.push(valorSeleccionado);
                }
                $('#tabla-detalle-tarimas').removeClass('d-none');
            });

            generarTablaTarimasSeleccionadas(seleccionados);

            // Recorre los selects y deshabilita opciones repetidas
            $('.tarima-select').each(function() {
                let $select = $(this);
                let valorActual = $select.val();

                $select.find('option').each(function() {
                    let opcionValor = $(this).val();

                    if (opcionValor && opcionValor !== valorActual) {
                        $(this).prop('hidden', seleccionados.includes(opcionValor));
                    }
                });
            });
        });

        $('#modal_prerevoltura').on('hidden.bs.modal', function() {
            // Limpiar el contenido del modal
            $('#tabla-detalle-tarimas tbody').empty();
            $('#tabla-detalle-tarimas tfoot').empty();
            $('#recetas').val(''); // Reiniciar el selector de recetas
            $('#tabla-detalle-receta').addClass('d-none'); // Ocultar la tabla de detalle

            // Reiniciar variables globales
            tarimasSeleccionadas = [];
            detalle_receta = [];

            // Eliminar eventos duplicados (opcional, si es necesario)
            $(document).off('change', '.tarima-select');
        });

        $('#recetas').on('change',() => {
            $('#tabla-detalle-tarimas').addClass('d-none');
            $('#tabla-detalle-tarimas tbody').empty();
            $('#tabla-detalle-tarimas tfoot').empty();
        });

    });


    function obtenerRecetas() {
        $.ajax({
            type: 'GET',
            url: 'catalogos/recetas_listado.php',
            success: function(response) {
                const recetas = JSON.parse(response);
                let options = '<option value="">Seleccione</option>';
                recetas.forEach(function(receta) {
                    if (receta.rre_estatus === 'A') {
                        options += `<option value='${receta.rre_id}'>${receta.cte_nombre} - ${receta.rre_descripcion}</option>`
                    }
                });
                $('#recetas').empty().append(options);
            }
        });
    }


    function obtenerDetalleReceta(rre_id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: 'catalogos/recetas_detalle_listado.php',
                data: {
                    'id_receta': rre_id
                },
                success: function(response) {
                    try {
                        let detalle = JSON.parse(response);
                        resolve(detalle);
                    } catch (error) {
                        reject('Error al parsear la respuesta del servidor');
                    }
                },
                error: function() {
                    reject('Error al obtener el detalle');
                }
            });
        });
    }


    function agruparPorTarima(data) {
        // Utilizamos el método reduce para iterar sobre el arreglo `data` y construir un objeto agrupado.
        return data.reduce((agrupado, item) => {
            // Verificamos si el número de tarima (`No_Tarima`) ya existe como clave en el objeto `agrupado`.
            if (!agrupado[item.No_Tarima]) {
                // Si no existe, inicializamos esa clave con un arreglo vacío.
                agrupado[item.No_Tarima] = [];
            }
            // Agregamos el elemento actual (`item`) al arreglo correspondiente a la tarima.
            agrupado[item.No_Tarima].push(item);

            // Devolvemos el objeto `agrupado` actualizado después de procesar cada elemento.
            return agrupado;
        }, {}); // Inicializamos `agrupado` como un objeto vacío al inicio.
    }

    async function generarTablaDetalleReceta(detalleReceta) {
        const $tabla = $('#tabla-detalle-receta');
        const $thead = $tabla.find('thead tr');
        const $tbody = $tabla.find('tbody');

        // Limpiar contenido previo
        limpiarTabla($thead, $tbody);

        // Agrupar datos por número de tarima
        const tarimasAgrupadas = agruparPorTarima(detalleReceta);

        // Determinar el máximo número de parámetros por tarima
        const maxParametros = determinarMaxParametros(tarimasAgrupadas);

        // Generar encabezados dinámicos
        generarEncabezados($thead, maxParametros);

        // Generar filas dinámicas
        const filas = await generarFilas(tarimasAgrupadas, maxParametros);

        // Insertar las filas generadas en el cuerpo de la tabla
        $tbody.append(filas.join(''));
    }

    /**
     * Limpia el contenido previo de la tabla.
     */
    function limpiarTabla($thead, $tbody) {
        $thead.find('th').not(':first').remove();
        $tbody.empty();
    }

    /**
     * Determina el máximo número de parámetros entre las tarimas agrupadas.
     */
    function determinarMaxParametros(tarimasAgrupadas) {
        return Math.max(...Object.values(tarimasAgrupadas).map(t => t.length));
    }

    /**
     * Genera los encabezados dinámicos para la tabla.
     */
    function generarEncabezados($thead, maxParametros) {
        for (let i = 1; i <= maxParametros; i++) {
            $thead.append(`<th>Parametro ${i}</th>`);
        }

        // Encabezado para la tarima
        $thead.append(`<th>Tarima</th>`);
    }

    /**
     * Genera las filas dinámicas de la tabla.
     */
    async function generarFilas(tarimasAgrupadas, maxParametros) {
        const filas = [];

        for (const [tarima, parametros] of Object.entries(tarimasAgrupadas)) {
            let fila = `<tr><td>${tarima}</td>`;

            // Procesar parámetros para las celdas
            const parametrosProcesados = await Promise.all(
                parametros.map(async param => {
                    return generarCeldaParametro(param);
                })
            );

            fila += parametrosProcesados.join('');

            // Rellenar con celdas vacías si faltan parámetros
            const celdasVacias = maxParametros - parametros.length;
            fila += generarCeldasVacias(celdasVacias);

            // Generar el select para la tarima
            const parametrosSinProcesar = parametros.map(param => ({
                ...param
            }));
            const selectHTML = await generarSelectTarima(tarima, parametrosSinProcesar);
            fila += `<td>${selectHTML}</td>`;

            fila += `</tr>`;
            filas.push(fila);
        }

        return filas;
    }

    /**
     * Genera una celda con el parámetro procesado.
     */
    async function generarCeldaParametro(param) {
        const paramProcesado = {
            ...param
        };
        paramProcesado.Nombre_valor = await validarParamValor(paramProcesado);

        return `<td>${paramProcesado.Nombre_parametro} ${paramProcesado.Comparacion} ${paramProcesado.Nombre_valor}</td>`;
    }

    /**
     * Genera celdas vacías para completar una fila.
     */
    function generarCeldasVacias(cantidad) {
        return '<td></td>'.repeat(cantidad);
    }

    /**
     * Genera el select de tarimas para una fila.
     */
    async function generarSelectTarima(tarima, parametros) {
        const opciones = await obtenerTarimasSeleccionadas(tarima, parametros);
        if (opciones.length < 5) return `<span>No hay tarimas para esta condición</span>`;

        return `
        <select class="form-select tarima-select" data-tarima="${tarima}" name="tar_id[]" required>
            <option value="">Seleccionar tarima</option>
            ${opciones.map(op => `<option value="${op.tar_id}">P${pro_id = op.pro_id_2 ? `${op.pro_id}/${op.pro_id_2}` : op.pro_id}T${op.tar_folio} - ${op.tar_fecha} - ${op.tar_kilos} kg </option>`).join('')}
        </select>
    `;
    }

    async function validarParamValor(param) {
        try {
            let valorProcesado;

            switch (param.Parametro) {
                case 'tar_fino':
                    // Para 'tar_fino', asignar 'Sí' o 'No' dependiendo del valor
                    valorProcesado = (param.Valor === 'F') ? 'Sí' : 'No';
                    break;

                case 'cal_id':
                    // Para 'cal_id', obtener el valor asíncrono con la función obtener_calidad
                    valorProcesado = await obtener_calidad(param.Valor);
                    break;

                case 'tar_rechazado':
                    // Para 'tar_rechazado', asignar 'Sí' o 'No' dependiendo del valor
                    valorProcesado = (param.Valor === 'C') ? 'Sí' : 'No';
                    break;

                default:
                    // En otros casos, mantener el valor original
                    valorProcesado = param.Valor;
                    break;
            }

            return valorProcesado;

        } catch (error) {
            console.error(`Error al procesar el parámetro "${param.Parametro}":`, error);
            return 'Error al procesar el valor'; // Retorno predeterminado en caso de error
        }
    }

    //Función para obtener las tarimas que cumplen los parametros
    async function obtenerTarimasSeleccionadas(tarima, params) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: 'funciones/tarimas_almacen_prerevoltura_consulta.php',
                data: {
                    tarima: tarima,
                    parametros: params
                },
                success: function(response) {
                    try {

                        let tarimas = JSON.parse(response);
                        let tarimasFiltradas = tarimas.filter(tarima => tarima.pro_id !== '0');
                        tarimasSeleccionadas = tarimasFiltradas;
                        resolve(tarimasFiltradas);
                    } catch (error) {
                        reject('Error al parsear la respuesta del servidor.');
                    }
                },
                error: function() {
                    reject('Error al obtener las tarimas desde el servidor.');
                }
            })
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


    function abrir_modal_revoltura() {
        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_almacen_modal_crear_revoltura.php',
            success: function(result) {
                // Cerrar el modal de receta
                const modalReceta = bootstrap.Modal.getInstance(document.getElementById('modal_prerevoltura'));
                if (modalReceta) {
                    modalReceta.hide();
                }

                // Cargar el contenido del modal de revoltura y abrirlo
                $('#modal_crear_revolturas').html(result);
                $('#modal_crear_revolturas').modal('show');
            }
        });
    }



    function crear_prerevoltura(data) {
        Swal.fire({
            title: "¿Seguro que deseas utilizar las tarimas seleccionadas?",
            text: '',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'funciones/tarimas_almacen_prerevoltura_tomar_tarimas.php',
                    data: data,
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Las tarimas han sido seleccionadas correctamente. Serás redirigido a la pantalla para la creación de la revoltura.",
                                text: `${res.message}`,
                                icon: "success"
                            });
                            $('#dataTableTarimasAlmacen').DataTable().ajax.reload();
                            abrir_modal_revoltura();
                        } else {
                            Swal.fire({
                                title: "Ocurrio un error!",
                                text: `${res.message}`,
                                icon: "error"
                            });
                        }
                    }
                });
            }
        });
    }

    async function generarTablaTarimasSeleccionadas(tarimas) {
        const $tbody = $('#tabla-detalle-tarimas tbody');
        const $tfoot = $('#tabla-detalle-tarimas tfoot');

        $tbody.empty();
        $tfoot.empty();

        let totalBloom = 0;
        let totalViscosidad = 0;
        let totalTran = 0;
        let totalMalla45 = 0;

        console.log(tarimasSeleccionadas);

        const tarimasFiltradas = tarimasSeleccionadas.filter(tarima => tarimas.includes(tarima.tar_id.toString()));
        tarimasFiltradas.forEach(tarima => {
            totalBloom += parseFloat(tarima.tar_bloom);
            totalViscosidad += parseFloat(tarima.tar_viscosidad);
            totalTran += parseFloat(tarima.tar_trans);
            totalMalla45 += parseFloat(tarima.tar_malla_45);

            let pro_id = tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id;
            $tbody.append(`
                <tr>
                    <td>P${pro_id}T${tarima.tar_folio}</td>
                    <td>${tarima.tar_bloom}</td>
                    <td>${tarima.tar_viscosidad}</td>
                    <td>${tarima.tar_trans}</td>
                    <td>${tarima.tar_malla_45}</td>
                    <td>${tarima.cal_descripcion}</td>
                </tr>
            `);
        });

        let promBloom = totalBloom / tarimasFiltradas.length;
        let promViscosidad = totalViscosidad / tarimasFiltradas.length;
        let promTran = totalTran / tarimasFiltradas.length;
        let promMala45 = totalMalla45 / tarimasFiltradas.length;

        let calidad = await obtenerCalidad(promBloom, promViscosidad);
        $tfoot.append(`
        <tr class="table-success fw-bold">
            <td>Promedio</td>
            <td>${promBloom.toFixed(2)}</td>
            <td>${promViscosidad.toFixed(2)}</td>
            <td>${promTran.toFixed(2)}</td>
            <td>${promMala45.toFixed(2)}</td>
            <td>${calidad.calidad}</td>
        </tr>
    `);
    }

    async function obtenerCalidad(prom_bloom, prom_visc) {
        try {
            const calidad = await determinarCalidad(prom_bloom, prom_visc);
            console.log("Calidad obtenida:", calidad);

            return calidad;
        } catch (error) {
            console.error("Error al determinar la calidad:", error);
            return {
                calidad: "Error"
            };
        }
    }

    function determinarCalidad(prom_bloom, prom_visc) {
        let dataForm = {
            'tar_bloom': prom_bloom,
            'tar_viscosidad': prom_visc
        };

        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: 'funciones/tarimas_determinar_calidad.php',
                data: dataForm,
                success: function(response) {
                    let res = JSON.parse(response);
                    resolve(res); // Resuelve la promesa con el valor de calidad
                },
                error: function(error) {
                    reject(error); // Rechaza la promesa en caso de error
                }
            });
        });
    }
</script>