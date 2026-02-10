<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviemvbre-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$fechaActual = date("Y-m-d");

?>


<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="title">Apartar para cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_cliente_empacado" method="POST" autocomplete="off">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <input type="text" id="search_clientes" class="form-control" placeholder="Buscar cliente">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cliente" class="form-label">Cliente</label>
                                <select name="cte_id" id="cte_id" class="form-select" required></select>
                            </div>
                        </div>
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
                    <button form="form_cliente_empacado" type="submit" class="btn btn-primary ms-2" id="guardar">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let arrayClientes = [];
        cargarDatosEmpaques_cliente();
        obtenerClientes();

        $('input[name="tipo"]').on('change', function() {
            // Obtén el valor seleccionado
            let tipoDocumento = $('input[name="tipo"]:checked').val();

            // Cambia el texto del label
            let label = tipoDocumento == 'F' ? 'Factura' : 'Remisión';
            $('#tipo_documento').text(label);
            $('#title').text(`Capturar ${label}`);
        });


        $('#form_cliente_empacado').submit(function(e) {
            e.preventDefault();
            insertarRegistros_cliente();
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
                                cte_nombre: cte.cte_nombre
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

        function eliminarEmpaque_cliente(index) {
            let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];

            // Elimina el elemento del arreglo
            empaquesArray.splice(index, 1);

            // Actualiza el localStorage con el nuevo arreglo sin el elemento eliminado
            localStorage.setItem('empaques', JSON.stringify(empaquesArray));

            // Vuelve a cargar los datos en la tabla
            cargarDatosEmpaques_cliente();
            $('#dataTableEmpaques').DataTable().ajax.reload();
        }

        // Función para cargar los datos del localStorage y mostrarlos en la tabla
        function cargarDatosEmpaques_cliente() {
            // Recupera el arreglo de empaques desde el localStorage o inicializa un arreglo vacío
            let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];
            const tbody = document.querySelector("#table tbody");
            tbody.innerHTML = ""; // Limpia el contenido actual de la tabla

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
                <td><a href="#" class="eliminar-empaque" data-index="${index}"><i class="fas fa-times-circle text-danger"></i></a></td>
            `;
                    tbody.appendChild(row);
                });
            } else {
                let row = document.createElement("tr");
                row.innerHTML = `<td colspan="4" class="text-center">Sin empaques seleccionados</td>`;
                tbody.appendChild(row);
            }
        }

        $(document).on('click', '.eliminar-empaque', function(e) {
            e.preventDefault();
            let index = $(this).data('index');
            eliminarEmpaque_cliente(index);
        });

        function insertarRegistros_cliente() {
            let empaquesArray = JSON.parse(localStorage.getItem('empaques')) || [];
            let cliente = $('#cte_id').val();
             let tipoCliente = $('#cte_tipo').val();
             let clasificacion = $('#cte_clasificacion').val();

            if (empaquesArray.length > 0) {
                let validacion = true;

                empaquesArray.forEach((empaque, index) => {
                    let cantidadIngresada = $(`#cantidad_${index}`).val();

                    // Verifica que la cantidad ingresada no sea mayor que la disponible
                    if (parseFloat(empaque.rr_ext_real) < parseFloat(cantidadIngresada)) {
                        validacion = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Cantidad excedida',
                            text: `No puedes tomar más de la cantidad disponible (${empaque.rr_ext_real}). Verifica la cantidad en la fila ${index + 1}.`
                        });
                        return; // Sale del bucle si encuentra una cantidad excedida
                    }
                });

                if (!validacion) return; // Sale de la función si alguna cantidad excede la disponible

                // Procesa cada registro si todas las cantidades son válidas
                empaquesArray.forEach((empaque, index) => {
                    let cantidadIngresada = $(`#cantidad_${index}`).val();

                    $.ajax({
                        url: 'funciones/cliente_empacado_insertar.php',
                        type: 'POST',
                        data: {
                            rr_id: empaque.rr_id,
                            rev_id: empaque.rev_id,
                            pres_id: empaque.pres_id,
                            rrc_cantidad: cantidadIngresada,
                            cte_id: cliente,
                            cte_tipo: tipoCliente,
                            cte_clasificacion: clasificacion
                        },
                        success: function(response) {
                            let res = JSON.parse(response);
                            if (res.success) {
                                alertas_v5("#alerta-factura", 'Listo!', res.success, 1, true, 5000);
                                localStorage.clear();
                                cargarDatosEmpaques_cliente();
                                $('#search_clientes').val('');
                                actualizarListadoClientes('');

                                $('#dataTableEmpaques').DataTable().ajax.reload();
                            } else {
                                alertas_v5("#alerta-factura", 'Error!', res.error, 3, true, 5000);
                            }
                        }
                    });
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'No hay datos',
                    text: 'No hay registros para insertar.'
                });
            }
        }

        function cambiar_de_cliente(rrc_id, cte_id){
            $.ajax({
                type: 'POST',
                url: 'funciones/cliente_empacado_cambiar_cliente.php',
                data: {rrc_id, cte_id},
                success: function(response){
                    Swal.fire({
                        'title': 'Cliente reasinganado correctamente',
                        'text': `El cliente nuevo sera ${d}`,
                        'icon':  'success'
                    });
                }
            });
        }
    });
</script>