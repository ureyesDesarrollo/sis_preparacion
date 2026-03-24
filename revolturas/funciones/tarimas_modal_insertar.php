<?php

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

if (isset($_POST['action']) && $_POST['action'] == 'obtener_consecutivo') {
    $fechaActual  = new DateTime();
    $primerDiaMes = new DateTime(date('Y-m-01 07:00:00'));

    if ($fechaActual < $primerDiaMes) {
        $primerDiaMesAnterior = new DateTime(date('Y-m-01 07:00:00', strtotime('-1 month')));
        $sql = "SELECT LPAD((COUNT(tar_id) + 1), 4, 0) AS total
                FROM rev_tarimas
                WHERE tar_fecha >= '" . $primerDiaMesAnterior->format('Y-m-d H:i:s') . "'";
    } else {
        $sql = "SELECT LPAD((COUNT(tar_id) + 1), 4, 0) AS total
                FROM rev_tarimas
                WHERE tar_fecha >= '" . $primerDiaMes->format('Y-m-d H:i:s') . "'";
    }

    $result     = mysqli_query($cnx, $sql);
    $registros  = mysqli_fetch_assoc($result);
    $consecutivo = $registros['total'];

    echo json_encode(['consecutivo' => $consecutivo]);
    exit;
}

if (isset($_POST['action']) && $_POST['action'] == 'ultima_tarima') {
    $sql      = "SELECT tar_fecha FROM rev_tarimas ORDER BY tar_id DESC LIMIT 1";
    $result   = mysqli_query($cnx, $sql);
    $registros = mysqli_fetch_assoc($result);
    echo json_encode(['tar_fecha' => $registros['tar_fecha']]);
    exit;
}
?>

<style>
    .custom-checkbox {
        width: 40px;
        transform: scale(1.5);
    }
</style>
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Agregar Tarima</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_tarima_agr" method="POST">

                <!-- ─── TOKEN DE IDEMPOTENCIA (oculto, generado por JS) ─── -->
                <input type="" name="tar_token" id="tar_token">
                <!-- ──────────────────────────────────────────────────────── -->

                <div class="row g-3">

                    <!-- Fila 1 -->
                    <div class="col-md-4">
                        <label for="pro_id" class="form-label">Proceso - Lote</label>
                        <select name="pro_id" id="pro_id" class="form-select" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="tar_folio" class="form-label">Folio Tarima</label>
                        <input type="text" name="tar_folio" id="tar_folio" class="form-control" readonly required>
                    </div>

                    <div class="col-md-2">
                        <label for="tar_kilos" class="form-label">Kilos</label>
                        <input type="text"
                            name="tar_kilos"
                            id="tar_kilos"
                            class="form-control"
                            required
                            onclick="$(this).val('')"
                            onkeypress="return isNumberKey(event, this);"
                            maxlength="7">
                    </div>

                    <!-- Fila 2 -->
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input custom-checkbox" type="checkbox" id="chk_fino" name="chk_fino">
                            <label class="form-check-label" for="chk_fino">
                                ¿Marcar tarima como fino?
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input custom-checkbox" type="checkbox" id="chk_tamiz_nova" name="chk_tamiz_nova">
                            <label class="form-check-label" for="chk_tamiz_nova">
                                ¿Marcar tarima como tamiz nova?
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input custom-checkbox" type="checkbox" id="chk_estatus" name="chk_estatus">
                            <label class="form-check-label" for="chk_estatus">
                                Terminar proceso
                            </label>
                        </div>
                    </div>

                </div>

            </form>
            <div id="formularioAutorizacionFinos" class="d-none">
                <h3>Autorización</h3>
                <form id="formAutorizacionFinos">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="clave" class="form-label">Clave de autorización</label>
                            <input type="password" class="form-control" id="claveFinos" required>
                        </div>
                    </div>
                    <button form="formAutorizacionFinos" type="button" class="btn btn-primary" id="btnAutorizarFinos">Autorizar</button>
                    <button type="button" class="btn btn-secondary" id="btnCancelarFinos">Cancelar</button>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-7 mb-3">
                    <div id="alerta-tarima" class="alert alert-success m-0 d-none d-flex align-items-center">
                        <strong class="alert-heading me-2"></strong>
                        <span class="alert-body me-2"></span>
                        <div id="loadingMessage" style="display: none;"></div>
                    </div>
                </div>
                <div class="col-md-5 d-flex justify-content-end">
                    <?php if ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || $_SESSION['privilegio'] == 19 || $_SESSION['privilegio'] == 25) { ?>
                        <button type="button" class="btn btn-info" onclick="abrir_modal_agrupar_procesos()"><i class="fa-solid fa-object-group"></i> Agrupar procesos</button>
                    <?php } ?>
                    <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_tarima_agr" type="submit" class="btn btn-primary ms-2" id="btnGuardar">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // ─── TOKEN DE IDEMPOTENCIA ────────────────────────────────────────────────
    // Se genera un token único cada vez que el modal está listo para un nuevo registro.
    // Solo rota después de un guardado exitoso, para permitir reintentos legítimos.
    let tarimaToken = null;

    function generarToken() {
        return 'tkn_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
    // ─────────────────────────────────────────────────────────────────────────

    $(document).ready(function() {

        tarimaToken = generarToken(); // Generar token al cargar el modal

        console.log(tarimaToken);

        $('#tar_kilos').val('1000.00');
        cargarProcesos();
        actualizarConsecutivo();

        $("#form_tarima_agr").submit(async function(e) {
            e.preventDefault();

            const ultima_tarima = await ultimaTarima();
            const diferenciaMs = new Date() - new Date(ultima_tarima);

            const isFino = $('#chk_fino').is(':checked');
            const proId = $('#pro_id').val();
            let tiempoEspera = 15;
            if (!isFino) {
                if (['1', '2', '3'].includes(proId)) {
                    tiempoEspera = 1;
                }
                if (diferenciaMs < tiempoEspera * 60 * 1000) {
                    const alertaTarima = document.getElementById('alerta-tarima');
                    const alertBody = alertaTarima.querySelector('.alert-body');
                    const loadingMessage = alertaTarima.querySelector('#loadingMessage');
                    alertaTarima.className = 'alert alert-danger m-0 d-flex align-items-center';
                    alertBody.innerText = `Por favor espera ${tiempoEspera} minutos entre la creación de tarimas. Tiempo restante: ${Math.ceil((tiempoEspera * 60 * 1000 - diferenciaMs) / 60000)} minutos. Tiempo promedio de salida de tarimas 1 hora.`;
                    loadingMessage.style.display = 'none';
                    return;
                }
            }

            // Asignar el token al campo hidden antes de serializar
            $('#tar_token').val(tarimaToken);
            const dataForm = $(this).serialize();

            $('#btnGuardar').prop('disabled', true).text('Guardando...');

            try {
                if (['1', '2', '3'].includes(proId)) {
                    $('#formularioAutorizacionFinos').removeClass('d-none');
                    $('#claveFinos').focus();

                    const resultadoAutorizacion = await new Promise((resolve) => {
                        $('#btnAutorizarFinos').off('click').on('click', async function() {
                            const clave = $('#claveFinos').val();
                            const result = await autorizar(clave);
                            resolve(result);
                        });
                    });

                    if (resultadoAutorizacion.success) {
                        await insertar_parametros(dataForm);
                    }

                } else {
                    const isChecked = $('#chk_estatus').is(':checked');

                    if (isChecked) {
                        const result = await Swal.fire({
                            title: "¿Estás seguro de que son todas las tarimas del proceso?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sí",
                            cancelButtonText: "No"
                        });

                        if (result.isConfirmed) {
                            await insertar_parametros(dataForm);
                        } else {
                            $('#chk_estatus').prop('checked', false);
                        }
                    } else {
                        await insertar_parametros(dataForm);
                    }
                }

            } catch (err) {
                console.error("Error en la operación:", err);
            } finally {
                $('#btnGuardar').prop('disabled', false).html('<img src="../iconos/guardar.png" alt=""> Guardar');
            }
        });

        $('#modal_agrupar_procesos').on('hidden.bs.modal', function() {
            cargarProcesos();
        });

        $('#btnCancelarFinos').on('click', function() {
            $('#formularioAutorizacionFinos').addClass('d-none');
        });
    });

    async function insertar_parametros(dataForm) {
        const alertaTarima = document.getElementById('alerta-tarima');
        const alertBody = alertaTarima.querySelector('.alert-body');
        const loadingMessage = alertaTarima.querySelector('#loadingMessage');

        try {
            alertaTarima.className = 'alert alert-info m-0 d-flex align-items-center';
            alertBody.innerText = 'Insertando datos...';
            loadingMessage.style.display = 'none';

            await delay(500);

            const insertResult = await enviarDatos(dataForm);

            if (insertResult.success) {
                alertaTarima.className = 'alert alert-success m-0 d-flex align-items-center';
                alertBody.innerText = insertResult.success;

                // ─── Rotar el token SOLO tras éxito ───────────────────────
                tarimaToken = generarToken();
                // ─────────────────────────────────────────────────────────

                await delay(1000);

                $('#dataTableTarimas').DataTable().ajax.reload();
                cargarProcesos();
                $('#form_tarima_agr')[0].reset();
                $('#tar_kilos').val('1000.00');
                actualizarConsecutivo();

                await imprimirQR(insertResult.tar_id, alertaTarima, loadingMessage);
            } else {
                alertaTarima.className = 'alert alert-danger m-0 d-flex align-items-center';
                alertBody.innerText = insertResult.error;
                // No rotar el token si falló, para permitir reintento legítimo del usuario
            }
        } catch (error) {
            alertaTarima.className = 'alert alert-danger m-0 d-flex align-items-center';
            alertBody.innerText = `Error en la solicitud: ${error.message}`;
            console.error(error);
        }
    }

    async function imprimirQR(tar_id, alertaTarima, loadingMessage) {
        const alertBody = alertaTarima.querySelector('.alert-body');

        try {
            alertaTarima.className = 'alert alert-info m-0 d-flex align-items-center';
            alertBody.innerText = '';
            loadingMessage.style.display = 'block';
            loadingMessage.innerHTML = '<div class="spinner-border" role="status"><span class="sr-only">Cargando generando impresión QR...</span></div>Cargando... generando impresión QR ';

            await delay(500);

            const qrResult = await generarQR(tar_id);
            alertBody.innerText = '';
            loadingMessage.style.display = 'none';

            if (qrResult.success) {
                alertaTarima.className = 'alert alert-success m-0 d-flex align-items-center';
                alertBody.innerText = qrResult.success;
            } else {
                alertaTarima.className = 'alert alert-danger m-0 d-flex align-items-center';
                alertBody.innerText = `${qrResult.error}, pero la tarima se ha guardado correctamente. Por favor, imprime el QR manualmente.`;
            }
        } catch (error) {
            alertaTarima.className = 'alert alert-danger m-0 d-flex align-items-center';
            alertBody.innerText = `Error: ${error.message}`;
            console.error(error);
        } finally {
            setTimeout(() => {
                loadingMessage.style.display = 'none';
            }, 1000);
        }
    }

    async function enviarDatos(dataForm) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: 'funciones/tarimas_insertar.php',
                data: dataForm,
                success: function(result) {
                    resolve(JSON.parse(result));
                },
                error: function(xhr, status, error) {
                    reject(new Error(error));
                }
            });
        });
    }

    async function generarQR(tar_id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'GET',
                url: 'funciones/tarimas_generar_qr.php',
                data: {
                    tar_id: tar_id,
                    opcion: 1
                },
                cache: false,
                headers: {
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                },
                success: function(response) {
                    resolve(JSON.parse(response));
                },
                error: function(xhr, status, error) {
                    reject(new Error(error));
                }
            });
        });
    }

    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    function cargarProcesos() {
        $.ajax({
            type: 'GET',
            url: 'funciones/tarimas_procesos_listado.php',
            success: function(data) {
                let procesos = JSON.parse(data);
                let procesosUnicos = filtrarProcesosUnicos(procesos);

                let options = '<option value="">Seleccione</option>';
                options += `<option value="1">FINOSA</option>`;
                options += `<option value="2">FINOSB</option>`;
                options += `<option value="3">FINOSC</option>`;

                procesosUnicos.forEach(function(pro) {
                    if (!pro.pro_id_pa) {
                        options += `<option value="${pro.pro_id}">${pro.pro_id} - ${pro.lote_folio}</option>`;
                    } else {
                        let firstProc = pro.pro_id_pa.split('/')[0];
                        let primerProcesoEncontrado = procesos.some(function(p) {
                            return p.pro_id === firstProc;
                        });
                        if (primerProcesoEncontrado) {
                            options += `<option value="${pro.pro_id_pa}">${pro.pro_id_pa} - ${pro.lote_folio}</option>`;
                        }
                    }
                });

                $('#pro_id').empty().append(options);
            },
            error: function() {
                alert('Error al cargar los procesos.');
            }
        });
    }

    function filtrarProcesosUnicos(data) {
        let uniqueValues = [];
        return data.filter((pro) => {
            let key = pro.pro_id_pa || pro.pro_id;
            if (!uniqueValues.includes(key)) {
                uniqueValues.push(key);
                return true;
            }
            return false;
        });
    }

    function actualizarConsecutivo() {
        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_modal_insertar.php',
            data: {
                action: 'obtener_consecutivo'
            },
            success: function(data) {
                let res = JSON.parse(data);
                $('#tar_folio').val(res.consecutivo);
            },
            error: function() {
                alert('Error al obtener el consecutivo.');
            }
        });
    }

    function autorizar(clave) {
        return new Promise((resolve, reject) => {
            if (!clave) {
                alert("Por favor ingresa una clave de autorización.");
                return resolve({
                    success: false,
                    error: "Clave vacía"
                });
            }

            $.ajax({
                url: "administrador/autorizacion_clave.php",
                type: "POST",
                dataType: "json",
                data: {
                    usu_clave_auth: clave
                },
                success: function(response) {
                    if (response.success) {
                        $('#formAutorizacionFinos')[0].reset();
                        $('#formularioAutorizacionFinos').addClass('d-none');
                        alert(response.success);
                        resolve({
                            success: true
                        });
                    } else {
                        alert("Error: " + response.error);
                        resolve({
                            success: false,
                            error: response.error
                        });
                    }
                },
                error: function() {
                    alert('Error en la validación de la clave');
                    resolve({
                        success: false,
                        error: 'Error en ajax'
                    });
                }
            });
        });
    }

    function ultimaTarima() {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: 'funciones/tarimas_modal_insertar.php',
                data: {
                    action: 'ultima_tarima'
                },
                success: function(data) {
                    let res = JSON.parse(data);
                    resolve(res.tar_fecha);
                },
                error: function() {
                    reject(new Error('Error al obtener la última tarima.'));
                }
            });
        });
    }
</script>
