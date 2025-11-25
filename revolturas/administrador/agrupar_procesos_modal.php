<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

if (isset($_POST['action']) && $_POST['action'] === 'procesos_recorte') {
    try {
        $listado_procesos_recorte = mysqli_query(
            $cnx,
            "SELECT DISTINCT l.lote_id, a.pro_id, l.lote_folio, a.pro_id_pa,m.mat_nombre,pt.pt_descripcion,pt.pt_id
            FROM lotes_anio AS l
            INNER JOIN procesos_agrupados AS a ON l.lote_id = a.lote_id
            INNER JOIN procesos_materiales AS pm ON pm.pro_id = a.pro_id
            INNER JOIN materiales AS m ON m.mat_id = pm.mat_id
            INNER JOIN procesos as p on p.pro_id = a.pro_id
            INNER JOIN preparacion_tipo as pt on pt.pt_id = p.pt_id
            WHERE l.lote_estatus = 2
            ORDER BY l.lote_fecha, l.lote_hora ASC;"
        );

        if (!$listado_procesos_recorte) {
            die("Error en la consulta: " . mysqli_error($cnx));
        }

        $datos_procesos_recorte = array();

        while ($fila = mysqli_fetch_assoc($listado_procesos_recorte)) {
            $datos_procesos_recorte[] = $fila;
        }

        $json_procesos_recorte = json_encode($datos_procesos_recorte);

        echo $json_procesos_recorte;
    } catch (Exception $e) {
        echo json_decode($e->getMessage());
    } finally {
        mysqli_close($cnx);
    }

    exit();
}


if (isset($_POST['action']) && $_POST['action'] === 'procesos') {
    try {
        $listado_procesos = mysqli_query(
            $cnx,
            "SELECT DISTINCT l.lote_id, a.pro_id, l.lote_folio, a.pro_id_pa,m.mat_nombre
            FROM lotes_anio AS l
            INNER JOIN procesos_agrupados AS a ON l.lote_id = a.lote_id
            INNER JOIN procesos_materiales AS pm ON pm.pro_id = a.pro_id
            INNER JOIN materiales AS m ON m.mat_id = pm.mat_id
            WHERE l.lote_estatus = 2
            AND a.pro_id_pa IS NULL
            ORDER BY l.lote_fecha, l.lote_hora ASC"
        );

        if (!$listado_procesos) {
            die("Error en la consulta: " . mysqli_error($cnx));
        }

        $datos_procesos = array();

        while ($fila = mysqli_fetch_assoc($listado_procesos)) {
            $datos_procesos[] = $fila;
        }

        $json_procesos = json_encode($datos_procesos);

        echo $json_procesos;
    } catch (Exception $e) {
        echo json_decode($e->getMessage());
    } finally {
        mysqli_close($cnx);
    }

    exit();
}

?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Agrupar procesos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_agrupar_procesos">
                <div class="row">
                    <div class="col-md-6">
                        <label for="pro_id_1" class="form-label">Proceso 1</label>
                        <select name="pro_id_1" id="pro_id_1" class="form-select"></select>
                    </div>
                    <div class="col-md-6">
                        <label for="pro_id_2" class="form-label">Proceso 2 - Recorte o preparación acida</label>
                        <select name="pro_id_2" id="pro_id_2" class="form-select"></select>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="terminar_recorte" name="terminar_recorte">
                            <label class="form-check-label" for="terminar_recorte">
                                Cerrar proceso de recorte o preparación acida
                            </label>
                        </div>
                    </div>
                </div>

                <input type="text" name="usu_id" id="usu_id" class="d-none">
            </form>
            <div id="formularioAutorizacion" class="d-none">
                <h3>Autorización</h3>
                <form id="formAutorizacion">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="clave" class="form-label">Clave de autorización</label>
                            <input type="password" class="form-control" id="clave" required>
                        </div>
                    </div>
                    <button form="formAutorizacion" type="button" class="btn btn-primary" id="btnAutorizar">Autorizar</button>
                    <button type="button" class="btn btn-secondary" id="btnCancelar">Cancelar</button>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-agrupar-procesos" class="alert alert-success m-0 d-none d-flex align-items-center">
                        <strong class="alert-heading me-2"></strong>
                        <span class="alert-body me-2"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button id="btn-pedir-autorizacion" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Autorizar
                    </button>
                    <button form="form_agrupar_procesos" type="submit" class="btn btn-primary ms-2 d-none" id="btn-guardar">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {


            $('#modal_agrupar_procesos').modal('hide').on('hidden.bs.modal', function() {
                $('#modal_tarimas_insertar').modal('show');
            });

            cargarProcesos1();
            cargarProcesos2();

            $('#btnAutorizar').on('click', function(e) {
                e.preventDefault();
                const clave = $('#clave').val();

                if (clave) {
                    $.ajax({
                        url: "administrador/autorizacion_clave.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            usu_clave_auth: clave
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#formularioAutorizacion').addClass('d-none');
                                $('#btn-guardar').removeClass('d-none');
                                $('#btn-pedir-autorizacion').addClass('d-none');
                                $('#usu_id').val(response.usu_id);
                                alert(response.success);
                            } else {
                                alert("Error: " + response.error);
                            }
                        },
                        error: function() {
                            alert('Error en la validación de la clave');
                        }
                    });
                } else {
                    alert("Por favor ingresa una clave de autorización.");
                }
            });

            $('#btn-pedir-autorizacion').on('click', function() {
                $('#formularioAutorizacion').removeClass('d-none');
                $('#clave').focus();
            });

            $('#btnCancelar').on('click', function() {
                $('#formularioAutorizacion').addClass('d-none');
            });

            $('#form_agrupar_procesos').submit(function(e) {
                e.preventDefault();
                let isChecked = $('#terminar_recorte').is(':checked');
                if (isChecked) {
                    Swal.fire({
                        title: "¿Estás seguro de cerrar este proceso de recorte o preparación acida?",
                        text: '',
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí",
                        cancelButtonText: "No"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            agrupar_procesos();
                        } else {
                            $('#terminar_recorte').prop('checked', false);
                        }
                    });
                } else {
                    agrupar_procesos();
                }
            });

            function cargarProcesos1() {
                $.ajax({
                    type: 'POST',
                    url: 'administrador/agrupar_procesos_modal.php',
                    data: {
                        action: 'procesos'
                    },
                    success: function(data) {
                        let procesos = JSON.parse(data);
                        let options = '<option value="">Seleccione</option>';
                        procesos.forEach(function(pro) {
                            options += `<option value="${pro.pro_id}">${pro.pro_id} - ${pro.mat_nombre}</option>`;
                        });
                        $('#pro_id_1').empty().append(options);
                    },
                    error: function() {
                        alert('Error al cargar los procesos.');
                    }
                });
            }

            function cargarProcesos2() {
                $.ajax({
                    type: 'POST',
                    url: 'administrador/agrupar_procesos_modal.php',
                    data: {
                        action: 'procesos_recorte'
                    },
                    success: function(data) {

                        let procesos = JSON.parse(data);
                        console.log(procesos)
                        let options = '<option value="">Seleccione</option>';
                        procesos.forEach(function(pro) {
                            options += `<option value="${pro.pro_id}">${pro.pro_id} - ${pro.mat_nombre} / ${pro.pt_descripcion} </option>`;
                        });
                        $('#pro_id_2').empty().append(options);
                    },
                    error: function() {
                        alert('Error al cargar los procesos.');
                    }
                });
            }

            function agrupar_procesos() {
                const data = {
                    pro_id_1: $('#pro_id_1').val(),
                    pro_id_2: $('#pro_id_2').val(),
                    usu_id: $('#usu_id').val(),
                    isChecked: $('#terminar_recorte').is(':checked')
                };
                $.ajax({
                    type: 'POST',
                    url: 'administrador/agrupar_procesos.php',
                    data: data,
                    success: function(result) {
                        const res = JSON.parse(result);
                        if (res.success) {
                            alertas_v5("#alerta-agrupar-procesos", 'Listo!', res.success, 1, true, 5000);
                        } else {
                            alertas_v5("#alerta-agrupar-procesos", 'Error!', res.error, 4, true, 5000);
                        }
                    },
                    error: function() {
                        alert('Error al agrupar los procesos.');
                    }
                });
            }
        });
    </script>