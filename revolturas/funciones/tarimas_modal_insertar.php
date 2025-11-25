<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Octubre-2024 */

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

if (isset($_POST['action']) && $_POST['action'] == 'obtener_consecutivo') {
    // Obtener la fecha actual y la hora actual
    $fechaActual = new DateTime();
    $primerDiaMes = new DateTime(date('Y-m-01 07:00:00')); // Primer día del mes a las 7:00:00

    // Verificar si la hora actual es antes o después de las 7:00:00
    if ($fechaActual < $primerDiaMes) {
        // Si es antes de las 7:00:00, usar el primer día del mes anterior a las 7:00:00
        $primerDiaMesAnterior = new DateTime(date('Y-m-01 07:00:00', strtotime('-1 month')));
        $sql = "SELECT LPAD((COUNT(tar_id) + 1), 4, 0) AS total 
                FROM rev_tarimas 
                WHERE tar_fecha >= '" . $primerDiaMesAnterior->format('Y-m-d H:i:s') . "'";
    } else {
        // Si es después de las 7:00:00, usar el primer día del mes actual a las 7:00:00
        $sql = "SELECT LPAD((COUNT(tar_id) + 1), 4, 0) AS total 
                FROM rev_tarimas 
                WHERE tar_fecha >= '" . $primerDiaMes->format('Y-m-d H:i:s') . "'";
    }

    $result = mysqli_query($cnx, $sql);
    $registros = mysqli_fetch_assoc($result);

    // El siguiente consecutivo es el total más uno
    $consecutivo = $registros['total'];

    // Enviar el consecutivo como respuesta en formato JSON
    echo json_encode(['consecutivo' => $consecutivo]);
    exit;
}

if (isset($_POST['action']) && $_POST['action'] == 'ultima_tarima') {
    //Obtener fecha y hora de la ultima tarima
    $sql = "SELECT tar_fecha FROM rev_tarimas ORDER BY tar_id DESC LIMIT 1";
    $result = mysqli_query($cnx, $sql);
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
                <div class="row">
                    <div class="col-md-3">
                        <label for="pro_id" class="form-label">Proceso - Lote</label>
                        <select name="pro_id" id="pro_id" class="form-select" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tar_folio" class="form-label">Folio Tarima</label>
                        <input type="text" name="tar_folio" id="tar_folio" class="form-control" readonly required>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch p-0">
                            <div class="d-flex flex-column-reverse gap-1">
                                <input class="form-check-input ms-0 custom-checkbox" type="checkbox" role="switch" id="chk_fino" name="chk_fino" />
                                <label class="form-check-label" for="chk_fino" id="chk_fino_label">¿Marcar tarima como fino?</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check form-switch p-0">
                            <div class="d-flex flex-column-reverse gap-1">
                                <input class="form-check-input ms-0 custom-checkbox" type="checkbox" role="switch" id="chk_estatus" name="chk_estatus" />
                                <label class="form-check-label" for="chk_estatus" id="chk_estatus_label">Terminar proceso</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="tar_kilos" class="form-label">Kilos</label>
                        <input type="text" name="tar_kilos" id="tar_kilos" class="form-control" required onclick="$(this).val('')" onkeypress="return isNumberKey(event, this);" maxlength="7">
                    </div>

                    <!-- <div class="col-md-3">
                        <label for="rac_id" class="form-label">Rack</label>
                        <select name="rac_id" id="rac_id" class="form-select" required onchange="cargarNivelPosicion(this);">
                            <option value="">Seleccione</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="nav_id" class="form-label">Nivel - Posición</label>
                        <select name="niv_id" id="niv_id" class="form-select" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div> -->
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
                        <div id="loadingMessage" style="display: none;">

                        </div>
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
    $(document).ready(function() {

        $('#tar_kilos').val('1000.00');
        cargarProcesos();
        actualizarConsecutivo();
        $("#form_tarima_agr").submit(async function(e) {
            e.preventDefault();

            const ultima_tarima = await ultimaTarima();
            const diferenciaMs = new Date() - new Date(ultima_tarima);

            if (diferenciaMs < 5 * 60 * 1000) { // 5 minutos en milisegundos
                const alertaTarima = document.getElementById('alerta-tarima');
                const alertBody = alertaTarima.querySelector('.alert-body');
                const loadingMessage = alertaTarima.querySelector('#loadingMessage');
                alertaTarima.className = 'alert alert-danger m-0 d-flex align-items-center';
                alertBody.innerText = 'Por favor espera 5 minutos entre la creación de tarimas. Tiempo restante: ' + Math.ceil((15 * 60 * 1000 - diferenciaMs) / 60000) + ' minutos. Tiempo promedio de salida de tarimas 1 hora.';
                loadingMessage.style.display = 'none';
                return;
            }

            const dataForm = $(this).serialize();
            const proId = $('#pro_id').val();

            $('#btnGuardar').prop('disabled', true).text('Guardando...');

            try {
                if (['1', '2', '3'].includes(proId)) {
                    // Mostrar formulario de autorización
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
                cache: false, // Evitar caché
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






    /* function cargarRacks() {
        $.ajax({
            type: 'GET',
            url: 'catalogos/racks_listado.php',
            success: function(data) {
                let racks = JSON.parse(data);
                let options = '';
                racks.forEach(function(rack) {
                    if (rack.rac_estatus === 'A') {
                        options += `<option value="${rack.rac_id}">${rack.rac_descripcion}</option>`;
                    }
                });
                $('#rac_id').append(options);
            },
            error: function() {
                alert('Error al cargar los racks.');
            }
        });
    }

    function cargarNivelPosicion(rac_id) {
        rac_id = rac_id.value;

        let dataForm = {
            'rac_id': rac_id
        };

        $('#niv_id').empty();
        $('#niv_id').append('<option value="">Seleccione</option>');


        $.ajax({
            type: 'POST',
            url: 'catalogos/nivel_posicion_listado.php',
            data: dataForm,
            success: function(data) {
                let niveles = JSON.parse(data);
                let options = '';
                niveles.forEach(function(niv) {
                    if (niv.niv_ocupado !== '1') {
                        options += `<option value="${niv.niv_id}">${niv.niv_nivel} - ${niv.niv_posicion}</option>`;
                    }
                });
                $('#niv_id').append(options);
            },
            error: function() {
                alert('Error al cargar niveles.');
            }
        });
    }
*/
    function cargarProcesos() {
        $.ajax({
            type: 'GET',
            url: 'funciones/tarimas_procesos_listado.php',
            success: function(data) {
                let procesos = JSON.parse(data);

                // Filtrar los procesos únicos basados en la lógica requerida
                let procesosUnicos = filtrarProcesosUnicos(procesos);
                //9288
                // Crear las opciones para el select
                let options = '<option value="">Seleccione</option>';
                options += `<option value="1">FINOSA</option>`;
                options += `<option value="2">FINOSB</option>`;
                options += `<option value="3">FINOSC</option>`;
                procesosUnicos.forEach(function(pro) {
                    // Si `pro_id_pa` está vacío, mostramos el proceso
                    if (!pro.pro_id_pa) {
                        options += `<option value="${pro.pro_id}">${pro.pro_id} - ${pro.lote_folio}</option>`;
                    } else {
                        // Si tiene `pro_id_pa`, necesitamos verificar si el primer proceso está presente
                        let firstProc = pro.pro_id_pa.split('/')[0];

                        // Verificar si el primer proceso está en la lista
                        let primerProcesoEncontrado = procesos.some(function(p) {
                            return p.pro_id === firstProc;
                        });

                        // Si el primer proceso está presente, mostramos el proceso con `pro_id_pa`
                        if (primerProcesoEncontrado) {
                            options += `<option value="${pro.pro_id_pa}">${pro.pro_id_pa} - ${pro.lote_folio}</option>`;
                        }
                    }
                });

                // Actualizar el select
                $('#pro_id').empty().append(options);
            },
            error: function() {
                alert('Error al cargar los procesos.');
            }
        });
    }

    // Función para filtrar procesos únicos
    function filtrarProcesosUnicos(data) {
        let uniqueValues = [];
        return data.filter((pro) => {
            // Usar `pro_id_pa` si no es NULL, de lo contrario usar `pro_id`
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