<?php
include "../../conexion/conexion.php";
if (isset($_POST['action']) && $_POST['action'] == 'validar_factura') {
    $cnx = Conectarse();
    $fe_factura = $_POST['fe_factura'];
    $fe_tipo = $_POST['fe_tipo'];

    $tipos = [
        'F' => 'Factura',
        'R' => 'Remisión',
        'V' => 'Vale de salida'
    ];

    $msg = $tipos[$fe_tipo] ?? 'Documento';

    // Verificación de existencia de la factura
    if ($fe_tipo == 'V') {
        $checkSql = "SELECT COUNT(*) AS count FROM rev_tarimas_facturas WHERE ft_vale_salida = '$fe_factura' AND ft_tipo = '$fe_tipo'";
        $checkResult = mysqli_query($cnx, $checkSql);
        $checkRow = mysqli_fetch_assoc($checkResult);

        if ($checkRow['count'] > 0) {
            // Si el vale de salida ya existe, devolver un mensaje de error
            $res = "El $msg $fe_factura ya está registrado.";
            echo json_encode(["error" => $res]);
            exit;
        } else {
            echo json_encode(["success" => "$msg válido"]);
            exit;
        }
    }

    $checkSql = "SELECT COUNT(*) AS count FROM rev_revolturas_pt_facturas WHERE fe_factura = '$fe_factura' AND fe_tipo = '$fe_tipo'";
    $checkResult = mysqli_query($cnx, $checkSql);
    $checkRow = mysqli_fetch_assoc($checkResult);

    $checkSql_2 = "SELECT COUNT(*) AS count FROM rev_tarimas_facturas WHERE ft_factura = '$fe_factura' AND ft_tipo = '$fe_tipo'";
    $checkResult_2 = mysqli_query($cnx, $checkSql_2);
    $checkRow_2 = mysqli_fetch_assoc($checkResult_2);

    if ($checkRow['count'] > 0 || $checkRow_2['count'] > 0) {
        // Si la factura ya existe, devolver un mensaje de error
        $res = "La $msg $fe_factura ya está registrada.";
        echo json_encode(["error" => $res]);
    } else {
        echo json_encode(["success" => "Factura válida"]);
    }
    exit;
}
?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content" style="position: relative;">
        <div class="modal-header">
            <h5 class="modal-title" id="title">Capturar Factura</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Overlay de loader -->
        <div id="modal-loader-overlay-tar" style="
            display: none;
            position: absolute;
            z-index: 9999;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.75);
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 0.375rem;
        ">
            <div style="margin-top: 25%;">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <div class="mt-3 fs-5 text-dark">Consultando Factura...</div>
            </div>
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
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo" id="vale_salida" value="V" required>
                        <label class="form-check-label" for="vale_salida">Vale de salida</label>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="factura" class="form-label" id="tipo_documento">Factura</label>
                                <input type="text" class="form-control" name="ft_factura" id="ft_factura" required autocomplete="off">
                            </div>
                            <div class="col-md-4">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input type="date" name="fecha" id="fecha" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label for="cliente" class="form-label">Cliente</label>
                                <input type="text" id="search_clientes" class="form-control mb-2" placeholder="Buscar cliente" autocomplete="off">
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
        const typingDelay = 800;
        let arrayClientes = [];
        let jsonDataSai = null;
        let clienteTipoVenta = '';
        let clienteClasificacion = '';
        obtenerClientes();
        obtenerTarimas();

        $('#ft_factura').on('input', function() {
            clearTimeout(typingTimer);
            const tipo = $('input[name="tipo"]:checked').val();

            // Mientras el usuario escribe: deshabilita guardar y limpia datos previos
            $('#guardar').prop('disabled', true);
            jsonDataSai = null;

            const factura = $(this).val().trim();
            if (!factura) return;

            typingTimer = setTimeout(function() {
                if (tipo === 'F') {
                    consultarFactura(factura);
                } else {
                    validar_factura(factura);
                }
            }, typingDelay);
        });

        const hoy = new Date().toISOString().split('T')[0]; // Formato 'YYYY-MM-DD'
        $('#fecha').val(hoy);

        $('input[name="tipo"]').on('change', function() {
            let tipoDocumento = $('input[name="tipo"]:checked').val();
            let tipos = {
                'F': 'Factura',
                'R': 'Remisión',
                'V': 'Vale de salida'
            };

            let label = tipos[tipoDocumento] || '';

            $('#tipo_documento').text(label);
            $('#title').text(`Capturar ${label}`);

            // Limpia datos SAI al cambiar tipo
            jsonDataSai = null;
            $('#ft_factura').val('');
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


        $('#form_factura_tar').submit(function(e) {
            e.preventDefault();
            const tipo = $('input[name="tipo"]:checked').val();

            if (tipo === 'F' && jsonDataSai) {
                // Si es factura, primero inserta en SAI y luego en tarimas
                insertarRegistrosSai(jsonDataSai)
                    .then(function() {
                        return insertarRegistros();
                    })
                    .catch(function(err) {
                        alertas_v5("#alerta-factura-tar", 'Error!', err, 3, true, 5000);
                    });
            } else {
                insertarRegistros();
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
                arrayClientes.forEach(cliente => {
                    opciones += `<option value="${cliente.cte_id}">${cliente.cte_nombre}</option>`;
                });
            }
            $('#cte_id_f').html(opciones).trigger('change');
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
                                cte_tipo: cte.cte_tipo,
                                cte_clasificacion: cte.cte_clasificacion
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

        // Al cambiar el cliente, actualiza tipo_venta y clasificacion en memoria
        $('#cte_id_f').on('change', function() {
            let cteId = $(this).val();
            let cliente = arrayClientes.find(c => c.cte_id == cteId);
            if (cliente) {
                clienteTipoVenta = cliente.cte_tipo;
                clienteClasificacion = cliente.cte_clasificacion;
            } else {
                clienteTipoVenta = '';
                clienteClasificacion = '';
            }

            console.log(clienteTipoVenta);
            console.log(clienteClasificacion);
        });

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

        // Consulta la factura en SAI y luego valida que no esté duplicada en BD
        function consultarFactura(factura) {
            $('#modal-loader-overlay-tar').css('display', 'flex');
            $('#guardar').prop('disabled', true);

            $.ajax({
                type: 'GET',
                url: `lib/consultar_factura_sai.php?folio=${encodeURIComponent(factura)}`,
                dataType: 'json',
                success: function(response) {
                    $('#modal-loader-overlay-tar').hide();

                    if (response.success === false || response.error) {
                        alertas_v5("#alerta-factura-tar", 'Error!', response.error || 'La factura no existe en SAI.', 3, true, 5000);
                        jsonDataSai = null;
                        return;
                    }

                    // Guarda los datos del SAI para usarlos al guardar
                    jsonDataSai = response.data || response;

                    // Una vez consultado SAI con éxito, valida duplicado en BD
                    validar_factura(factura);
                    alertas_v5("#alerta-factura-tar", 'Correcto!', 'Factura encontrada en SAI.', 1, true, 3000);
                },
                error: function(xhr, status, error) {
                    $('#modal-loader-overlay-tar').hide();
                    let msg = 'No se pudo consultar la factura en SAI. ';
                    if (status === 'timeout') msg += 'La consulta tardó demasiado.';
                    else if (xhr.responseJSON && xhr.responseJSON.error) msg += xhr.responseJSON.error;
                    else msg += error;
                    alertas_v5("#alerta-factura-tar", 'Error!', msg, 3, true, 5000);
                    jsonDataSai = null;
                }
            });
        }

        function validar_factura(factura) {
            let tipo = $('input[name="tipo"]:checked').val();

            $.ajax({
                type: 'POST',
                url: 'funciones/facturas_tarimas_modal.php',
                data: {
                    action: 'validar_factura',
                    fe_factura: factura,
                    fe_tipo: tipo
                },
                success: function(data) {
                    let res = JSON.parse(data);
                    if (res.error) {
                        $('#fe_factura').val('');
                        jsonDataSai = null;
                        alertas_v5("#alerta-factura-tar", 'Error!', res.error, 3, true, 5000);
                    } else {
                        $('#guardar').prop('disabled', false);
                    }
                }
            });
        }

        // Inserta la factura en SAI
        function insertarRegistrosSai(data) {
            return new Promise(function(resolve, reject) {
                data.FACTURA_CABECERA.TIPO_VENTA = clienteTipoVenta;
                data.FACTURA_CABECERA.TIPO_CLIENTE = clienteClasificacion;

                console.log(clienteTipoVenta);
                console.log(clienteClasificacion);

                $.ajax({
                    url: 'funciones/sai/insertar.php',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr) {
                        reject(xhr.responseText || 'Error al registrar en SAI');
                    }
                });
            });
        }

        function insertarRegistros() {
            let tarimasArray = JSON.parse(localStorage.getItem('tarimas')) || [];
            let tipo = $('input[name="tipo"]:checked').val();
            let factura = $('#ft_factura').val();
            let fecha = $('#fecha').val();
            let cliente = $('#cte_id_f').val();

            if (tarimasArray.length > 0) {
                tarimasArray.forEach(tarima => {
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
                        success: function(data) {
                            let res = JSON.parse(data);
                            if (res.success) {
                                alertas_v5("#alerta-factura-tar", 'Correcto!', res.success, 1, true, 5000);
                                localStorage.removeItem('tarimas');
                                $('#form_factura_tar')[0].reset();
                                $('#dataTableTarimasAlmacenVenta').DataTable().ajax.reload();
                                obtenerTarimas();
                                jsonDataSai = null;
                            } else if (res.error) {
                                alertas_v5("#alerta-factura-tar", 'Error!', res.error, 3, true, 5000);
                            }
                        }
                    });
                });
            }
        }
    });
</script>
