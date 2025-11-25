<?php
extract($_POST);
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
?>

<!-- Modal formulario -->
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Carga en equipo(Etapa 1)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_pelambre">
                <div class="container-fluid p-3">
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <label for="txt-lavador" class="form-label">Lavador</label>
                            <input type="text" name="lavador" id="txt-lavador" class="form-control" readonly disabled value="<?php echo $_POST['nombre_lavador']; ?>">
                            <input type="text" name="lavador" id="txt-id-lavador" class="form-control" readonly disabled value="<?php echo $_POST['id_lavador'] ?>" hidden>
                        </div>
                        <div class="col-12 col-md-8 mb-3">
                            <label for="txt-material" class="form-label">Ticket / Kilos/Material</label>
                            <select type="text" class="form-select" id="slc_material" name="slc_material" required>
                                <option value="">Selecciona</option>
                                <?php
                                $consulta =  mysqli_query($cnx, "SELECT i.inv_id,i.inv_kilos,i.inv_no_ticket,m.mat_id,m.mat_nombre,p.prv_id,p.prv_nombre FROM inventario as i
                                INNER JOIN materiales as m ON(i.mat_id = m.mat_id)
                                INNER JOIN proveedores as p ON(i.prv_id = p.prv_id)
                                WHERE inv_enviado = 5;");
                                $reg = mysqli_fetch_assoc($consulta);
                                do {
                                ?>
                                    <option value="<?php echo $reg['inv_id'] ?>">
                                        <?php echo $reg['inv_no_ticket'] . " / " . $reg['inv_kilos'] . " / " . $reg['mat_nombre']; ?>
                                    </option>
                                <?php  } while ($reg = mysqli_fetch_assoc($consulta));
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <label for="txt-fecha" class="form-label">Fecha carga</label>
                            <input type="datetime-local" name="txt_fecha" id="txt_fecha" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="txt-hora-inicio" class="form-label">Hora inicio carga</label>
                            <input type="time" name="txt_hora_inicio" id="txt_hora_inicio" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="txt-hora-termino" class="form-label">Hora termino carga</label>
                            <input type="time" name="txt_hora_termino" id="txt_hora_termino" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <label for="txt-fecha-remojo" class="form-label">Fecha de remojo</label>
                            <input type="date" name="txt_fe_remojo" id="txt_fe_remojo" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="txt-hora-remojo" class="form-label">Hora que inicia el remojo</label>
                            <input type="time" name="txt_hora_ini_remojo" id="txt_hora_ini_remojo" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center flex-wrap">
                    <div class="col-12 col-md-6 mb-3">
                        <div id="alerta-etapa1" class="alert alert-success d-none m-0">
                            <strong class="alert-heading"></strong>
                            <span class="alert-body"></span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="location.reload()">
                            <i class="fa-solid fa-rectangle-xmark"></i> Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="../js/alerta.js"></script>
<script>
    $("#form_pelambre").submit(function(e) {
        const data = {
            inv_id: $('#slc_material').val(),
            ep_id: $('#txt-id-lavador').val(),
            ep_fecha: $('#txt_fecha').val(),
            ep_fecha_remojo: $('#txt_fe_remojo').val(),
            ep_hora_ini_remojo: $('#txt_hora_ini_remojo').val(),
            ep_hora_ini_carga: $('#txt_hora_inicio').val(),
            ep_hora_fin_carga: $('#txt_hora_termino').val(),
        }

        $.ajax({
            type: 'POST',
            url: 'pelambre/pelambre_etapa1_insertar.php',
            data: data,
            success: function(result) {
                let res = JSON.parse(result);
                console.log(res.success);
                if (res.success) {
                    alertas_v5('#alerta-etapa1', '', res.success, 1, true, 5000)
                    setTimeout("location.reload()", 1000);
                } else {
                    alertas_v5('#alerta-etapa1', '', res.error, 3, true, 5000)
                }
            }
        })

        console.log(data);
        e.preventDefault();
    });
</script>