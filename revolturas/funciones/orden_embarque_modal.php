<?php
include "../../seguridad/user_seguridad.php";
$fechaActual = date("Y-m-d");
?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
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
                        <select name="cte_id" id="cte_id" class="form-select" required>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" required value="<?= $fechaActual ?>">
                    </div>

                    <div class="col-md-4 text-end mt-3 mt-md-0">
                        <button class="btn btn-secondary d-none" id="cambiar_cliente">Re-asignar cliente</button>
                    </div>
                </div>

                <div class="row">
                    <div class="">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tipo</th>
                                    <th>Revoltura / Lote</th>
                                    <th>Empaque</th>
                                    <th>Existencias</th>
                                    <th>Existencias a tomar</th>
                                    <th>Bloom</th>
                                    <th>Quitar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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

        setTimeout(() => {
            $('#cte_id').val(localStorage.getItem('cliente_id') || '');
            if ($('#cte_id').val() !== '') {
                $('#cambiar_cliente').removeClass('d-none');
            }
            cargarDatosEmpaques();
        }, 100);

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
            cargarDatosEmpaques();
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
                    let clientes = JSON.parse(data);

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
                },
                error: function() {
                    alert('Error al cargar los clientes.');
                }
            });
        }

        $('#cambiar_cliente').on('click', function(e) {
            e.preventDefault();

            let cliente = $('#cte_id option:selected').text();
            let cte_id = $('#cte_id').val();
            let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];

            const soloEmpacados = empaquesArray.filter(item =>
                item.tipo_producto === 'REVOLTURA' && item.rrc_id
            );

            if (soloEmpacados.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin productos reasignables',
                    text: 'Solo los productos empacados pueden reasignarse de cliente.'
                });
                return;
            }

            for (let index = 0; index < empaquesArray.length; index++) {
                let empaque = empaquesArray[index];

                if (empaque.tipo_producto !== 'REVOLTURA') {
                    continue;
                }

                let cantidadDisponible = parseFloat(empaque.rr_ext_real || 0);
                let cantidadIngresada = parseFloat($(`#cantidad_${index}`).val()) || 0;

                if (cantidadIngresada > cantidadDisponible) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cantidad excedida',
                        text: `No puedes tomar más de la cantidad disponible (${cantidadDisponible}). Verifica la fila ${index + 1}.`
                    });
                    return;
                }

                cambiar_de_cliente(empaque.rrc_id, cte_id, cliente);
            }
        });
    });

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

                let cantidadDisponible = parseFloat(empaque.rr_ext_real || 0);
                let esExterno = empaque.tipo_producto === 'EXTERNO';

                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>
                        ${esExterno
                            ? '<span class="badge bg-warning text-dark">EXTERNO</span>'
                            : '<span class="badge bg-success">REVOLTURA</span>'
                        }
                    </td>
                    <td>${empaque.revoltura}</td>
                    <td>${empaque.pres_descrip}</td>
                    <td>${cantidadDisponible}</td>
                    <td>
                        <input type="text"
                            class="form-control"
                            id="cantidad_${index}"
                            onclick="$(this).val('')"
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
                                        value="${bloomCliente ? bloomCliente : ''}"
                                        onclick="$(this).val('')"
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
        $('#dataTableEmpaques').DataTable().ajax.reload();
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
        let empaquesProcesados = [];

        empaquesArray.forEach((empaque, index) => {
            let cantidadIngresada = parseFloat($(`#cantidad_${index}`).val()) || 0;
            let cantidadDisponible = parseFloat(empaque.rr_ext_real || 0);
            let esExterno = empaque.tipo_producto === 'EXTERNO';
            let bloomAsig = esExterno ? null : ($(`#bloom_${index}`).val() || null);

            if (cantidadIngresada <= 0) {
                validacion = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Cantidad inválida',
                    text: `Debes capturar una cantidad válida en la fila ${index + 1}.`
                });
                return false;
            }

            if (cantidadIngresada > cantidadDisponible) {
                validacion = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Cantidad excedida',
                    text: `No puedes tomar más de la cantidad disponible (${cantidadDisponible}). Verifica la fila ${index + 1}.`
                });
                return false;
            }

            if (!esExterno && (!bloomAsig || bloomAsig === '')) {
                validacion = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Bloom requerido',
                    text: `Debes capturar bloom en la fila ${index + 1}.`
                });
                return false;
            }

            empaquesProcesados.push({
                tipo_producto: empaque.tipo_producto ?? 'REVOLTURA',
                rr_id: empaque.rr_id || null,
                pe_id: empaque.pe_id || null,
                rrc_id: empaque.rrc_id || null,
                cantidad: cantidadIngresada,
                bloom: bloomAsig
            });
        });

        if (!validacion) return;

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
                    localStorage.removeItem('cliente_id');

                    cargarDatosEmpaques();
                    $('#cte_id').val('');
                    $('#dataTableEmpaques').DataTable().ajax.reload();
                    $('#dataTableEmpaquesClientes').DataTable().ajax.reload();
                } else {
                    alertas_v5("#alerta-factura", 'Error!', response.message, 3, true, 5000);
                }
            },
            error: function(xhr) {
                alertas_v5(
                    "#alerta-factura",
                    'Error de red',
                    xhr.responseText || 'No se pudo conectar con el servidor.',
                    3,
                    true,
                    5000
                );
            }
        });
    }

    function cambiar_de_cliente(rrc_id, cte_id, cliente) {
        $.ajax({
            type: 'POST',
            url: 'funciones/cliente_empacado_cambiar_cliente.php',
            data: {
                rrc_id,
                cte_id
            },
            success: function(response) {
                Swal.fire({
                    title: 'Cliente reasignado correctamente',
                    text: `El cliente nuevo será ${cliente}`,
                    icon: 'success'
                });

                localStorage.removeItem('empaques');
                localStorage.removeItem('cliente_id');

                cargarDatosEmpaques();
                $('#dataTableEmpaquesClientes').DataTable().ajax.reload();
            },
            error: function() {
                Swal.fire({
                    title: 'Error al reasignar cliente',
                    text: 'Ocurrió un error al intentar reasignar el cliente. Por favor intenta nuevamente.',
                    icon: 'error'
                });
            }
        });
    }
</script>
