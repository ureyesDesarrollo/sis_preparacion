<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/

include "../seguridad/user_seguridad.php";
include "../funciones/funciones.php";
include "../conexion/conexion.php";
$cnx = Conectarse();

if (isset($_POST['action']) && $_POST['action'] == 'obtener_inventario') {
    $registros_por_pagina = 10;
    $pagina_actual = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
    $offset = ($pagina_actual - 1) * $registros_por_pagina;

    // Recibir los valores del buscador y las fechas
    $buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';
    $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
    $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';

    // Verificar que ambas fechas estén presentes
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        // Crear consulta SQL con los filtros de búsqueda y fechas
        $sql = "SELECT i.*, m.mat_nombre, m.mat_id, p.prv_nombre, p.prv_tipo, t.mt_descripcion
        FROM inventario i
        LEFT JOIN proveedores AS p ON i.prv_id = p.prv_id
        INNER JOIN materiales AS m ON i.mat_id = m.mat_id
        INNER JOIN materiales_tipo AS t ON m.mt_id = t.mt_id
        WHERE i.inv_fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' 
        AND i.inv_enviado NOT IN (3, 4)";

        // Si hay búsqueda, añadir condición en el SQL
        if (!empty($buscar)) {
            $sql .= " AND (m.mat_nombre LIKE '%$buscar%' 
            OR i.inv_no_ticket LIKE '%$buscar%' 
            OR p.prv_nombre LIKE '%$buscar%' 
            OR t.mt_descripcion LIKE '%$buscar%')";
        }

        $sql .= " LIMIT $registros_por_pagina OFFSET $offset;";

        $resultado = mysqli_query($cnx, $sql);

        // Para calcular el total de registros filtrados
        $sql_total = "SELECT COUNT(*) as total FROM inventario AS i
                      LEFT JOIN proveedores AS p ON (i.prv_id = p.prv_id)
                      INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
                      INNER JOIN materiales_tipo AS t ON (m.mt_id = t.mt_id)
                      WHERE i.inv_fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";

        if (!empty($buscar)) {
            $sql_total .= " AND (m.mat_nombre LIKE '%$buscar%' 
            OR i.inv_no_ticket LIKE '%$buscar%' 
            OR p.prv_nombre LIKE '%$buscar%' 
            OR t.mt_descripcion LIKE '%$buscar%')";
        }

        $resultado_total = mysqli_query($cnx, $sql_total);
        $fila_total = mysqli_fetch_assoc($resultado_total);
        $total_registros = $fila_total['total'];
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        // Verificar si hay resultados
        if (mysqli_num_rows($resultado) > 0) {
            // Generar el HTML para los elementos
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $resultado_costo_mp = mysqli_query($cnx, "SELECT mc_costo FROM materiales_costos WHERE mat_id ='$fila[mat_id]' and mc_year = '" . date("Y") . "' and prv_id = '$fila[prv_id]'");

                if ($resultado_costo_mp && mysqli_num_rows($resultado_costo_mp) > 0) {
                    $costo_mp = mysqli_fetch_assoc($resultado_costo_mp)['mc_costo'];
                } else {
                    $costo_mp = null;
                }
                echo '<tr>
                    <td>' . htmlspecialchars($fila['inv_fecha']) . '</td>
                    <td>' . htmlspecialchars($fila['inv_no_ticket']) . '</td>
                    <td>' . htmlspecialchars($fila['inv_folio_interno']) . '</td>
                    <td>' . htmlspecialchars($fila['mt_descripcion']) . '</td>
                    <td>' . htmlspecialchars($fila['mat_nombre']) . '</td>
                    <td>' . htmlspecialchars($fila['inv_kilos']) . '</td>
                    <td>' . htmlspecialchars($fila['inv_kg_totales']) . '</td>
                    <td>' . htmlspecialchars(($fila['prv_tipo'] == 'L') ?  'Proveedor Local' : 'Proveedor Extranjero') . '</td>
                    <td>' . htmlspecialchars($fila['prv_nombre']) . '</td>
                    <td>';

                if ($fila['inv_costo'] !== null && $fila['inv_costo'] != '0.00') {
                    // Si inv_costo tiene un valor guardado distinto de 0.00, mostrar el valor guardado
                    echo '<input type="hidden" name="ids_mp[]" value="' . $fila['inv_id'] . '">
                              <input type="text" class="form-control" name="costos_mp[]" value="' . htmlspecialchars($fila['inv_costo']) . '" title="Valor guardado" style="background-color: #d4edda;" oninput="validarNumero(this)">'; // Verde para indicar guardado
                } else {
                    // Si inv_costo es 0.00 o nulo, mostrar el valor sugerido de $costo_mp
                    echo '<input type="hidden" name="ids_mp[]" value="' . $fila['inv_id'] . '">
                              <input type="text" class="form-control" name="costos_mp[]" value="' . htmlspecialchars($costo_mp) . '" placeholder="Agregar costo mp" title="Valor sugerido" style="background-color: #fff3cd;" oninput="validarNumero(this)">'; // Amarillo para indicar sugerido
                }
                echo '</td>';
                echo '<td>';

                // Mismo proceso para inv_costo_maquila
                if (strtoupper($fila['prv_tipo']) == 'L') {
                    echo '<span>NA</span>';
                } else {
                    if (!is_null($fila['inv_costo_mql'])) {
                        echo '<input type="hidden" name="ids_maquilas[]" value="' . $fila['inv_id'] . '">
                                  <input type="text" class="form-control" name="costos_maquila[]" value="' . htmlspecialchars($fila['inv_costo_mql']) . '" oninput="validarNumero(this)">';
                    } else {
                        if ($fila['inv_enviado'] == '2') {
                            echo '<input type="hidden" name="ids_maquilas[]" value="' . $fila['inv_id'] . '">
                            <input type="text" class="form-control" name="costos_maquila[]" placeholder="Agregar costo maquila" oninput="validarNumero(this)">';
                        } else {
                            echo '<input type="hidden" name="ids_maquilas[]" value="' . $fila['inv_id'] . '">
                            <input type="text" class="form-control" name="costos_maquila[]" placeholder="Aun no se recibe" readonly >';
                        }
                    }
                }

                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="2">No se encontraron elementos.</td></tr>';
        }

        echo '<script>';
        echo 'document.getElementById("paginacion").innerHTML = `<nav aria-label="Paginación">
        <ul class="pagination justify-content-center">';
        for ($i = 1; $i <= $total_paginas; $i++) {
            $active_class = ($i == $pagina_actual) ? 'active' : '';
            echo "<li class='page-item $active_class'><a class='page-link' href='#' onclick='cargarPagina($i)'>$i</a></li>";
        }
        echo '</ul></nav>`;';
        echo '</script>';
        $inicio = ($pagina_actual - 1) * $registros_por_pagina + 1;
        $fin = min($pagina_actual * $registros_por_pagina, $total_registros); // Evita pasar del total de registros

        // Añadir un resumen de cuántos elementos se están mostrando
        echo "<script>";
        echo "document.getElementById('resumenElementos').innerHTML = 'Mostrando $inicio a $fin de $total_registros elementos';";
        echo "</script>";
        exit();
    } else {
        echo '<tr><td colspan="2">No se encontraron elementos debido a que no se especificaron las fechas.</td></tr>';
    }
}
?>



<style>
    .modal-fullscreen {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
    }

    .modal-fullscreen .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0;
    }

    .modal-fullscreen .modal-body {
        padding: 20px;
        height: calc(100% - 120px);
        /* Ajusta este valor según el tamaño del header y footer */
    }

    .table-responsive {
        max-height: 70vh;
        /* Limitar la altura de la tabla */
        overflow-y: auto;
        /* Habilitar desplazamiento solo en el cuerpo de la tabla */
    }

    .table-fixed-header thead {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: white;
        /* Fijar el encabezado en la parte superior */
    }

    .table-fixed-header th {
        background-color: #f8f9fa;
        text-align: left;
        /* Asegura que el fondo del encabezado permanezca visible */
    }
</style>

<div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
        <div class="modal-header" style="padding: 10px;">
            <div class="m-4 p-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 20px;" onclick="localStorage.clear()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row align-items-center mb-3 mt-4" style="width: 100%;">
                <div class="col-md-4">
                    <img src="../imagenes/logo_progel_v5.png" alt="Progel Mexicana" class="img-fluid">
                </div>
                <div class="col-md-4 text-center">
                    <h3>Registro de costos</h3>
                </div>
                <div class="col-md-4 text-right">
                    <button type="button" id="guardarCostosMp" class="btn btn-primary" style="margin-top: 20px;">Guardar Costo Mp</button>
                    <button type="button" id="guardarCostosMaquila" class="btn btn-primary" style="margin-top: 20px;">Guardar Costo Maquila</button>
                    <button type="button" id="exportarExcel" class="btn btn-success" style="margin-top: 20px;">Exportar a Excel</button>
                </div>
            </div>
        </div>


        <div class="modal-body container-fluid">
            <div class="">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-4">
                        <input type="date" id="fecha_inicio" class="form-control" placeholder="Fecha de inicio">
                    </div>
                    <div class="col-md-4">
                        <input type="date" id="fecha_fin" class="form-control" placeholder="Fecha de fin">
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="buscador" class="form-control" placeholder="Buscar...">
                    </div>
                </div>


                <div class="table-responsive table-fixed-header">
                    <form id="formularioCostos">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha de registro</th>
                                    <th>No. Ticket</th>
                                    <th>Folio interno</th>
                                    <th>Origen material</th>
                                    <th>Material</th>
                                    <th>Kilos</th>
                                    <th>Kilos Recibidos</th>
                                    <th>Tipo Proveedor</th>
                                    <th>Proveedor Recibe</th>
                                    <th>
                                        Costo MP
                                        <span class="badge badge-success" style="background-color: #28a745;" title="Valor guardado">Guardado</span>
                                        <span class="badge badge-warning" style="background-color: #ffc107;" title="Valor sugerido">Sugerido</span>
                                    </th>

                                    <th>Costo Maquila</th>
                                </tr>
                            </thead>
                            <tbody id="listaElementos">
                                <!-- Aquí se cargarán dinámicamente los elementos -->
                            </tbody>
                        </table>
                        <div id="resumenElementos" class="text-center mt-3"></div>
                        <div id="paginacion"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        const pagina_actual = localStorage.getItem("pagina_actual") || 1;
        const buscar = localStorage.getItem("buscar") || '';
        const fecha_inicio = localStorage.getItem("fecha_inicio") || '';
        const fecha_fin = localStorage.getItem("fecha_fin") || '';

        // Restaurar los valores en los inputs de búsqueda y filtros
        $("#buscador").val(buscar);
        $("#fecha_inicio").val(fecha_inicio);
        $("#fecha_fin").val(fecha_fin);

        obtener_inventario(pagina_actual, buscar);

        // ------------- Buscador dinámico -------------
        $('#buscador').on('keyup', function() {
            let valorBusqueda = $(this).val();
            obtener_inventario(1, valorBusqueda); // Pasa la búsqueda a la función
        });

        $('#fecha_inicio').on('change', function() {
            obtener_inventario(1);
            guardarEstadoActual();
        })

        $('#fecha_fin').on('change', function() {
            obtener_inventario(1);
            guardarEstadoActual();
        });

        $('#paginacion').on('click', '.page-link', function() {
            // Obtener el texto del enlace que fue clicado
            let numeroPagina = $(this).text();

            localStorage.setItem("pagina_actual", numeroPagina);
        });



        // ------------- Exportar tabla a Excel -------------
        $('#exportarExcel').on('click', function() {
            let clonedTable = $('#formularioCostos').clone();

            // Reemplazar los inputs con sus valores en la tabla clonada
            clonedTable.find('input[name="costos_mp[]"], input[name="costos_maquila[]"]').each(function() {
                const inputValue = $(this).val();
                $(this).parent().text(inputValue);
            });

            // Exportar la tabla clonada a Excel
            let table = clonedTable.get(0).querySelector('table');
            let wb = XLSX.utils.table_to_book(table, {
                sheet: "Inventario"
            });
            XLSX.writeFile(wb, "inventario_maquila.xlsx");
        });

        // ------------- Guardar costos de maquila -------------
        $('#guardarCostosMaquila').on('click', function(e) {
            e.preventDefault();
            guardarEstadoActual();
            const ids = [];
            const costos = [];

            // Recopilar los IDs y costos
            $('input[name="ids_maquilas[]"]').each(function(index) {
                const id = $(this).val();
                const costo = $('input[name="costos_maquila[]"]').eq(index).val();
                if (costo.trim() !== '') {
                    ids.push(id);
                    costos.push(costo);
                }
            });

            // Confirmación con SweetAlert antes de guardar
            Swal.fire({
                title: 'Confirmar',
                text: '¿Estás seguro de guardar los costos de maquila?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Llamada AJAX para guardar los datos
                    $.post('guardar_costos_maquila.php', {
                            ids,
                            costos
                        })
                        .done(function(response) {
                            let res = JSON.parse(response);
                            Swal.fire({
                                title: res.status === 'success' ? 'Éxito' : 'Error',
                                text: res.message,
                                icon: res.status === 'success' ? 'success' : 'error',
                                confirmButtonText: 'Aceptar'
                            });
                            if (res.status === 'success') obtener_inventario(localStorage.getItem('pagina_actual'));
                        })
                        .fail(function() {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al guardar los costos.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        });
                }
            });
        });

        // ------------- Guardar costos de material -------------
        $('#guardarCostosMp').on('click', function(e) {
            e.preventDefault();
            guardarEstadoActual();
            const ids = [];
            const costos = [];

            // Recopilar los IDs y costos
            $('input[name="ids_mp[]"]').each(function(index) {
                const id = $(this).val();
                const costo = $('input[name="costos_mp[]"]').eq(index).val();
                if (costo.trim() !== '') {
                    ids.push(id);
                    costos.push(costo);
                }
            });

            // Confirmación con SweetAlert antes de guardar
            Swal.fire({
                title: 'Confirmar',
                text: '¿Estás seguro de guardar los costos de material?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Llamada AJAX para guardar los datos
                    $.post('guardar_costos_mp.php', {
                            ids,
                            costos
                        })
                        .done(function(response) {
                            let res = JSON.parse(response);
                            Swal.fire({
                                title: res.status === 'success' ? 'Éxito' : 'Error',
                                text: res.message,
                                icon: res.status === 'success' ? 'success' : 'error',
                                confirmButtonText: 'Aceptar'
                            });
                            if (res.status === 'success') obtener_inventario(localStorage.getItem('pagina_actual'));
                        })
                        .fail(function() {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al guardar los costos.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        });
                }
            });
        });
    });

    // ------------- Obtener inventario -------------
    function obtener_inventario(pagina = 1, buscar = '') {
        const fecha_inicio = $('#fecha_inicio').val();
        const fecha_fin = $('#fecha_fin').val();

        $.ajax({
            method: 'POST',
            url: 'modal_costo_maquila.php',
            data: {
                action: 'obtener_inventario',
                pagina,
                buscar,
                fecha_inicio, // Pasar fecha de inicio
                fecha_fin // Pasar fecha de fin
            },
            success: function(response) {
                $('#listaElementos').html(response);
            },
            error: function() {
                alert('Error al cargar los elementos.');
            }
        });
    }


    // ------------- Manejar la paginación -------------
    function cargarPagina(pagina) {
        let valorBusqueda = $('#buscador').val();
        obtener_inventario(pagina, valorBusqueda);
    }

    function validarNumero(input) {
        input.value = input.value.replace(/[^0-9.]/g, ''); // Solo permite números y el punto decimal

        // Evitar más de un punto decimal
        if ((input.value.match(/\./g) || []).length > 1) {
            input.value = input.value.replace(/\.+$/, ''); // Elimina puntos extra
        }
    }


    function guardarEstadoActual() {
        const pagina_actual = $(".page-item.active").text();
        const buscar = $("#buscador").val();
        const fecha_inicio = $("#fecha_inicio").val();
        const fecha_fin = $("#fecha_fin").val();

        localStorage.setItem("pagina_actual", pagina_actual);
        localStorage.setItem("buscar", buscar);
        localStorage.setItem("fecha_inicio", fecha_inicio);
        localStorage.setItem("fecha_fin", fecha_fin);
    }
</script>