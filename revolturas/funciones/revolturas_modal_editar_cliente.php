<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Cambiar cliente teorico</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <div class="col-md-12">
                    <input type="text" id="search_clientes" class="form-control" placeholder="Buscar cliente" autocomplete="off">
                </div>
                <div class="col-md-12 mt-3 mb-2">
                    <select name="cte_id" id="cte_id" class="form-select" required></select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-cambiar-cliente" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button type="button" class="btn btn-primary ms-2" id="guardar-cliente">
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

        obtenerClientes();


        $('#search_clientes').on('input', function() {

            const inputValue = $(this).val().trim().toLowerCase();

            actualizarListadoClientes(inputValue);

        });


        function actualizarListadoClientes(filtro = '') {

            const select = $('#cte_id');

            select.empty();

            select.append('<option value="">Seleccione un cliente</option>');

            const clientesFiltrados = filtro ?
                arrayClientes.filter(cliente =>
                    cliente.cte_nombre.toLowerCase().includes(filtro)
                ) :
                arrayClientes;

            if (clientesFiltrados.length === 0) {
                select.html('<option value="">No se encontraron resultados</option>');
                return;
            }

            clientesFiltrados.forEach(cliente => {

                select.append(
                    $('<option>', {
                        value: cliente.cte_id,
                        text: cliente.cte_nombre
                    })
                );

            });

        }


        function obtenerClientes() {

            $.ajax({
                type: 'GET',
                url: 'catalogos/clientes_listado.php',
                dataType: 'json',

                success: function(clientes) {

                    arrayClientes = clientes
                        .filter(cte => cte.cte_estatus === 'A')
                        .map(cte => ({
                            cte_id: cte.cte_id,
                            cte_nombre: cte.cte_nombre
                        }));

                    actualizarListadoClientes();

                },

                error: function(xhr) {

                    console.error(xhr.responseText);

                    alertas_v5(
                        "#alerta-cambiar-cliente",
                        'Error!',
                        'Error al cargar los clientes.',
                        3,
                        true,
                        5000
                    );

                }
            });

        }


        $('#guardar-cliente').on('click', function() {

            const rev_id = <?= json_encode($_POST['rev_id'] ?? '') ?>;
            const cte_id = $('#cte_id').val();

            if (!cte_id) {

                alertas_v5(
                    "#alerta-cambiar-cliente",
                    'Error!',
                    'Seleccione un cliente.',
                    3,
                    true,
                    5000
                );

                return;
            }


            $.ajax({
                url: 'funciones/revolturas_cambiar_cliente.php',
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',

                data: JSON.stringify({
                    rev_id,
                    cte_id
                }),

                success: function(res) {

                    if (!res.success) {
                        alertas_v5("#alerta-cambiar-cliente", 'Error!', res.message, 3, true, 5000);
                        return;
                    }

                    alertas_v5("#alerta-cambiar-cliente", 'Listo!', res.message, 1, true, 5000);

                    $('#dataTableRevolturas')
                        .DataTable()
                        .ajax
                        .reload(null, false);

                    setTimeout(function() {
                        $('#modalCambiarCliente').modal('hide');
                    }, 1500);
                },

                error: function(xhr) {

                    alertas_v5("#alerta-cambiar-cliente", 'Error!', 'Error al actualizar el cliente.', 3, true, 5000);

                    console.error(xhr.responseText);

                }

            });

        });

    });
</script>