<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

//Obtener proceso
if (isset($_POST['action']) && $_POST['action'] == 'obtener_procesos') {
    try {
        /*$listado_procesos = mysqli_query(
            $cnx,
            "SELECT l.lote_id, a.pro_id, l.lote_folio
        FROM lotes_anio as l
        inner join procesos_agrupados as a on(l.lote_id = a.lote_id)
        where l.lote_estatus = 3
        ORDER BY lote_fecha, lote_hora asc"
        );*/

        $listado_procesos = mysqli_query(
            $cnx,
            "SELECT DISTINCT pro_id FROM rev_tarimas WHERE tar_estatus <> 6 AND tar_rendimiento IS NOT NULL"
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
<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalCerrarProcesosLabel">Activar rendimiento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_activar_rendimiento_admin" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <label for="pro_id_rendi" class="form-label">Proceso</label>
                        <select name="pro_id" id="pro_id_rendi" class="form-select" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-7 mb-3">
                    <div id="alerta_activar_rendimiento" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-5 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_activar_rendimiento_admin" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        cargarProcesos();

        $("#form_activar_rendimiento_admin").submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "administrador/activar_rendimiento.php",
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        alertas_v5("#alerta_activar_rendimiento", 'Listo!', res.success, 1, true, 5000);
                        $('#form_activar_rendimiento_admin')[0].reset();
                    } else {
                        alertas_v5("#alerta_activar_rendimiento", 'Error!', res.error, 3, true, 5000);
                    }
                },
                error: function() {
                    alertas_v5("#alerta_activar_rendimiento", 'Error!', 'Hubo un problema al procesar la solicitud.', 3, true, 5000);
                }
            });
        });
    });

    function cargarProcesos() {
        $.ajax({
            type: 'POST',
            url: 'administrador/activar_rendimiento_modal.php',
            data: {
                action: 'obtener_procesos'
            },
            success: function(data) {
                let procesos = JSON.parse(data);
                let options = '<option value="">Seleccione</option>';
                procesos.forEach(function(pro) {
                    options += `<option value="${pro.pro_id}">${pro.pro_id}</option>`;
                });
                $('#pro_id_rendi').empty().append(options);
            },
            error: function() {
                alert('Error al cargar los procesos.');
            }
        });
    }
</script>