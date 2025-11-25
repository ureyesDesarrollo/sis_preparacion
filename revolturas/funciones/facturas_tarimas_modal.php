<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="title">Capturar Factura</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_factura_tar" method="POST">
                <div class="form-group">
                    <label class="form-label">Tipo:</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo" id="factura" value="F" required checked>
                        <label class="form-check-label" for="factura">Factura</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo" id="remision" value="R" required>
                        <label class="form-check-label" for="remision">Remisión</label>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="factura" class="form-label" id="tipo_documento">Factura</label>
                                <input type="text" class="form-control" name="ft_factura" id="ft_factura" required>
                            </div>
                            <div class="col-md-4">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input type="date" name="fecha" id="fecha" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label for="cliente" class="form-label">Cliente</label>
                                <input type="text" id="search_clientes" class="form-control mb-2" placeholder="Buscar cliente">
                                <select name="cte_id_f" id="cte_id_f" class="form-select" required></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tarima</th>
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
                    <div id="alerta-factura-tar" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_factura_tar" type="submit" class="btn btn-primary ms-2" id="guardar">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let typingTimer;
        const typingDelay = 500;
        let arrayClientes = [];
        obtenerClientes();
        obtenerTarimas();

        $('#ft_factura').on('input', function() {
            clearTimeout(typingTimer);
            const factura = $(this).val();

            typingTimer = setTimeout(function() {
                if (factura) { 
                    validar_factura(factura);
                }
            }, typingDelay);
        });

        const hoy = new Date().toISOString().split('T')[0]; // Formato 'YYYY-MM-DD'
        $('#fecha').val(hoy);

        $('input[name="tipo"]').on('change', function() {
            let tipoDocumento = $('input[name="tipo"]:checked').val();
            let label = tipoDocumento == 'F' ? 'Factura' : 'Remisión';
            $('#tipo_documento').text(label);
            $('#title').text(`Capturar ${label}`);
        });

        $('#search_clientes').on('input', function() {
            const inputValue = $(this).val().toLowerCase();
            if (inputValue.length > 0) {
                const filteredClientes = arrayClientes.filter(cliente =>
                    cliente.cte_nombre.toLowerCase().includes(inputValue)
                );
                const select = $('#cte_id_f');
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


        $('#form_factura_tar').submit(function(e){
            e.preventDefault();
            insertarRegistros();
        });

        function actualizarListadoClientes(filtro) {
            let opciones = '<option value="">Seleccione un cliente</option>';
            if (filtro.length > 0) {
                arrayClientes.filter(cliente => cliente.cte_nombre.toLowerCase().includes(filtro))
                    .forEach(cliente => {
                        opciones += `<option value="${cliente.cte_id}">${cliente.cte_nombre}</option>`;
                    });
            } else {
                arrayClientes.forEach(cliente => {
                    opciones += `<option value="${cliente.cte_id}">${cliente.cte_nombre}</option>`;
                });
            }
            $('#cte_id_f').html(opciones);
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

        function eliminarTarima(index) {
            let tarimasArray = JSON.parse(localStorage.getItem('tarimas')) || [];
            tarimasArray.splice(index, 1);
            localStorage.setItem('tarimas', JSON.stringify(tarimasArray));
            obtenerTarimas();
        }

        function obtenerTarimas() {
            let tarimas = JSON.parse(localStorage.getItem('tarimas')) || [];
            $('#table tbody').empty();

            if (tarimas.length > 0) {
                tarimas.forEach(function(tarima, index) {
                    $('#table tbody').append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>P${tarima.pro_id_2 ? `${tarima.pro_id}/${tarima.pro_id_2}` : tarima.pro_id}T${tarima.tar_folio}</td>
                    <td><a href="#" class="eliminar-tarima" data-index="${index}"><i class="fas fa-times-circle text-danger"></i></a></td>
                </tr>
            `);
                });
            } else {
                $('#table tbody').append(`
            <tr>
                <td colspan="6" class="text-center">No hay tarimas para facturar</td>
            </tr>
        `);
            }
        }

        $('#table').on('click', '.eliminar-tarima', function(e) {
            e.preventDefault();
            const index = $(this).data('index');
            eliminarTarima(index);
        });


        function validar_factura(factura) {
            let tipo = $('input[name="tipo"]:checked').val();

            $.ajax({
                type: 'POST',
                url: 'funciones/facturas_empacado_modal.php',
                data: {
                    action: 'validar_factura',
                    fe_factura: factura,
                    fe_tipo: tipo
                },
                success: function(data) {
                    let res = JSON.parse(data);
                    if(res.error){
                        $('#fe_factura').val('');
                        alertas_v5("#alerta-factura-tar", 'Error!', res.error, 3, true, 5000);
                    }
                }
            });
        }

        function insertarRegistros(){
            let tarimasArray = JSON.parse(localStorage.getItem('tarimas')) || [];
            let tipo = $('input[name="tipo"]:checked').val();
            let factura = $('#ft_factura').val();
            let fecha = $('#fecha').val();
            let cliente = $('#cte_id_f').val();

            if(tarimasArray.length > 0){
                tarimasArray.forEach(tarima =>{
                    $.ajax({
                        type: 'POST',
                        url: 'funciones/facturas_tarimas_insertar.php',
                        data: {
                            ft_factura: factura,
                            fecha: fecha,
                            ft_tipo: tipo,
                            cte_id_f: cliente,
                            tar_id: tarima.tar_id
                        },
                        success: function(data){
                            let res = JSON.parse(data);
                            if(res.success){
                                alertas_v5("#alerta-factura-tar", 'Correcto!', res.success, 1, true, 5000);
                                localStorage.removeItem('tarimas');
                                $('#form_factura_tar')[0].reset();
                                $('#dataTableTarimasAlmacenVentas').DataTable().ajax.reload();
                                obtenerTarimas();
                            }else if(res.error){
                                alertas_v5("#alerta-factura-tar", 'Error!', res.error, 3, true, 5000);
                            }
                        }
                    });
                });
            }

        }
    });
</script>