<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviemvbre-2024*/
include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$fechaActual = date("Y-m-d");

$oe_id = null;
if (!isset($_POST['action']) || $_POST['action'] !== 'validar_factura') {
    $oe_id = isset($_POST['oe_id']) ? json_decode($_POST['oe_id']) : null;
}

if (isset($_POST['action']) && $_POST['action'] == 'validar_factura') {
    $cnx = Conectarse();
    $fe_factura = $_POST['fe_factura'];
    $fe_tipo = $_POST['fe_tipo'];

    $msg = $fe_tipo == 'F' ? 'Factura' : 'Remisión';
    // Verificación de existencia de la factura
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
<div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 1200px;">
    <div class="modal-content border-0 shadow-lg" style="position:relative;">
        <div class="modal-header">
            <h5 class="modal-title fs-5 fw-semibold">
                <i class="fas fa-file-invoice me-2"></i> <span id="title">Capturar Factura</span>
            </h5>
            <button type="button" class="btn-close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <!-- Overlay de loader -->
        <div id="modal-loader-overlay" style="
    position: absolute;
    z-index: 9999;
    top: 0; left: 0; right: 0; bottom: 0;
    display: none;
    background: rgba(255,255,255,0.7);
    align-items: center;
    justify-content: center;
    text-align: center;
    border-radius: 1rem;
">
            <div style="margin-top:20%;">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <div class="mt-3 fs-5 text-dark">Consultando <span id="tipo_documento">Factura</span>...</div>
            </div>
        </div>

        <div class="modal-body p-4">
            <input type="text" id="orden_id" name="orden_id" value="<?= $oe_id ?>" hidden>

            <form id="form_factura" method="POST" class="needs-validation" novalidate autocomplete="off">
                <!-- Sección de Tipo de Documento y Fecha - Mejorada -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Tipo de Documento</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="tipo" id="factura" value="F" required checked>
                                    <label class="btn btn-outline-primary" for="factura">
                                        <i class="fas fa-file-invoice me-2"></i>Factura
                                    </label>

                                    <input type="radio" class="btn-check" name="tipo" id="remision" value="R" required>
                                    <label class="btn btn-outline-primary" for="remision">
                                        <i class="fas fa-truck me-2"></i>Remisión
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6" id="fecha_container">
                                <label for="fecha" class="form-label fw-medium">Fecha</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                                    <input type="date" name="fecha" id="fecha" class="form-control" required value="<?= $fechaActual ?>">
                                </div>
                                <div class="invalid-feedback">Selecciona una fecha válida</div>
                            </div>
                            <div class="col-md-3 d-none" id="vendedor_container">
                                <label for="vendedor" class="form-label fw-medium">Vendedor</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-user-tie"></i></span>
                                    <select name="vendedor" id="vendedor" class="form-select" required>
                                    </select>
                                </div>
                                <div class="invalid-feedback">Por favor selecciona un vendedor</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Datos Principales - Mejorada -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title mb-3 fw-medium text-primary">
                            <i class="fas fa-user-tie me-2"></i>Datos del Cliente
                        </h6>

                        <div class="row g-3">
                            <!-- Cliente -->
                            <div class="col-md-5">
                                <label for="cte_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-building"></i></span>
                                    <select name="cte_id" id="cte_id" class="form-select select2" required>
                                        <option value="" disabled selected>Buscar cliente...</option>
                                    </select>
                                    <input type="text" name="cte_ubicacion" id="cte_ubicacion" class="form-control" readonly hidden>
                                </div>
                                <div class="invalid-feedback">Por favor selecciona un cliente</div>
                            </div>

                            <!-- Tipo de Cliente -->
                            <div class="col-md-3">
                                <label for="cte_tipo" class="form-label">Tipo de Cliente</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-tag"></i></span>
                                    <input type="text" name="cte_tipo" id="cte_tipo" class="form-control" readonly required>
                                </div>
                            </div>

                            <!-- Selector de Tipo cuando es "Ambos" -->
                            <div class="col-md-3 d-none" id="tipo_venta_container">
                                <label for="cte_tipo_select" class="form-label">Tipo de Venta <span class="text-danger">*</span></label>
                                <select name="cte_tipo_select" id="cte_tipo_select" class="form-select" required>
                                    <option value="" disabled selected>Seleccione tipo</option>
                                    <option value="Comercial">Comercial</option>
                                    <option value="Industrial">Industrial</option>
                                </select>
                                <div class="invalid-feedback">Seleccione el tipo de venta</div>
                            </div>

                            <!-- Giro -->
                            <div class="col-md-4">
                                <label for="cte_clasificacion" class="form-label">Clasificación</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-industry"></i></span>
                                    <input type="text" name="cte_clasificacion" id="cte_clasificacion" class="form-control" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Documentos - Mejorada -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title mb-3 fw-medium text-primary">
                            <i class="fas fa-file-alt me-2"></i>Datos del Documento
                        </h6>

                        <div class="row g-3">
                            <!-- Factura -->
                            <div class="col-md-4">
                                <label for="fe_factura" class="form-label" id="tipo_documento_label">
                                    Factura <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-hashtag"></i></span>
                                    <input type="text" class="form-control" name="fe_factura" id="fe_factura" required>
                                </div>
                                <div class="invalid-feedback" id="factura-feedback">Este campo es requerido</div>
                            </div>

                            <!-- Carta Porte -->
                            <div class="col-md-4">
                                <label for="fe_cartaporte" class="form-label">Carta Porte</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-file-contract"></i></span>
                                    <input type="text" class="form-control" name="fe_cartaporte" id="fe_cartaporte">
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="col-md-4">
                                <label for="total" class="form-label">Total Real</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-dollar-sign"></i></span>
                                    <div class="form-control fw-bold text-end pe-3" id="total-display" style="height: 38px; line-height: 1.5;">
                                        $0.00
                                    </div>
                                    <input type="hidden" name="total" id="total">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 d-none" id="total-container-nota">

                            <div class="col-md-4">
                                <label for="total" class="form-label">Total Nota Credito</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-dollar-sign"></i></span>
                                    <div class="form-control fw-bold text-end pe-3" id="total-display-nota" style="height: 38px; line-height: 1.5;">
                                        $0.00
                                    </div>
                                    <input name="total-nota" id="total-nota" class="form-control fw-bold text-end pe-3" style="height: 38px; line-height: 1.5;" hidden>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="total" class="form-label">Total <span id=""></span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-dollar-sign"></i></span>
                                    <div class="form-control fw-bold text-end pe-3" id="total-display-factura" style="height: 38px; line-height: 1.5;">
                                        $0.00
                                    </div>
                                    <input name="total-factura" id="total-factura" class="form-control fw-bold text-end pe-3" style="height: 38px; line-height: 1.5;" hidden>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Tabla de Productos - Mejorada -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40">#</th>
                                        <th>Revoltura</th>
                                        <th>Calidad</th>
                                        <th>Empaque</th>
                                        <th>Existencias</th>
                                        <th>Cantidad solicitada</th>
                                        <th>Kilos</th>
                                        <th class="text-end">Costo kg</th>
                                        <th id="promocion" class="d-none text-end">Promoción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Filas se generarán dinámicamente -->
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="7" class="fw-bold text-end">Total:</td>
                                        <td class="fw-bold text-end" id="tabla-total">$0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Alertas - Mejoradas -->
                <div id="alerta-factura" class="alert m-0 mb-3 d-none">
                    <div class="d-flex align-items-center">
                        <i class="fas flex-shrink-0 me-3 fs-4"></i>
                        <div>
                            <strong class="alert-heading"></strong>
                            <span class="alert-body d-block"></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Pie de Modal - Mejorado -->
        <div class="modal-footer bg-light">
            <div class="d-flex justify-content-between w-100 align-items-center">
                <div id="mensajes-validacion" class="text-muted small">
                    <i class="fas fa-info-circle me-2"></i>Los campos marcados con <span class="text-danger">*</span> son obligatorios
                </div>
                <div>
                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button form="form_factura" type="submit" class="btn btn-primary" id="guardar">
                        <i class="fas fa-save me-2"></i>Guardar Factura
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        cargarDatosEmpaques();

        $('input[name="tipo"]').on('change', async function() {
            // Obtén el valor seleccionado
            let tipoDocumento = $('input[name="tipo"]:checked').val();

            // Cambia el texto del label
            let label = tipoDocumento == 'F' ? 'Factura' : 'Remisión';
            $('#tipo_documento').text(label);
            $('#tipo_documento_label').text(label);
            $('#title').text(`Capturar ${label}`);
            $('#guardar').html(`<i class="fas fa-save me-2"></i>Guardar ${label}`);


            if (tipoDocumento === 'R') {
                await consultarRemision();
                let consecutivo = consecutivoRemision;
                let hoy = new Date();
                let dia = String(hoy.getDate()).padStart(2, '0');
                let mes = String(hoy.getMonth() + 1).padStart(2, '0');
                let anio = String(hoy.getFullYear()).slice(-2);
                let fechaFormateada = `${consecutivo}-${dia}-${mes}-${anio}`;
                $('#fe_factura').val(fechaFormateada);
                $('#fe_factura').prop('readonly', true); // Desactiva input
                $('#promocion').removeClass('d-none');
                $('.promocion').removeClass('d-none');
                $('#fecha_container').removeClass('col-md-6').addClass('col-md-3');
                $('#vendedor_container').removeClass('d-none');
                $('#total-container-nota').removeClass('d-none');
                $('#total-nota').removeAttr('hidden');
                actualizarFooterTotal();
                obtenerVendedores();
            } else {
                $('#fe_factura').val('');
                $('#fe_factura').prop('readonly', false);
                $('#promocion').addClass('d-none');
                $('.promocion').addClass('d-none');
                $('#fecha_container').removeClass('col-md-3').addClass('col-md-6');
                $('#vendedor_container').addClass('d-none');
                actualizarFooterTotal();
            }
        });

        let typingTimer;
        const typingDelay = 700;
        let lastFactura = "";

        $('#fe_factura').on('input', function() {
            clearTimeout(typingTimer);
            const factura = $(this).val().trim();
            let tipoDocumento = $('input[name="tipo"]:checked').val();

            // Bloquea botón y limpia feedback mientras escribe
            $('#guardar').prop('disabled', true);
            $('#factura-feedback').text('');
            $('#fe_factura').removeClass('is-invalid');
            $('#total-display').text('$0.00');
            $('#tabla-total').text('$0.00');

            typingTimer = setTimeout(function() {
                if (!factura) {
                    lastFactura = "";
                    return;
                }
                if (factura === lastFactura) return;
                lastFactura = factura;

                if (tipoDocumento === 'F') {
                   
                        consultarFactura();
                
                } else {
                    validar_factura(lastFactura);
                }

            }, typingDelay);
        });

        // Quita el error al escribir o cambiar
        $(document).on('input change', '.form-control, .form-select', function() {
            $(this).removeClass('is-invalid');
        });

        function validarFormulario($form) {
            let hayError = false;

            // Recorre TODOS los campos visibles requeridos
            $form.find('[required]:visible').each(function() {
                let $campo = $(this);
                let valor = $campo.val();

                // Para select, checa si está vacío o no seleccionado
                if ($campo.is('select')) {
                    if (!valor || valor === '' || valor === null) {
                        $campo.addClass('is-invalid');
                        hayError = true;
                    } else {
                        $campo.removeClass('is-invalid');
                    }
                } else {
                    if (!valor || valor.trim() === '') {
                        $campo.addClass('is-invalid');
                        hayError = true;
                    } else {
                        $campo.removeClass('is-invalid');
                    }
                }
            });

            // Si hay error, activa los feedbacks de Bootstrap
            if (hayError) {
                $form.addClass('was-validated');
                return false;
            } else {
                $form.removeClass('was-validated');
                return true;
            }
        }


        $('#form_factura').submit(function(e) {
            e.preventDefault();
            const $guardar = $('#guardar');
            if (!validarFormulario($(this))) {
                // Faltan campos: NO envía, el usuario ve los mensajes
                return;
            }

            let tipoDocumento = $('input[name="tipo"]:checked').val();
            if (tipoDocumento === 'R') {

                let data = generarDataRemision();
                if (!data) {
                    // Si no hay datos, no se puede continuar
                    $guardar.prop('disabled', false);
                    return;
                }

                if ($('#fe_factura').val() === '') {
                    $guardar.prop('disabled', false);
                    $('#fe_factura').addClass('is-invalid');
                    $('#factura-feedback').text('La factura es requerida');
                    return;
                }

                insertarRegistros()
                    .then(function(res) {
                        return insertarRegistrosRemision(data);
                    })
                    .then(function(resRemision) {
                        generarRemision();
                        showAlerta('success', 'Remisión', 'Remisión guardada');
                    })
                    .catch(function(error) {
                        // Maneja cualquier error de la cadena
                        showAlerta('error', 'Error', error);
                    })
                    .finally(function() {
                        $guardar.prop('disabled', false);
                        $guardar.html('<i class="fas fa-save me-2"></i>Guardar Factura');
                        limpiarFormulario();
                    });


            } else {
                if ($('#fe_factura').val() === '') {
                    $guardar.prop('disabled', false);
                    $('#fe_factura').addClass('is-invalid');
                    $('#factura-feedback').text('La factura es requerida');
                    return;
                }

                insertarRegistros()
                    .then(function(res) {
                        return insertarRegistrosSai(jsonData);
                    })
                    .then(function(resSai) {
                        // Puedes validar resSai aquí, mostrar alerta de éxito, etc.
                        showAlerta('success', 'Facturación', 'Factura guardada');
                    })
                    .catch(function(error) {
                        showAlerta('error', 'Error', error);
                    })
                    .finally(function() {
                        $guardar.prop('disabled', false);
                        $guardar.html('<i class="fas fa-save me-2"></i>Guardar Factura');
                        limpiarFormulario();
                    });
            }
        });

        $(document).on('input', '.costo-unitario', function() {
            let tipo = $('input[name="tipo"]:checked').val();
            if (tipo === 'R') {
                recalcularTotalRemision();
            }
        });

        $(document).on('input', '.promocion input', function() {
            let tipo = $('input[name="tipo"]:checked').val();
            if (tipo === 'R') {
                recalcularTotalRemision();
            }
        });

        $('input[name="tipo"]').on('change', function() {
            let tipoDocumento = $('input[name="tipo"]:checked').val();
            if (tipoDocumento === 'R') {
                $('.costo-unitario').removeAttr('readonly');
                recalcularTotalRemision();

            } else {
                $('.costo-unitario').attr('readonly', true);
                $('#tabla-total').text('$0.00');
            }
        });

        $('#total-nota').on('input', function() {
            let tipo = $('input[name="tipo"]:checked').val();
            if (tipo === 'R') {
                recalcularTotalRemision();
            }
        });

        $('#table').on('input', '.costo-unitario', function() {
            let $actual = $(this).closest('tr');
            let costo = $(this).val();
            let bloom = $actual.find('td').eq(2).text().trim();

            $('#table tbody tr').each(function() {
                let $fila = $(this);
                let bloomFila = $fila.find('td').eq(2).text().trim();

                if (bloomFila === bloom) {
                    $fila.find('.costo-unitario').val(costo);
                }
            });

            recalcularTotalRemision();
        });



    });

    // Función para insertar registros de SAI
    let jsonData = [];
    let consecutivoRemision = 0;

    function insertarRegistrosSai(jsonData) {
        return new Promise(function(resolve, reject) {
            let tipoVenta = $('#cte_tipo_select').val();
            let clasificacion = $('#cte_clasificacion').val();
            jsonData.FACTURA_CABECERA.TIPO_VENTA = tipoVenta;
            jsonData.FACTURA_CABECERA.TIPO_CLIENTE = clasificacion;

            $.ajax({
                url: 'funciones/sai/insertar.php',
                type: 'POST',
                data: JSON.stringify(jsonData),
                contentType: 'application/json',
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr) {
                    reject(xhr.responseText || "Error en SAI");
                }
            });
        });
    }



    // Función para cargar los datos del localStorage y mostrarlos en la tabla
    function cargarDatosEmpaques() {
        const oe_id = $('#orden_id').val();
        $.ajax({
            type: 'POST',
            url: 'funciones/orden_embarque_detalle_listado.php',
            data: {
                oe_id: oe_id
            },
            success: function(response) {
                let data = JSON.parse(response);
                setTimeout(() => {
                    obtenerClientes(data[0].cliente_id);
                    let tableBody = $('#table tbody');
                    tableBody.empty();

                    // En la función cargarDatosEmpaques(), modifica la creación de filas:
                    data.forEach((item, index) => {
                        let tipoDoc = $('input[name="tipo"]:checked').val();
                        let readonlyAttr = (tipoDoc === 'R') ? '' : 'readonly';
                        let row = `<tr data-empaque-id="${item.empaque_id}" data-empaque-tipo="${item.presentacion_descripcion}" data-presentacion-id="${item.presentacion_id}">
                <td>${index + 1}</td>
                <td>${item.rev_folio}</td>
                <td><span<span class="badge bg-primary">${item.calidad}</span></td>
                <td>${item.presentacion_descripcion}</td>
                <td>${item.existencia_inicial}</td>
                <td>${item.cantidad_solicitada}</td>
                <td>${item.pres_kg * item.cantidad_solicitada}</td>
                <td><input type="number" class="form-control costo-unitario" 
                value="${item.costo_unitario || ''}" step="0.01" min="0" ${readonlyAttr}></td>
                <td class="promocion d-none"><input type="number" class="form-control" min="0" placeholder="Cantidad en kilos"/></td>
                </tr>`;
                        tableBody.append(row);
                    });

                    localStorage.setItem('embarque', JSON.stringify(data));

                }, 100);
            },
            error: function() {
                alert('Error al cargar los datos de los empaques.');
            }
        });
    }

    function insertarRegistros() {
        return new Promise(function(resolve, reject) {
            let empaquesArray = JSON.parse(localStorage.getItem('embarque')) || [];
            let fecha = $('#fecha').val();
            let factura = $('#fe_factura').val();
            let cartaporte = $('#fe_cartaporte').val();
            let cliente = $('#cte_id').val();
            let tipo = $('input[name="tipo"]:checked').val();
            const $guardar = $('#guardar');

            if (empaquesArray.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No hay datos',
                    text: 'No hay registros para insertar.'
                });
                reject("No hay registros para insertar.");
                return;
            }

            $guardar.prop('disabled', true);
            $guardar.html('<span class="spinner-border spinner-border-sm"></span> Guardando...');

            let registrosProcesados = 0,
                exitos = 0,
                errores = 0;

            empaquesArray.forEach((empaque, index) => {
                let costoUnitario = $(`tr[data-empaque-id="${empaque.empaque_id}"] .costo-unitario`).val();

                $.ajax({
                    url: 'funciones/facturas_empacado_insertar.php',
                    type: 'POST',
                    data: {
                        oe_id: $('#orden_id').val(),
                        rr_id: empaque.empaque_id,
                        fe_factura: factura,
                        fe_cartaporte: cartaporte,
                        fe_cantidad: empaque.cantidad_solicitada,
                        costo_unitario: costoUnitario,
                        fe_fecha: fecha,
                        cte_id: cliente,
                        tipo: tipo,
                        cliente: empaque.tipo_revoltura
                    },
                    success: function(response) {
                        let res = JSON.parse(response);
                        if (res.success) {
                            exitos++;
                        } else {
                            errores++;
                        }
                    },
                    error: function() {
                        errores++;
                    },
                    complete: function() {
                        registrosProcesados++;
                        if (registrosProcesados === empaquesArray.length) {
                            $guardar.prop('disabled', false);
                            $guardar.html('<i class="fas fa-save me-2"></i>Guardar Factura');
                            if (exitos > 0) {
                                resolve({
                                    success: true
                                });
                            } else {
                                reject("No se pudo insertar ninguno.");
                            }
                        }
                    }
                });
            });
        });
    }


    function obtenerClientes(cliente_id = '') {
        $.ajax({
            type: 'GET',
            url: 'catalogos/clientes_listado.php',
            success: function(data) {
                let clientes = JSON.parse(data);
                let options = '<option value="">Seleccione un cliente...</option>';

                clientes.forEach(function(cte) {
                    if (cte.cte_estatus == 'A') {
                        let selected = (cte.cte_id == cliente_id) ? 'selected' : '';
                        options += `<option value="${cte.cte_id}" ${selected}>${cte.cte_nombre}</option>`;

                        // Si es el cliente seleccionado
                        if (cte.cte_id == cliente_id) {
                            // Manejo del tipo de cliente
                            if (cte.cte_tipo == 'Ambos') {
                                $('#tipo_venta_container').removeClass('d-none');
                                $('#cte_tipo').val(''); // Limpiamos el campo readonly
                            } else {
                                $('#tipo_venta_container').addClass('d-none');
                                $('#cte_tipo').val(cte.cte_tipo);
                                $('#cte_tipo_select').val(cte.cte_tipo);
                            }

                            $('#cte_clasificacion').val(cte.cte_clasificacion);
                            $('#cte_ubicacion').val(cte.cte_ubicacion);
                        }
                    }
                });

                $('#cte_id').empty().append(options);

                // Si hay un cliente seleccionado inicialmente, activamos el change
                if (cliente_id) {
                    $('#cte_id').trigger('change');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los clientes',
                    confirmButtonColor: '#3085d6'
                });
            }
        });
    }

    // Evento change para el select de tipo cuando es "Ambos"
    $('#cte_tipo_select').on('change', function() {
        $('#cte_tipo').val($(this).val());
    });

    function validar_factura(factura, onSuccess) {
        console.log('Validando factura:', factura);
        let tipo = $('input[name="tipo"]:checked').val();
        const $input = $('#fe_factura');
        const $feedback = $('#factura-feedback');
        const $guardar = $('#guardar');

        $input.removeClass('is-invalid');
        $feedback.text('');

        if (!factura) {
            $feedback.text('Este campo es requerido');
            $input.addClass('is-invalid');
            $guardar.prop('disabled', true);
            return false;
        }

        if (factura.length <= 3) {
            $feedback.removeClass('invalid-feedback').addClass('text-danger');
            $feedback.text('La factura debe tener al menos 4 caracteres');
            $input.addClass('is-invalid');
            $guardar.prop('disabled', true);
            console.warn('Factura demasiado larga');
            return false;
        }

        if (factura.length >= 5) {
            $feedback.removeClass('invalid-feedback').addClass('text-danger');
            $feedback.text('La factura no puede exceder los 5 caracteres');
            $input.addClass('is-invalid');
            $guardar.prop('disabled', true);
            console.warn('Factura demasiado larga');
            return false;
        }

        $guardar.html('<span class="spinner-border spinner-border-sm"></span> Validando...');
        $guardar.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: 'funciones/facturas_empacado_modal.php',
            data: {
                action: 'validar_factura',
                fe_factura: factura,
                fe_tipo: tipo
            },
            dataType: 'json',
            success: function(res) {
                if (res.error) {
                    $feedback.text(res.error);
                    $input.addClass('is-invalid');
                    $guardar.prop('disabled', true);
                    let label = tipo === 'F' ? 'Factura' : 'Remisión';
                    $guardar.html(`<i class="fas fa-save me-2"></i>Guardar ${label}`);
                    showAlerta('error', `${label} no válida`, res.error);
                } else {
                    $input.removeClass('is-invalid');
                    $feedback.text('');
                    $guardar.prop('disabled', false);
                    $guardar.html('<i class="fas fa-save me-2"></i>Guardar Factura');
                    if (typeof onSuccess === 'function') {
                        onSuccess();
                    }
                }
            },
            error: function() {
                $feedback.text('Error validando la factura. Intenta de nuevo.');
                $input.addClass('is-invalid');
                $guardar.prop('disabled', true);
                $guardar.html('<i class="fas fa-save me-2"></i>Guardar Factura');
                showAlerta('error', 'Error', 'No se pudo validar la factura. Intenta más tarde.');
            }
        });

        return true;
    }

    function consultarFactura() {
        const factura = $('#fe_factura').val();
        const $display = $('#total-display');
        const $detalle = $('#tabla-total');
        const $guardar = $('#guardar');
        const $totalNotaContainer = $('#total-container-nota')
        if (!factura) {
            $display.text('$0.00');
            $detalle.text('$0.00');
            $guardar.prop('disabled', true);
            return;
        }

        // Muestra el overlay sobre el modal
        $('#modal-loader-overlay').fadeIn(100);
        $guardar.prop('disabled', true);

        $.ajax({
            type: 'GET',
            url: `lib/consultar_factura_sai.php?folio=${encodeURIComponent(factura)}`,
            dataType: 'json',
            timeout: 30000,
            success: function(response) {
                if (response.success === false) {
                    $('#modal-loader-overlay').fadeOut(100);
                    $display.text('$0.00');
                    $detalle.text('$0.00');
                    $guardar.prop('disabled', true);
                    showAlerta('error', 'Error', response.error);
                    return;
                }


                jsonData = response.data || response;
                console.table(jsonData);
                $('#modal-loader-overlay').fadeOut(100);

                let cab = response.FACTURA_CABECERA || (response.data && response.data.FACTURA_CABECERA);

                if (cab.TOTAL_CREDITO && cab.TOTAL_CREDITO > 0) {
                    $totalNotaContainer.removeClass('d-none');
                    $('#total-nota').val(cab.TOTAL_CREDITO);
                    $('#total-display-nota').text(`$${parseFloat(cab.TOTAL_CREDITO).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
                    $('#total-factura').val(cab.TOTAL_FACTURADO);
                    $('#total-display-factura').text(`$${parseFloat(cab.TOTAL_FACTURADO).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
                } else {
                    $totalNotaContainer.addClass('d-none');
                }

                if (response.error || !cab) {
                    $display.text('$0.00');
                    $detalle.text('$0.00');
                    $guardar.prop('disabled', true);
                    $('#table tbody tr').each(function() {
                        let $fila = $(this);
                        let loteTabla = $fila.find('td').eq(1).text().trim();
                        $fila.find('.costo-unitario').val('');
                    });
                    showAlerta('warning', 'No encontrada', response.error || 'La factura no existe en SAI.');
                    return;
                }

                $display.text(`$${parseFloat(cab.TOTAL_REAL).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
                $detalle.text(`$${parseFloat(cab.TOTAL_REAL).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);

                let detalles = response.data.FACTURA_DETALLE;
                // Para recorrer la tabla:
                $('#table tbody tr').each(function() {
                    let $fila = $(this);
                    let loteTabla = $fila.find('td').eq(1).text().trim();
                    let precioAsignado = false;

                    detalles.forEach(det => {
                        if (Array.isArray(det.LOTE) && det.LOTE.includes(loteTabla)) {
                            $fila.find('.costo-unitario').val(det.PRECIO_KG);
                            $fila.find('.costo-unitario').prop('readonly', true);
                            $fila.find('.costo-unitario').addClass('bg-light text-end');
                            precioAsignado = true;
                        }
                    });

                    if (!precioAsignado) {
                        $fila.find('.costo-unitario').val('');
                    }
                });

                $guardar.prop('disabled', false);
            },
            error: function(xhr, status, error) {
                $('#modal-loader-overlay').fadeOut(100);
                $display.text('$0.00');
                $detalle.text('$0.00');
                $guardar.prop('disabled', true);

                let msg = 'No se pudo consultar la factura. ';
                if (status === "timeout") {
                    msg += 'La consulta tardó demasiado.';
                } else if (xhr && xhr.responseJSON && xhr.responseJSON.error) {
                    msg += xhr.responseJSON.error;
                } else {
                    msg += error;
                }
                showAlerta('error', 'Error', msg);
            }
        });
    }

    async function consultarRemision() {
        const $guardar = $('#guardar');
        // Muestra el overlay sobre el modal
        $('#modal-loader-overlay').fadeIn(100);
        $guardar.prop('disabled', true);

        await $.ajax({
            type: 'GET',
            url: `lib/consultar_remision.php`,
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                let total = response.total || response;
                console.log('Consecutivo de remisión:', total);

                $('#modal-loader-overlay').fadeOut(100);

                $guardar.prop('disabled', false);

                consecutivoRemision = total;
            },
            error: function(xhr, status, error) {
                $('#modal-loader-overlay').fadeOut(100);

                $guardar.prop('disabled', true);

                let msg = 'No se pudo consultar la remision. ';
                if (status === "timeout") {
                    msg += 'La consulta tardó demasiado.';
                } else if (xhr && xhr.responseJSON && xhr.responseJSON.error) {
                    msg += xhr.responseJSON.error;
                } else {
                    msg += error;
                }
                showAlerta('error', 'Error', msg);
            }
        });
    }


    function recalcularTotalRemision() {
        let total = 0;
        let totalRemision = 0;

        $('#table tbody tr').each(function() {
            let $fila = $(this);
            let kilos = parseFloat($fila.find('td').eq(6).text()) || 0;
            let promocion = parseFloat($fila.find('.promocion input').val()) || 0;
            let costo = parseFloat($fila.find('.costo-unitario').val()) || 0;

            let kilosFacturables = Math.max(0, kilos - promocion);
            total += kilosFacturables * costo; // total REAL
            totalRemision += kilos * costo; // total antes de promocion
        });

        // Si hay nota de crédito, réstala al total real y muéstrala
        let totalNota = parseFloat($('#total-nota').val()) || 0;
        let totalConNota = total - totalNota;
        if (totalNota > 0) {
            $('#total-display-nota').removeClass('d-none').text(
                `-$${totalNota.toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2})}`
            );
        } else {
            $('#total-display-nota').addClass('d-none').text('$0.00');
        }

        // Actualiza los totales en pantalla
        $('#tabla-total').text(`$${totalConNota.toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2})}`);
        $('#total').val(totalConNota.toFixed(2));
        $('#total-display').text(`$${totalConNota.toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2})}`);
        $('#total-factura').val(totalRemision.toFixed(2));
        $('#total-display-factura').text(`$${totalRemision.toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2})}`);
    }


    function showAlerta(icono, titulo, mensaje) {
        Swal.fire({
            icon: icono, // 'success', 'warning', 'error', 'info'
            title: titulo,
            text: mensaje,
            timer: 3000,
            showConfirmButton: false
        });
    }


    function generarRemision() {
        // Construir la URL con parámetros
        let precios = [];
        $('#table tbody tr').each(function() {
            let $fila = $(this);
            let empaqueId = $fila.data('empaque-id');
            let costoUnitario = $fila.find('.costo-unitario').val();
            let promocion = $fila.find('.promocion input').val() || '';
            precios.push({
                empaque_id: empaqueId,
                costo_unitario: costoUnitario,
                promocion: promocion
            });
        });
        precios = JSON.stringify(precios);
        const url = `lib/generar_remision.php?orden_id=${$('#orden_id').val()}&folio=${$('#fe_factura').val()}&precios=${encodeURIComponent(precios)}`;

        // Crear un enlace invisible y hacer clic para descargar
        const link = document.createElement('a');
        link.href = url;
        link.target = '_self';
        link.click();
    }

    function actualizarFooterTotal() {
        // Cuenta los th visibles en el thead (por si hay columnas ocultas con display: none)
        let numCols = $('#table thead tr th:visible').length;

        // Si ya existe tfoot, lo eliminamos (evita duplicados)
        $('#table tfoot').remove();

        // Agrega el nuevo tfoot con el colspan adecuado
        $('#table').append(`
        <tfoot class="table-light">
            <tr>
                <td colspan="${numCols - 1}" class="fw-bold text-end">Total:</td>
                <td class="fw-bold text-end" id="tabla-total">$0.00</td>
            </tr>
        </tfoot>
    `);
    }

    function generarDataRemision() {
        let tipoVenta = $('#cte_tipo_select').val();
        let clasificacion = $('#cte_clasificacion').val();

        let jsonData = {
            FACTURA_CABECERA: {
                FOLIO: $('#fe_factura').val(),
                VENDEDOR: $('#vendedor option:selected').text(),
                CLIENTE: $('#cte_id option:selected').text(),
                UBICACION: $('#cte_ubicacion').val(),
                TIPO_CLIENTE: clasificacion,
                TIPO_VENTA: tipoVenta,
                TOTAL_REMISION: parseFloat($('#total-factura').val()) || 0,
                TOTAL_NOTA: parseFloat($('#total-nota').val()) || 0,
                TOTAL_REAL: parseFloat($('#total').val()) || 0,
                FECHA: $('#fecha').val(),
            },
            FACTURA_DETALLE: []
        };

        console.log('Generando datos de remisión:', jsonData);

        $('#table tbody tr').each(function() {
            let $fila = $(this);
            let empaqueId = $fila.data('empaque-id');
            let presentacionId = $fila.data('presentacion-id');
            let bloom = $fila.find('td').eq(2).text();
            let claveDesc = obtenerClaveYDescripcionProducto(bloom, presentacionId);
            let lote = $fila.find('td').eq(1).text();
            let cantidad_kilos = parseFloat($fila.find('td').eq(6).text());
            let precio = parseFloat($fila.find('.costo-unitario').val()) || 0;
            let promocion = parseFloat($fila.find('.promocion input').val()) || 0;

            // Línea normal (lo facturado)
            let cantidadFacturada = cantidad_kilos - promocion;
            if (cantidadFacturada > 0) {
                jsonData.FACTURA_DETALLE.push({
                    PRODUCTO_CVE: claveDesc.clave,
                    PRODUCTO_DESCRIPCION: claveDesc.descripcion,
                    CANTIDAD: cantidadFacturada,
                    PRECIO: precio,
                    PROMOCION: 0, // Línea normal
                    LOTE: [lote]
                });
            }

            // Línea de promoción (solo si aplica)
            if (promocion > 0) {
                jsonData.FACTURA_DETALLE.push({
                    PRODUCTO_CVE: claveDesc.clave,
                    PRODUCTO_DESCRIPCION: claveDesc.descripcion + ' (MERCANCÍA DE PROMOCIÓN)',
                    CANTIDAD: promocion,
                    PRECIO: 0,
                    PROMOCION: 1, // Línea de promoción
                    LOTE: [lote]
                });
            }
        });

        return jsonData;
    }


    function obtenerVendedores() {
        $.ajax({
            type: 'GET',
            url: 'catalogos/vendedores_listado.php',
            success: function(response) {
                let vendedores = response.data;
                let options = '<option value="">Seleccione un vendedor...</option>';

                vendedores.forEach(function(vendedor) {
                    options += `<option value="${vendedor.ven_id}">${vendedor.ven_nombre}</option>`;
                });

                $('#vendedor').empty().append(options);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los vendedores',
                    confirmButtonColor: '#3085d6'
                });
            }
        });
    }


    function obtenerClaveYDescripcionProducto(bloom, presentacionId) {
        // Mapeo de IDs a nombre presentación
        const presentaciones = {
            2: 'SACOS',
            3: 'CAJA',
            4: 'CAJA 1/4'
        };

        let bloomNum = parseInt(bloom);

        // Catálogo de productos (clave por combinación de bloom y presentaciónId)
        const productos = [{
                clave: 'GRE250B',
                bloom: 250,
                presentacion: 2,
                descripcion: 'GRENETINA ALIMENTICIA 250 BLOOM SACOS'
            },
            {
                clave: 'GRE265B',
                bloom: 265,
                presentacion: 2,
                descripcion: 'GRENETINA ALIMENTICIA 265 BLOOM SACOS'
            },
            {
                clave: 'GRE280B',
                bloom: 280,
                presentacion: 2,
                descripcion: 'GRENETINA ALIMENTICIA 280 BLOOM SACOS'
            },
            {
                clave: 'GRE300B',
                bloom: 300,
                presentacion: 2,
                descripcion: 'GRENETINA ALIMENTICIA 300 BLOOM SACOS'
            },
            {
                clave: 'GRE315B',
                bloom: 315,
                presentacion: 2,
                descripcion: 'GRENETINA ALIMENTICIA 315 BLOOM SACOS'
            },

            {
                clave: 'GRE230C',
                bloom: 230,
                presentacion: 3,
                descripcion: 'GRENETINA ALIMENTICIA 230 BLOOM CAJA'
            },
            {
                clave: 'GRE265C',
                bloom: 265,
                presentacion: 3,
                descripcion: 'GRENETINA ALIMENTICIA 265 BLOOM CAJA'
            },
            {
                clave: 'GRE300C',
                bloom: 300,
                presentacion: 3,
                descripcion: 'GRENETINA ALIMENTICIA 300 BLOOM CAJA'
            },
            {
                clave: 'GRE315C',
                bloom: 315,
                presentacion: 3,
                descripcion: 'GRENETINA ALIMENTICIA 315 BLOOM CAJA'
            },

            {
                clave: 'GRE3151/4',
                bloom: 315,
                presentacion: 4,
                descripcion: 'GRENETINA ALIMENTICIA 315 BLOOM CAJA 1/4'
            },
        ];

        // Buscar el producto por bloom y presentacionId
        let encontrado = productos.find(p =>
            p.bloom === bloomNum &&
            p.presentacion == presentacionId
        );

        if (encontrado) {
            return {
                clave: encontrado.clave,
                descripcion: encontrado.descripcion
            };
        } else {
            // Si no se encuentra la combinación exacta, puedes devolver algo genérico:
            return {
                clave: '',
                descripcion: `PRODUCTO BLOOM ${bloomNum} PRESENTACIÓN ${presentaciones[presentacionId] || presentacionId}`
            };
        }
    }

    function insertarRegistrosRemision(data) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: 'funciones/remisiones_insertar.php',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr) {
                    reject(xhr.responseText || "Error al insertar remisión");
                }
            });
        });
    }

    function limpiarFormulario() {
        // Limpia sólo si al menos uno fue exitoso
        localStorage.clear();
        let tableBody = $('#table tbody');
        tableBody.empty();
        $('#fe_factura').val('');
        $('#cte_id').val('');
        $('#fe_cartaporte').val('');
        $('#total-display').text('$0.00');
        $('#tabla-total').text('$0.00');
        $('#cte_tipo').val('');
        $('#cte_tipo_select').val('');
        $('#cte_clasificacion').val('');
        $('#cte_ubicacion').val('');
        $('#vendedor').val('');
        $('#tipo_venta_container').addClass('d-none');
        $('#dataTableEmbarque').DataTable().ajax.reload();
    }
</script>