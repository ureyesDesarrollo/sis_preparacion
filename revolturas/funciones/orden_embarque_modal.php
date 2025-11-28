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
                    <!-- Búsqueda y selección de cliente -->
                    <div class="col-md-5">
                        <input type="text" id="search_clientes" class="form-control mb-2" placeholder="Buscar cliente...">
                        <label for="cte_id" class="form-label">Cliente</label>
                        <select name="cte_id" id="cte_id" class="form-select" required>
                            <!-- Opciones se cargarán dinámicamente -->
                        </select>
                    </div>

                    <!-- Fecha -->
                    <div class="col-md-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" required value="<?= $fechaActual ?>">
                    </div>

                    <!-- Botón de Re-asignar -->
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
                                    <th>Revoltura</th>
                                    <th>Empaque</th>
                                    <th>Existencias</th>
                                    <th>Existencias a tomar</th>
                                    <th>Bloom</th>
                                    <th>Quitar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
        let typingTimer;
        const typingDelay = 500; // Milisegundos de espera después de dejar de escribir
        obtenerClientes();
        setTimeout(() => {
            $('#cte_id').val(localStorage.getItem('cliente_id') || '');
            if ($('#cte_id').val() != '') {
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

                // Actualiza el select con los clientes filtrados
                const select = $('#cte_id');
                select.empty(); // Limpia las opciones actuales
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

        function actualizarListadoClientes(filtro) {
            let opciones = '<option value="">Seleccione un cliente</option>';

            if (filtro.length > 0) {
                arrayClientes.filter(cliente => cliente.cte_nombre.toLowerCase().includes(filtro))
                    .forEach(cliente => {
                        opciones += `<option value="${cliente.cte_id}">${cliente.cte_nombre}</option>`;
                    });
            } else {
                // Si no hay filtro, muestra todos los clientes
                let cliente = {};
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

            empaquesArray.forEach((empaque, index) => {
                let cantidadIngresada = parseFloat($(`#cantidad_${index}`).val());

                if (cantidadIngresada > parseFloat(empaque.rr_ext_real)) {
                    validacion = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Cantidad excedida',
                        text: `No puedes tomar más de la cantidad disponible (${empaque.rr_ext_real}). Verifica la fila ${index + 1}.`
                    });
                    return;
                }
                cambiar_de_cliente(empaque.rrc_id, cte_id, cliente);
            });
        });
    });


    // Función para cargar los datos del localStorage y mostrarlos en la tabla
    function cargarDatosEmpaques() {
        // Recupera el arreglo de empaques desde el localStorage o inicializa un arreglo vacío
        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];
        const tbody = document.querySelector("#table tbody");
        tbody.innerHTML = ""; // Limpia el contenido actual de la tabla

        // Tomar cliente seleccionado
        const cteId = $('#cte_id').val();
        console.log(cteId);

        // Buscar bloom del cliente
        let bloomCliente = null;
        if (cteId) {
            const clienteSel = arrayClientes.find(c => c.cte_id === cteId);
            bloomCliente = clienteSel?.cte_bloom || null;
        }

        console.log(bloomCliente);
        if (empaquesArray.length > 0) {
            // Itera sobre el arreglo y agrega cada elemento como una nueva fila en la tabla
            empaquesArray.forEach((empaque, index) => {
                let row = document.createElement("tr");
                let cantidad = (empaque.rr_ext_real === '0.00') ? empaque.rr_ext_inicial : empaque.rr_ext_real;

                row.innerHTML = `
                <td>${index + 1}</td>
                <td>${empaque.revoltura}</td>
                <td>${empaque.pres_descrip}</td>
                <td>${cantidad}</td>
                <td><input type="text" class="form-control" id="cantidad_${index}"  onclick="$(this).val('')" onkeypress="return isNumberKey(event, this);" maxlength="7" required></td>
                <td>
                    <input type="text" 
                        class="form-control" 
                        id="bloom_${index}"  
                        value="${bloomCliente ? bloomCliente : ''}"
                        onclick="$(this).val('')" 
                        onkeypress="return isNumberKey(event, this);" 
                        maxlength="3" 
                        required>
                </td>

                <td><a href="#" onclick="eliminarEmpaque(${index})"><i class="fas fa-times-circle text-danger"></i></a></td>
            `;
                tbody.appendChild(row);
            });
        } else {
            let row = document.createElement("tr");
            row.innerHTML = `<td colspan="6" class="text-center">Sin empaques seleccionados</td>`;
            tbody.appendChild(row);
        }
    }


    // Función para eliminar un elemento específico del localStorage y de la tabla
    function eliminarEmpaque(index) {
        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];

        // Elimina el elemento del arreglo
        empaquesArray.splice(index, 1);

        // Actualiza el localStorage con el nuevo arreglo sin el elemento eliminado
        localStorage.setItem('empaques', JSON.stringify(empaquesArray));

        // Vuelve a cargar los datos en la tabla
        cargarDatosEmpaques();
        $('#dataTableEmpaques').DataTable().ajax.reload();
    }


    function insertarOrdenEmbarque() {
        let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];
        let fecha = $('#fecha').val();
        let cliente = $('#cte_id').val();

        if (empaquesArray.length === 0) {
            return Swal.fire({
                icon: 'info',
                title: 'No hay datos',
                text: 'No hay registros para insertar.'
            });
        }

        let validacion = true;
        let empaquesProcesados = [];

        empaquesArray.forEach((empaque, index) => {
            let cantidadIngresada = parseFloat($(`#cantidad_${index}`).val());
            let bloomAsig = $(`#bloom_${index}`).val();

            if (cantidadIngresada > parseFloat(empaque.rr_ext_real)) {
                validacion = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Cantidad excedida',
                    text: `No puedes tomar más de la cantidad disponible (${empaque.rr_ext_real}). Verifica la fila ${index + 1}.`
                });
                return;
            }

            empaquesProcesados.push({
                rr_id: empaque.rr_id || null,
                rrc_id: empaque.rrc_id || null,
                cantidad: cantidadIngresada,
                bloom: bloomAsig
            });
        });

        if (!validacion) return;

        // Enviar una sola petición al backend
        $.ajax({
            url: 'funciones/orden_embarque_insertar.php',
            type: 'POST',
            data: JSON.stringify({
                cte_id: cliente,
                empaques: empaquesProcesados
            }),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    alertas_v5("#alerta-factura", 'Listo!', response.message, 1, true, 5000);
                    localStorage.clear();
                    cargarDatosEmpaques();
                    $('#fe_factura').val('');
                    $('#cte_id').val('');
                    $('#dataTableEmpaques').DataTable().ajax.reload();
                    $('#dataTableEmpaquesClientes').DataTable().ajax.reload();
                } else {
                    alertas_v5("#alerta-factura", 'Error!', response.message, 3, true, 5000);
                }
            },
            error: function(xhr) {
                alertas_v5("#alerta-factura", 'Error de red', xhr.responseText || 'No se pudo conectar con el servidor.', 3, true, 5000);
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
                    'title': 'Cliente reasinganado correctamente',
                    'text': `El cliente nuevo sera ${cliente}`,
                    'icon': 'success'
                });
                localStorage.clear();
                cargarDatosEmpaques();
                $('#dataTableEmpaquesClientes').DataTable().ajax.reload();

            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Error al reasignar cliente',
                    text: 'Ocurrió un error al intentar reasignar el cliente. Por favor intente nuevamente.',
                    icon: 'error'
                });
            }
        });
    }
</script>