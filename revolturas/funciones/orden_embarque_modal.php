<?php
include "../../seguridad/user_seguridad.php";
$fechaActual = date("Y-m-d");
?>

<script type="text/javascript" src="../js/alerta.js"></script>

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="title">Generar orden de embarque</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <form id="form_orden_embarque" method="POST">
                <div class="row mb-3 align-items-end">
                    <div class="col-md-5">
                        <input type="text" id="search_clientes" class="form-control mb-2" placeholder="Buscar cliente...">

                        <label for="cte_id" class="form-label">Cliente</label>
                        <select name="cte_id" id="cte_id" class="form-select" required></select>
                    </div>

                    <div class="col-md-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" required value="<?= $fechaActual ?>">
                    </div>

                    <div class="col-md-4 text-end mt-3 mt-md-0">
                        <button class="btn btn-secondary d-none" id="cambiar_cliente">Re-asignar cliente</button>
                    </div>
                </div>

                <!-- 
                    Cuando el modal viene desde "Clientes empacado", aquí se muestran los empaques
                    disponibles del cliente para que el usuario seleccione cuáles quiere embarcar.
                    No se agregan automáticamente a la orden.
                -->
                <div class="row mb-3 d-none" id="contenedor_disponibles_cliente">
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                Empaques disponibles del cliente
                            </div>

                            <div class="card-body">
                                <div class="alert alert-info py-2">
                                    Selecciona los empaques que deseas agregar a la orden.
                                    La cantidad no puede superar el disponible.
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle" id="table_disponibles_cliente">
                                        <thead>
                                            <tr>
                                                <th style="width: 60px;">Sel.</th>
                                                <th>Revoltura</th>
                                                <th>Empaque</th>
                                                <th>Existencia real</th>
                                                <th>Comprometido</th>
                                                <th>Disponible</th>
                                                <th style="width: 170px;">Cantidad a tomar</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="text-end">
                                    <button type="button" class="btn btn-success" id="agregar_seleccionados_cliente">
                                        Agregar seleccionados a la orden
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Partidas definitivas de la orden -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-2">Partidas de la orden</h6>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tipo</th>
                                        <th>Revoltura / Lote</th>
                                        <th>Empaque</th>
                                        <th>Disponible</th>
                                        <th>Existencias a tomar</th>
                                        <th>Bloom</th>
                                        <th>Quitar</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-factura" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>

                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>

                    <button form="form_orden_embarque" type="submit" class="btn btn-primary ms-2" id="guardar">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let arrayClientes = [];

    $(document).ready(function() {
        obtenerClientes();

        $('#form_orden_embarque').submit(function(e) {
            e.preventDefault();
            insertarOrdenEmbarque();
        });

        $('#search_clientes').on('input', function() {
            const inputValue = $(this).val().toLowerCase();

            if (inputValue.length > 0) {
                const filteredClientes = arrayClientes.filter(cliente =>
                    cliente.cte_nombre.toLowerCase().includes(inputValue)
                );

                const select = $('#cte_id');
                select.empty();

                if (filteredClientes.length > 0) {
                    filteredClientes.forEach(cliente => {
                        select.append(`<option value="${cliente.cte_id}">${cliente.cte_nombre}</option>`);
                    });
                } else {
                    select.append('<option value="">No se encontraron resultados</option>');
                }
            } else {
                actualizarListadoClientes('');
            }
        });

        $('#cte_id').on('change', function() {
            /*
                IMPORTANTE:
                No limpiar "empaques" al cambiar cliente.

                Este select también se usa para REASIGNAR cliente.
                Si limpiamos localStorage aquí, al elegir el cliente nuevo
                se pierden los empaques seleccionados.

                La limpieza debe hacerse únicamente:
                - al cerrar el modal,
                - al guardar la orden,
                - o después de reasignar correctamente.
            */
            cargarDisponiblesCliente();
            cargarDatosEmpaques();
        });

        $('#agregar_seleccionados_cliente').on('click', function() {
            agregarSeleccionadosClienteAOrden();
        });

        $('#cambiar_cliente').on('click', function(e) {
            e.preventDefault();

            let cliente = $('#cte_id option:selected').text();
            let cte_id = $('#cte_id').val();
            let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];

            const soloEmpacados = empaquesArray.filter(item =>
                item.tipo_producto === 'REVOLTURA' && item.rrc_id
            );

            if (!cte_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cliente requerido',
                    text: 'Selecciona el cliente nuevo.'
                });
                return;
            }

            if (soloEmpacados.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin productos reasignables',
                    text: 'Solo los productos empacados de cliente pueden reasignarse.'
                });
                return;
            }

            Swal.fire({
                icon: 'question',
                title: 'Confirmar reasignación',
                text: `Se cambiarán ${soloEmpacados.length} empaque(s) al cliente ${cliente}.`,
                showCancelButton: true,
                confirmButtonText: 'Sí, reasignar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                reasignarEmpaquesCliente(soloEmpacados, cte_id, cliente);
            });
        });
    });

    function actualizarListadoClientes(filtro) {
        let opciones = '<option value="">Seleccione un cliente</option>';

        if (filtro.length > 0) {
            arrayClientes
                .filter(cliente => cliente.cte_nombre.toLowerCase().includes(filtro))
                .forEach(cliente => {
                    opciones += `<option value="${cliente.cte_id}">${cliente.cte_nombre}</option>`;
                });
        } else {
            arrayClientes.forEach(cliente => {
                opciones += `<option value="${cliente.cte_id}">${cliente.cte_nombre}</option>`;
            });
        }

        $('#cte_id').html(opciones);
    }

    function obtenerClientes() {
        $.ajax({
            type: 'GET',
            url: 'catalogos/clientes_listado.php',
            success: function(data) {
                let clientes = typeof data === 'string' ? JSON.parse(data) : data;

                clientes.forEach(function(cte) {
                    if (cte.cte_estatus === 'A') {
                        arrayClientes.push({
                            cte_id: cte.cte_id,
                            cte_nombre: cte.cte_nombre,
                            cte_bloom: cte.cte_tipo_bloom
                        });
                    }
                });

                actualizarListadoClientes('');

                const clienteLocal = localStorage.getItem('cliente_id') || '';

                if (clienteLocal !== '') {
                    $('#cte_id').val(clienteLocal);
                    $('#cambiar_cliente').removeClass('d-none');
                }

                cargarDisponiblesCliente();
                cargarDatosEmpaques();
            },
            error: function() {
                alert('Error al cargar los clientes.');
            }
        });
    }

    function cargarDisponiblesCliente() {
        let disponibles = JSON.parse(localStorage.getItem('empaques_cliente_disponibles')) || [];
        const tbody = document.querySelector("#table_disponibles_cliente tbody");

        if (!tbody) {
            return;
        }

        tbody.innerHTML = "";

        if (disponibles.length === 0) {
            $('#contenedor_disponibles_cliente').addClass('d-none');
            return;
        }

        $('#contenedor_disponibles_cliente').removeClass('d-none');

        disponibles.forEach((empaque, index) => {
            let disponible = parseFloat(empaque.cantidad_disponible || empaque.rr_ext_real || 0);
            let real = parseFloat(empaque.rr_ext_real || 0);
            let comprometido = parseFloat(empaque.cantidad_comprometida || 0);

            let row = document.createElement("tr");

            row.innerHTML = `
                <td class="text-center">
                    <input type="checkbox" class="form-check-input" id="chk_cliente_${index}">
                </td>
                <td>${empaque.revoltura || ''}</td>
                <td>${empaque.pres_descrip || ''}</td>
                <td>${formatNumber(real)}</td>
                <td>${formatNumber(comprometido)}</td>
                <td>${formatNumber(disponible)}</td>
                <td>
                    <input type="text"
                        class="form-control form-control-sm"
                        id="cantidad_cliente_${index}"
                        placeholder="0.00"
                        onkeypress="return isNumberKey(event, this);"
                        maxlength="7">
                </td>
            `;

            tbody.appendChild(row);
        });
    }

    function agregarSeleccionadosClienteAOrden() {
        let disponibles = JSON.parse(localStorage.getItem('empaques_cliente_disponibles')) || [];
        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];

        if (disponibles.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Sin disponibles',
                text: 'No hay empaques disponibles para seleccionar.'
            });
            return;
        }

        let errores = [];
        let agregados = 0;

        disponibles.forEach((empaque, index) => {
            let seleccionado = $(`#chk_cliente_${index}`).is(':checked');

            if (!seleccionado) {
                return;
            }

            let cantidad = parseFloat($(`#cantidad_cliente_${index}`).val()) || 0;
            let disponible = parseFloat(empaque.cantidad_disponible || empaque.rr_ext_real || 0);

            if (cantidad <= 0) {
                errores.push(`Fila ${index + 1}: la cantidad debe ser mayor que 0.`);
                return;
            }

            if (cantidad > disponible) {
                errores.push(`Fila ${index + 1}: cantidad ${cantidad} mayor al disponible ${disponible}.`);
                return;
            }

            const llave = getLlaveProducto(empaque);
            const existeIndex = empaquesArray.findIndex(item => getLlaveProducto(item) === llave);

            const empaqueOrden = {
                tipo_producto: empaque.tipo_producto || 'REVOLTURA',

                revoltura: empaque.revoltura,
                rev_id: empaque.rev_id || null,

                rr_id: empaque.rr_id || null,
                rrc_id: empaque.rrc_id || null,
                pe_id: empaque.pe_id || null,

                pres_id: empaque.pres_id || null,
                pres_descrip: empaque.pres_descrip,

                rr_ext_inicial: empaque.rr_ext_inicial || 0,
                rr_ext_real: empaque.rr_ext_real || 0,

                cantidad_comprometida: empaque.cantidad_comprometida || 0,
                cantidad_disponible: disponible,

                pres_kg: empaque.pres_kg || 0,
                cantidad: cantidad
            };

            if (existeIndex >= 0) {
                empaquesArray[existeIndex] = empaqueOrden;
            } else {
                empaquesArray.push(empaqueOrden);
            }

            agregados++;
        });

        if (errores.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validación incorrecta',
                html: errores.join('<br>')
            });
            return;
        }

        if (agregados === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Sin selección',
                text: 'Selecciona al menos un empaque para agregarlo a la orden.'
            });
            return;
        }

        localStorage.setItem('empaques', JSON.stringify(empaquesArray));

        cargarDatosEmpaques();

        Swal.fire({
            icon: 'success',
            title: 'Agregado',
            text: 'Los empaques seleccionados fueron agregados a la orden.',
            timer: 1200,
            showConfirmButton: false
        });
    }

    function cargarDatosEmpaques() {
        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];
        const tbody = document.querySelector("#table tbody");
        tbody.innerHTML = "";

        const cteId = $('#cte_id').val();

        let bloomCliente = null;
        if (cteId) {
            const clienteSel = arrayClientes.find(c => c.cte_id == cteId);
            bloomCliente = clienteSel?.cte_bloom || null;
        }

        if (empaquesArray.length > 0) {
            empaquesArray.forEach((empaque, index) => {
                let row = document.createElement("tr");

                let cantidadDisponible = parseFloat(empaque.cantidad_disponible || empaque.rr_ext_real || 0);
                let cantidadSeleccionada = empaque.cantidad ? parseFloat(empaque.cantidad) : '';
                let esExterno = empaque.tipo_producto === 'EXTERNO';

                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>
                        ${esExterno
                            ? '<span class="badge bg-warning text-dark">EXTERNO</span>'
                            : '<span class="badge bg-success">REVOLTURA</span>'
                        }
                    </td>
                    <td>${empaque.revoltura || ''}</td>
                    <td>${empaque.pres_descrip || ''}</td>
                    <td>${formatNumber(cantidadDisponible)}</td>
                    <td>
                        <input type="text"
                            class="form-control"
                            id="cantidad_${index}"
                            value="${cantidadSeleccionada}"
                            onclick="$(this).select()"
                            onkeypress="return isNumberKey(event, this);"
                            maxlength="7"
                            required>
                    </td>
                    <td>
                        ${
                            esExterno
                                ? '<input type="text" class="form-control" value="N/A" readonly>'
                                : `<input type="text"
                                        class="form-control"
                                        id="bloom_${index}"
                                        value="${empaque.bloom || (bloomCliente ? bloomCliente : '')}"
                                        onclick="$(this).select()"
                                        onkeypress="return isNumberKey(event, this);"
                                        maxlength="3"
                                        required>`
                        }
                    </td>
                    <td>
                        <a href="#" onclick="eliminarEmpaque(${index}); return false;">
                            <i class="fas fa-times-circle text-danger"></i>
                        </a>
                    </td>
                `;

                tbody.appendChild(row);
            });
        } else {
            let row = document.createElement("tr");
            row.innerHTML = `<td colspan="8" class="text-center">Sin productos seleccionados</td>`;
            tbody.appendChild(row);
        }
    }

    function eliminarEmpaque(index) {
        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];
        empaquesArray.splice(index, 1);
        localStorage.setItem('empaques', JSON.stringify(empaquesArray));
        cargarDatosEmpaques();

        if ($.fn.DataTable.isDataTable('#dataTableEmpaques')) {
            $('#dataTableEmpaques').DataTable().ajax.reload();
        }
    }

    function insertarOrdenEmbarque() {
        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];
        let cliente = $('#cte_id').val();

        if (empaquesArray.length === 0) {
            return Swal.fire({
                icon: 'info',
                title: 'No hay datos',
                text: 'No hay registros para insertar.'
            });
        }

        if (!cliente) {
            return Swal.fire({
                icon: 'warning',
                title: 'Cliente requerido',
                text: 'Selecciona un cliente para generar la orden.'
            });
        }

        let validacion = true;
        let errores = [];
        let empaquesProcesados = [];
        let acumuladoPorProducto = {};

        empaquesArray.forEach((empaque, index) => {
            let cantidadIngresada = parseFloat($(`#cantidad_${index}`).val()) || 0;
            let cantidadDisponible = parseFloat(empaque.cantidad_disponible || empaque.rr_ext_real || 0);
            let esExterno = empaque.tipo_producto === 'EXTERNO';
            let bloomAsig = esExterno ? null : ($(`#bloom_${index}`).val() || null);

            if (cantidadIngresada <= 0) {
                validacion = false;
                errores.push(`Fila ${index + 1}: debes capturar una cantidad válida.`);
                return;
            }

            if (cantidadIngresada > cantidadDisponible) {
                validacion = false;
                errores.push(`Fila ${index + 1}: cantidad ${cantidadIngresada} mayor al disponible ${cantidadDisponible}.`);
                return;
            }

            if (!esExterno && (!bloomAsig || bloomAsig === '')) {
                validacion = false;
                errores.push(`Fila ${index + 1}: debes capturar bloom.`);
                return;
            }

            const llave = getLlaveProducto(empaque);

            if (!acumuladoPorProducto[llave]) {
                acumuladoPorProducto[llave] = {
                    solicitado: 0,
                    disponible: cantidadDisponible,
                    filas: []
                };
            }

            acumuladoPorProducto[llave].solicitado += cantidadIngresada;
            acumuladoPorProducto[llave].filas.push(index + 1);

            empaquesProcesados.push({
                tipo_producto: empaque.tipo_producto || 'REVOLTURA',
                rr_id: empaque.rr_id || null,
                pe_id: empaque.pe_id || null,
                rrc_id: empaque.rrc_id || null,
                cantidad: cantidadIngresada,
                bloom: bloomAsig
            });
        });

        Object.keys(acumuladoPorProducto).forEach(llave => {
            const item = acumuladoPorProducto[llave];

            if (item.solicitado > item.disponible) {
                validacion = false;
                errores.push(
                    `Producto repetido en filas ${item.filas.join(', ')}: ` +
                    `solicitado ${item.solicitado}, disponible ${item.disponible}.`
                );
            }
        });

        if (!validacion) {
            return Swal.fire({
                icon: 'error',
                title: 'Validación incorrecta',
                html: errores.join('<br>')
            });
        }

        $.ajax({
            url: 'funciones/orden_embarque_insertar.php',
            type: 'POST',
            data: JSON.stringify({
                cte_id: cliente,
                empaques: empaquesProcesados
            }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alertas_v5("#alerta-factura", 'Listo!', response.message, 1, true, 5000);

                    localStorage.removeItem('empaques');
                    localStorage.removeItem('empaques_cliente_disponibles');
                    localStorage.removeItem('cliente_id');

                    cargarDatosEmpaques();
                    cargarDisponiblesCliente();

                    $('#cte_id').val('');

                    if ($.fn.DataTable.isDataTable('#dataTableEmpaques')) {
                        $('#dataTableEmpaques').DataTable().ajax.reload();
                    }

                    if ($.fn.DataTable.isDataTable('#dataTableEmpaquesClientes')) {
                        $('#dataTableEmpaquesClientes').DataTable().ajax.reload();
                    }
                } else {
                    alertas_v5("#alerta-factura", 'Error!', response.message, 3, true, 5000);
                }
            },
            error: function(xhr) {
                let mensaje = 'No se pudo conectar con el servidor.';

                if (xhr.responseJSON) {
                    mensaje = xhr.responseJSON.message || xhr.responseJSON.error || mensaje;
                } else if (xhr.responseText) {
                    try {
                        let res = JSON.parse(xhr.responseText);
                        mensaje = res.message || res.error || mensaje;
                    } catch (e) {
                        mensaje = xhr.responseText;
                    }
                }

                alertas_v5(
                    "#alerta-factura",
                    'Error',
                    mensaje,
                    3,
                    true,
                    7000
                );
            }
        });
    }

    function cambiar_de_cliente(rrc_id, cte_id, cantidad) {
        return $.ajax({
            type: 'POST',
            url: 'funciones/cliente_empacado_cambiar_cliente.php',
            dataType: 'json',
            data: {
                rrc_id: rrc_id,
                cte_id: cte_id,
                cantidad: cantidad
            }
        });
    }

    function reasignarEmpaquesCliente(empaques, cte_id, cliente) {
        Swal.fire({
            title: 'Reasignando...',
            text: 'Espera mientras se cambia el cliente.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        let cadena = Promise.resolve();

        empaques.forEach(empaque => {
            let cantidad = parseFloat(empaque.cantidad || 0);

            cadena = cadena.then(() => cambiar_de_cliente(
                empaque.rrc_id,
                cte_id,
                cantidad
            ));
        });

        cadena.then(() => {
            Swal.fire({
                title: 'Cantidad reasignada correctamente',
                text: `La cantidad seleccionada fue reasignada al cliente ${cliente}`,
                icon: 'success'
            });

            localStorage.removeItem('empaques');
            localStorage.removeItem('empaques_cliente_disponibles');
            localStorage.removeItem('cliente_id');

            cargarDatosEmpaques();
            cargarDisponiblesCliente();

            if ($.fn.DataTable.isDataTable('#dataTableEmpaquesClientes')) {
                $('#dataTableEmpaquesClientes').DataTable().ajax.reload();
            }
        }).catch(error => {
            let mensaje = 'Ocurrió un error al intentar reasignar el cliente.';

            if (error.responseJSON) {
                mensaje = error.responseJSON.error || error.responseJSON.message || mensaje;
            }

            Swal.fire({
                title: 'Error al reasignar cliente',
                text: mensaje,
                icon: 'error'
            });
        });
    }

    function getLlaveProducto(empaque) {
        if (empaque.tipo_producto === 'EXTERNO') {
            return `EXTERNO_${empaque.pe_id}`;
        }

        if (empaque.rrc_id) {
            return `RRC_${empaque.rrc_id}`;
        }

        return `RR_${empaque.rr_id}`;
    }

    function formatNumber(value) {
        let number = parseFloat(value || 0);

        return number.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
</script>